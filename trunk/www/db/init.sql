-- manage users
drop table if exists `kvke_manage_users`;
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
drop table if exists `kvke_assess_agent`;
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


--kvke_agent
drop table if exists `kvke_agents`;
create table `kvke_agents`(
    `id` int(11) unsigned not null auto_increment,
    `cur_time` varchar2(6) not null default '000000',
    `agent_id` int(11) unsigned not null,
    `step1`   int(11) not null default 0,
    `step2`   int(11) not null default 0,
    `step3`   int(11) not null default 0,
    `step4`   int(11) not null default 0,
    `amount`  int(11) not null default 0,
    primary key (`id`)
);

-- init kvke_agent table
insert into kvke_agent (agent_id) values select user_id from kvke_users where is_agent=1;




-- alter 
alter table kvke_users add manager_id int(11) unsigned not null default 0;
