<?php $this->load->view('header');?>

<script type="text/javascript">
var grid = null;

$(function(){
    grid = $('#datagrid').ligerGrid({
	checkbox: true, 
	columns: [
	    {display:'区域名称', name:'region_name', align:'left', width:'100'}, 
	    {display:'代理状态', name:'enabled', align:'center', width:'100', render:function(value, index){
		return value.enabled;
	    }} 
	], 
	usePager: false, 
	dataAction:'server', 
	url: '<?php echo site_url("json/regions");?>', 
	sortName: 'region_id', 
	width:'100%', 
	height:'99.8%'
    });
    $('#pageloading').hide();
});
</script>
</head>
<body>

<div id="datagrid" style="margin:0; padding:0; border:0 none;"></div>

<?php $this->load->view('footer');?>
