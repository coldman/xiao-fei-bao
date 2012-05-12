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
    <script type="text/javascript">
    var accordion;
    // init
    var bodyHeight =  $(".l-layout-center:first").height();
    $(function(){
	$('#viewport').ligerLayout({
	    leftWidth: 200
	});
	$('#navigation').ligerAccordion({height:bodyHeight});
    });
    </script>
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
		<ul>
		    <li class="icon">aaaa</li>
		    <li class="icon">aaaa</li>
		    <li class="icon">aaaa</li>
		    <li class="icon">aaaa</li>
		    <li class="icon">aaaa</li>
		    <li class="icon">aaaa</li>
		</ul>
	    </div>
	    <div title="系统设置">
		<ul>
		    <li class="icon">bbbb</li>
		    <li class="icon">bbbb</li>
		    <li class="icon">bbbb</li>
		    <li class="icon">bbbb</li>
		    <li class="icon">bbbb</li>
		    <li class="icon">bbbb</li>
		</ul>
	    </div>
	</div>
	<div position="center" title="内容">center</div>
	<div position="right">right</div>
	<div position="bottom">bottom</div>
    </div>
</body>
</html>
