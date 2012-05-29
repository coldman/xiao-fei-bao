-- manage users
--drop table if exists `kvke_manage_users`;
create table `kvke_manage_users` (
    `id` int(11) unsigned not null auto_increment, 
    `username` varchar(40) unique not null, 
    `password` varchar(40) not null, 
    `realname` varchar(40),
    `email` varchar(64) not null, 
    `phone` varchar(15), 
    `sex` tinyint(1) not null default 0, 
    `add_time` timestamp not null default current_timestamp, 
    `last_login` timestamp, 
    `role_type` tinyint(2) not null default 0, 
    primary key (`id`)
);

-- manage users 
--drop table if exists `kvke_assess_agent`;
create table `kvke_assess_agent` (
    `id` int(11) unsigned not null auto_increment, 
    `agent_id` int(11) unsigned not null, 
    `begin_time` int(10) not null default 0, 
    `end_time` int(10) not null default 0, 
    `target` decimal(15,2) not null default 0.00, 
    `finished` decimal(15,2) not null default 0.00, 
    `status` tinyint(2) not null default 0,
    primary key (`id`)
);


--kvke_agents  代理商账户表
--drop table if exists `kvke_agents`;
create table `kvke_agents`(
    `id` int(11) unsigned not null auto_increment,
    `agent_id` int(11) unsigned not null,
    `cur_month` char(6) not null default '000000',
    `step1`   int(11) not null default 0,
    `step2`   int(11) not null default 0,
    `step3`   int(11) not null default 0,
    `step4`   int(11) not null default 0,
    `amount`  int(11) not null default 0,
    `update_time` timestamp not null default current_timestamp, 
    primary key (`id`)
);

--kvke_agents_plan  额度表
--drop table if exists `kvke_agents_plan`;
create table `kvke_agents_plan`(
    `id` int(11) unsigned not null auto_increment,
    `agent_id` int(11) unsigned not null,
    `step1`   int(11) not null default 0,
    `step2`   int(11) not null default 0,
    `step3`   int(11) not null default 0,
    `step4`   int(11) not null default 0,
    `update_time` timestamp not null default current_timestamp, 
    primary key (`id`)
);

--kvke_agents_log  代理商日志表
--drop table if exists `kvke_agent_log`;
create table `kvke_agent_log`(
    `id` int(11) unsigned not null auto_increment,
    `manage_id` int(11) unsigned not null,
    `agent_id` int(11) unsigned not null,
    `step1`   int(11) not null default 0,
    `step2`   int(11) not null default 0,
    `step3`   int(11) not null default 0,
    `step4`   int(11) not null default 0,
    `description` varchar(500),
    `update_time` timestamp not null default current_timestamp, 
    primary key (`id`)
);

-- kvke_agent_rate_template  代理商结算费率模板
-- drop table if exists `kvke_agent_rate_template`;
create table `kvke_agent_rate_template`(
    `id` int(11) unsigned not null auto_increment,
    `name`  varchar(64)   not null,
    `amt1`  int(11)     default 0,
    `rate1` float       default 0.0,
    `amt2`  int(11)     default 0,
    `rate2` float       default 0.0,
    `amt3`  int(11)     default 0,
    `rate3` float       default 0.0,
    `insert_time` timestamp not null default current_timestamp, 
    primary key (`id`),
    unique key (`name`)
);


-- init kvke_agents table
insert into kvke_agents (agent_id) values select user_id from kvke_users where is_agent=1;

-- alter 用户表新增一列
alter table kvke_users add manager_id int(11) unsigned not null default 0;
alter table kvke_users add rate_template_name varchar(64) ;


-- 获取用户帐户资金信息
DROP PROCEDURE IF EXISTS proc_get_agent_account;
CREATE PROCEDURE proc_get_agent_account()
top: BEGIN

