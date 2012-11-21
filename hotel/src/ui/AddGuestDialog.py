#coding:utf-8
#Boa:Dialog:Dialog2

import wx
import wx.lib.masked.numctrl
import wx.lib.stattext

import public

[wxID_DIALOG2, wxID_DIALOG2BUTTON1, wxID_DIALOG2BUTTON2, 
 wxID_DIALOG2COMBOBOX1, wxID_DIALOG2GENSTATICTEXT1, wxID_DIALOG2NUMCTRL1, 
 wxID_DIALOG2NUMCTRL2, wxID_DIALOG2STATICTEXT1, wxID_DIALOG2STATICTEXT2, 
 wxID_DIALOG2STATICTEXT3, wxID_DIALOG2TEXTCTRL1, 
] = [wx.NewId() for _init_ctrls in range(11)]

class AddGuestDialog(wx.Dialog):
    def _init_ctrls(self, prnt):
        # generated method, don't edit
        wx.Dialog.__init__(self, id=wxID_DIALOG2, name='', parent=prnt,
              pos=wx.Point(372, 308), size=wx.Size(424, 413),
              style=wx.DEFAULT_DIALOG_STYLE, title=u"新增住客")
        self.SetClientSize(wx.Size(416, 379))

        self.st_name = wx.StaticText(label=u'住客姓名*', parent=self, 
			pos=(24, 32), size=(63, 14), style=0)
        self.st_name.SetForegroundColour('red')
        
        self.tc_name = wx.TextCtrl(parent=self, pos=(104, 32), size=(100, 22),
            style=0, value='')
        
        self.rb_sex = wx.RadioBox(
                self, -1, "", wx.Point(224, 20), wx.DefaultSize,
                [u'男', u'女'], 2, wx.RA_SPECIFY_COLS)

        self.st_mobile = wx.StaticText(label=u'联系电话*',parent=self, pos=(24, 80), 
			size=(63, 14), style=0)
        self.st_mobile.SetForegroundColour('red')
        
        self.tc_mobile = wx.TextCtrl(parent=self, pos=(104, 78), size=(100, 22),
            style=0, value='')
        
        self.st_idtype= wx.StaticText(label=u'证件类型',parent=self, pos=(24, 128), 
			size=(63, 14), style=0)
        
        self.cb_idtype = wx.ComboBox(choices=public.ID_TYPES,parent=self, 
		    pos=(104, 120), size=(100, 22), style=0)
        self.cb_idtype.SetSelection(0)
        
        self.st_idno= wx.StaticText(label=u'证件号码',parent=self, pos=(224, 128), 
			size=(63, 14), style=0)
        
        self.tc_idno = wx.TextCtrl(parent=self, pos=(294, 120), size=(100, 22),
            style=0, value='')

        self.st_follows = wx.StaticText(label=u'随行人数', parent=self, pos=(24, 168), 
		    size=(63, 14), style=0)
        
        self.sc_follows = wx.SpinCtrl(initial=0, max=10, min=0, parent=self, 
		    pos=(104,168), size=(48, 22), style=wx.SP_ARROW_KEYS)

        self.st_addr = wx.StaticText(label=u'地址', parent=self, pos=(24,216), 
		    size=(63, 14), style=0)
        
        self.tc_addr = wx.TextCtrl(parent=self, pos=(104, 216), size=(300, 60),
            style=wx.TE_MULTILINE|wx.TE_AUTO_SCROLL|wx.TE_LEFT, value='')
        

        self.btn_save = wx.Button(id=wx.ID_OK,label=u'保存', parent=self,
              pos=(56, 296), size=(75, 24))

        self.btn_cancel = wx.Button(id=wx.ID_CANCEL, label=u'取消',parent=self,
              pos=(248, 296), size=(75, 24))
        
        #bind
        self.Bind(wx.EVT_BUTTON, self.OnSaveBtn, self.btn_save)
        self.Bind(wx.EVT_BUTTON, self.OnExit, self.btn_cancel)
        self.Bind(wx.EVT_TEXT, self.EvtText, self.cb_idtype)
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.rb_sex)
        

    def __init__(self, parent):
        self._init_ctrls(parent)
        
        # init data
        
        
    def OnSaveBtn(self, evt):
        name = self.tc_name.GetValue().strip()
        if not name:
            public.msgbox(self, u"住客姓名不能为空", u"提示")
            self.tc_name.SetFocus()
            return 
        mobile = self.tc_mobile.GetValue().strip()
        if not mobile:
            public.msgbox(self, u"住客电话不能为空", u"提示")
            self.tc_mobile.SetFocus()
            return 
        
        sex = self.rb_sex.GetSelection()
        idtype = self.cb_idtype.GetSelection()
        idno = self.tc_idno.GetValue().strip()
        follow = self.sc_follows.GetValue()
        addr = self.tc_addr.GetValue().strip()
        
        retcode,retmsg = public.db_saveguest(name, idno, mobile, sex, idtype, 
											follow, addr)
        public.msgbox(self, retmsg, u"提示")
        
        evt.Skip()

    def OnExit(self, evt):        
        evt.Skip()
    
    def EvtText(self, evt):
        self.type = evt.GetString()
        evt.Skip()
    
    def EvtRadioBox(self, event):
        self.net =  event.GetInt()


if __name__ == '__main__':
    import os
    demoPath = r'F:\google\trunk\hotel\src'
    os.chdir(demoPath)
    app = wx.PySimpleApp()
    dlg = AddGuestDialog(None)
    try:
        dlg.ShowModal()
    finally:
        dlg.Destroy()
    app.MainLoop()
