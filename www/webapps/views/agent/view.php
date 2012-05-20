<?php $this->load->view('header');?>
</head>
<body>
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
		<td>公司</td>
		<td><?php echo isset($agent['comp_name'])?$agent['comp_name']:'';?></td>
	    </tr>
	    <tr>
		<td>办公电话</td>
		<td><?php echo isset($agent['comp_phone'])?$agent['comp_phone']:'';?></td>
	    </tr>
	    <tr>
		<td>手机</td>
		<td><?php echo isset($agent['mobile_phone'])?$agent['mobile_phone']:'';?></td>
	    </tr>
	    <tr>
		<td>QQ</td>
		<td><?php echo isset($agent['qq'])?$agent['qq']:'';?></td>
	    </tr>
	    <tr>
		<td>MSN</td>
		<td><?php echo isset($agent['msg'])?$agent['msg']:'';?></td>
	    </tr>
	    <tr>
		<td>地址</td>
		<td><?php echo isset($agent['address'])?$agent['address']:'';?></td>
	    </tr>
	    <tr>
		<td>开户银行</td>
		<td><?php echo isset($agent['bank_name'])?$agent['bank_name']:'';?></td>
	    </tr>
	    <tr>
		<td>开户人名称</td>
		<td><?php echo isset($agent['bank_user'])?$agent['bank_user']:'';?></td>
	    </tr>
	    <tr>
		<td>银行帐号</td>
		<td><?php echo isset($agent['bank_no'])?$agent['bank_no']:'';?></td>
	    </tr>
	    <tr>
		<td>创建日期</td>
		<td><?php echo isset($agent['reg_time'])?date('Y-m-d H:m:s', $agent['reg_time']):'';?></td>
	    </tr>
	</table>
    </div>

<?php $this->load->view('footer');?>
