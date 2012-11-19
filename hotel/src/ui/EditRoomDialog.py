#coding:utf-8
#Boa:Dialog:Dialog2

import wx
import wx.lib.masked.numctrl
import wx.lib.stattext

from public import public

def create(parent):
    return EditRoomDialog(parent)

[wxID_DIALOG2, wxID_DIALOG2BUTTON1, wxID_DIALOG2BUTTON2, 
 wxID_DIALOG2COMBOBOX1, wxID_DIALOG2GENSTATICTEXT1, wxID_DIALOG2NUMCTRL1, 
 wxID_DIALOG2NUMCTRL2, wxID_DIALOG2STATICTEXT1, wxID_DIALOG2STATICTEXT2, 
 wxID_DIALOG2STATICTEXT3, wxID_DIALOG2TEXTCTRL1, 
] = [wx.NewId() for _init_ctrls in range(11)]

class EditRoomDialog(wx.Dialog):
    def _init_ctrls(self, prnt, title):
        # generated method, don't edit
        wx.Dialog.__init__(self, id=wxID_DIALOG2, name='', parent=prnt,
              pos=wx.Point(372, 308), size=wx.Size(424, 413),
              style=wx.DEFAULT_DIALOG_STYLE, title=title)
        self.SetClientSize(wx.Size(416, 379))

        self.staticText1 = wx.StaticText(id=wxID_DIALOG2STATICTEXT1,
              label=u'房间编号', name='staticText1',
              parent=self, pos=wx.Point(24, 32), size=wx.Size(48, 14), style=0)

        self.textCtrl1 = wx.TextCtrl(id=wxID_DIALOG2TEXTCTRL1, name='textCtrl1',
              parent=self, pos=wx.Point(104, 32), size=wx.Size(100, 22),
              style=0, value='')

        self.staticText2 = wx.StaticText(id=wxID_DIALOG2STATICTEXT2,
              label=u'租金(月/天)', name='staticText2',
              parent=self, pos=wx.Point(24, 80), size=wx.Size(63, 14), style=0)
        
        self.numCtrl1 = wx.lib.masked.numctrl.NumCtrl(id=wxID_DIALOG2NUMCTRL1,
              name='numCtrl1', parent=self, pos=wx.Point(104, 80),
              size=wx.Size(100, 22), style=0, value=0)

        self.genStaticText1 = wx.lib.stattext.GenStaticText(ID=wxID_DIALOG2GENSTATICTEXT1,
              label=u'客房户型', name='genStaticText1',
              parent=self, pos=wx.Point(24, 120), size=wx.Size(48, 14),
              style=0)

        self.comboBox1 = wx.ComboBox(choices=[], id=wxID_DIALOG2COMBOBOX1,
              name='comboBox1', parent=self, pos=wx.Point(104, 120),
              size=wx.Size(100, 22), style=0, value=u'未知')

        self.staticText3 = wx.StaticText(id=wxID_DIALOG2STATICTEXT3,
              label=u'押金(元)', name='staticText3',
              parent=self, pos=wx.Point(24, 160), size=wx.Size(46, 14),
              style=0)

        self.numCtrl2 = wx.lib.masked.numctrl.NumCtrl(id=wxID_DIALOG2NUMCTRL2,
              name='numCtrl2', parent=self, pos=wx.Point(104, 160),
              size=wx.Size(100, 22), style=0, value=0)

        self.web_rb = wx.RadioBox(
                self, -1, u"宽带", wx.Point(24, 220), wx.DefaultSize,
                [u'无', u'有'], 2, wx.RA_SPECIFY_COLS
                )

        self.button1 = wx.Button(id=wx.ID_OK,
              label=u'保存', name='button1', parent=self,
              pos=wx.Point(56, 296), size=wx.Size(75, 24))

        self.button2 = wx.Button(id=wx.ID_CANCEL,
              label=u'退出', name='button2', parent=self,
              pos=wx.Point(248, 296), size=wx.Size(75, 24))
        
        #bind
        self.button1.Bind(wx.EVT_BUTTON, self.OnSaveBtn, self.button1)
        self.button2.Bind(wx.EVT_BUTTON, self.OnExit, self.button2)
        self.comboBox1.Bind(wx.EVT_TEXT, self.EvtText, self.comboBox1)
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.web_rb)
        

    def __init__(self, parent, roomid=0, title=""):
        self._init_ctrls(parent, title)
        
        # init data
        self.type = u"未知" #房屋类型
        room_type=[]
        self.net = 0 # 0-有宽带  1-无宽带
        for it in public.ROOM_TYPE:
            room_type.append(it[1])
        self.comboBox1.SetItems(room_type)
        
        self.roomid = roomid
        print "roomid:%s" % self.roomid
        if self.roomid:#编辑
            self.textCtrl1.SetValue(self.roomid)
            self.textCtrl1.Disable()
            import sqlite3
            con = sqlite3.connect(public.DB_FILE)
            c = con.cursor()
            sql = """select amt_month,type,amt_ensure,network from room
                       where number='%s'""" % self.roomid
            c.execute(sql)
            row = c.fetchone()
            amt_month = row[0]
            
            self.type = row[1]
            amt_ensure = row[2]
            self.net = row[3]
            print (amt_month,self.type,amt_ensure,self.net)
            
            self.numCtrl1.SetValue(amt_month)
            self.numCtrl2.SetValue(amt_ensure)
            self.web_rb.SetSelection(self.net)
            self.comboBox1.SetValue(self.type)
      
    def OnSaveBtn(self, evt):
        no = self.textCtrl1.GetValue().strip()
        rate = self.numCtrl1.GetValue()
        ensure = self.numCtrl2.GetValue()
        type = self.comboBox1.GetValue()
        network = self.web_rb.GetSelection()
        
        print (no, rate, ensure, self.type, self.net)
        sql = """update room set amt_month=%s, amt_ensure=%s, type='%s', network=%s
                 where number=%s""" % (rate,ensure,type,network, self.roomid)
        print sql
        if public.dbopt(sql):
            public.msgbox(self, u"客房[%s]编辑成功" % no, u"编辑客房")
        else:
            public.msgbox(self, u"编辑失败，请检查输入项", u"编辑客房")
        #self.Parent.loaddata()     
        #print self.Parent.loaddata()
        evt.Skip()

    def OnExit(self, evt):        
        evt.Skip()
    
    def EvtText(self, evt):
        self.type = evt.GetString()
        evt.Skip()
    
    def EvtRadioBox(self, event):
        self.net =  event.GetInt()


if __name__ == '__main__':
    app = wx.PySimpleApp()
    dlg = create(None)
    try:
        dlg.ShowModal()
    finally:
        dlg.Destroy()
    app.MainLoop()
