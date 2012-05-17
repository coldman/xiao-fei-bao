<?php include('header.php');?>

<script type="text/javascript">
var grid = null;
$(function(){
    grid = $('#datagrid').ligerGrid({
	checkbox: true, 
	columns: [
	    {display:'用户名', name:'user_name', align:'left', width:'100'}, 
	    {display:'真实姓名', name:'real_name', align:'left', width:'100'}, 
	    {display:'Email', name:'email', align:'left', width:'150'}, 
	    {display:'性别', name:'sex', align:'left', width:'40'},
	    {display:'QQ', name:'qq', align:'left', width:'60'}, 
	    {display:'MSN', name:'msg', align:'left', width:'60'}, 
	    {display:'办公电话', name:'office_phone', align:'left', width:'100'}, 
	    {display:'手机', name:'mobile_phone', align:'left', width:'100'}, 
	    {display:'公司', name:'comp_name', align:'left', width:'100' }
	], 
	dataAction:'server', 
	pageSize:20, 
	url: '<?php echo site_url("json/agent");?>', 
	sortName: 'id', 
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
