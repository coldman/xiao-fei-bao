/*
create by  zhangxk  2012-10-11 11:08:23
for sqlite3 database
*/

-- Describe ROOM
CREATE TABLE room (
    "id" INTEGER NOT NULL,
    "number" TEXT NOT NULL,
    "type" TEXT NOT NULL,
    "amt_month" INTEGER DEFAULT (0),
    "amt_month_fact" INTEGER DEFAULT (0),
    "amt_ensure" INTEGER DEFAULT (0),
    "amt_ensure_fact" INTEGER DEFAULT (0),
    "amt_paid" INTEGER DEFAULT (0),
    "amy_needpay" INTEGER DEFAULT (0),
    "contract_signed" TEXT,
    "contract_enable" TEXT,
    "status" INTEGER DEFAULT (0),
    "network" INTEGER DEFAULT (0), 
    "consumer" INTEGER
)

-- Describe USERS
CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT,
    "role" INTEGER NOT NULL,
    "actived" INTEGER NOT NULL,
    "create_time" TEXT,
    "last_login" TEXT
)

-- Describe CONSUMER
CREATE TABLE "consumer" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT NOT NULL,
    "id_no" TEXT DEFAULT (0),
    "mobile" TEXT DEFAULT (0),
    "sex" INTEGER DEFAULT (0),
    "has_photo" INTEGER DEFAULT (0),
    "room_number" INTEGER NOT NULL
)

-- Describe FUND_DETIAL
CREATE TABLE "fund_detial" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "type" INTEGER NOT NULL DEFAULT (0),    -- 0:pay  1:income
    "amt" INTEGER NOT NULL DEFAULT (0),
    "time" TEXT DEFAULT ('1972-07-01 00:00:00'),
    "descp" TEXT,
    "user_id" INTEGER
)


insert into room (number,type,amt_month,amt_month_fact,
amt_ensure,amt_ensure_fact,status,network) values 
('1001','一室一厅',1000,1000,600,400,1,1)



insert into consumer (name,id_no,mobile,
sex,room_number) values 
('李四','3301233244322323','13958003022',1,'1001')

insert into fund_detial (amt,descp,user_id) values 
(99.21,'测试记录',2)

