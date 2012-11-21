#coding:utf-8
#----------------------------------------------------------------------------
# Name:         ListCtrl.py
# Purpose:      Testing lots of stuff, controls, window types, etc.
#
# Author:       Robin Dunn & Gary Dumer
#
# Created:
# RCS-ID:       $Id: ListCtrl.py 51049 2008-01-06 21:38:01Z RD $
# Copyright:    (c) 1998 by Total Control Software
# Licence:      wxWindows license
#----------------------------------------------------------------------------
import wx
import sys
import time,datetime
import wx.lib.mixins.listctrl  as  listmix

#import RoomDialog

import public
from EditRoomDialog import EditRoomDialog
from BookDialog import BookDialog


#---------------------------------------------------------------------------

#musicdata = {}
#musicdata = {
#1 : ("101", u"一室一厅", u"空闲", "1000", ""),
#2 : ("102", u"一室一厅", u"空闲", "1000", ""),
#3 : ("103", u"一室一厅", u"使用中", "1000", ""),
#4 : ("104", u"一室一厅", u"空闲", "1000", ""),
#5 : ("201", u"两室一厅", u"使用中", "1200", ""),
#6 : ("202", u"两室一厅", u"空闲", "1200", ""),
#
#}

#---------------------------------------------------------------------------

class TestListCtrl(wx.ListCtrl, listmix.ListCtrlAutoWidthMixin):
    def __init__(self, parent, ID, pos=wx.DefaultPosition,
                 size=wx.DefaultSize, style=0):
        wx.ListCtrl.__init__(self, parent, ID, pos, size, style)
        listmix.ListCtrlAutoWidthMixin.__init__(self)


