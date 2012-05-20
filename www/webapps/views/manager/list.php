<?php $this->load->view('header');?>

<link type="text/css" rel="stylesheet" href="<?php echo $media_root.'js/ligerui/ligerUI/skins/ligerui-icons.css';?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $media_root.'css/icons.css';?>" />
<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerToolBar.js';?>"></script>

<script type="text/javascript">
var grid = null;
function edit_manager(id) {
    window.parent.addTab('edit-manager', '编辑业务员', "<?php echo site_url('user/edit_manager');?>");
}

$(function(){
    $('#toolbar').ligerToolBar({items:[
	{text:'增加', icon:'add', click:function(){
	    window.parent.addTab('add-manager', '添加业务员', "<?php echo site_url('user/add_manager');?>");
	}}	
    ]});
    grid = $('#datagrid').ligerGrid({
	checkbox: true, 
	columns: [
	    {display:'用户名', name:'username', align:'left', width:'100'}, 
	    {display:'真实姓名', name:'realname', align:'left', width:'100'}, 
	    {display:'Email', name:'email', align:'left', width:'150'}, 
	    {display:'性别', name:'sex', align:'center', width:'40', render:function(value, index){
		var s = '';
		if (value.sex == '1') {
		    s = '男';  
		}
		else if (value.sex == '2') {
		    s = '女';
		}
		return s;
	    }},
	    {display:'手机', name:'phone', align:'left', width:'100'}, 
	    {display:'编辑', name:'opt', align:'center', width:'40', render:function(value, index){
		var e = '<span class="icon icon-edit" onclick="edit_manager('+value.id+');"></span>';
		return e;
	    }}
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/managers");?>', 
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
