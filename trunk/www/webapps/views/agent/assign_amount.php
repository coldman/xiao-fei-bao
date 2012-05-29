<?php $this->load->view('header');?>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerForm.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTextBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerButton.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerDialog.js';?>"></script>
<script type="text/javascript">
var grid = null;
var rate_template_name = "<?php echo $agent['rate_template_name'];?>";
$(function(){
    $('#agent_info_form').ligerForm();
    grid = $('#datagrid').ligerGrid({
	columns: [
	    {display:'选择', name:'opt', align:'center', width:'40', render:function(value, index){
		if (rate_template_name == value.name) {
		    var checked='checked';
		}
		else {
		    var checked='';
		}
		var r = '<input type="radio" name="rate" value="'+value.name+'"'+checked+' style="margin-top:5px;" />';
		return r;
	    }}, 
	    {display:'模版名称', name:'name', align:'left', width:'100'}, 
	    {display:'金额1', name:'amt1', align:'left', width:'80'}, 
	    {display:'费率1', name:'rate1', align:'left', width:'80'}, 
	    {display:'金额2', name:'amt2', align:'left', width:'80'}, 
	    {display:'费率2', name:'rate2', align:'left', width:'80'},
	    {display:'金额3', name:'amt3', align:'left', width:'80'},
	    {display:'费率3', name:'rate3', align:'left', width:'80'}
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/agent_rates");?>', 
	sortName: 'name', 
        width: '700'	
    });
});
</script>
</head>
<body>
    <div class="agent_info" style="padding:20px;">
    <form id="agent_info_form" name="agent_info_form" action="" method="post">
        <table cellpadding="0" cellspacing="0" class="l-table-edit">
        <tr>
            <td width="100"><b>用户名</b></td>
            <td><b><?php echo isset($agent['user_name'])?$agent['user_name']:'';?></b></td>
        </tr>
        <tr>
            <td><b>真实姓名</b></td>
            <td><b><?php echo isset($agent['real_name'])?$agent['real_name']:'';?></b></td>
        </tr>
        
        
		<tr>
		    <td align="right" class="l-table-edit-td">1号-7号</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="step1" id="temp_step1" value="<?php echo $agent_plan['step1'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">8号-14号</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="step2" id="step2" value="<?php echo $agent_plan['step2'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">15号-21号</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="step3" id="step3" value="<?php echo $agent_plan['step3'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">22号-月末</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="step4" id="step4" value="<?php echo $agent_plan['step4'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">费率模版</td>
		    <td align="left" class="l-table-edit-td">
			<div id="datagrid" class="rate_template"></div>
		    </td>
		</tr>
		<tr>
		    <td></td>
		    <td style="padding:8px 4px;">
			<input type="hidden" name="submitted" value="rate" />
			<input type="hidden" name="id" value="<?php echo $agent_id;?>" />
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