class RoomListCtrlPanel(wx.Panel, listmix.ColumnSorterMixin):
    def __init__(self, parent):
        wx.Panel.__init__(self, parent, -1, style=wx.WANTS_CHARS)

        
        tID = wx.NewId()
        
        sizer = wx.BoxSizer(wx.VERTICAL)
        
        if wx.Platform == "__WXMAC__" and \
               hasattr(wx.GetApp().GetTopWindow(), "LoadDemo"):
            self.useNative = wx.CheckBox(self, -1, "Use native listctrl")
            self.useNative.SetValue( 
                not wx.SystemOptions.GetOptionInt("mac.listctrl.always_use_generic") )
            self.Bind(wx.EVT_CHECKBOX, self.OnUseNative, self.useNative)
            sizer.Add(self.useNative, 0, wx.ALL | wx.ALIGN_RIGHT, 4)
            
        self.il = wx.ImageList(16, 16)
        
        #wx.Bitmap(public.opj(path), wx.BITMAP_TYPE_PNG)
        self.idx1 = self.il.Add(wx.Bitmap(public.opj('image/smiles2.bmp'), wx.BITMAP_TYPE_BMP))#self.il.Add(images.Smiles.GetBitmap())
        self.sm_up = self.il.Add(wx.Bitmap(public.opj('image/sm_up.bmp'), wx.BITMAP_TYPE_BMP))#self.il.Add(images.Smiles.GetBitmap())
        self.sm_dn = self.il.Add(wx.Bitmap(public.opj('image/sm_down.bmp'), wx.BITMAP_TYPE_BMP))#self.il.Add(images.Smiles.GetBitmap())
        #self.sm_up = self.il.Add(images.SmallUpArrow.GetBitmap())
        #self.sm_dn = self.il.Add(images.SmallDnArrow.GetBitmap())
        
        

        self.list = TestListCtrl(self, tID,
                                 style=wx.LC_REPORT 
                                 | wx.BORDER_SUNKEN
                                 #| wx.BORDER_NONE
                                 #| wx.LC_EDIT_LABELS
                                 #| wx.LC_SORT_ASCENDING
                                 #| wx.LC_NO_HEADER
                                 | wx.LC_VRULES
                                 | wx.LC_HRULES
                                 | wx.LC_SINGLE_SEL
                                 )
        
        self.list.SetImageList(self.il, wx.IMAGE_LIST_SMALL)
        sizer.Add(self.list, 1, wx.EXPAND)
        self.PopulateList()

        # Now that the list exists we can init the other base class,
        # see wx/lib/mixins/listctrl.py
        
        """ read data from DB """
        musicdata= {}
        dbret = public.dbgetroomlist()
        for i in range(len(dbret)):
            musicdata[i+1] = dbret[i] 
        
        self.itemDataMap = musicdata
        
        listmix.ColumnSorterMixin.__init__(self, len(public.ROOM_LIST))
        #self.SortListItems(0, True)

        self.SetSizer(sizer)
        self.SetAutoLayout(True)

        self.Bind(wx.EVT_LIST_ITEM_SELECTED, self.OnItemSelected, self.list)
        self.Bind(wx.EVT_LIST_ITEM_DESELECTED, self.OnItemDeselected, self.list)
        self.Bind(wx.EVT_LIST_ITEM_ACTIVATED, self.OnItemActivated, self.list)
        self.Bind(wx.EVT_LIST_DELETE_ITEM, self.OnItemDelete, self.list)
        self.Bind(wx.EVT_LIST_COL_CLICK, self.OnColClick, self.list)
        self.Bind(wx.EVT_LIST_COL_RIGHT_CLICK, self.OnColRightClick, self.list)
        self.Bind(wx.EVT_LIST_COL_BEGIN_DRAG, self.OnColBeginDrag, self.list)
        #self.Bind(wx.EVT_LIST_COL_DRAGGING, self.OnColDragging, self.list)
        self.Bind(wx.EVT_LIST_COL_END_DRAG, self.OnColEndDrag, self.list)
        #self.Bind(wx.EVT_LIST_BEGIN_LABEL_EDIT, self.OnBeginEdit, self.list)

        self.list.Bind(wx.EVT_LEFT_DCLICK, self.OnDoubleClick)
        self.list.Bind(wx.EVT_RIGHT_DOWN, self.OnRightDown)

        # for wxMSW
        self.list.Bind(wx.EVT_COMMAND_RIGHT_CLICK, self.OnRightClick)

        # for wxGTK
        self.list.Bind(wx.EVT_RIGHT_UP, self.OnRightClick)


    def OnUseNative(self, event):
        wx.SystemOptions.SetOptionInt("mac.listctrl.always_use_generic", not event.IsChecked())
        wx.GetApp().GetTopWindow().LoadDemo("ListCtrl")

    def reload(self, roomstatus="", num=""):
        """
    	roomstatus 房间状态
    	num string  房号
    	"""
        musicdata= {}
        dbret = public.dbgetroomlist(roomstatus, num)
        for i in range(len(dbret)):
            musicdata[i+1] = dbret[i] 
        
        self.itemDataMap = musicdata
        self.loaddata(musicdata)
    
    def PopulateList(self):
        for i in range(len(public.ROOM_LIST)):
            self.list.InsertColumn(i, public.ROOM_LIST[i])
        
        self.reload()

    
    def loaddata(self, musicdata={}):
        # clear item frist
        self.list.DeleteAllItems()
        items = musicdata.items()
        
        for key, data in items:
            d0 = "%s" % data[0]
            d1 = "%s" % data[1]
            d2 = public.ROOM_STATUS[data[2]]
            d3 = "%s" % data[3]
            d4 = ""
            d5 = ""
            if data[4]:
                d4 = data[4]
            
            if data[5]:
                
                m_array = data[5].split("-")
                data1 = datetime.date(int(m_array[0]), int(m_array[1]), 
									int(m_array[2]))
                itl = (datetime.date.today() - data1).days
                d5 = "%s(%s)" % (str(itl).ljust(3), data[5]) 
            
            index = self.list.InsertImageStringItem(sys.maxint, d0, self.idx1)
            #print key,data, index
            self.list.SetStringItem(index, 1, d1)
            self.list.SetStringItem(index, 2, d2)
            self.list.SetStringItem(index, 3, d3)
            self.list.SetStringItem(index, 4, d4)
            self.list.SetStringItem(index, 5, d5)
            self.list.SetItemData(index, key)
            
            if data[2] == 1: #使用中            
                item = self.list.GetItem(index)
                item.SetTextColour(wx.BLUE)
                item.SetBackgroundColour("pink")
                self.list.SetItem(item)
            
            if data[2] == 2: #欠费            
                item = self.list.GetItem(index)
                item.SetTextColour(wx.BLUE)
                item.SetBackgroundColour("green")
                self.list.SetItem(item)

        self.list.SetColumnWidth(0, 80)
        self.list.SetColumnWidth(1, 80)
        self.list.SetColumnWidth(2, 80)
        self.list.SetColumnWidth(3, 80)
        self.list.SetColumnWidth(4, 80)
        self.list.SetColumnWidth(5, wx.LIST_AUTOSIZE)

        # show how to select an item
        self.list.SetItemState(6, wx.LIST_STATE_SELECTED, wx.LIST_STATE_SELECTED)

        # show how to change the colour of a couple items
        
        #item = self.list.GetItem(1)
        #item.SetTextColour(wx.BLUE)
        #self.list.SetItem(item)
        #item = self.list.GetItem(4)
        #item.SetTextColour(wx.RED)
        #self.list.SetItem(item)

        self.currentItem = 0
        
        self.itemDataMap = musicdata
    
    # Used by the ColumnSorterMixin, see wx/lib/mixins/listctrl.py
    def GetListCtrl(self):
        return self.list

    # Used by the ColumnSorterMixin, see wx/lib/mixins/listctrl.py
    #def GetSortImages(self):
    #    return (self.sm_dn, self.sm_up)


    def OnRightDown(self, event):
        x = event.GetX()
        y = event.GetY()
        print("x, y = %s\n" % str((x, y)))
        item, flags = self.list.HitTest((x, y))

        if item != wx.NOT_FOUND and flags & wx.LIST_HITTEST_ONITEM:
            self.list.Select(item)

        event.Skip()


    def getColumnText(self, index, col):
        item = self.list.GetItem(index, col)
        return item.GetText()


    def OnItemSelected(self, event):
        ##print event.GetItem().GetTextColour()
        self.currentItem = event.m_itemIndex
        print("OnItemSelected: %s, %s, %s, %s\n" %
                           (self.currentItem,
                            self.list.GetItemText(self.currentItem),
                            self.getColumnText(self.currentItem, 1),
                            self.getColumnText(self.currentItem, 2)))

        if self.currentItem == 10:
            print("OnItemSelected: Veto'd selection\n")
            #event.Veto()  # doesn't work
            # this does
            self.list.SetItemState(10, 0, wx.LIST_STATE_SELECTED)

        event.Skip()


    def OnItemDeselected(self, evt):
        #item = evt.GetItem()
        print("OnItemDeselected: %d" % evt.m_itemIndex)

        # Show how to reselect something we don't want deselected
        if evt.m_itemIndex == 11:
            wx.CallAfter(self.list.SetItemState, 11, wx.LIST_STATE_SELECTED, wx.LIST_STATE_SELECTED)


    def OnItemActivated(self, event):
        self.currentItem = event.m_itemIndex
        print("OnItemActivated: %s\nTopItem: %s" %
                           (self.list.GetItemText(self.currentItem), self.list.GetTopItem()))

    def OnBeginEdit(self, event):
        print("OnBeginEdit")
        event.Allow()

    def OnItemDelete(self, event):
        print("OnItemDelete\n")

    def OnColClick(self, event):
        print("OnColClick: %d\n" % event.GetColumn())
        event.Skip()

    def OnColRightClick(self, event):
        item = self.list.GetColumn(event.GetColumn())
        print("OnColRightClick: %d %s\n" %
                           (event.GetColumn(), (item.GetText(), item.GetAlign(),
                                                item.GetWidth(), item.GetImage())))

    def OnColBeginDrag(self, event):
        print("OnColBeginDrag\n")
        ## Show how to not allow a column to be resized
        #if event.GetColumn() == 0:
        #    event.Veto()


    def OnColDragging(self, event):
        print("OnColDragging\n")

    def OnColEndDrag(self, event):
        print("OnColEndDrag\n")

    def OnDoubleClick(self, event):
        print("OnDoubleClick item %s\n" % self.list.GetItemText(self.currentItem))
        roomid = self.list.GetItemText(self.currentItem)
        dlg = BookDialog(self, roomid)
        dlg.Center()
        dlg.ShowModal()
        self.reload()

    def OnRightClick(self, event):
        print("OnRightClick %s\n" % self.list.GetItemText(self.currentItem))
        
        # only do this part the first time so the events are only bound once
        if not hasattr(self, "popupID1"):
            self.popupID1 = wx.NewId()
            self.popupID2 = wx.NewId()
            self.popupID3 = wx.NewId()
            self.popupID4 = wx.NewId()
            self.popupID5 = wx.NewId()
            self.popupID6 = wx.NewId()
            self.popupID7 = wx.NewId()

            self.Bind(wx.EVT_MENU, self.OnPopupOne, id=self.popupID1)
            self.Bind(wx.EVT_MENU, self.OnPopupTwo, id=self.popupID2)
            self.Bind(wx.EVT_MENU, self.OnPopupThree, id=self.popupID3)
            self.Bind(wx.EVT_MENU, self.OnPopupFour, id=self.popupID4)
            self.Bind(wx.EVT_MENU, self.OnPopupFive, id=self.popupID5)
            self.Bind(wx.EVT_MENU, self.OnPopupSix, id=self.popupID6)
            self.Bind(wx.EVT_MENU, self.OnPopupSeven, id=self.popupID7)

        # make a menu
        menu = wx.Menu()
        # add some items
        menu.Append(self.popupID1, u"住户入住")
        menu.Append(self.popupID2, u"费用录入")
        menu.Append(self.popupID3, u"住户缴费")
        menu.Append(self.popupID4, u"结账退房")
        menu.Append(self.popupID5, u"编辑客房")
        menu.Append(self.popupID6, u"删除客房")
        menu.Append(self.popupID7, u"预留1")

        # Popup the menu.  If an item is selected then its handler
        # will be called before PopupMenu returns.
        self.PopupMenu(menu)
        menu.Destroy()


    #===========================================================================
    # OnPopupOne：住户入住
    #===========================================================================
    def OnPopupOne(self, event):
        roomid = self.list.GetItemText(self.currentItem)
        dlg = BookDialog(self, roomid)
        dlg.Center()
        dlg.ShowModal()
        self.reload()

    #===========================================================================
    # OnPopupTwo：费用录入
    #===========================================================================
    def OnPopupTwo(self, event):
        print("Selected items:\n")
        index = self.list.GetFirstSelected()

        while index != -1:
            print("      %s: %s\n" % (self.list.GetItemText(index), self.getColumnText(index, 1)))
            index = self.list.GetNextSelected(index)

    #===========================================================================
    # OnPopupThree：住户缴费
    #===========================================================================
    def OnPopupThree(self, event):
        print("住户缴费\n")
        

    #===========================================================================
    # OnPopupFour：结账退房
    #===========================================================================
    def OnPopupFour(self, event):
        pass

    #===========================================================================
    # OnPopupFive：编辑客房
    #===========================================================================
    def OnPopupFive(self, event):
        #self.list.EditLabel(self.currentItem)
        roomid = self.getColumnText(self.currentItem, 0).strip()
        dlg = EditRoomDialog(self, roomid, u"客房编辑[%s]" % roomid)
        try:
            dlg.ShowModal()
        finally:
            dlg.Destroy()
        
        self.reload()
  
    #===========================================================================
    # OnPopupSix：删除客房
    #===========================================================================
    def OnPopupSix(self, event):
        #self.list.DeleteItem(self.currentItem)
        roomid = self.getColumnText(self.currentItem, 0).strip()
        sql = "delete from room where number=%s" % roomid
        print sql
        
        retcode,retmsg = public.dbopt(sql)
        public.msgbox(self, "%s[%s]" % (retmsg,roomid), u"提示")
        self.list.DeleteItem(self.currentItem)

    #===========================================================================
    # OnPopupSeven：预留
    #===========================================================================
    def OnPopupSeven(self, event):
        print u'预留'
         

