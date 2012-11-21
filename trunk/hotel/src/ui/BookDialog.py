#coding:utf-8
#Boa:Dialog:Dialog1
import os
import wx
import public

def create(parent):
    return BookDialog(parent)

class BookDialog(wx.Dialog):
    def _init_ctrls(self, prnt):
        # generated method, don't edit
        wx.Dialog.__init__(self, id=-1, name='', parent=prnt,
              pos=wx.Point(376, 94), size=wx.Size(742, 489),
              style=wx.DEFAULT_DIALOG_STYLE, title=u'客人登记')
        self.SetClientSize(wx.Size(734, 455))

        self.stb_1 = wx.StaticBox(id=-1,
              label=u'客人资料', parent=self, 
              pos=wx.Point(16, 8), size=wx.Size(704, 160),
              style=0)

        self.stb_2 = wx.StaticBox(id=-1,
              label=u'客房资料', parent=self, 
              pos=wx.Point(16, 178), size=wx.Size(696, 62),
              style=0)

        self.st_teleid = wx.StaticText(id=-1,
              label=u'联系电话', parent=self, 
              pos=wx.Point(32, 40), size=wx.Size(48, 14), style=0)

        self.tc_teleid= wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(96, 40), size=wx.Size(113, 24), style=0,
              value='1385713445')

        self.st_name = wx.StaticText(id=-1,
              label=u'姓名', parent=self,
              pos=wx.Point(248, 40), size=wx.Size(24, 14), style=0)

        self.tc_name = wx.TextCtrl(id=-1,
              parent=self, pos=wx.Point(304, 40), size=wx.Size(104, 22),
              style=0, value=u'张小康')
        
        self.tc_name.SetFocus()
        
        self.rb_sex = wx.RadioBox(
                self, -1, u"", wx.Point(424, 32), wx.Size(80,36),
                [u'男', u'女'], 2, wx.RA_SPECIFY_COLS)
        
        self.st_flownum = wx.StaticText(label=u'随行人数', parent=self, 
              pos=wx.Point(424, 88), size=wx.Size(48, 14),
              style=0)

        self.spinCtrl1 = wx.SpinCtrl(initial=1,
              max=10, min=0, parent=self, pos=wx.Point(488,
              88), size=wx.Size(48, 22), style=wx.SP_ARROW_KEYS)

        self.st_idtype= wx.StaticText(label=u'证件类型',
              parent=self, pos=wx.Point(32, 88), size=wx.Size(48, 14), style=0)

        self.cb_idtype= wx.ComboBox(choices=public.ID_TYPES, 
			  parent=self, pos=wx.Point(96, 88),
              size=wx.Size(138, 22), style=0)
        self.cb_idtype.SetSelection(0)

        self.st_idno = wx.StaticText(label=u'证件号码', parent=self, pos=(248, 88), 
              size=(48, 14), style=0)

        self.tc_idno = wx.TextCtrl(parent=self, pos=(304, 88), size=(100, 22),
              style=0, value='61032119840610401X')
        
        self.st_addr = wx.StaticText(label=u'联系地址', parent=self, pos=(32, 128), 
              size=(48, 14), style=0)

        self.tc_addr = wx.TextCtrl(parent=self, pos=(96, 128), size=(340, 34),
              style=wx.TE_MULTILINE|wx.TE_AUTO_SCROLL|wx.TE_LEFT, value=u'拱墅区') 
        self.status_cb.SetSelection(0)

        self.staticBox3 = wx.StaticBox(label=u'费用信息', parent=self, 
			  pos=wx.Point(16, 248), size=wx.Size(696, 120), style=0)

        self.staticText6 = wx.StaticText(label=u'房间编号',
              parent=self, pos=wx.Point(32, 208), size=wx.Size(48, 14),
              style=0)

        self.staticText7 = wx.StaticText(label='1001', parent=self, pos=wx.Point(88,
              208), size=wx.Size(28, 14), style=0)

        self.staticText8 = wx.StaticText(label=u'客房类型', parent=self,
              pos=wx.Point(160, 208), size=wx.Size(24, 14), style=0)

        self.staticText9 = wx.StaticText(label=u'一室一厅',
              parent=self, pos=wx.Point(208, 208), size=wx.Size(48, 14),
              style=0)

        self.staticText10 = wx.StaticText(label=u'租金（月/元）',
			  parent=self, pos=(280, 208), size=(24, 14), style=0)

        self.tc_rate = wx.TextCtrl(parent=self, pos=(304, 88), size=(100, 22),
              style=0, value='1000')

        self.st_ensure= wx.StaticText(label=u'押金(元)', parent=self,
              pos=wx.Point(416, 208), size=wx.Size(24, 14), style=0)
      
        self.tc_ensure= wx.TextCtrl(parent=self, pos=(304, 88), size=(100, 22),
              style=0, value='1000')
        
        self.rb_web = wx.RadioBox(
                self, -1, u"宽带", wx.Point(528, 188), wx.DefaultSize,
                [u'有', u'无'], 2, wx.RA_SPECIFY_COLS)

        self.st_contract= wx.StaticText(label=u'编号',
              parent=self, pos=(24, 272), size=(48, 14), style=0)
        
        self.tc_contract= wx.TextCtrl(parent=self, pos=(304, 88), size=(100, 22),
              style=0, value='C101511')

        self.st_signdate= wx.StaticText(label=u'签订日期', 
              parent=self, pos=(160, 272), size=(48, 14),style=0)

        self.staticText17 = wx.StaticText(label='2012-10-10', parent=self,
              pos=wx.Point(224, 272), size=wx.Size(64, 14), style=0)

        self.staticText18 = wx.StaticText(label=u'生效日期',
              parent=self, pos=(312, 272), size=(48, 14), style=0)

        self.staticText19 = wx.StaticText(label='2012-10-12',parent=self,
              pos=wx.Point(376, 272), size=wx.Size(64, 14), style=0)

        self.btn_book = wx.Button(id=wx.ID_OK,
              label=u'客人登记', name='button1', parent=self,
              pos=wx.Point(32, 400), size=wx.Size(75, 24))
        
        self.btn_cannelroom = wx.Button(id=wx.ID_OK,
              label=u'客人退房', name='button2', parent=self,
              pos=wx.Point(232, 400), size=wx.Size(75, 24))
        
        

        self.btn_close = wx.Button(id=wx.ID_CANCEL,
              label=u'退出', parent=self,
              pos=wx.Point(624, 400), size=wx.Size(79, 26), style=0)
        
        imgpath = 'photos/%s.jpg' % self.roomid
        if not os.path.exists(imgpath):
            imgpath =  'photos/unknow.jpg'

        photo = wx.Image(imgpath, wx.BITMAP_TYPE_ANY).ConvertToBitmap()
        wx.StaticBitmap(self, -1, photo,(610, 18), 
					(photo.GetWidth()*0.75, photo.GetHeight()*0.75))
        
        #bind
        self.btn_book.Bind(wx.EVT_BUTTON, self.OnBookBtn, self.btn_book)
        self.btn_cannelroom.Bind(wx.EVT_BUTTON, self.OnCancelRoomBtn, self.btn_cannelroom)
        self.btn_close.Bind(wx.EVT_BUTTON, self.OnCloseBtn, self.btn_close)
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.rb_sex)
        self.Bind(wx.EVT_RADIOBOX, self.EvtRadioBox, self.rb_web)

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
        #insert guest表
        name  = self.tc_name.GetValue().strip()
        id_no = self.tc_idno.GetValue().strip()
        id_type = self.cb_idtype.GetSelection()
        sex = self.rb_sex.GetSelection()
        mobile = self.tc_teleid.GetValue().strip()
        follow_person = self.spinCtrl1.GetValue()
        addr = self.tc_addr.GetValue().strip()
        print (name,id_no,id_type,sex,mobile,follow_person,addr)
        
        #add new guest
        
        
        
        print "roomid", self.roomid
        retcode,retmsg = public.db_bookroom(self.roomid, name, mobile, id_no, id_type, 
								sex, follow_person, addr)
        
        
        if retcode<0:
            public.msgbox(self, info=u'客户端资料保存失败[%s]' % retmsg,title=u'提示')
        else:
            public.msgbox(self, info=u'订房成功[roomid=%s,guestid=%s]' %\
						 (self.roomid, retcode),title=u'提示')
            #update room表
        
            sql = "update room set guest=%s,status=1 where number='%s'" % (retcode, self.roomid) 
            public.dbopt(sql)
        #evt.skip()

    def OnCancelRoomBtn(self, evt):
        print "开始退房.."
        if public.db_cancelroom(self.roomid):
            public.msgbox(self, info=u'退房成功',title=u'提示')
        else:
            public.msgbox(self, info=u'退房失败',title=u'提示')
        #self.Destroy()
        #evt.skip()
    

    def OnCloseBtn(self, evt):
        print "Exit.."
        self.Close()
        pass
        #self.Destroy()
        #evt.skip()
    

if __name__ == '__main__':
    os.chdir(r"F:\workspace\hotel\src")
    app = wx.PySimpleApp()
    dlg = create(None)
    val = dlg.ShowModal()
    print val, wx.ID_OK
    if val == wx.ID_OK:
        print("You pressed OK\n")
    else:
        print("You pressed Cancel\n")

    dlg.Destroy()
    app.MainLoop()
