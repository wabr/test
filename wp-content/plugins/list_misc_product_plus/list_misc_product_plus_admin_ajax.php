<?php
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
global $wpdb;
// $postid = $_REQUEST['postID'];
$postid = get_the_ID();
?>


<script type="text/javascript">
jQuery(document).ready(function($){
	//最定义两个函数：求最大值、最小值
	/*************
	Usage:
	[1,2,3].max()// => 3 
	[1,2,3].min()// => 1
	**************/
	Array.prototype.max = function(){ 
		return Math.max.apply({},this) 
	}
	Array.prototype.min = function(){ 
		return Math.min.apply({},this) 
	}

	//全局变量，记录最大的分组id
	var max_gid;

	//全局变量，存储整个dataTables的数据
	var table;

	var dataTablesOptions = {
		"aLengthMenu":  [10,20,30,40,50,60,70,80,90,100,200],//设置每页显示多少行的选项
		"iDisplayLength": 10,//每页多少行。
		"ordering":false,//整个表不能排序
		"columnDefs": [//决定列的属性
			{
			"targets": [ 0,2,3 ],//第几列，从0开始
			"searchable": false//不能搜索
			}
		]
	};
	
	$("#list_misc .handlediv").click(function(){
		$(this).siblings(".inside").toggle();
		$("#list_misc").toggleClass('closed');
	});

	$("#list_misc .add").click(function(){
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_ajax_2.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"postid=<?php echo $postid;?>&action=add",
			beforeSend:function(){
				$(".list_catalog").html("Loading...");
				$(".notice").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog").html("").append(msg.msg).show();
				$(".notice").html("").append(msg.notice);
				table = $(".filter_table").DataTable(dataTablesOptions);
			}
		});
	});
	$(document).on("click","#list_misc .update",function(){
		var classify = $(this).data("classify");
		$(this).addClass("current").siblings().removeClass("current");
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_ajax_2.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"classify="+classify+"&action=update",
			beforeSend:function(){
				$(".list_catalog").html("Loading...");
				$(".notice").html("Loading...");
			},
			success:function(msg){
				$(".list_catalog").html("").append(msg.msg).show();//.append(msg);
				$(".notice").html("").append(msg.notice);
				table = $(".filter_table").DataTable(dataTablesOptions);
			}
		});
	});

	$(document).on("click",".list_catalog .save",function(){
		var classify = $("#list_misc_product_form_add").find("input[name='classify']").val();
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>",
			beforeSubmit: function(){
				$(".list_catalog").html("Form preservation, please wait...").show();
			},
			type:'POST',
			dataType:'json',
			data: $(".list_catalog").find("input[type='hidden']").serialize()+"&"+$(".column_message").find("input,select").serialize()+"&"+$(".user_manual").find("input").serialize()+"&"+table.$('input, select').serialize(),
			success: function(msg){
				if(msg.status=="true"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
					if(msg.action=="insert"){
						$(".update").last().after("<input type=\"button\" class=\"update\" data-classify=\""+classify+"\" value=\"Update : "+classify+"\">");
					}
				}else if(msg.status=="false"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
				}else{
					console.log("why is here");
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
		var classify = $(this).data("classify");
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>",
			data:"classify="+classify+"&action=delete",
			dataType:"json",
			type:"POST",
			success:function(msg){
				if(msg.status=="true"){
					$(".list_catalog").html("");
					$(".notice").html(msg.notice);
					$(".update").each(function(){
						if($(this).data("classify")==classify){
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

	$(document).on("click",".check",function(){
		$(this).toggleClass("on");
		$(".merge").show();
		return false;
	});

	$(document).on("click",".merge",function(){
		var gids = new Array();
		table.$(".check").each(function(){
			gids.push($(this).data("gid"));
		});
		if(max_gid>0){
			max_gid++;
		}else{
			max_gid = gids.max() * 1 + 1;
		}
		$(".check.on").each(function(){
			$(this).data("gid",max_gid).removeClass("on");
			key = $(this).data("id");
			//如果存在相同的hidden值，删除它
			$("input[type=hidden]").each(function(){
				if($(this).data("id")==key){
					$(this).remove();
				}
			});
			//在表单里加入隐藏属性，存储值
			$(".list_catalog").prepend("<input type=\"hidden\" data-id=\""+key+"\" name=\"group_id["+$(this).data("id")+"]\" value=\""+max_gid+"\" />");
			//加入group_id标签
			$(this).siblings(".gid").html("Group: "+max_gid+" <a href=\"#\" data-id=\""+key+"\" class=\"remove_gid\"></a>");
		});
		$(".merge").hide();
	});

	//删除group_id
	$(document).on("click",".remove_gid",function(){
		var id = $(this).data("id");
		$("input[type=hidden]").each(function(){
			if($(this).data("id")==id){
				$(this).remove();
			}
		});
		$(this).parent(".gid").empty();
		return false;
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

<?php
$all_classify_arr = get_list_misc_product_all_classify($wpdb,$postid);
foreach ($all_classify_arr as $one_classify) {
?>
<input type="button" class="update" data-classify="<?php echo $one_classify;?>" value="Update : <?php echo $one_classify;?>" />
<?php
}
?>
<input type="button" class="add" value="Add" title="Add one more products list" />
<span class="notice" placeholder="Feedback information" title="Feedback information"></span>
<form action="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/list_misc_product_plus_admin_do.php'?>" method="post" name="list_misc_product_form_add" id="list_misc_product_form_add">
	<div class="list_catalog">
	</div>
</form>
<a href="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/manual_of_plugin[List_misc_product_plus].docx'?>" class="help" target="_blank" title="Down manual here">?</a>