/**************************************************************************/
    DECLARE agentId,isExsits int;
    DECLARE cur_month_str varchar(8);
    DECLARE v_amount,v_step int;
    DECLARE today int ;

    DECLARE lastmonth_str varchar(6);
    DECLARE last23 varchar(8);
    DECLARE curmonth_str varchar(6);
    DECLARE curmon1 varchar(8);
    DECLARE curmon8 varchar(8);
    DECLARE curmon16 varchar(8);
    DECLARE curmon23 varchar(8);

    DECLARE no_more_data                tinyint(1)  default 0;
    DECLARE cur_get_undo_record_isopen  tinyint(1)  default 0;
    
    DECLARE cur_get_undo_record cursor for select user_id from kvke_users where is_agent=1;  -- 声明游标 

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_data = 1;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        IF (cur_get_undo_record_isopen<>0) THEN
            CLOSE cur_get_undo_record;
            SET cur_get_undo_record_isopen = 0;
        END IF;
    END;
    

    SET isExsits=0;
    SET v_amount=0;
    SET v_step =0;
    SET curmon1=CONCAT(curmonth_str, '1');  -- 当月1号
    
    
    
    select date_format(now(),'%Y-%m') into cur_month_str;  -- '2012-05'
    select DAYOFMONTH(CURRENT_TIMESTAMP()) into today;  -- 24
    select date_format(CURRENT_TIMESTAMP(), '%Y%m') into curmonth_str;
    
    
   
    OPEN    cur_get_undo_record;
    SET     cur_get_undo_record_isopen = 1;
    REPEAT  -- 循环开始
    
    FETCH   cur_get_undo_record into agentId;
    
    
    
    
    IF (!no_more_data) THEN
        SET v_amount = 0;
    
        select count(1) into isExsits from kvke_agents where agent_id=agentId;
        
        if isExsits < 1 then  -- 新增代理商  首先插入记录
            insert into kvke_agents (agent_id,cur_month) values (agentId, cur_month_str);
        end if;
    
        -- get current month amount
        select sum(goods_amount) into v_amount from kvke_order_info 
        where app_user_id=agentId and app_user_id>0
            and pay_time >= UNIX_TIMESTAMP(STR_TO_DATE(curmon1 ,'%Y%m%d'))
            and pay_time < UNIX_TIMESTAMP(current_date());
     
        
        
        if today=1 then  -- 统计上个月23号-月末
            select date_format(DATE_SUB(CURRENT_TIMESTAMP(),INTERVAL 1 MONTH), '%Y%m') into lastmonth_str;
            
            SET last23=CONCAT(lastmonth_str, '23');
            
            select sum(goods_amount) into v_step from kvke_order_info 
            where app_user_id=agentId and app_user_id>0
                and pay_time >= UNIX_TIMESTAMP(STR_TO_DATE(last23 ,'%Y%m%d'))  -- from_unixtime
                and pay_time < UNIX_TIMESTAMP(STR_TO_DATE(curmon1,'%Y%m%d'));
            
            update kvke_agents set amount=v_amount, step4=v_step, update_time=now() where agent_id=agentId;
        end if;

        if today=8 then   -- 统计当月1号-7号
        
            SET curmon8=CONCAT(curmonth_str, '8');
            select sum(goods_amount) into v_step from kvke_order_info 
            where app_user_id=agentId and app_user_id>0
                and pay_time >= UNIX_TIMESTAMP(STR_TO_DATE(curmon1 ,'%Y%m%d'))
                and pay_time < UNIX_TIMESTAMP(STR_TO_DATE(curmon8,'%Y%m%d'));
            
            insert into kvke_agents (agent_id,amount,cur_month,step1,update_time) values (agentId,v_amount,curmonth_str,v_step,now());
        end if;
            
        if today=16 then  -- 统计8号-15号
        
            SET curmon16=CONCAT(curmonth_str, '16');
            select sum(goods_amount) into v_step from kvke_order_info 
            where app_user_id=agentId and app_user_id>0
                and pay_time >= UNIX_TIMESTAMP(STR_TO_DATE(curmon8 ,'%Y%m%d'))
                and pay_time < UNIX_TIMESTAMP(STR_TO_DATE(curmon16,'%Y%m%d'));
            
            update kvke_agents set amount=v_amount, step2=v_step, update_time=now() where agent_id=agentId;
        end if;
        
        if today=23 then  -- 统计16号-22号
        
            SET curmon23=CONCAT(curmonth_str, '23');
            select sum(goods_amount) into v_step from kvke_order_info 
            where app_user_id=agentId and app_user_id>0
                and pay_time >= UNIX_TIMESTAMP(STR_TO_DATE(curmon16 ,'%Y%m%d'))
                and pay_time < UNIX_TIMESTAMP(STR_TO_DATE(curmon23,'%Y%m%d'));
            
            update kvke_agents set amount=v_amount, step2=v_step, update_time=now() where agent_id=agentId;
       
        end if;
        
    END IF;
   

    UNTIL no_more_data END REPEAT;-- 循环结束
    CLOSE cur_get_undo_record;
    SET cur_get_undo_record_isopen = 0;
    
    
