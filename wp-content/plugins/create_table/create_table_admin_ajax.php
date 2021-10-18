<?php
require_once(dirname(__FILE__)."/create_table_functions.php");
global $wpdb;
// $postid = $_REQUEST['postID'];
$title =!empty($_REQUEST['title'])?$_REQUEST['title']:'';
$id =!empty($_REQUEST['id'])?$_REQUEST['id']:'';
?>
<div class="custom">
    <div class="custombox">
        <div class="close">点击关闭</div>
    </div>
</div>
<div>
<h1 class="start" id='h1'>Create Table</h1>
<a href="/wp-admin/admin.php?page=create_table" class="start btn">Add New</a>
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<a class="start btn" href='/wp-admin/admin.php?page=table_list'>table list</a>
</div>
<div id="create_table" class="postbox ">
<script type="text/javascript">
jQuery(document).ready(function($){
	var str='';
	var str2='';
	var cat_nos='';
	var datetable='';
	var search='';
	var reg = new RegExp("(^|&)id=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
	var id=r==null?'':r[2];
	$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_ajax_2.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"id="+id+"&action=add",
			beforeSend:function(){
				$(".list_catalog_box").html("Loading...");
			},
			success:function(msg){
				//console.log(title);
				$(".list_catalog_box").html("").append(msg.msg).show();
				if(id!=''){
					if(msg.msg=='error'){
						alert('Table does not exist');
						window.location.href='/wp-admin/admin.php?page=table_list';
						return false;
					}
				cat_nos=msg.cat_nos+",";
				datetable=msg.datetable;
				$("h3").append(msg.title);
				$("#datetable").val(datetable);
				$("#is_hide").val(msg.is_hide);
				$(".shortcode").html("").append(msg.notice);
				$("#h1").html(msg.h1);
				}
			}
		});

	$("#create_table .handlediv").click(function(){
		$(this).siblings(".inside").toggle();
		$("#create_table").toggleClass('closed');
	});
	$(document).on("click","#create_table .search",function(){
		if(id!=''){
			datetable=$("#datetable").val();
		}
		search=$("input[name=search]").val();
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_ajax_2.php';?>",
			type:"POST",
			dataType:"json",
			async:true,
			data:"action=table&id="+id+"&datetable="+datetable+"&search="+search,
			beforeSend:function(){
				$(".list_catalog_box").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog_box").html("").append(msg).show();
			}
		});
	});
	$("#datetable").live('change',function(){
			datetable=$("#datetable").val();
			//console.log(datetable);
            $.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_ajax_2.php';?>",
			type:"POST",
			dataType:"json",
			async:true,
			data:"action=table&datetable="+datetable,
			beforeSend:function(){
				$(".list_catalog_box").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog_box").html("").append(msg).show();
			}
		});	});

	$(document).on("click",".page .jump",function(){
		var page = $(this).val();
		if(id!=''){
			datetable=$("#datetable").val();
		}
		$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
			if($(this).is(':checked')){
			if (str.indexOf($(this).val()) == -1) {
				str += ($(this).val() + ",");
				var link=$("input[name='link["+$(this).val()+"]']").val();
				if(link!=''){
					str2 += ($(this).val()+"."+link + ",");
				}
			}if (cat_nos.indexOf($(this).val()) == -1) {
				cat_nos += ($(this).val() + ",");
			}
		}else{//取消复选框时 含有该id时将id从全局变量中去除
			if (str.indexOf($(this).val() != -1)) {
				str = str.replace(($(this).val() + ","), "");
			}
			if (cat_nos.indexOf($(this).val() != -1)) {
				cat_nos = cat_nos.replace(($(this).val() + ","), "");
			}
		}
		})
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_ajax_2.php';?>",
			type:"POST",
			dataType:"json",
			async:true,
			data:"action=table&page="+page+"&datetable="+datetable+"&id="+id+"&search="+search,
			beforeSend:function(){
				$(".list_catalog_box").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog_box").html("").append(msg).show();//.append(msg);
				$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
					if (str.indexOf($(this).val()) != -1) {
						$(this).prop("checked",true);
					}
				});
				$("input[value='"+page+"']").addClass("current").siblings().removeClass("current");
			}
		});
	});

	$(document).on("click",".list_catalog .save",function(){
		var title = $("input[name=title]").val();
			datetable=$("#datetable").val();
			is_hide=$("#is_hide").val();
		action='insert';
		if(id!=''){
			action='update';
		}
		$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
			if($(this).is(':checked')){
			if (str.indexOf($(this).val()) == -1) {
				str += ($(this).val() + ",");
				var link=$("input[name='link["+$(this).val()+"]']").val();
				if(link!=''){
					str2 += ($(this).val()+"|"+link + ",");
				}
			}if (cat_nos.indexOf($(this).val()) == -1) {
				cat_nos += ($(this).val() + ",");
			}
		}else{
			//取消复选框时 含有该id时将id从全局变量中去除
			if (cat_nos.indexOf($(this).val() != -1)) {
				cat_nos = cat_nos.replace(($(this).val() + ","), "");
			}
		}
		})
		if(id!=''){
			str=cat_nos;
		}
		//console.log(datetable);
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_do.php'?>",
			type:'POST',
			dataType:"json",
			data: "is_hide="+is_hide+"&title="+title+"&cat_nos="+str+"&link="+str2+"&action="+action+"&datetable="+datetable+"&id="+id,
			beforeSend: function(){
				$(".list_catalog").html("Form preservation, please wait...").show();
			},
			success: function(msg){
				//console.log(msg);
				if(msg.status=="true"){
					str='';
					str2='';
					cat_nos='';
					$(".list_catalog").html("").append(msg.msg);
					$(".shortcode").html(msg.notice);
					$("h3").html("").append(msg.title);
				}else if(msg.status=="false"){
					$(".list_catalog").html("").append(msg.msg);
					$(".shortcode").html(msg.notice);
				}else{
					console.log("why is here");
				}
			}
		});
		return false;
	});
	$(document).on("click",".delete",function(){
		console.log('xx');
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_do.php'?>",
			data:"id="+id+"&action=delete",
			dataType:"json",
			type:"POST",
			success:function(msg){
				if(msg.status=="true"){
					alert(msg.notice);
					window.location.href='/wp-admin/admin.php?page=table_list';
				}else if(msg.status=="false"){
					$(".list_catalog").html("");
					$(".shortcode").html(msg.notice);
				}else{
					console.log("why is here");
				}
			}
		});
		return false;
	});

	$(document).on("dblclick",".list_catalog_box .ln",function(){
		$(this).val($(this).attr("placeholder"));
		$(this).prev(".link_icon").addClass('active');
	});

	$(document).on("click",".select_all",function(){
		if($(this).is(":checked")==true){
			$(".list_catalog_box").find("input[name='cat_nos[]']").prop("checked",true);
			$(".select_all").prop("checked",true);
		}else{
			$(".list_catalog_box").find("input[name='cat_nos[]']").prop("checked",false);
			$(".select_all").prop("checked",false);
		}
	});
    $(document).keydown(function(event){ 
    	if(event.keyCode==13){ 
    		$('.search').click();; //此处为要执行的方法
    	} 
    }); 
});
</script>
<h3><?php echo $title; ?></h3>
	<?php if(!empty($id)){ ?>
		<input type="button" value="Delete" class="delete" />
	<?php } ?>
