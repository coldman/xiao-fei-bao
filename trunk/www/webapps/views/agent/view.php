<?php $this->load->view('header');?>
</head>
<body>
    <?php print_r($agent);?>
    <div class="userinfo" style="padding:10px;">
	<table class="info" border="0" cellspacing="0" cellpadding="0">
	    <tr>
		<td width="100">用户名</td>
		<td><?php echo isset($agent['user_name'])?$agent['user_name']:'';?></td>
	    </tr>
	    <tr>
		<td>真实姓名</td>
		<td><?php echo isset($agent['real_name'])?$agent['real_name']:'';?></td>
	    </tr>
	    <tr>
		<td>Email</td>
		<td><?php echo isset($agent['email'])?$agent['email']:'';?></td>
	    </tr>
	    <tr>
		<td>性别</td>
		<td><?php if (isset($agent['sex'])){if ($agent['sex']==1){echo '男';}elseif ($agent['sex']==2){echo '女';}}?></td>
	    </tr>
	    <tr>
		<td>创建日期</td>
		<td><?php echo isset($agent['reg_time'])?date('Y-m-d H:m:s', $agent['reg_time']):'';?></td>
	    </tr>
	</table>
    </div>

<?php $this->load->view('footer');?>
