#coding:utf-8
import  wx

musicdata = [
(u"支出", u"100.00", u"张三", "1000", "aaa"),
(u"支出", u"200", u"李四", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),
(u"收入", u"200", u"王五", "1000", "aaa"),

]
#----------------------------------------------------------------------

class TestVirtualList(wx.ListCtrl):
    def __init__(self, parent):
        wx.ListCtrl.__init__(
            self, parent, -1, 
            style=wx.LC_REPORT|wx.LC_VIRTUAL|wx.LC_HRULES|wx.LC_VRULES
            )


        #self.il = wx.ImageList(16, 16)
        #self.idx1 = self.il.Add(images.Smiles.GetBitmap())
        #self.SetImageList(self.il, wx.IMAGE_LIST_SMALL)


        self.InsertColumn(0, u"类型")
        self.InsertColumn(1, u"金额")
        self.InsertColumn(2, u"经办人")
        self.InsertColumn(3, u"描述")
        self.InsertColumn(4, u"时间")
        self.SetColumnWidth(0, 80)
        self.SetColumnWidth(1, 80)
        self.SetColumnWidth(2, 80)
        self.SetColumnWidth(3, 280)
        #self.SetColumnWidth(4, 100)

        font = wx.Font(8, wx.SWISS, wx.NORMAL, wx.NORMAL, False, u'Segoe UI')

        self.attr1 = wx.ListItemAttr()
        self.attr1.SetBackgroundColour("yellow")
        self.attr1.SetFont(font)
        
        
        
        self.attr2 = wx.ListItemAttr()
        self.attr2.SetBackgroundColour("light blue")
        self.attr2.SetFont(font)
        
        
        #self.ClearAll()
        
        
        #self.loaddate(musicdata)
        self.Bind(wx.EVT_LIST_ITEM_SELECTED, self.OnItemSelected)
        self.Bind(wx.EVT_LIST_ITEM_ACTIVATED, self.OnItemActivated)
        self.Bind(wx.EVT_LIST_ITEM_DESELECTED, self.OnItemDeselected)
        
        #init data
        self.data = []
        
        self.SetItemCount(len(self.data))
        
    
    def loaddate(self, data=[]):
        print "self.data=", self.data
        if not data :return 
        self.data = data
        self.SetItemCount(len(self.data))
        print "self.data=", self.data
        #self.data.reverse()
        #self.RefreshItems()
        self.Refresh()
        
    



    def OnItemSelected(self, event):
        self.currentItem = event.m_itemIndex
        print('OnItemSelected: "%s", "%s", "%s", "%s"\n' %
                           (self.currentItem,
                            self.GetItemText(self.currentItem),
                            self.getColumnText(self.currentItem, 1),
                            self.getColumnText(self.currentItem, 2)))

    def OnItemActivated(self, event):
        self.currentItem = event.m_itemIndex
        print("OnItemActivated: %s\nTopItem: %s\n" %
                           (self.GetItemText(self.currentItem), self.GetTopItem()))

    def getColumnText(self, index, col):
        item = self.GetItem(index, col)
        return item.GetText()

    def OnItemDeselected(self, evt):
        print("OnItemDeselected: %s" % evt.m_itemIndex)


    #---------------------------------------------------
    # These methods are callbacks for implementing the
    # "virtualness" of the list...  Normally you would
    # determine the text, attributes and/or image based
    # on values from some external data source, but for
    # this demo we'll just calculate them
    def OnGetItemText(self, item, col): 
        print "item=%s,col=%s" % (item, col)
        return musicdata[item][col]
        #return "I %d, c %d" % (item, col)

    def OnGetItemImage(self, item):
        if item % 3 == 0:
            #return self.idx1
            return None
        else:
            return -1

    def OnGetItemAttr(self, item):
        if item % 3 == 1:
            return self.attr1
        elif item % 3 == 2:
            return self.attr2
        else:
            return None
