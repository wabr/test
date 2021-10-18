<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;

$cat_nos = isset($_REQUEST['cat_nos']) ? $_REQUEST['cat_nos'] : '';
$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$link_arr = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';
$datetable = isset($_REQUEST['datetable']) ? $_REQUEST['datetable'] : '';
$cat_nos=rtrim($cat_nos, ",");
$link_arr=rtrim($link_arr, ",");
//$group_id_arr = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';

if($action=='insert'){//添加
	if(!empty($cat_nos)){
		$val=sprintf(" ('%s','%s','%s') ",$cat_nos, $title,$datetable);
		$sql = sprintf("INSERT INTO `wp_create_table` (`catalog`,`title`,`datetable`) values %s",$val);

		$result = $wpdb->query($sql);// or $wpdb->print_error();
		if( $result == true){
			echo json_encode(array('status'=>'true', 'action'=>'insert', 'notice'=>"<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus title='".$title."']\" />" ,'msg'=>'Data added.', 'label'=>"<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus title='".$title."']\" />" ,'msg'=>'Data added.'));
		}else{//false
			echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'Products Add Fail. Please Retry Again.','msg'=>''));
		}
	}else{//提交的catalog是空的
		echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'No Catalog Submit,Nothing to do.','msg'=>''));
	}
	
}elseif($action=='update'){//更新
	$arr=explode(',', $title);
	if(count($arr)==2){
		$sql = sprintf("UPDATE wp_create_table SET catalog='%s',title='%s' WHERE title='%s';",$cat_nos,$arr[0],$arr[1]);
	}else{
		$sql = sprintf("UPDATE wp_create_table SET catalog='%s' WHERE title='%s';",$cat_nos,$title);
	}
	
	$result = $wpdb->query($sql);
	$c=count($arr)==2;
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'update', 'notice'=>'Products Update.'));
	}else{
		echo json_encode(array('status'=>'false','action'=>'update', 'notice'=>'Try again.'));
	}
}elseif($action=='delete'){//删除
	$sql = sprintf("DELETE FROM `wp_create_table` WHERE title='%s'",$title);
	$result = $wpdb->query($sql);
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'delete', 'notice'=>'Products Delete.'));
	}else{
		echo json_encode(array('status'=>'false','action'=>'delete', 'notice'=>'Try again.'));
	}
}

