<?php $this->load->view('header');?>
</head>
<body>
    <div class="userinfo" style="padding:10px;">
	<table class="info" border="0" cellspacing="0" cellpadding="0">
	    <tr>
		<td width="100">用户名</td>
		<td><?php echo isset($trader['user_name'])?$trader['user_name']:'';?></td>
	    </tr>
	    <tr>
		<td>真实姓名</td>
		<td><?php echo isset($trader['real_name'])?$trader['real_name']:'';?></td>
	    </tr>
	    <tr>
		<td>Email</td>
		<td><?php echo isset($trader['email'])?$trader['email']:'';?></td>
	    </tr>
	    <tr>
		<td>性别</td>
		<td><?php if (isset($trader['sex'])){if ($trader['sex']==1){echo '男';}elseif ($trader['sex']==2){echo '女';}}?></td>
	    </tr>
	    <tr>
		<td>公司</td>
		<td><?php echo isset($trader['comp_name'])?$trader['comp_name']:'';?></td>
	    </tr>
	    <tr>
		<td>办公电话</td>
		<td><?php echo isset($trader['comp_phone'])?$trader['comp_phone']:'';?></td>
	    </tr>
	    <tr>
		<td>手机</td>
		<td><?php echo isset($trader['mobile_phone'])?$trader['mobile_phone']:'';?></td>
	    </tr>
	    <tr>
		<td>QQ</td>
		<td><?php echo isset($trader['qq'])?$trader['qq']:'';?></td>
	    </tr>
	    <tr>
		<td>MSN</td>
		<td><?php echo isset($trader['msg'])?$trader['msg']:'';?></td>
	    </tr>
	    <tr>
		<td>地址</td>
		<td><?php echo isset($trader['address'])?$trader['address']:'';?></td>
	    </tr>
	    <tr>
		<td>开户银行</td>
		<td><?php echo isset($trader['bank_name'])?$trader['bank_name']:'';?></td>
	    </tr>
	    <tr>
		<td>开户人名称</td>
		<td><?php echo isset($trader['bank_user'])?$trader['bank_user']:'';?></td>
	    </tr>
	    <tr>
		<td>银行帐号</td>
		<td><?php echo isset($trader['bank_no'])?$trader['bank_no']:'';?></td>
	    </tr>
	    <tr>
		<td>创建日期</td>
		<td><?php echo isset($trader['reg_time'])?date('Y-m-d H:m:s', $trader['reg_time']):'';?></td>
	    </tr>
	</table>
    </div>

<?php $this->load->view('footer');?>
