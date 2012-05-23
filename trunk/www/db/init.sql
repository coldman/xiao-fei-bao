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


--kvke_agents  自动执行统计表
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


DROP PROCEDURE IF EXISTS proc_count_agent_amt;
CREATE PROCEDURE proc_count_agent_amt()
BEGIN
    declare amt_t1 type int;
    declare iId int;
    declare isExsits int;
    declare cur_month_str varchar(8);
    
    
    declare today date;
    declare stop int default 0;-- 这个用于处理游标到达最后一行的情况  
    declare cur cursor for select users_id from kvke_users where is_agent=1;  -- 声明游标 
    declare CONTINUE HANDLER FOR SQLSTATE '02000' SET stop=1; -- 声明游标的异常处理，设置一个终止标记

    set today = current_date();
    set isExsits = 0;
    
    select date_format(now(),'%Y-%m') int cur_month_str;
    
    open cur;
    -- 读取一行数据到变量   
    fetch cur into iId;

    -- 这个就是判断是否游标已经到达了最后   
    while stop <> 1 do 
        
        select count(1) into isExsits from kvke_agents where agent_id=iId;
        if isExsits == 0 then  -- 新增代理商  首先插入记录
            insert into kvke_agents (agent_id,cur_month) values (iId, cur_month_str);
        end if;
    
        -- get yesterday amount
        select sum(A.goods_amount) into amt 
        from kvke_order_info as A LEFT  JOIN kvke_users as B on A.app_user_id=B.user_id 
        where A.app_user_id>0  and from_unixtime(A.add_time) >= (today-1) 
        and from_unixtime(A.add_time)<today and B.user_id=user_id;


        if 1号 then
        step4=0;   --update
        end if;

        if 8号 then 
        step1=0;   --insert
        end if;

        if 17号 then  --update
        step2=0;
        end if;

        if 22号 then  --update
        step3=0;
        end if;

        -- 读取下一行的数据    
        fetch cur into iId;;
    end while;  -- 循环结束  
    close cur; -- 关闭游标
  
     
END;




