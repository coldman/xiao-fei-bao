<?php $this->load->view('header');?>

<script type="text/javascript">
var grid = null;
function view_agent(id) {
    window.parent.addTab('view-agent', '查看代理商', "<?php echo site_url('analysis/view_agent');?>/"+id);
}

$(function(){
    grid = $('#datagrid').ligerGrid({
	checkbox: true, 
	columns: [
	    {display:'用户名', name:'user_name', align:'left', width:'100'}, 
	    {display:'真实姓名', name:'real_name', align:'left', width:'60'}, 
	    {display:'区域', name:'province_name', align:'left', width:'120', render:function(value,index){
		var s = value.province_name + '-' + value.city_name + '-' + value.district_name;
		return s;
	    }},
	    {display:'公司', name:'comp_name', align:'left', width:'120' },
	    {display:'1号-7号', name:'step1', align:'right', width:'100' },
	    {display:'8号-14号', name:'step2', align:'right', width:'100' },
	    {display:'15号-21号', name:'step3', align:'right', width:'100' },
	    {display:'22号-月末', name:'step4', align:'right', width:'100' },
	    {display:'当月营业额', name:'amount', align:'right', width:'80' },
	    {display:'操作', name:'opt', align:'center', width:'100', render:function(value, index){
		var v = '<span class="icon icon-view" onclick="view_agent('+value.user_id+');"></span>';
		return v;
	    }}
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/g_agents");?>', 
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
