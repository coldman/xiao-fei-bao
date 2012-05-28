<?php $this->load->view('header');?>

<script type="text/javascript">
var grid = null;
function view_trader(id) {
    window.parent.addTab('view-trader', '查看商家', "<?php echo site_url('analysis/view_trader');?>/"+id);
}

$(function(){
    grid = $('#datagrid').ligerGrid({
	columns: [
	    {display:'用户名', name:'user_name', align:'left', width:'100', frozen: true}, 
	    {display:'真实姓名', name:'real_name', align:'left', width:'100', frozen: true}, 
	    {display:'区域', name:'area', align:'left', width:'150', frozen: true, render:function(value,index){
		var s = value.province + '-' + value.city + '-' + value.district;
		return s;
	    }},
	    {display:'公司', name:'comp_name', align:'left', width:'100' },
	    {display:'出单数', name:'total_orders', align:'left', width:'100'}, 
	    {display:'操作', name:'opt', align:'center', width:'100', render:function(value, index){
		var v = '<span class="icon icon-view" onclick="view_trader('+value.user_id+');"></span>';
		return v;
	    }}
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/g_traders");?>', 
	sortName: 'user_id', 
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
