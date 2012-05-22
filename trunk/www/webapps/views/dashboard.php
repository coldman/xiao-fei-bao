<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
    <meta name="Keywords" content="91消费宝 电子商务 业务员管理" />
    <meta name="Description" content="91消费宝电子商务平台" />
    <title>91消费宝业务员管理系统</title>
    <link type="text/css" rel="stylesheet" href="<?php echo $media_root.'js/ligerui/ligerUI/skins/Aqua/css/ligerui-all.css';?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $media_root.'css/dashboard.css';?>" />
    <script type="text/javascript" src="<?php echo $media_root.'js/jquery-1.7.2.min.js';?>"></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/core/base.js';?>"></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerLayout.js';?>" ></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTree.js';?>"></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerAccordion.js';?>"></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTab.js';?>"></script>
    <script type="text/javascript" src="<?php echo $media_root.'js/dashboard.js';?>"></script>
</head>
<body>
    <div id="header">
	<div class="title">91消费宝业务员管理系统</div>
	<div class="console">
	    <span>欢迎 <?php echo $manager['username'];?></span> | 
	    <a href="<?php echo site_url('login/logout');?>">退出</a>
	</div>
    </div>
    <div id="viewport">
	<div position="left" title="导航菜单" id="navigation">
	    <div title="业务员中心">
		<div class="node">
		    <a class="l-link" id="agent_list" href="<?php echo site_url('analysis/agent');?>">代理商列表</a>
		    <a class="l-link" id="trader_list" href="<?php echo site_url('analysis/trader');?>">商户列表</a>
		    <a class="l-link" id="region_data" href="<?php echo site_url('analysis/region');?>">区域信息</a>
		</div>
	    </div>
	    <div title="系统设置">
		<div class="node">
		<?php if ($manager['role_type'] == 1):?>
		    <a class="l-link" id="manager_list" href="<?php echo site_url('user/managers');?>">业务员列表</a>
		<?php endif;?>
		    <a class="l-link" id="user_info" href="<?php echo site_url('user/info');?>">个人信息</a>
		    <a class="l-link" id="change_pwd" href="<?php echo site_url('user/change_pwd');?>">修改密码</a>
		</div>
	    </div>
	</div>
	<div position="center" id="framecenter">
	    <div tabid="home" title="首页" style="height:300px;">
		欢迎进入业务员管理系统
	    </div>
	</div>
	<!--<div position="right">right</div>-->
    </div>
    <div id="footer">Copyright &copy;2012~2020  91消费宝  All right reversed.</div>
</body>
</html>
