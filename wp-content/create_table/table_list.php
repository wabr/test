<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/create_table_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;
function get_table_list($wpdb,$page=1,$where=''){
	$where=$where==''?'':stripslashes($where);
	$cou=$wpdb->get_var(sprintf("select count(*) from wp_create_table %s",$where));
	$maxpage=ceil($cou/10);
	$star=($page-1)*10;
	$tables=$wpdb->get_results(sprintf("select id,title,create_date from wp_create_table %s limit %s,10",$where,$star));
	if(!empty($tables)){
		foreach ($tables as $table) {
		$str1.="<tr>
			<td class='fr'><input name='cat_nos[]' type='checkbox' class='che' value='".$table->id."' /></td>
			<td class='se'><a href='/wp-admin/admin.php?page=create_table&id=".$table->id."'>".$table->title."</a></td>
			<td class='se'>".$table->create_date."</td>
			<td class='th'>[create_table id=".$table->id."]</td>
			</tr>";
		}
	}
	if($cou>10){
		$str2 = "<input type='button' class='jump current' value='1'/>";
		if($maxpage<5){
			for($i=2;$i<=$maxpage;$i++){
				$str2 .="<input type='button' class='jump' value='".$i."'/>";
			}
		}else{
		if($page>3){
			$str2.="<span class='ellipsis'>…</span>";
		}
		if($page<=2){
			$str2.="<input type='button' class='jump ' value='2'/><input type='button'  class='jump' value='3'/>";
		}else if($page>=$maxpage-1){
			$str2.="<input type='button' class='jump' value='".($maxpage-2)."'/><input type='button' class='jump' value='".($maxpage-1)."'/>";
		}else{
			$str2="<input type='button' class='jump' value='".($page-1)."'/><input type='button' class='jump' value='".$page."'/><input type='button' class='jump' value='".($page+1)."'/>";
		}
		if($page<$maxpage-2){
			$str2.="<span class='ellipsis'>…</span>";
		}
		$str2 .="<input type='button' class='jump' value='".$maxpage."'/>";
	}}
	$arr[0]=$str1;
	$arr[1]=$str2;
	$arr[3]=$cou;
	//$arr[4]=sprintf("select count(*) from wp_create_table %s",$where);
	return $arr;
}
if(!empty($_POST['action'])){
	$page=$_POST['page']==''?1:$_POST['page'];
	$where=$_POST['where']==''?'':$_POST['where'];
	$arr=get_table_list($wpdb,$page,$where);
	echo json_encode($arr);
	exit;
}
$arr=get_table_list($wpdb);

$time=$wpdb->get_results("select YEAR(create_date) as year,MONTH(create_date) as mon from wp_create_table group by year,mon");
?>
<div>
<h1 class="start">Table List</h1>
<a href="/wp-admin/admin.php?page=create_table" class="start">Add New</a>
</div>
<div>
<select class="sel" id="bulk1">
	<option value="">Bulk actions</option>
	<option value="delete">delete</option>
</select>
<input type="button" id="bulk2" value="Apply" />
</div>
	filter table:
<select class="sel" id="dt1">
	<option value="">Select datetable</option>
	<option value="_cs_misc_product">misc</option>
	<option value="vector">vector</option>
</select>
<select class="sel" id="time">
	<option value="">All date</option>
	<?php
	if(!empty($time)){
	foreach ($time as $key => $value) {
		echo '<option value="'.$value->year."-".$value->mon.'">'.$value->year."-".$value->mon.'</option>';
	}}
	?>
</select>
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
search label:
<input type="text" placeholder="title or product" name="search_la" id="search_la"/>
<button class='search_la' type="button"><span class="dashicons dashicons-search"></span></button>
<span class='sp'><?php echo $arr[3]; ?> tables</span>
<table class="widefat striped wid">
<thead>
	<tr>
		<th class='fr'><input name='select_all' type='checkbox' class='select_all' /></th>
		<th class='se'>Title</th>
		<th class='th'>Date</th>
		<th class='fo'>Shortcode</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<th class='fr'><input name='select_all' type='checkbox' class='select_all' /></th>
		<th class='se'>Title</th>
		<th class='th'>Date</th>
		<th class='fo'>Shortcode</th>
		</tr>
</tfoot>
<tbody id='table'>
<?php if(!empty($arr[0])){
		echo $arr[0];
	  }else{
	  	echo 'no date';
	  }?>
