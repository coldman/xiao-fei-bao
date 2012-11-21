#coding:utf-8
import wx
import sqlite3
import logging
import sys
import os 


#房屋类型 (编号，名称)
ROOM_TYPE = [(0,u"1室1厅1卫"), (1,u'2室1厅1卫'), (2,u'1室0厅1卫'), (99,u'未知')]
ROOM_STATUS = {0:u"空闲 ", 1:u"使用中", 2:u"欠费", 3:u"未知"}
ROOM_NETWORK = {0:u"无宽带", 1:u"有宽带"}
ROOM_LIST = [u"房号", u"户型", u"状态", u"月租金", u"住户姓名", u"到期天数"]
USER_LIST = [u"编号", u"姓名", u"证件号码", u"电话", u"性别", u"房间号", u"备注"]
USER_SEX = {0:u"男", 1:u"女"}
ID_TYPES = [u"身份证",u"驾驶证", u"军官证", u"其他"]



#数据库

#TODO:
DB_FILE=  os.getcwd()+r'/db/llhotel.db'
#DB_FILE=  r'F:\workspace\hotel\src\db/llhotel.db'
def TransMap(key, d={}):
    value = u"未知"
    if not d.has_key(key): return value
    return d[key]


def opj(path):
    """Convert paths to the platform-specific separator"""
    st = apply(os.path.join, tuple(path.split('/')))
    # HACK: on Linux, a leading / gets lost...
    if path.startswith('/'):
        st = '/' + st
    return st

def initlog(logfile='system.log'):
    print logfile
    logger = logging.getLogger()
    hdlr = logging.FileHandler(logfile)
    formatter = logging.Formatter('%(asctime)s %(levelname)s %(message)') 
    hdlr.setFormatter(formatter)
    logger.addHandler(hdlr)
    logger.setLevel(logging.NOTSET)
    return logger

def myprint(strp):
    co = sys._getframe(1).f_code
    print "(%s:%d @%s) %s" % (co.co_filename, co.co_firstlineno,co.co_name,strp)

#-------------DB---------------------------------------------------------------#
def dbopt(sql=""):
    if not sql:return False
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        myprint(sql)
        c.execute(sql)
        conn.commit()
        c.close()
        return (True, u"执行成功")
    except Exception, e:
        print e
        return (False, str(e))

def dbselectone():
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        c.execute("sql")
        conn.commit()
        c.close()
        return True
    except Exception, e:
        print e
        return False
       
def dbgetroomlist(status="", num=""):
    rooms = []
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """select a.number as number,a.type,a.status as status,a.amt_month,
                     b.name, a.contract_enable as contract_enable
                 from room a 
                 left join guests b on a.guest=b.id """
        
        
        
        sql = "select * from (%s) T where 1=1 " % sql 
        
        
        if status : 
            sql += " and T.status=%s" % status
        if num :
            sql += " and T.number=%s" % num
        sql += " order by T.status desc"

        #print myprint(sql)
        c.execute(sql)
        rooms = c.fetchall()
        conn.commit()
        c.close()
    except Exception, e:
        print e
    return rooms

def db_getroom(id):
    if not id:return None
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """select * from room
                  where number=%s""" % id
        print sql
        c.execute(sql)
        room = c.fetchone()
        conn.commit()
        c.close()
        return room

    except Exception, e:
        print e
        return None 

def db_addguest(name, mobile, id_no="",id_type=0,sex=0,follow_person=0,
            addr=""):
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """select id from guests
                  where name='%s' and mobile='%s'""" % (name, mobile)
        myprint(sql) 
        c.execute(sql)
        
        if c.rowcount>0:#已存在
            sql = """update gusets set (id_no,id_type,sex,follow_person,addr)
                     values ('%s',%s,%s,%s,'%s') 
                     where name='%s' and mobile='%s'""" % (id_no,id_type,sex,
										follow_person,addr,name,mobile)
            #
        
        sql = """insert into guests (name,id_no,id_type,sex,mobile,
                    follow_person, addr) values 
                    ('%s','%s',%s,%s,'%s',%s,'%s')""" %\
                     (name,id_no,id_type,sex,mobile,follow_person,addr)
        myprint( sql)
        c.execute(sql)
        c.close()
        conn.close()
        return True
         
    except Exception, e:
        myprint(str(e))
        return False

     

