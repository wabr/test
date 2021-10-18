<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;

$cat_nos = isset($_REQUEST['cat_nos']) ? $_REQUEST['cat_nos'] : '';
$classify = isset($_REQUEST['classify']) ? $_REQUEST['classify'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$cn1 = isset($_REQUEST['cn1']) ? $_REQUEST['cn1'] : '';
$cn2 = isset($_REQUEST['cn2']) ? $_REQUEST['cn2'] : '';
$cn3 = isset($_REQUEST['cn3']) ? $_REQUEST['cn3'] : '';
$cn4 = isset($_REQUEST['cn4']) ? $_REQUEST['cn4'] : '';
$cn5 = isset($_REQUEST['cn5']) ? $_REQUEST['cn5'] : '';
$cn6 = isset($_REQUEST['cn6']) ? $_REQUEST['cn6'] : '';
$linkat = isset($_REQUEST['linkat']) ? $_REQUEST['linkat'] : '';
$link_arr = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';
$manual1 = isset($_REQUEST['manual1']) ? $_REQUEST['manual1'] : '';
$manual2 = isset($_REQUEST['manual2']) ? $_REQUEST['manual2'] : '';

$group_id_arr = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';

if($action=='insert'){//添加
	if(!empty($cat_nos)){
		foreach ($cat_nos as $cat_no) {
			$part_of_sql[] = sprintf(" ('%s','%s','%s','%s') ",$cat_no,$classify,(isset($link_arr[$cat_no]) ? $link_arr[$cat_no] : ''),(isset($group_id_arr[$cat_no]) ? $group_id_arr[$cat_no] : ''));
		}
		//一次插入多条记录，速度非常快
		$sql = sprintf("INSERT INTO `_cs_misc_product_class` (`catalog`,`class`,`url`,`group_id`) values %s",implode(',', $part_of_sql));

		$options = array('column1'=>$cn1,'column2'=>$cn2,'column3'=>$cn3,'column4'=>$cn4,'column5'=>$cn5,'column6'=>$cn6,'linkat'=>$linkat,'manual1'=>$manual1,'manual2'=>$manual2);

		$result = $wpdb->query($sql);// or $wpdb->print_error();
		$result2 = add_option($classify,$options);
		
		if( $result == true){
			echo json_encode(array('status'=>'true', 'action'=>'insert', 'notice'=>"<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus class='".$classify."']\" />" ,'msg'=>'Data added.'));
		}else{//false
			echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'Products Add Fail. Please Retry Again.','msg'=>''));
		}
	}else{//提交的catalog是空的
		echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'No Catalog Submit,Nothing to do.','msg'=>''));
	}
	
}elseif($action=='update'){//更新
	foreach ($cat_nos as $cat_no) {
		$part_of_sql[] = sprintf(" ('%s','%s','%s','%s') ",$cat_no,$classify,(isset($link_arr[$cat_no]) ? $link_arr[$cat_no] : ''),(isset($group_id_arr[$cat_no]) ? $group_id_arr[$cat_no] : ''));
	}
	//先删除原先的记录
	$sql = sprintf("DELETE FROM `_cs_misc_product_class` WHERE class='%s'",$classify);
	$result = $wpdb->query($sql);

	//然后插入新的记录
	$sql = sprintf("INSERT INTO `_cs_misc_product_class` (`catalog`,`class`,`url`,`group_id`) values %s",implode(',', $part_of_sql));
	$result = $wpdb->query($sql);

	$options = array('column1'=>$cn1,'column2'=>$cn2,'column3'=>$cn3,'column4'=>$cn4,'column5'=>$cn5,'column6'=>$cn6,'linkat'=>$linkat,'manual1'=>$manual1,'manual2'=>$manual2);
	$result2 = update_option($classify,$options);
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'update', 'notice'=>'Products Update.'));
	}else{
		echo json_encode(array('status'=>'false','action'=>'update', 'notice'=>'Try again.'));
	}
}elseif($action=='delete'){//删除
	$sql = sprintf("DELETE FROM `_cs_misc_product_class` WHERE class='%s'",$classify);
	$result = $wpdb->query($sql);
	$result2 = delete_option($classify);
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'delete', 'notice'=>'Products Delete.'));
	}else{
		echo json_encode(array('status'=>'false','action'=>'delete', 'notice'=>'Try again.'));
	}
}

