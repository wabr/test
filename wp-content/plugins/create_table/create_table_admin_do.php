<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/create_table_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;

$cat_nos = isset($_REQUEST['cat_nos']) ? $_REQUEST['cat_nos'] : '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$link_arr = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';
$datetable = isset($_REQUEST['datetable']) ? $_REQUEST['datetable'] : '';
$is_hide=isset($_REQUEST['is_hide']) ? $_REQUEST['is_hide'] : '';
$cat_nos=rtrim($cat_nos, ",");
$link_arr=rtrim($link_arr, ",");
//$group_id_arr = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';

if($action=='insert'){//添加
	if(!empty($cat_nos)){
		$miss=$wpdb->get_var(sprintf("SELECT count(*) FROM `wp_create_table` where title='%s'",$title));
		if($miss==0){
		$id2=$wpdb->get_results("select id from wp_create_table order by id desc limit 1");
		$id2=$id2[0]->id+1;
		$title=empty($title)?$id2:$title;
		$val=sprintf(" ('%s','%s','%s','%s') ",$cat_nos, $title,$datetable,$is_hide);
		$sql = sprintf("INSERT INTO `wp_create_table` (`content`,`title`,`datetable`,`is_hide`) values %s",$val);
		$result = $wpdb->query($sql);// or $wpdb->print_error();

		if(!empty($link_arr)){
		if($datetable=='_cs_misc_product'){
			$sort='catalog';
			$url='url';
		}else if($datetable=='vector'){
			$sort='vector_name';
			$url='vector_link';
		}
		$arr2=explode(',', $link_arr);
		foreach ($arr2 as $arr) {
			$arr3=explode('|', $arr);
			$sql2 = sprintf("UPDATE %s SET %s='%s' WHERE %s='%s';",$datetable,$url,$arr3[1],$sort,$arr3[0]);
			$result2 = $wpdb->query($sql2);
		}
		}

		if( $result == true){
			echo json_encode(array('status'=>'true', 'action'=>'insert', 'notice'=>"<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode2' value=\"[create_table id='".$id2."']\" /><br /><a class='btn' href='/wp-admin/admin.php?page=create_table&id=".$id2."'>table:".$title."</a>" ,'msg'=>'Data added.','title'=>'table:'.$title));
		}else{//false
			echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'Products Add Fail. Please Retry Again.','msg'=>'','title'=>$title));
		}
		}else{
			echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'Products Add Fail. Duplicate Table Title.','msg'=>'','title'=>''));
		}
	}else{//提交的catalog是空的
		echo json_encode(array('status'=>'false','action'=>'insert', 'notice'=>'No Content Submit,Nothing to do.','msg'=>'','title'=>''));
	}
	
}elseif($action=='update'){//更新
	if(!empty($title)){
		$miss=$wpdb->get_var(sprintf("SELECT count(*) FROM `wp_create_table` where title='%s'",$title));
		if($miss!=0){
			echo json_encode(array('status'=>'false','action'=>'update', 'notice'=>'Duplicate Table Title.','title'=>'table:'.$result3[0]->title));
			exit;
		}
		$sql = sprintf("UPDATE wp_create_table SET content='%s',title='%s',is_hide='%s' WHERE id='%s';",$cat_nos,$title,$is_hide,$id);
	}else{
		$sql = sprintf("UPDATE wp_create_table SET content='%s',is_hide='%s' WHERE id='%s';",$cat_nos,$is_hide,$id);
	}
	$result = $wpdb->query($sql);
	$result3=$wpdb->get_results("select title from wp_create_table where id={$id}");
	if(!empty($link_arr)){
		if($datetable=='_cs_misc_product'){
			$sort='catalog';
			$url='url';
		}else if($datetable=='vector'){
			$sort='vector_name';
			$url='vector_link';
		}
		$arr2=explode(',', $link_arr);
		foreach ($arr2 as $arr) {
			$arr3=explode('|', $arr);
			$sql2 = sprintf("UPDATE %s SET %s='%s' WHERE %s='%s';",$datetable,$url,$arr3[1],$sort,$arr3[0]);
			$result2 = $wpdb->query($sql2);
		}
	}	
	
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'update', 'notice'=>'Products Update.<br/><br/><a class="btn" href="/wp-admin/admin.php?page=create_table&id='.$id.'">table:'.$result3[0]->title.'</a>','title'=>'table:'.$result3[0]->title));
	}else{
		echo json_encode(array('status'=>'false','action'=>'update', 'notice'=>'Try again.','title'=>'table:'.$result3[0]->title));
	}
}elseif($action=='delete'){//删除
	if(gettype($id)=='array'){
		$where='(';
		foreach($id as $key=>$value){
			if($key==(count($id)-1)){
				$where.=$value.')';
			}else{
				$where.=$value.',';
			}
		}
	}else{
		$where='('.$id.')';
	}
	$sql = sprintf("DELETE FROM `wp_create_table` WHERE id IN %s",$where);
	$result = $wpdb->query($sql);
	if( $result !== false ){
		echo json_encode(array('status'=>'true','action'=>'delete', 'notice'=>'Products Delete.'));
	}else{
		echo json_encode(array('status'=>'false','action'=>'delete', 'notice'=>'Try again.'));
	}
}

