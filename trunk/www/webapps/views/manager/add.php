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
	username: '代理人用户名', 
	realname: '真实姓名', 
	region: '代理区域'
    };
    sel.dst.header = {
	id: 'ID', 
	username: '代理人用户名', 
	realname: '真实姓名', 
	region: '代理区域'
    };
    sel.src.dataKey = 'id';
    sel.src.title   = '可选';
    sel.dst.dataKey = 'id';
    sel.dst.title   = '已选';
    sel.render();
    var input_html = '...';
    var url = "<?php echo site_url('json/undispatch_agents');?>";
    $.getJSON(url, {random:Math.random()}, function(data){
	for (var i=0; i<data.Total; i++) {
	    sel.src.add({id:data.Rows[i].user_id, username:data.Rows[i].user_name, realname:data.Rows[i].real_name, region:(data.Rows[i].province+'-'+data.Rows[i].city+'-'+data.Rows[i].district)});
	}
    });
    //sel.src.add({id: 0, name: 'None', region: '幽灵'});
    //sel.src.add({id: 1, name: 'Tom', region: '汤姆'});

    $('#pwd_form').ligerForm();
    function get_selected_agents()
    {
	var s = sel.getSelected();
	var r = Array();
	for (var i=0; i<s.length; i++) {
	    r.push(s[i].id);
	}
	return r;
    }
    $('#manager_form').submit(function(){
	var ss = get_selected_agents();
	var agents = ss.join(',');
	$('#agents').val(agents);
    });
});
</script>
</head>
<body>
    <div class="manageradd" style="padding:20px;">
	<form id="manager_form" name="manager_form" action="" method="post">
	    <table cellpadding="0" cellspacing="0" class="l-table-edit">
		<tr>
		    <td align="right" class="l-table-edit-td">用户名</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="username" id="username" value="" ltype="text" /></td>
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
		    <td align="right" class="l-table-edit-td">手机</td>
		    <td align="left" class="l-table-edit-td">
			<input type="text" name="phone" id="phone" value="" ltype="text" />
		    </td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">区域代理人</td>
		    <td align="left" class="l-table-edit-td">
			<div id="sel_view"></div>
		    </td>
		</tr>
		<tr>
		    <td></td>
		    <td style="padding:8px 4px;">
			<input type="hidden" name="submitted" value="pwd" />
			<input type="hidden" id="agents" name="agents" value="" />
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
