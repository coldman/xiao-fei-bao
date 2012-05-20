<?php include('header.php');?>

<script type="text/javascript">
var grid = null;
$(function(){
    grid = $('#datagrid').ligerGrid({
	checkbox: true, 
	columns: [
	    {display:'用户名', name:'user_name', align:'left', width:'100'}, 
	    {display:'真实姓名', name:'real_name', align:'left', width:'100'}, 
	    {display:'区域', name:'province_name', align:'left', width:'150', render:function(value,index){
            var s = value.province_name + '-' + value.city_name + '-' + value.district_name;
            return s;
        }},
        {display:'公司', name:'comp_name', align:'left', width:'100' },
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

<?php include('footer.php');?>