<div class='label'>
</div>
<span class="shortcode" placeholder="Feedback information" title="Feedback information"></span>
<form action="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_do.php'?>" method="post" name="create_table_form_add" id="create_table_form_add">
	<div class="list_catalog">

		<input type="button" value="Save" class="save" /><br />
		
title:
<input type="text" name="title">&emsp;&emsp;
is hide:
<select class="is_hide" id="is_hide">
	<option value="1">yes</option>
	<option value="0">no</option>
</select>&emsp;&emsp;
		<?php if(empty($id)){ ?>
select datetable:
<select class="datetable" name="datetable" id="datetable">
	<option value="_cs_misc_product">misc</option>
	<option value="vector">vector</option>
</select>&emsp; &emsp;  
select tabletype:
<select class="tabletype" name="tabletype" id="tabletype">
	<option value="0">default</option>
	<option value="1">custom</option>
</select>&emsp;&emsp;&emsp;&emsp;&emsp;
<?php }else{
	echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
} ?>
<?php if(!empty($id)){ 
	echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
}?>
search:
<input type="text" placeholder="product or description" name="search" id="search"/>
<button class='search' type="button"><span class="dashicons dashicons-search"></span></button>
<div class="list_catalog_box">
</div>
<input type="button" value="Save" class="save" />

	</div>
</form>
<a href="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/manual_of_plugin[create_table].docx'?>" class="help" target="_blank" title="Down manual here">?</a>
</div>
<style type="text/css">
      .custom{width: 100%;height: 100%;background-color: rgba(0,0,0,0.8);display: none;position: fixed;z-index: 1;}
      .custombox{width: 800px;height: 100%;margin: 0 auto;background-color: rgba(255,255,255,0.8);padding: 20px;}
 </style>