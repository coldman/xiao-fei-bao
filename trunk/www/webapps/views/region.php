<?php $this->load->view('header');?>

<script type="text/javascript" src="<?php echo $media_root.'js/jstree/_lib/jquery.cookie.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/jstree/_lib/jquery.hotkeys.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/jstree/jquery.jstree.js';?>"></script>
<script type="text/javascript" src="<?php echo $media_root.'js/jstree/jstreegrid.js';?>"></script>


<script type="text/javascript">
var manager = null;

$(function(){
    var url = "<?php echo site_url('json/regions');?>";
    //var url = "<?php echo $media_root.'test.json';?>";

    $('#tree').bind("load_grid.jstree", function(){
	$('span#status').text('加载');
    });
    $('#tree').jstree({
	plugins: ["themes", "json_data", "grid", "dnd", "cookies"], 
	json_data: {
	    ajax: {
		url: url, 
		cache: false, 
		data: function(n) {
		    return {
			"fid": n.attr ? n.attr("region_id") : 1
		    };
		}
	    }
	}, 
	grid: {
	    columns: [
		{width:300, header:"名称", title:"_DATA_"}, 
		{cellClass:"icons", value:"enabled", width:80, header:"代理状态", title:"enabled", formatter:"<span class=\"icon icon-%s\"></span>"}
		], 
		resizable: true
	}
    });
    
});
</script>
</head>
<body>

<div id="tree" style="margin:0; padding:10px; border:0 none;"></div>

<?php $this->load->view('footer');?>
