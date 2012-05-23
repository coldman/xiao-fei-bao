<?php $this->load->view('header');?>

<script type="text/javascript">
var grid = null;
var manage_id = <?php echo isset($manager['id'])?$manager['id']:null;?>;
var role_type = <?php echo isset($manager['role_type'])?$manager['role_type']:0;?>;
function view_agent(id) {
    window.parent.addTab('view-agent', '查看代理商', "<?php echo site_url('analysis/view_agent');?>/"+id);
}

function assign_amount(id) {
    window.parent.addTab('assign-amount', '结算模版', "<?php echo site_url('analysis/assign_amount');?>/"+id);
}

function show_result(a, p) {
    var amount = 0;
    var plan   = 0;
    var style  = '';
    if (a) {
	var amount = a;
    }
    if (p) {
	var plan = p;
    }
    if (amount < plan) {
	style = 'color:#FF0000';
    }
    var s = '<span style="'+style+'">'+amount+'</span> | <span>'+plan+'</span>';
    return s;
	
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
	    {display:'1号-7号', name:'step1', align:'right', width:'90', render:function(value,index){
		var s = show_result(value.step1, value.step1_plan);
		return s;
	    }},
	    {display:'8号-14号', name:'step2', align:'right', width:'90', render:function(value,index){
		var s = show_result(value.step2, value.step2_plan);
		return s;
	    }},
	    {display:'15号-21号', name:'step3', align:'right', width:'90', render:function(value,index){
		var s = show_result(value.step3, value.step3_plan);
		return s;
	    }},
	    {display:'22号-月末', name:'step4', align:'right', width:'90', render:function(value,index){
		var s = show_result(value.step4, value.step4_plan);
		return s;
	    }},
	    {display:'当月营业额', name:'amount', align:'right', width:'90' },
        {display:'归属业务员', name:'manage_name', align:'left', width:'80' },
	    {display:'操作', name:'opt', align:'center', width:'100', render:function(value, index){
		var show = false;
		if (role_type == 1) {
		    show = true;
		}
		else {
		    if (value.manage_id == manage_id) {
			show = true;
		    }
		}
		if (show) {
		    var v = '<span class="icon icon-view" onclick="view_agent('+value.user_id+');"></span>';
		}
		else {
		    var v = '';
		}
		if (role_type == 1) {
		    var e = '<span class="icon icon-edit" onclick="assign_amount('+value.user_id+');" style="margin-left:10px;"></span>';
		}
		else {
		    var e = '';
		}
		return v + e;
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
