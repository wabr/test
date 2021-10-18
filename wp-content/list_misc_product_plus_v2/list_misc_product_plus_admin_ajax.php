<?php
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
global $wpdb;
// $postid = $_REQUEST['postID'];
$title ='';
?>

<script type="text/javascript">
jQuery(document).ready(function($){
	var str='';
	var str2='';
	var cat_nos='';
	$("#list_misc .handlediv").click(function(){
		$(this).siblings(".inside").toggle();
		$("#list_misc").toggleClass('closed');
	});
	$("#datetable").live('change',function(){
            var title = $("input[name=title2]").val();
			var datetable=$("#datetable").val();
			console.log(datetable);
            $.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/ajax3.php';?>",
			type:"POST",
			async:true,
			data:"datetable="+datetable,
			beforeSend:function(){
				$(".filter_table").html("Loading...");
			},
			success:function(msg){
				$(".filter_table").html("").append(msg).show();
			}
		});	});
	$("#list_misc .add").click(function(){
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_ajax_2.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"action=add",
			beforeSend:function(){
				$(".list_catalog").html("Loading...");
				$(".notice").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog").html("").append(msg.msg).show();
				$(".notice").html("").append(msg.notice);
			}
		});
	});
	$(document).on("click","#list_misc .update",function(){
		var title = $(this).data("title");
		$(this).addClass("current").siblings().removeClass("current");
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_ajax_2.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"title="+title+"&action=update",
			beforeSend:function(){
				$(".list_catalog").html("Loading...");
				$(".notice").html("Loading...");
			},
			success:function(msg){
				console.log(title);
				cat_nos=msg.cat_nos+",";
				$(".list_catalog").html("").append(msg.msg).show();//.append(msg);
				$(".notice").html("").append(msg.notice);
			}
		});
	});

	$(document).on("click",".page .jump",function(){
		var page = $(this).data("page");
		var action = $("input[name=action]").val();

		if(action=='update'){
			var title = $("input[name=title2]").val();
			var datetable='';
		}else if(action=='insert'){
			var title='';
			var datetable=$("#datetable").val();
		}
		$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
			if($(this).is(':checked')){
			if (str.indexOf($(this).val()) == -1) {
				str += ($(this).val() + ",");
				var link=$("#list_misc_product_form_add").find("input[name='link["+$(this).val()+"]']").val();
				if(link!=''){
					str2 += ($(this).val()+"."+link + ",");
				}
			}if (cat_nos.indexOf($(this).val()) == -1) {
				cat_nos += ($(this).val() + ",");
			}
		}else{
			//取消复选框时 含有该id时将id从全局变量中去除
			if (str.indexOf($(this).val() != -1)) {
				str = str.replace(($(this).val() + ","), "");
			}
			if (cat_nos.indexOf($(this).val() != -1)) {
				cat_nos = cat_nos.replace(($(this).val() + ","), "");
			}
		}
		})
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/ajax3.php';?>",
			type:"POST",
			async:true,
			data:"page="+page+"&datetable="+datetable+"&title="+title,
			beforeSend:function(){
				$(".filter_table").html("Loading...");
			},
			success:function(msg){
				$(".filter_table").html("").append(msg).show();//.append(msg);
				$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
					if (str.indexOf($(this).val()) != -1) {
						$(this).prop("checked",true);
					}
				});
				$("input[data-page='"+page+"']").addClass("current").siblings().removeClass("current");
			}
		});
	});

	$(document).on("click",".list_catalog .save",function(){
		var title1 = $("input[name=title1]").val();
		var title2 = $("input[name=title2]").val();
		var action = $("input[name=action]").val();
		var datetable=$("#datetable").val();
		if(action=='insert'){
			title = title1 == "" ? title2 : title1;
		}else if(action=='update'){
			title = title1 == "" ? title2 : title1+","+title2;
		}
		console.log(cat_nos);
		$('input[name="cat_nos[]"][type="checkbox"]').each(function(){
			if($(this).is(':checked')){
			if (str.indexOf($(this).val()) == -1) {
				str += ($(this).val() + ",");
				var link=$("#list_misc_product_form_add").find("input[name='link["+$(this).val()+"]']").val();
				if(link!=''){
					str2 += ($(this).val()+"."+link + ",");
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
		if(action=='update'){
			str=cat_nos;
		}
		console.log(cat_nos);
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>",
			beforeSend: function(){
				$(".list_catalog").html("Form preservation, please wait...").show();
			},
			type:'POST',
			dataType:"json",
			data: "title="+title+"&cat_nos="+str+"&link="+str2+"&action="+action+"&datetable="+datetable,
			success: function(msg){
				console.log(msg);
				if(msg.status=="true"){
					str='';
					str2='';
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
					if(msg.action=="insert"){
						//console.log('x');
						$(".label").append("<input type=\"button\" class=\"update\" data-title=\""+title+"\" value=\"Update : "+title+"\">");
					}else if(msg.action=="update"){
						if(title1!=''){
							$("input[data-title=\""+title2+"\"]").val("Update : "+title1);
							$("input[data-title=\""+title2+"\"]").attr("data-title",title1);
							
						}
					}
				}else if(msg.status=="false"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
				}else{
					console.log("why is here");
				}
			},
			 error:function(XMLHttpRequest, textStatus, errorThrown){
            debugger
            // 状态码
            console.log(XMLHttpRequest.status);
            // 状态
            console.log(XMLHttpRequest.readyState);
            // 错误信息
            console.log(textStatus);
            __hideLoading();
            
            if(XMLHttpRequest.readyState==0){
                // 对应登录超时问题，直接跳到登录页面
                location.href='../Login.action';
            }else{
                $.messager.alert('提示','系统内部错误，请联系管理员处理！','info');
            }
 
        }
		});

		$(".update").removeClass("current");
		return false;
	});

	$(document).on("click",".list_catalog .close",function(){
		$(".list_catalog").empty();
		$(".notice").empty();
	});

	$(document).on("click",".list_catalog .delete",function(){
		var title = $(this).data("title");
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>",
			data:"title="+title+"&action=delete",
			dataType:"json",
			type:"POST",
			success:function(msg){
				if(msg.status=="true"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
					$(".update").each(function(){
						if($(this).data("title")==title){
							$(this).remove();
						}
					});

				}else if(msg.status=="false"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
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
			$(".filter_table").find("input[name='cat_nos[]']").prop("checked",true);
			$(".select_all").prop("checked",true);
		}else{
			$(".filter_table").find("input[name='cat_nos[]']").prop("checked",false);
			$(".select_all").prop("checked",false);
		}
	});

	//datatable页码切换时，判断全选、全不选框的值
	$(document).on("mouseup",".list_catalog_box .paginate_button",function(){
		setTimeout(function(){//当鼠标点击后，瞬间拿到的表格数据还是上一页的数据，需要延时一段时间（暂时设定5毫秒），让表格取回这一页的数据后才开始筛选并处理
			var ischecked = true;
			$(".filter_table").find("input[name='cat_nos[]']").each(function(){
				if($(this).prop("checked")){//有选中，不理它
					;
				}else{//一旦有未选中的，就返回未选中，全选按钮不勾选
					ischecked = false;
				}
			});
			if(ischecked){
				$(".select_all").prop("checked",true);
			}else{
				$(".select_all").prop("checked",false);
			}
		},5);
	});
});
</script>
<div class='label'>
<?php
$all_classify_arr = get_list_misc_product_all_classify($wpdb);
foreach ($all_classify_arr as $one_classify) {
?>
<input type="button" class="update" data-title="<?php echo $one_classify;?>" value="Update : <?php echo $one_classify;?>" />
<?php
}
if(count($all_classify_arr)==20){
	echo '<span class="ellipsis">…</span>';
}
?>
</div>
<input type="button" class="add" value="Add" title="Add one more products list" />
<span class="notice" placeholder="Feedback information" title="Feedback information"></span>
<form action="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>" method="post" name="list_misc_product_form_add" id="list_misc_product_form_add">
	<div class="list_catalog">
	</div>
</form>
<a href="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/manual_of_plugin[List_misc_product_plus].docx'?>" class="help" target="_blank" title="Down manual here">?</a>
<style type="text/css">
	.update{
		color: black;
	}
</style>