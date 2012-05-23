<?php $this->load->view('header');?>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerForm.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTextBox.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerButton.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerDialog.js';?>"></script>
<script type="text/javascript">
$(function(){
    $('#agent_rate_form').ligerForm();
});
</script>
</head>
<body>
    <div class="userpwd" style="padding:20px;">
	<form id="agent_rate_form" name="agent_rate_form" action="" method="post">
	    <table cellpadding="0" cellspacing="0" class="l-table-edit">
		<tr>
		    <td align="right" class="l-table-edit-td">模版名称</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="name" id="temp_name" value="<?php echo $agent_rate['name'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">金额1</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="amt1" id="amt1" value="<?php echo $agent_rate['amt1'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">费率1</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="rate1" id="rate1" value="<?php echo $agent_rate['rate1'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">金额2</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="amt2" id="amt2" value="<?php echo $agent_rate['amt2'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">费率2</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="rate2" id="rate2" value="<?php echo $agent_rate['rate2'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">金额3</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="amt3" id="amt3" value="<?php echo $agent_rate['amt3'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td align="right" class="l-table-edit-td">费率3</td>
		    <td align="left" class="l-table-edit-td"><input type="text" name="rate3" id="rate3" value="<?php echo $agent_rate['rate3'];?>" ltype="text" /></td>
		</tr>
		<tr>
		    <td></td>
		    <td style="padding:8px 4px;">
			<input type="hidden" name="submitted" value="rate" />
			<input type="hidden" name="id" value="<?php echo $agent_rate['id'];?>" />
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
