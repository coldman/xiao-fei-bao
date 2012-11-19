#coding:utf-8

import wx
import ListCtrl_virtual

#收支流水页面        
class DetailPanel(wx.Panel):
    def __init__(self, parent, pos=(0,0)):
        wx.Panel.__init__(self, parent)
        self.parent = parent
        self.pos = pos
        self._init_ctrls_()
        
        
        
    def _init_ctrls_(self):
        self.SetPosition(self.pos)
        self.SetSize(self.GetSize())
        self.SetBackgroundColour('while')
        
        
        st_begin = wx.StaticText(self, -1, u"起始时间")
        begin = wx.DatePickerCtrl(self, 
                                style = wx.DP_DROPDOWN
                                      | wx.DP_SHOWCENTURY
                                      | wx.DP_ALLOWNONE )
        
        
        st_end = wx.StaticText(self, -1, u"截止时间")
        end = wx.DatePickerCtrl(self, 
                                style = wx.DP_DROPDOWN
                                      | wx.DP_SHOWCENTURY
                                      | wx.DP_ALLOWNONE )
        
        
        self.btn_search = wx.Button(self, -1, u"搜索")
        
        self.list = ListCtrl_virtual.TestVirtualList(self)
                
        # ---- layout -----
        HBox = wx.BoxSizer(wx.VERTICAL)        
        hbox = wx.BoxSizer(wx.HORIZONTAL)
        hbox.Add(st_begin, 0, wx.LEFT | wx.TOP, 10)
        hbox.Add((20, -1))
        hbox.Add(begin, 0, wx.TOP, 10)
        hbox.Add((20, -1))
        hbox.Add(st_end, 0, wx.TOP, 10)
        hbox.Add((20, -1))
        hbox.Add(end, 0, wx.TOP, 10)
        hbox.Add((20, -1))
        hbox.Add(self.btn_search, 0, wx.TOP, 10)
        
        
        bbox = wx.BoxSizer(wx.HORIZONTAL)
        bbox.Add(self.list, 1, wx.LEFT |wx.EXPAND, 10)
        
        
        HBox.Add(hbox, 0, wx.EXPAND)
        HBox.Add((-1, 20))
        HBox.Add(bbox, 1, wx.EXPAND)
        self.SetSizer(HBox)
        
        #bind
        self.Bind(wx.EVT_DATE_CHANGED, self.OnBeginDate, begin)
        self.Bind(wx.EVT_DATE_CHANGED, self.OnEndDate, end)
        self.Bind(wx.EVT_BUTTON, self.OnSearch, self.btn_search)
     
    def OnBeginDate(self, evt):
        print ("OnDateChanged: %s" % evt.GetDate())
        
    def OnEndDate(self, evt):
        print ("OnDateChanged: %s" % evt.GetDate())

    def OnSearch(self, evt):
        self.list.loaddate(ListCtrl_virtual.musicdata)
        print ("OnSearch\n")



class TestFrame(wx.Frame):
    def __init__(self, parent, log):
        wx.Frame.__init__(self, parent, -1, "Huge (virtual) Table Demo", size=(640,480))
        p = DetailPanel(self)


#---------------------------------------------------------------------------

if __name__ == '__main__':
    import sys
    app = wx.PySimpleApp()
    frame = TestFrame(None, sys.stdout)
    frame.Show(True)
    app.MainLoop()