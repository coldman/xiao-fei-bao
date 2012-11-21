#coding:utf-8
'''
Created on 2012-10-10
@author: zhangxk
'''
import os
import wx
import ColorPanel

import ListCtrl
import UserListCtrl
import AddRoomDialog

import public
import DetailPanel
from src.ui.AddGuestDialog import AddGuestDialog


colourList = [ "Aquamarine", "Black", "Blue", "Blue Violet", "Brown", "Cadet Blue",
               "Coral", "Cornflower Blue", "Cyan", "Dark Grey", "Dark Green",
               "Dark Olive Green",
               ]
   
class MainLB(wx.Listbook):
    def __init__(self, parent, id):
        wx.Listbook.__init__(self, parent, id, style=wx.BK_DEFAULT)
        
        # make an image list using the LBXX images
        il = wx.ImageList(32, 32, True)
        for x in range(5):
        
            path = 'image/LB%02d.png' % (x+1)
            png = wx.Bitmap(public.opj(path), wx.BITMAP_TYPE_PNG)
            il.Add(png)
        self.AssignImageList(il)
        
        
        # Now make a bunch of panels for the list book
        userPanel = UserPanel(self)
        self.AddPage(userPanel, u'客人管理', imageId=0)
        
        roomPanel = RoomPanel(self)
        self.AddPage(roomPanel, u'客房管理', imageId=1)
        
        superPanel= SuperPanel(self)
        self.AddPage(superPanel, u'超级管理', imageId=2)
        
        detailPanel= DetailPanel.DetailPanel(self)
        self.AddPage(detailPanel, u'收支流水', imageId=3)
        
        superPanel= SuperPanel(self)
        self.AddPage(superPanel, u'报表中心', imageId=4)
        
        self.SetSize(parent.GetSize())
        self.Bind(wx.EVT_LISTBOOK_PAGE_CHANGED, self.OnPageChanged)
        self.Bind(wx.EVT_LISTBOOK_PAGE_CHANGING, self.OnPageChanging)
        #defult page is "客房管理"
        #self.SetSelection(1)
 
    def makeColorPanel(self, color):
        p = wx.Panel(self, -1)
        win = ColorPanel.ColoredPanel(p, color)
        p.win = win
        def OnCPSize(evt, win=win):
            win.SetPosition((0,0))
            win.SetSize(evt.GetSize())
        p.Bind(wx.EVT_SIZE, OnCPSize)
        return p
                 
    def OnPageChanged(self, event):
        old = event.GetOldSelection()
        new = event.GetSelection()
        sel = self.GetSelection()
        print ('OnPageChanged,  old:%d, new:%d, sel:%d\n' % (old, new, sel))
        event.Skip()

    def OnPageChanging(self, event):
        old = event.GetOldSelection()
        new = event.GetSelection()
        sel = self.GetSelection()
        print ('OnPageChanging, old:%d, new:%d, sel:%d\n' % (old, new, sel))
        event.Skip()

