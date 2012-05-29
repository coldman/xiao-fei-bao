<?php $this->load->view('header');?>

<script type="text/javascript">
var grid = null;

$(function(){
    grid = $('#datagrid').ligerGrid({
	columns: [
	    {display:'用户名', name:'user_name', align:'left', width:'120', frozen:true}, 
	    {display:'真实姓名', name:'real_name', align:'centor', width:'120', frozen:true}, 
        
        {display:'区域', name:'region', align:'left', width:'120', frozen: true, render:function(value,index){
            var s = value.province + '-' + value.city + '-' + value.district;
            return s;
	    }},
	    {display:'结算月份', name:'cur_month', align:'centor', width:'100' },
	    {display:'营业额', name:'amount', align:'right', width:'100' },
	    {display:'佣金', name:'settled_amount', align:'right', width:'100' }
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/agents_account");?>', 
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