def db_bookroom(roomid, guestid):
   
    if not roomid or not guestid:
        print "输入数据不足(roomid,name,mobile)必输项"
        return (-1, u'数据不足')

    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        #TODO:
        
        
        c.close()
        conn.close()
    except Exception, e:
        print e
        return (-3, str(2))

def db_cancelroom(roomid):
    if not roomid:return False
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """update room set status=0, guest=0 where number=%s""" % roomid
        c.execute(sql)
        conn.commit()
        c.close()
        return True
    except Exception, e:
        print e
        return False

def msgbox(parent, info='info', title='title'):  
    dlg = wx.MessageDialog(parent, info, title,
                               wx.OK | wx.ICON_INFORMATION
                               #wx.YES_NO | wx.NO_DEFAULT | wx.CANCEL | wx.ICON_INFORMATION
                               )
    dlg.ShowModal()
    dlg.Destroy()


    

def db_chkuser(user, passwd):
    if not user :
        return 0
    
    con = sqlite3.connect(DB_FILE)
    cur = con.cursor()
    sql = """select count(*) from users
               where user_id='%s' and passwd='%s'""" % (user, passwd)
    print sql
    cur.execute(sql)
    ret, = cur.fetchone()
    cur.close()
    con.close()
    return ret
    
def db_getuserinfo(userid):
    if not userid: return 0;
    con = sqlite3.connect(DB_FILE)
    cur = con.cursor()
    sql = """select * from users
               where user_id='%s' """ % userid
    print sql
    cur.execute(sql)
    ret = cur.fetchone()
    cur.close()
    con.close()
    return ret

def db_userlist(name="", id_no=""):
    users = []
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """select id,name,id_no,mobile,sex,room_number,addr
        		from guests 
        		where 1=1 """
        if name: 
            sql += " and name like '%" + name + "%'"
        if id_no:
            sql += " and id_no like '%" + id_no + "%'"
        sql += " order by id"

        myprint(sql)
        c.execute(sql)
        users = c.fetchall()
        conn.commit()
        c.close()
    except Exception, e:
        print e
    return users

def db_saveroom(no, rate=0, ensure=0, type=0, net=0):
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """insert into room (number,type,amt_month,amt_ensure,network) 
                values ('%s','%s','%d','%d',%d)""" % (no,type,rate,ensure,net)
        c.execute(sql)
        conn.commit()
        c.close()
        return (True, "保存成功")
    except Exception, e:
        print e
        return (False, str(e))
       
def db_saveguest(name="未知",id_no="",mobile="",sex=0,id_type=0,follow_person=0,
                 addr=""):
    try:
        conn = sqlite3.connect(DB_FILE)
        c = conn.cursor()
        sql = """insert into guests (name,id_no,mobile,sex,id_type,follow_person,
           addr,insert_time) values ('%s','%s','%s',%s,%s,%s,'%s', datetime())""" %\
           (name,id_no,mobile,sex,id_type,follow_person,addr)
        myprint(sql)
        c.execute(sql)
        conn.commit()
        c.close()
        return (True, "保存成功")
    except Exception, e:
        print e
        return (False, str(e))
    

#data1:datetime  data2:datetime    
#return int
def dataita(date1, date2):
    import datetime
    a = datetime.date(2012, 8, 1)
    
    #b = datetime.date.today()
    b = datetime.date(2012, 10, 1)
    ita = (b-a)
    print dir(ita) 
    #itl = datetime.datetime.now() - a
    #print itl
    
    

"""
if __name__ == "__main__":
    DB_FILE = r'F:\google\trunk\hotel\src\db\llhotel.db'
    #print db_getroom('1001')
    #print db_bookroom('1001', u'张小康', '1385713445', 
    #                id_no="",id_type=0,sex=0,follow_person=0,
    #        addr="")
    #print loginto('admin', '123')
    #print db_getuserinfo('admin')
    #print dbgetroomlist()
    #test()
    print db_userlist('12', '111')
"""   
    
    