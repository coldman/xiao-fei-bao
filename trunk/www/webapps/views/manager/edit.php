<?php $this->load->view('header');?>

<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerForm.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTextBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerComboBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerButton.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerDialog.js';?>"></script>
<script type="text/javascript">
$(function(){
    $('#pwd_form').ligerForm();
});
</script>
</head>
<body>
    <div class="userpwd" style="padding:20px;">
	<form id="pwd_form" name="pwd_form" action="" method="post">
	    <table cellpadding="0" cellspacing="0" class="l-table-edit">
		<tr>
		    <td align="right" class="l-table-edit-td">用户名</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="username" id="username" value="" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">密码</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="realname" id="realname" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">确认密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="cfm_pwd" id="cfm_pwd" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">Email</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="email" id="email" value="" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">性别</td>
		    <td align="left" class="l-table-edit-td">
			<select name="sex" id="sex" ltype="select">
			    <option value="0"></option>
			    <option value="1">男</option>
			    <option value="2">女</option>
			</select>
		    </td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">区域代理人</td>
		    <td align="left" class="l-table-edit-td">
			sss
		    </td>
		</tr>
		<tr>
		    <td></td>
		    <td style="padding:8px 4px;">
			<input type="hidden" name="submitted" value="pwd" />
			<input type="submit" value="提交" class="l-button l-button-submit" />
		    </td>
		</tr>
	    </table>
	</form>
    </div>
    <script type="text/javascript">
    <?php if ($this->session->flashdata('error')):?>
	$.ligerDialog.error("<?php echo $this->session->flashdata('error');?>");
    <?php endif;?>
    <?php if ($this->session->flashdata('msg')):?>
	$.ligerDialog.success("<?php echo $this->session->flashdata('msg');?>");
    <?php endif;?>
    </script>

<?php $this->load->view('footer');?>