</tbody>
</table>
<div id='jump' class='page'>
<?php if(!empty($arr[1])){
		echo $arr[1];
	}?>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	$("#dt1").live('change',function(){
		var dt1=$("#dt1").val();
		where=dt1==''?'':"where datetable='"+dt1+"'";
		search_la=$("input[name=search_la]").val()==''?'':$("input[name=search_la]").val();
		if(search_la!=''){
			where+="and (content like '%"+search_la+"%' or title like '%"+search_la+"%')";
		}
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/table_list.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"where="+where+"&action=dt1",
			beforeSend:function(){
				$("#table").html("Loading...");
			},
			success:function(msg){
				$("#table").html("").append(msg[0]).show();
				$("#jump").html("").append(msg[1]).show();
			}
		});
	});
	$("#time").live('change',function(){
			time=$("#time").val().split('-');
			where='where YEAR(create_date)='+time[0]+' and MONTH(create_date)='+time[1];
			search_la=$("input[name=search_la]").val()==''?'':$("input[name=search_la]").val();
			if(search_la!=''){
				where+="and (content like '%"+search_la+"%' or title like '%"+search_la+"%')";
			}
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/table_list.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"where="+where+"&action=time",
			beforeSend:function(){
				$("#table").html("Loading...");
			},
			success:function(msg){
				$("#table").html("").append(msg[0]).show();
				$("#jump").html("").append(msg[1]).show();
			}
		});
	});
	$(".search_la").click(function(){
		var search_la=$("#search_la").val();
		where="where content like '%"+search_la+"%' or title like '%"+search_la+"%'";
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/table_list.php';?>",
			type:"POST",
			async:true,
			dataType:"json",
			data:"where="+where+"&action=search",
			beforeSend:function(){
				$(".table").html("Loading...");
			},
			success:function(msg){
		console.log(msg);
		console.log(msg[4]);
				$("#table").html("").append(msg[0]).show();
				$("#jump").html("").append(msg[1]).show();
				$("#time").val('');
				$("#dt1").val('');
			}
		});
	});
	$(document).on("click",".jump",function(){
		var dt1=$("#dt1").val();
		var time=$("#time").val().split('-');
		var page = $(this).val();
		var search_la=$("#search_la").val();
		where='';
		if(dt1!=''||time!=''||search_la!=''){
		where='where ';
		if(dt1!=''){
				where+="datetable="+dt1;
		}
		if(time!=''){
			if(dt1!=''){
				where+=" and ";
			}
			where+=" YEAR(create_date) ="+time[0]+" and MONTH(create_date)="+time[1];
		}
		if(search_la!=''){
			if(dt1!=''||time!=''){
				where+=" and ";
			}
			where+=" (content like '%"+search_la+"%' or title like '%"+search_la+"%')";
		}}
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/table_list.php';?>",
			type:"POST",
			dataType:"json",
			async:true,
			data:"page="+page+"&where="+where+"&action=jump",
			beforeSend:function(){
				$(".table").html("Loading...");
			},
			success:function(msg){
				console.log(msg);
				$("#table").html("").append(msg[0]).show();//.append(msg);
				$("#jump").html("").append(msg[1]).show();
				$("input[value='"+page+"']").addClass("current").siblings().removeClass("current");
			}
		});
	});
	$("#bulk2").click(function(){
		if($("#bulk1").val()=="delete"){
		id=[];
		$('input[name="cat_nos[]"][type="checkbox"]:checked').each(function(){
			id.push($(this).val());
		});
		if(id==''){
			alert('No data selected');
			return false;
		}
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/create_table_admin_do.php'?>",
			data:{id:id,action:'delete'},
			dataType:"json",
			type:"POST",
			success:function(msg){
				console.log(msg);
				if(msg.status=="true"){
					alert(msg.notice);
					$(location).attr('href','/wp-admin/admin.php?page=table_list');
				}else if(msg.status=="false"){
					alert(msg.notice);
				}else{
					console.log("why is here");
				}
			}
		});
	}
	});
	$(document).on("click",".select_all",function(){
		if($(this).is(":checked")==true){
			$("input[name='cat_nos[]']").prop("checked",true);
			$(".select_all").prop("checked",true);
		}else{
			$("input[name='cat_nos[]']").prop("checked",false);
			$(".select_all").prop("checked",false);
		}
	});

});
</script>
<style>
	select,input{border-radius: 3px;border-width: 1px;}
	.search_la{
		background: #eee;border-width: 0;
	}
	.sp{
		float:right;
		padding: 10px 20px 0 0;
	}
	.sel{
		margin:0 0 5px 0;
	}

.bulck{
	color: #555;
    border-color: #ccc;
    background: #f7f7f7;
    box-shadow: 0 1px 0 #ccc;
    font-size: 13px;
    line-height: 23px;
    padding: 0 10px 1px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    -webkit-appearance: none;
    border-radius: 3px;
}
	a.start {
    display: inline;
    padding: 4px 8px;
    position: relative;
    top: -3px;
    text-decoration: none;
    border: 1px solid #ccc;
    border-radius: 2px;
    background: #f7f7f7;
    font-weight: 600;
}
h1.start {
    font-size: 2em;
    line-height: 1.6em;
    margin: 10px 15px 10px 0;
    display: inline-block;
}
.wid{
	width: 1147px;
}
.fr{
    width: 2.2em;
}
.se{
	width: 399px;
}
.th{
	width: 300px;
}
.fo{
	width: 350px;
}
.che{
    margin-left: 8px !important;
	}
.jump.current{
	background-color: #fff;
	color: #333 !important;
    border: 1px solid #cacaca;
    background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%);
    }
.jump{
	box-sizing: border-box;
	background-color: #f1f1f1;
    display: inline-block;
    min-width: 1.5em;
    padding: 0.5em 1em;
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    color: #333 !important;
    border: 1px solid transparent;
}
.page :hover{
	color: white !important;
    border: 1px solid #111;
    background-color: #585858;
    background: linear-gradient(to bottom, #585858 0%, #111 100%);
}
	</style>