/**************************************************************************/ 
END


-- 代理自动结算
DROP PROCEDURE IF EXISTS proc_settle_agent;
CREATE PROCEDURE proc_settle_agent()
top: BEGIN
    
    DECLARE no_more_data                tinyint(1)  default 0;
    DECLARE cur_get_undo_record_isopen  tinyint(1)  default 0;
    DECLARE v_agentId  int;
    DECLARE v_unsettle_amount int;  -- 未结算金额
    DECLARE v_rate1,v_rate2,v_rate3 float;
    DECLARE v_amt1,v_amt2,v_amt3 float;
    DECLARE v_settled_amount,v_settle_time int;
    
    
    DECLARE cur_get_undo_record cursor for select agent_id from kvke_agents where cur_month=date_format(CURRENT_TIMESTAMP(), '%Y%m');  -- 声明游标 
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_data = 1;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        IF (cur_get_undo_record_isopen<>0) THEN
            CLOSE cur_get_undo_record;
            SET cur_get_undo_record_isopen = 0;
        END IF;
    END;
    
    OPEN    cur_get_undo_record;
    SET     cur_get_undo_record_isopen = 1;
    REPEAT  -- 循环开始
    
    myloop: BEGIN
        FETCH   cur_get_undo_record into v_agentId;
        IF (!no_more_data) THEN
            SET v_settled_amount=0;
        
            select amount, settle_time into v_unsettle_amount, v_settle_time from kvke_agents where agent_id=v_agentId;
            if v_settle_time > 0 then      -- 已结算
                LEAVE myloop; 
            end if;
            
            select a.amt1,a.rate1,a.amt2,a.rate2,a.amt3,a.rate3 into v_amt1,v_rate1,v_amt2,v_rate2,v_amt3,v_rate3 
                from kvke_agent_rate_template a, kvke_users b
                where a.name=b.rate_template_name;
            
            -- 第一档
            SET v_settled_amount = v_unsettle_amount * v_rate1;
            if (v_unsettle_amount-v_amt1)<=0 then
                update kvke_agents set settled_amount=v_settled_amount, settle_time=UNIX_TIMESTAMP(now())
                    where agent_id=v_agentId;
                LEAVE myloop; 
            end if;
            
            -- 第二档
            SET v_unsettle_amount = (v_unsettle_amount-v_amt1);
            SET v_settled_amount = v_settled_amount + v_unsettle_amount * rate2;
            if  (v_unsettle_amount-v_amt2)<=0 then
                update kvke_agents set settled_amount=v_settled_amount, settle_time=UNIX_TIMESTAMP(now())
                    where agent_id=v_agentId;
                LEAVE myloop; 
            end if;
            
            -- 第三档
            SET v_unsettle_amount = (v_unsettle_amount-v_amt2);
            SET v_settled_amount = v_settled_amount + v_unsettle_amount * rate3;
            update kvke_agents set settled_amount=v_settled_amount, settle_time=UNIX_TIMESTAMP(now())
                    where agent_id=v_agentId;
            
        
        END IF;
    END myloop;
    
    UNTIL no_more_data END REPEAT;-- 循环结束
    CLOSE cur_get_undo_record;
    SET cur_get_undo_record_isopen = 0;
    

END top




/*******************************定时器*************************************/
-- 查看是否开启定时器
-- SHOW VARIABLES LIKE '%sche%';

-- 开启定时器 0：off 1：on
-- SET GLOBAL event_scheduler = 1; 

CREATE EVENT IF NOT EXISTS event_count_agent_amount
ON SCHEDULE EVERY 1 DAY starts '2012-05-28 02:00:00'        /*  每天凌晨2点执行 */
ON COMPLETION PRESERVE
DO CALL proc_get_agent_account(); 

-- ALTER EVENT event_count_agent_amount ON COMPLETION PRESERVE ENABLE;
-- ALTER EVENT event_count_agent_amount ON COMPLETION PRESERVE DISABLE; 



