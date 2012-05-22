<?php $this->load->view('header');?>

<script type="text/javascript" src="<?php echo $media_root.'js/ligerui/ligerUI/js/plugins/ligerTree.js';?>"></script>

<script type="text/javascript">
//var grid = null;
var manager = null;

$(function(){
    /*
    grid = $('#datagrid').ligerGrid({
	columns: [
	    {display:'区域名称', name:'region_name', id:'region_name', align:'left', width:'200'}, 
	    {display:'代理状态', name:'enabled', align:'center', width:'100', render:function(value, index){
		return value.enabled;
	    }} 
	], 
	usePager: false, 
	dataAction:'server', 
	url: '<?php echo site_url("json/regions");?>', 
	sortName: 'region_id', 
	width:'100%', 
	height:'99.8%', 
	tree: {columnId: 'region_name', treeLine:true}
    });
    $('#pageloading').hide();
     */

    $('#tree').ligerTree({
	url: '<?php echo site_url("json/regions");?>', 
	nodeWidth: 200, 
	checkbox: false, 
	parentIcon: null, 
	childIcon: null, 
	textFieldName: 'region_name',
	idFieldName: 'region_id' 
    });

    manager = $('#tree').ligerGetTreeManager();
});
</script>
</head>
<body>

<div id="tree" style="margin:0; padding:10px; border:0 none;"></div>

<?php $this->load->view('footer');?>
