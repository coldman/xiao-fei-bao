<?php include('header.php');?>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerForm.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTextBox.js';?>"></script>
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
		    <td align="right" class="l-table-edit-td">原密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="old_pwd" id="old_pwd" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">新密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="new_pwd" id="new_pwd" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">确认密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="cfm_pwd" id="cfm_pwd" value="" ltype="password" /></td>
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

<?php include('footer.php');?>
