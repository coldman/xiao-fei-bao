-- manage users
drop table if exists `kvke_manage_users`;
create table `kvke_manage_users` (
    `id` int(11) unsigned not null auto_increment, 
    `username` varchar(40) unique not null, 
    `password` varchar(40) not null, 
    `email` varchar(64) not null, 
    `phone` varchar(15), 
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

--kvke_assess_agent_his
drop table if exists `kvke_agent_assess`;
create table `kvke_agent_assess`(
    `id` int(11) unsigned not null auto_increment,
    `agent_id` int(11) unsigned not null,
    `begin_time` int(11) not null default 0,
    `end_time`   int(11) not null default 0,
    `insert_time` timestamp not null default current_timestamp,
    `status`  tinyint(2) not null default 0,    -- 0:no pass   1:passed
    primary key (`id`)
);


-- alter 
alter table kvke_users add manager_id int(11) unsigned not null default 0;
