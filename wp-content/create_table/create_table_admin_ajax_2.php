<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/create_table_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;
$id=empty($_POST['id'])?'':$_POST['id'];

if($_REQUEST['action']=='add'){
ob_start();
 echo create_table_list($wpdb,$id);
$output = ob_get_clean();
if(!empty($id)){
$product=$wpdb->get_results(sprintf("SELECT * FROM `wp_create_table` where id='%s'",$id));
echo json_encode(array('notice' => "<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode2' value=\"[create_table id='".$id."']\" />",'datetable'=>$product[0]->datetable,'title'=>'table:'.$product[0]->title,'cat_nos'=>$product[0]->content,"h1"=>'Update Table','msg'=>$output));
}else{
	echo json_encode(array('msg'=>$output));
}


}else if($_REQUEST['action']=='table'){
$page=empty($_REQUEST['page'])?1:$_REQUEST['page'];
$datetable=empty($_REQUEST['datetable'])?'_cs_misc_product':$_REQUEST['datetable'];
$search=empty($_REQUEST['search'])?'':$_REQUEST['search'];
ob_start();
echo create_table_list($wpdb,$id,$page,$datetable,$search);
$output = ob_get_clean();
echo json_encode($output);

}
?>