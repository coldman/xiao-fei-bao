#coding:utf-8
'''
Created on 2012-10-10
@author: zhangxk
'''
import  wx   
     
class MainTB(wx.Treebook):
    def __init__(self, parent, id):
        wx.Treebook.__init__(self, parent, id, style=wx.DEFAULT)
        idGenerator=self.getNextID(50)#实例化生成器
        first=True
        panlist=self.getPageList()#得到目录列表
        for x in panlist:
            for k,v in x.items():
                win=self.makeDemoPanel()#父页面
                self.AddPage(win,k,imageId=idGenerator.next())
                if first:
                    first=False
                else:
                    for sub in v:
                        win=sub[1]
                        self.AddSubPage(win,sub[0],imageId=idGenerator.next())
                        #由上面的例子可以看出，父/子页面关系完全由添加的顺序而定
                        #即你AddPage后AddSubPage，一定是刚才这个Page所对应的菜单的子菜单的页面，
                        #所以看不到设“父目录”的代码
 
        self.Bind(wx.EVT_TREEBOOK_PAGE_CHANGED, self.OnPageChanged)
        self.Bind(wx.EVT_TREEBOOK_PAGE_CHANGING, self.OnPageChanging)
 
        # This is a workaround for a sizing bug on Mac...
        wx.FutureCall(100, self.AdjustSize)
        
 
    def getNextID(self,count):
        '''一个ID生成器'''
        imID = 0
        while True:
            yield imID
            imID += 1
            if imID == count:
                imID = 0
                 
    def AdjustSize(self):
        self.GetTreeCtrl().InvalidateBestSize()
        self.SendSizeEvent()
         
 
    def makeDemoPanel(self,type=None):
        p = wx.Panel(self, -1)#子窗体的容器
        win=wx.Panel(p,-1)#子窗体容器包含的页面，此处我还是用了一个panel
        wx.StaticText(win,-1,type or "parent")#此两句，在项目中用的时候应该包装出去，因为你的窗体可能非常复杂，而不是像我现在演示的这样仅仅只有一个Label
        p.win = win
        def OnCPSize(evt, win=win):
            win.SetPosition((0,0))
            win.SetSize(evt.GetSize())
        p.Bind(wx.EVT_SIZE, OnCPSize)
        return p
    
    def makeRoomPanel(self, type=None):
        
        pass
     
    def getPageList(self):
        '''我是以父目录为键名，子目录List为键值来保存本示例的菜单数据
          同时，子目录列表的结构为（子目录名，对应窗体对象）的元组，这样每次生成tree的子目录的时候，可以直接把窗体对象AddSubPage进去
        '''
        return [
            {u'客房管理':[]}, 
            {u'收支流水':[(u'',self.makeDemoPanel('内页1')),('二级目录二',self.makeDemoPanel('内页2')),('二级目录三',self.makeDemoPanel('内页3')),('二级目录四',self.makeDemoPanel('内页4')),]}, 
            {u'报表中心':[('二级目录一',self.makeDemoPanel('内页1')),('二级目录二',self.makeDemoPanel('内页2')),('二级目录三',self.makeDemoPanel('内页3')),('二级目录四',self.makeDemoPanel('内页4')),]}, 
            {u'超级管理':[('二级目录一',self.makeDemoPanel('内页1')),('二级目录二',self.makeDemoPanel('内页2')),('二级目录三',self.makeDemoPanel('内页3')),('二级目录四',self.makeDemoPanel('内页4')),]}, 
            ]
 
 
    def OnPageChanged(self, event):
        '''演示捕捉页面切换事件'''
        old = event.GetOldSelection()
        new = event.GetSelection()
        sel = self.GetSelection()
        print 'OnPageChanged,  old:%d, new:%d, sel:%d\n' % (old, new, sel)
        event.Skip()
 
    def OnPageChanging(self, event):
        '''演示捕捉页面切换事件'''
        old = event.GetOldSelection()
        new = event.GetSelection()
        sel = self.GetSelection()
        print 'OnPageChanging, old:%d, new:%d, sel:%d\n' % (old, new, sel)
        event.Skip()
 
def main():
    app=wx.App()#实际化应用
    frame=wx.Frame(None,-1,u"管理系统")#实例化窗体
    
    icon=wx.EmptyIcon() 
    icon.LoadFile("autorun.ico",wx.BITMAP_TYPE_ICO) 
    frame.SetIcon(icon)
     
    def onExitApp(evt):
        '''退出的方法'''
        frame.Close(True)
        
    menuBar=wx.MenuBar()#顺便生成一个菜单，含有“退出”功能
    menu=wx.Menu()
    item=menu.Append(-1,"Exit\tCtrl-Q","Exit")
    frame.Bind(wx.EVT_MENU,onExitApp,item)
    frame.SetMenuBar(menuBar)
     
    menuBar.Append(menu,"&File")
    win=MainTB(frame,-1)#生成TreeBook
    if win:
        frame.SetSize((800,600))
        frame.Centre()
        frame.Show()
        win.SetFocus()
        frame.window=win#设置TreeBook控件为主窗体对象（其实我还是不明白frame对象的window属性有什么特权）
    else:
        frame.Destory()
      
    app.MainLoop()#进入主消息循环
     
if __name__ == '__main__':
    main()
    
    