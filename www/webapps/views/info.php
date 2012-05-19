<?php include('header.php');?>
</head>
<body>
    <div class="userinfo" style="padding:10px;">
	<table class="info" border="0" cellspacing="0" cellpadding="0">
	    <tr>
		<td width="100">用户名</td>
		<td><?php echo isset($manager['username'])?$manager['username']:'';?></td>
	    </tr>
	    <tr>
		<td>真实姓名</td>
		<td><?php echo isset($manager['realname'])?$manager['realname']:'';?></td>
	    </tr>
	    <tr>
		<td>Email</td>
		<td><?php echo isset($manager['email'])?$manager['email']:'';?></td>
	    </tr>
	    <tr>
		<td>性别</td>
		<td><?php if (isset($manager['sex'])){if ($manager['sex']==1){echo 'male';}elseif ($manager['sex']==2){echo 'female';}}?></td>
	    </tr>
	    <tr>
		<td>创建日期</td>
		<td><?php echo isset($manager['add_time'])?$manager['add_time']:'';?></td>
	    </tr>
	</table>
    </div>

<?php include('footer.php');?>
