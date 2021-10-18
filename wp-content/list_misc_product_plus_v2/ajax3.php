<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;
$title=empty($_REQUEST['title'])?'':$_REQUEST['title'];
$page=empty($_REQUEST['page'])?1:$_REQUEST['page'];
$datetable=empty($_REQUEST['datetable'])?'_cs_misc_product':$_REQUEST['datetable'];
echo list_misc_product_list_v2($wpdb,$title,$page,$datetable);