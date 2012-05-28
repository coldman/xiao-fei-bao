<?php $this->load->view('header');?>

<link type="text/css" rel="stylesheet" href="<?php echo $media_root.'js/ligerui/ligerUI/skins/ligerui-icons.css';?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $media_root.'css/icons.css';?>" />
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerToolBar.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerDialog.js';?>"></script>

<script type="text/javascript">
var grid = null;
function edit_agent_rate(id) {
    window.parent.addTab('edit-agent-rate', '编辑代理商模版', "<?php echo site_url('user/agent_rate_temp_edit');?>/"+id);
}

function del_agent_rate(id) {
    var url = "<?php echo site_url('json/del_agent_rate');?>/"+id;
    $.getJSON(url, {}, function(data){
	if (data.result) {
	    $.ligerDialog.success("代理商费率模版删除成功！");
	    window.location.reload();
	}
    });
}

$(function(){
    $('#toolbar').ligerToolBar({items:[
	{text:'增加', icon:'add', click:function(){
	    window.parent.addTab('add-agent-rate', '添加代理商模版', "<?php echo site_url('user/agent_rate_temp_add');?>");
	}}	
    ]});
    grid = $('#datagrid').ligerGrid({
	columns: [
	    {display:'模版名称', name:'name', align:'left', width:'100'}, 
	    {display:'金额1', name:'amt1', align:'left', width:'100'}, 
	    {display:'费率1', name:'rate1', align:'left', width:'100'}, 
	    {display:'金额2', name:'amt2', align:'left', width:'100'}, 
	    {display:'费率2', name:'rate2', align:'left', width:'100'}, 
	    {display:'金额3', name:'amt3', align:'left', width:'100'}, 
	    {display:'费率3', name:'rate3', align:'left', width:'100'}, 
	    {display:'编辑', name:'opt', align:'center', width:'40', render:function(value, index){
		var e = '<span class="icon icon-edit" onclick="edit_agent_rate('+value.id+');"></span>';
		return e;
	    }}, 
	    {display:'删除', name:'opt', align:'center', width:'40', render:function(value, index){
		var d = '<span class="icon icon-del" onclick="del_agent_rate('+value.id+');"></span>';
		return d;
	    }}
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/agent_rates");?>', 
	sortName: 'user_id', 
	width:'100%', 
	height:'99.8%'
    });
    $('#pageloading').hide();
});
</script>
</head>
<body>
<div id="toolbar"></div>
<div id="datagrid" style="margin:0; padding:0; border:0 none;"></div>

<?php $this->load->view('footer');?>