class RoomPanel(wx.Panel):
    def __init__(self, parent, pos=(0,0)):
        wx.Panel.__init__(self, parent)
        self.parent = parent
        self.pos = pos
        self._init_ctrls_()
        
    def _init_ctrls_(self):
        self.SetPosition(self.pos)
        self.SetSize(self.GetSize())
        self.SetBackgroundColour('white')
        
        
        self.filter_st = wx.StaticText(self, label=u'客房状态')
        roomstatusList = [u"全部"]
        items = public.ROOM_STATUS.items()
        for key, data in items:
            roomstatusList.append(data)
        self.status_cb = wx.ComboBox(self, 500, u"", (90, 50), 
                         (60, -1), roomstatusList,
                         wx.CB_DROPDOWN
                         #| wx.TE_PROCESS_ENTER
                         #| wx.CB_SORT
                         )
        
        self.status_cb.SetSelection(0)
        filter_roomnum_st = wx.StaticText(self, label=u'房号')        
        self.filter_roomnum_tc = wx.TextCtrl(parent=self, value="", size=(50, 16))
        
        
        self.list = ListCtrl.RoomListCtrlPanel(self)   
        self.search_btn = wx.Button(self, label=u'搜索')
        self.add_btn = wx.Button(self, label=u'新增')
        self.refresh_btn = wx.Button(self, label=u'刷新')
        self.close_btn = wx.Button(self, label=u'退出', id=wx.ID_CANCEL)
        
        # ---- layout -----
        HBox = wx.BoxSizer(wx.VERTICAL)
        
        fbox = wx.BoxSizer(wx.HORIZONTAL)
        fbox.Add(self.filter_st, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.status_cb, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(filter_roomnum_st, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.filter_roomnum_tc, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.search_btn, 0, wx.EXPAND)        
        fbox.Add((10, -1))
        fbox.Add(self.add_btn, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.refresh_btn, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.close_btn, 0, wx.EXPAND)
        
        gbox = wx.BoxSizer(wx.HORIZONTAL)
        gbox.Add(self.list, 1, wx.EXPAND)
        
        #HBox.Add(fbox, 0, wx.EXPAND, 10)
        HBox.Add(fbox, 0, wx.LEFT | wx.TOP, 10)
        HBox.Add((-1, 20))
        HBox.Add(gbox, 1, wx.LEFT | wx.EXPAND, 10)
        self.SetSizer(HBox)
        
        # ---- layout -----
        
        
        self.Bind(wx.EVT_COMBOBOX, self.EvtComboBox, self.status_cb)
        self.Bind(wx.EVT_BUTTON, self.OnSearchBtn, self.search_btn)
        self.Bind(wx.EVT_BUTTON, self.OnAddBtn, self.add_btn)
        self.Bind(wx.EVT_BUTTON, self.OnREFBtn, self.refresh_btn)
        self.Bind(wx.EVT_BUTTON, self.OnClose, self.close_btn)
    
    def OnClose(self, evt):
        dlg = wx.MessageDialog(self, u'您是否确定退出系统?',
                               u'提示',
                               wx.YES_NO
                               #wx.OK | wx.ICON_INFORMATION
                               #|wx.YES_NO | wx.NO_DEFAULT | wx.CANCEL | wx.ICON_INFORMATION
                               )
        if dlg.ShowModal()==wx.ID_YES:
            self.Parent.Parent.Parent.Close()
        dlg.Destroy()
    
    def OnREFBtn(self, evt):
        #print "refesh..."
        self.list.reload()
        evt.Skip()
        
    def OnSearchBtn(self, evt):
        status = self.status_cb.GetSelection() -1  #选择序列与数据库中定义差1
        if status<0:
            status=""
        num = self.filter_roomnum_tc.GetValue().strip()
        print "status=%s, num=%s" % (status,num)
        self.list.reload(status, num)
        evt.Skip()
    
    def OnAddBtn(self, evt):
        dlg = AddRoomDialog.AddRoomDialog(self)
        try:
            dlg.ShowModal()
        finally:
            dlg.Destroy()
        self.list.reload()
    
        evt.Skip()
    
    def EvtComboBox(self, evt):
        cb = evt.GetEventObject()
        #data = cb.GetClientData(evt.GetSelection())
        #print data
        print evt.GetString() 
        evt.Skip()
        
class UserPanel(wx.Panel):
    def __init__(self, parent, pos=(0,0)):
        wx.Panel.__init__(self, parent)
        self.parent = parent
        self.pos = pos
        self._init_ctrls_()
        
    def _init_ctrls_(self):
        self.SetPosition(self.pos)
        self.SetSize(self.GetSize())
        self.SetBackgroundColour('white')
        
        
        self.filter_st = wx.StaticText(self, label=u'客人姓名')
        self.filter_name_tc = wx.TextCtrl(parent=self, value="", size=(50, 16))
               
        filter_idno_st = wx.StaticText(self, label=u'证件号码')        
        self.filter_idno_tc = wx.TextCtrl(parent=self, value="", size=(100, 16))
        
        
        self.list = UserListCtrl.ListCtrlPanel(self)   
        self.search_btn = wx.Button(self, label=u'搜索')
        self.add_btn = wx.Button(self, label=u'新增')
        self.refresh_btn = wx.Button(self, label=u'刷新')
        self.close_btn = wx.Button(self, label=u'退出', id=wx.ID_CANCEL)
        
        # ---- layout -----
        HBox = wx.BoxSizer(wx.VERTICAL)
        
        fbox = wx.BoxSizer(wx.HORIZONTAL)
        fbox.Add(self.filter_st, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.filter_name_tc, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(filter_idno_st, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.filter_idno_tc, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.search_btn, 0, wx.EXPAND)        
        fbox.Add((10, -1))
        fbox.Add(self.add_btn, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.refresh_btn, 0, wx.EXPAND)
        fbox.Add((10, -1))
        fbox.Add(self.close_btn, 0, wx.EXPAND)
        
        gbox = wx.BoxSizer(wx.HORIZONTAL)
        gbox.Add(self.list, 1, wx.EXPAND)
        
        
        
        #HBox.Add(fbox, 0, wx.EXPAND, 10)
        HBox.Add(fbox, 0, wx.LEFT | wx.TOP, 10)
        HBox.Add((-1, 20))
        HBox.Add(gbox, 1, wx.LEFT | wx.EXPAND, 10)
        self.SetSizer(HBox)
        
        # ---- layout -----
        
        
        #self.Bind(wx.EVT_COMBOBOX, self.EvtComboBox, self.status_cb)
        self.Bind(wx.EVT_BUTTON, self.OnSearchBtn, self.search_btn)
        self.Bind(wx.EVT_BUTTON, self.OnAddBtn, self.add_btn)
        self.Bind(wx.EVT_BUTTON, self.OnREFBtn, self.refresh_btn)
        self.Bind(wx.EVT_BUTTON, self.OnClose, self.close_btn)
    
    def OnClose(self, evt):
        dlg = wx.MessageDialog(self, u'您是否确定退出系统?',
                               u'提示',
                               wx.YES_NO
                               #wx.OK | wx.ICON_INFORMATION
                               #|wx.YES_NO | wx.NO_DEFAULT | wx.CANCEL | wx.ICON_INFORMATION
                               )
        if dlg.ShowModal()==wx.ID_YES:
            self.Parent.Parent.Parent.Close()
        dlg.Destroy()
    
    def OnREFBtn(self, evt):
        #print "refesh..."
        self.list.reload()
        evt.Skip()
        
    def OnSearchBtn(self, evt):
        name = self.filter_name_tc.GetValue().strip()
        id_no = self.filter_idno_tc.GetValue().strip()
        print "name=%s, id_no=%s" % (name,id_no)
        self.list.reload(name, id_no)
        evt.Skip()
    
    def OnAddBtn(self, evt):
        dlg = AddGuestDialog(self)
        try:
            dlg.ShowModal()
        finally:
            dlg.Destroy()
        self.list.reload()
        evt.Skip()
    
    def EvtComboBox(self, evt):
        cb = evt.GetEventObject()
        #data = cb.GetClientData(evt.GetSelection())
        #print data
        print evt.GetString() 
        evt.Skip()

#超级管理页面
class SuperPanel(wx.Panel):
    def __init__(self, parent, pos=(0,0)):
        wx.Panel.__init__(self, parent)
        self.parent = parent
        self.pos = pos
        self._init_ctrls_()
        
    def _init_ctrls_(self):
        self.SetPosition(self.pos)
        self.SetSize(self.GetSize())
        self.SetBackgroundColour('Dark Grey')
        


class MainPanel(wx.Panel):
    def __init__(self, parent, pos=(0,0)):
        wx.Panel.__init__(self, parent)
        self.parent = parent
        self.pos = pos
        self._init_ctrls_()
        #bind
        self.Bind(wx.EVT_SIZE, self.OnSize)
        
    def _init_ctrls_(self):
        self.SetPosition(self.pos)
        self.SetSize(self.GetSize())
        self.SetBackgroundColour('Dark Grey')
        
    def OnSize(self, evt):
        self.Refresh() 
  

class MainFrame(wx.Frame):
    def __init__(
            self, parent, ID, title, pos=wx.DefaultPosition,
            size=wx.DefaultSize, style=wx.DEFAULT_FRAME_STYLE
            ):

        wx.Frame.__init__(self, parent, ID, title, pos, size, style)
        icon=wx.EmptyIcon() 
        icon.LoadFile("image/autorun.ico",wx.BITMAP_TYPE_ICO) 
        self.SetIcon(icon)
        
        panel = wx.Panel(self, -1)
        # create booklist
        win = MainLB(panel, -1)
        # ---- layout -----
        HBox = wx.BoxSizer(wx.VERTICAL)
        HBox.Add(win, 1, wx.EXPAND)
        panel.SetSizer(HBox)
        # ---- end layout -----
        
        self.SetSize((800,600))
        self.SetPosition((10,10))
        self.SetMinSize((800,600))
        self.Center()
        
        self.Bind(wx.EVT_CLOSE, self.OnCloseWindow)


    def OnCloseMe(self, event):
        self.Close(True)

    def OnCloseWindow(self, event):
        self.Destroy()
 
def main():
    app=wx.PySimpleApp()#实际化应用
    frame=MainFrame(None, -1, u'管理系统')
    
    frame.Show()
    app.MainLoop()#进入主消息循环
     
if __name__ == '__main__':
    demoPath = r'F:\google\trunk\hotel\src'
    os.chdir(demoPath)
    main()
    
    