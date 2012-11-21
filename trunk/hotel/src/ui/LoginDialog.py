#coding:utf-8
#Boa:Dialog:Dialog1

import wx
import public

class LoginDialog(wx.Dialog):
    def _init_ctrls(self, prnt, title):
        # generated method, don't edit
        wx.Dialog.__init__(self, id=-1, name='', parent=prnt,
              pos=wx.Point(547, 311), size=wx.Size(239, 188),
              style=wx.DEFAULT_DIALOG_STYLE, title=title)
        self.SetClientSize(wx.Size(231, 154))

        self.staticText1 = wx.StaticText(id=-1,
              label=u'用户名:', name='staticText1', parent=self,
              pos=wx.Point(8, 24), size=wx.Size(62, 14), style=0)

        self.textCtrl1 = wx.TextCtrl(id=-1, name='textCtrl1',
              parent=self, pos=wx.Point(104, 16), size=wx.Size(100, 22),
              style=0, value='')

        self.staticText2 = wx.StaticText(id=-1,
              label=u'密  码:', name='staticText2', parent=self,
              pos=wx.Point(8, 56), size=wx.Size(62, 14), style=0)

        self.textCtrl2 = wx.TextCtrl(id=-1, name='textCtrl2',
              parent=self, pos=wx.Point(104, 56), size=wx.Size(100, 22),
              style=wx.TE_PASSWORD, value='')

        self.staticText3 = wx.StaticText(id=-1,
              label='', name='staticText3', parent=self,
              pos=wx.Point(8, 88), size=wx.Size(62, 14), style=0)

        self.staticLine1 = wx.StaticLine(id=-1,
              name='staticLine1', parent=self, pos=wx.Point(0, 112),
              size=wx.Size(224, 2), style=0)

        self.button1 = wx.Button(id=-1, label=u'登录',
              name='button1', parent=self, pos=wx.Point(16, 128),
              size=wx.Size(75, 24), style=0)
        self.button1.Bind(wx.EVT_BUTTON, self.OnButton1Button,
              id=-1)

        self.button2 = wx.Button(id=-1, label=u'取消',
              name='button2', parent=self, pos=wx.Point(128, 128),
              size=wx.Size(75, 24), style=0)
        self.button2.Bind(wx.EVT_BUTTON, self.OnButton2Button,
              id=-1)

    def __init__(self, parent, title):
        self._init_ctrls(parent,title)

    def OnButton1Button(self, event):
        if  self.ChkUser():
            self.Close()
        self.staticText3.SetLabel(u"用户名或密码错误，请重新输入...")
        self.staticText3.SetForegroundColour('red')
        self.staticText3.Refresh()
        
        self.textCtrl1.SetFocus()
        
        event.Skip()

    def OnButton2Button(self, event):
        self.Close()
        event.Skip()
    
    def ChkUser(self):
        user = self.textCtrl1.GetValue().strip()
        passwd = self.textCtrl2.GetValue().strip()
        print user, passwd
        return public.db_chkuser(user, passwd)

if __name__ == '__main__':
    app = wx.PySimpleApp()
    dlg = LoginDialog(None, title=u'登录')
    try:
        dlg.ShowModal()
    finally:
        dlg.Destroy()
    app.MainLoop()
