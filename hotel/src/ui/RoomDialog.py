#coding:utf-8

import os
import wx

def create(parent):
    return RoomDialog(parent)

class RoomDialog(wx.Dialog):
    def _init_ctrls(self, prnt):
        # generated method, don't edit
        wx.Dialog.__init__(self, id=-1, parent=prnt, pos=(376, 94), 
			  size=(742, 489),  style=0, title=u'客人登记')
        self.SetClientSize(wx.Size(734, 455))

        self.staticBox1 = wx.StaticBox(id=-1,
              label=u'客人资料', parent=self, 
              pos=wx.Point(16, 8), size=wx.Size(704, 160),
              style=0)

        self.staticBox2 = wx.StaticBox(id=-1,
              label=u'客房资料', parent=self, 
              pos=wx.Point(16, 178), size=wx.Size(696, 62),
              style=0)

        self.staticText1 = wx.StaticText(id=-1,
              label=u'客人编号', parent=self, 
              pos=wx.Point(32, 40), size=wx.Size(48, 14), style=0)

        self.textCtrl1 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(96, 40), size=wx.Size(113, 24), style=0,
              value='')

        self.staticText2 = wx.StaticText(id=-1,
              label=u'姓名', parent=self,
              pos=wx.Point(248, 40), size=wx.Size(24, 14), style=0)

        self.textCtrl2 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 40), size=wx.Size(104, 22),
              style=0, value='')

        self.sex_rb = wx.RadioBox(
                self, -1, u"", wx.Point(424, 32), wx.Size(80,36),
                [u'男', u'女'], 2, wx.RA_SPECIFY_COLS
                )
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.sex_rb)
       

        self.staticText3 = wx.StaticText(id=-1,
              label=u'随行人数', parent=self, 
              pos=wx.Point(424, 88), size=wx.Size(48, 14),
              style=0)

        self.spinCtrl1 = wx.SpinCtrl(id=-1, initial=1,
              max=10, min=0, name='spinCtrl1', parent=self, pos=wx.Point(488,
              88), size=wx.Size(48, 22), style=wx.SP_ARROW_KEYS)

        self.staticText4 = wx.StaticText(id=-1,
              label=u'证件类型',
              parent=self, pos=wx.Point(32, 88), size=wx.Size(48, 14), style=0)

        self.comboBox1 = wx.ComboBox(choices=[u'身份证', u'军官证', u'驾驶证'], 
			  id=-1, parent=self, pos=wx.Point(96, 88),
              size=wx.Size(138, 22), style=0)
        self.comboBox1.SetValue(u"身份证")

        self.staticText5 = wx.StaticText(id=-1,
              label=u'证件号码', parent=self, pos=wx.Point(248, 88), 
              size=wx.Size(48, 14), style=0)

        self.textCtrl3 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 88), size=wx.Size(100, 22),
              style=0, value='')

        self.staticBox3 = wx.StaticBox(id=-1,
              label=u'费用信息', parent=self, pos=wx.Point(16, 248), 
              size=wx.Size(696, 120), style=0)

        self.btn_book = wx.Button(id=wx.OK,
              label=u'客人登记', parent=self, pos=wx.Point(32, 400), 
              size=wx.Size(79, 26),style=0)

        self.staticText6 = wx.StaticText(id=-1,
              label=u'房间编号',
              parent=self, pos=wx.Point(32, 208), size=wx.Size(48, 14),
              style=0)

        self.staticText7 = wx.StaticText(id=-1,
              label='1001', parent=self, pos=wx.Point(88,
              208), size=wx.Size(28, 14), style=0)

        self.staticText8 = wx.StaticText(id=-1,
              label=u'客房类型', parent=self,
              pos=wx.Point(160, 208), size=wx.Size(24, 14), style=0)

        self.staticText9 = wx.StaticText(id=-1,
              label=u'一室一厅',
              parent=self, pos=wx.Point(208, 208), size=wx.Size(48, 14),
              style=0)

        self.staticText10 = wx.StaticText(id=-1,
              label=u'租金（月/元）', name='staticText10', parent=self,
              pos=wx.Point(280, 208), size=wx.Size(24, 14), style=0)

        self.textCtrl4 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 88), size=wx.Size(100, 22),
              style=0, value='1000')

        self.staticText12 = wx.StaticText(id=-1,
              label=u'押金(元)', name='staticText12', parent=self,
              pos=wx.Point(416, 208), size=wx.Size(24, 14), style=0)

      
        self.textCtrl5 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 88), size=wx.Size(100, 22),
              style=0, value='1000')

        
        self.web_rb = wx.RadioBox(
                self, -1, u"宽带", wx.Point(528, 188), wx.DefaultSize,
                [u'有', u'无'], 2, wx.RA_SPECIFY_COLS
                )
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.web_rb)
        

        self.staticText14 = wx.StaticText(id=-1,
              label=u'编号', name='staticText14',
              parent=self, pos=wx.Point(24, 272), size=wx.Size(48, 14),
              style=0)
        
        self.textCtrl6 = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 88), size=wx.Size(100, 22),
              style=0, value='C101511')

        self.staticText16 = wx.StaticText(id=-1,
              label=u'签订日期', name='staticText16',
              parent=self, pos=wx.Point(160, 272), size=wx.Size(48, 14),
              style=0)

        self.staticText17 = wx.StaticText(id=-1,
              label='2012-10-10', name='staticText17', parent=self,
              pos=wx.Point(224, 272), size=wx.Size(64, 14), style=0)

        self.staticText18 = wx.StaticText(id=-1,
              label=u'生效日期', name='staticText18',
              parent=self, pos=wx.Point(312, 272), size=wx.Size(48, 14),
              style=0)

        self.staticText19 = wx.StaticText(id=-1,
              label='2012-10-12', name='staticText19', parent=self,
              pos=wx.Point(376, 272), size=wx.Size(64, 14), style=0)


        self.btn_close = wx.Button(id=wx.CANCEL,
              label=u'退出', parent=self,
              pos=wx.Point(624, 408), size=wx.Size(79, 26), style=0)
        
        imgpath = 'photos/%s.jpg' % self.roomid
        if not os.path.exists(imgpath):
            imgpath =  'photos/unknow.jpg'

        
        photo = wx.Image(imgpath, wx.BITMAP_TYPE_ANY).ConvertToBitmap()
        wx.StaticBitmap(self, -1, photo,(610, 18), 
					(photo.GetWidth()*0.75, photo.GetHeight()*0.75))
        
        #bind
        self.btn_book.Bind(wx.EVT_BUTTON, self.OnBookBtn, self.btn_book)
        self.btn_close.Bind(wx.EVT_BUTTON, self.OnCloseBtn, self.btn_close)
        

    def __init__(self, parent, roomid=""):
        self.roomid= roomid
        self._init_ctrls(parent)
        
        self.SetTitle(u"客房[%s]" % roomid)
        
    def _init_data_(self):
        
        
        pass
    
    def OnRadioButton1Radiobutton(self, event):
        event.Skip()
    
    def EvtRadioBox(self, event):
        print ('EvtRadioBox: %d\n' % event.GetInt())

    def OnBookBtn(self, evt):
        #evt.skip()
        pass

    def OnCloseBtn(self, evt):
        self.Destroy()
        #evt.skip()
        #pass

if __name__ == '__main__':
    os.chdir(r"F:\google\trunk\hotel\src")
    app = wx.PySimpleApp()
    dlg = create(None)
    
    dlg.ShowModal()
    app.MainLoop()
