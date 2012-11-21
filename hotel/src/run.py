#coding:utf-8

import wx
import os
from ui.MainFrame import MainFrame
from ui.LoginDialog import LoginDialog
from ui import public


log = public.initlog("sys.log") 

class MySplashScreen(wx.SplashScreen):
    def __init__(self):
        bmp = wx.Image(public.opj("image/splash.png")).ConvertToBitmap()
                
        wx.SplashScreen.__init__(self, bmp,
                                 wx.SPLASH_CENTRE_ON_SCREEN | wx.SPLASH_TIMEOUT,
                                 5000, None, -1)
        self.Bind(wx.EVT_CLOSE, self.OnClose)
        self.fc = wx.FutureCall(2000, self.ShowMain)

    def OnClose(self, evt):
        # Make sure the default handler runs too so this window gets
        # destroyed
        evt.Skip()
        self.Hide()
        
        # if the timer is still running then go ahead and show the
        # main frame now
        if self.fc.IsRunning():
            self.fc.Stop()
            self.ShowMain()


    def ShowMain(self):
        
        frame=MainFrame(None, -1, u'管理系统')
        frame.Show()
        if self.fc.IsRunning():
            self.Raise()
        #wx.CallAfter(frame.ShowTip)
        
        icon=wx.EmptyIcon()
        icon.LoadFile("image/autorun.ico",wx.BITMAP_TYPE_ANY) 
        frame.tbicon=wx.TaskBarIcon()
        frame.tbicon.SetIcon(icon,"LL. Hotel") 
        



class MyApp(wx.App):
    def OnInit(self):
        # lets import images
        #import  ui.images as i 
        #global images
        #images = i
        
        # Create and show the splash screen.  It will then create and show
        # the main frame when it is time to do so.
        wx.SystemOptions.SetOptionInt("mac.window-plain-transition", 1)
        self.SetAppName("wxPyDemo")
        
        # For debugging
        #self.SetAssertMode(wx.PYAPP_ASSERT_DIALOG)

        # Normally when using a SplashScreen you would create it, show
        # it and then continue on with the applicaiton's
        # initialization, finally creating and showing the main
        # application window(s).  In this case we have nothing else to
        # do so we'll delay showing the main frame until later (see
        # ShowMain above) so the users can see the SplashScreen effect.        
        
        #dlg = LoginDialog(None, u"登录")
        #try:
        #    dlg.ShowModal()
        #finally:
        #    dlg.Destroy() 
        #retcode = dlg.ShowModal()
        #print retcode
        #if 5101 == retcode:
        #    return False
        
        #splash = MySplashScreen()
        #splash.Show()
        frame=MainFrame(None, -1, u'管理系统')
        frame.Show()


        return True



def main():
    try:
        demoPath = os.path.dirname(__file__)
        os.chdir(demoPath)
    except:
        pass 
    app = MyApp(False)
    
    app.MainLoop()


if __name__ == '__main__':
    __name__ = 'Main'
    main()