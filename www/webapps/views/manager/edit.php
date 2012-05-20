<?php $this->load->view('header');?>

<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerForm.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTextBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerComboBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerButton.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerDialog.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerCheckBox.js';?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $media_root.'js/selectView/style.css';?>" />
<script type="text/javascript" src="<?php echo $media_root.'js/selectView/scripts/TableView.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/selectView/scripts/SelectorView.js';?>"></script>
<script type="text/javascript">
$(function(){
    var sel = new SelectorView('sel_view');
    sel.src.header = {
	id: 'ID', 
	name: '代理人名称', 
	region: '代理区域'
    };
    sel.dst.header = {
	id: 'ID', 
	name: '代理人名称', 
	region: '代理区域'
    };
    sel.src.dataKey = 'id';
    sel.src.title   = '可选';
    sel.dst.dataKey = 'id';
    sel.dst.title   = '已选';
    sel.render();
    var input_html = '...';
    sel.src.add({id: 0, name: 'None', region: '幽灵'});
    sel.src.add({id: 1, name: 'Tom', region: '汤姆'});

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
		    <td align="left" class="l-table-edit-td"><input type="text" name="username" id="username" value="<?php echo isset($manager['username'])?$manager['username']:'';?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="password" id="realname" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">确认密码</td>
		    <td align="left" class="l-table-edit-td"><input type="password" name="cfm_pwd" id="cfm_pwd" value="" ltype="password" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">Email</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="email" id="email" value="<?php echo isset($manager['email'])?$manager['email']:'';?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">性别</td>
		    <td align="left" class="l-table-edit-td">
			<select name="sex" id="sex" ltype="select">
			    <option value="0"></option>
			    <option value="1" <?php if(isset($manager['sex'])){if ($manager['sex'] == 1){echo 'selected="selected"';}}?>>男</option>
			    <option value="2" <?php if(isset($manager['sex'])){if ($manager['sex'] == 2){echo 'selected="selected"';}}?>>女</option>
			</select>
		    </td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">手机</td>
		    <td align="left" class="l-table-edit-td">
			<input type="text" name="phone" id="phone" value="<?php echo isset($manager['phone'])?$manager['phone']:'';?>" ltype="text" />
		    </td>
		</tr>
		<tr style="display:none;">
		    <td align="right" class="l-table-edit-td">区域代理人</td>
		    <td align="left" class="l-table-edit-td">
			<div id="sel_view"></div>
		    </td>
		</tr>
		<tr>
		    <td></td>
		    <td style="padding:8px 4px;">
			<input type="hidden" name="submitted" value="pwd" />
			<input type="hidden" name="id" value="<?php echo isset($manager['id'])?$manager['id']:'';?>" />
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
