<?php
/*
 Plugin Name: Create Table
 Plugin URI: #
 Description: Enhanced Intelligence version of list_misc_product plugin
 Version: 3.0
 Author: RQQ & huimingdeng(DHM)
 Author URI: #
 License: none
 */
register_activation_hook(__FILE__,'wp_create_table');
function wp_create_table() {
	global $wpdb;
		$sql =  'CREATE TABLE IF NOT EXISTS `wp_create_table` (
			`id` int(11) NOT NULL auto_increment,
			`title` VARCHAR(255) default NULL,
			`content` TEXT default NULL,
			`note` TEXT default NULL,
			`datetable` VARCHAR(255) NOT NULL DEFAULT 0 , 
			`create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
			UNIQUE KEY `id` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
dbDelta($sql);
}
//在移除插件时删除wp_friendship_links表，如有需要记得备份
register_uninstall_hook( __FILE__, 'delete_wp_create_table' );
function delete_wp_create_table(){
	global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS `wp_create_table`;");
}
function add_manage(){
    add_menu_page( 
    	'table manage', 
    	'table manage',  
    	'edit_pages', 
    	'table_list', 
    	'table_list'
        
    );
	
     add_submenu_page( 
    	'table_list',
    	'create table',
        'create table',
        'edit_pages',
        'create_table',
        'table_admin_ajax' );
     add_submenu_page( 
    	'table_list',
    	'other functions', 
    	'other functions',  
    	'edit_pages', 
    	'other_functions', 
    	'other_functions' );
}

function table_admin_ajax(){
	include 'create_table_admin_ajax.php';
}

function other_functions(){
	include 'other_functions_ajax.php';
}
function table_list(){
	include 'table_list.php';
}


add_action('admin_menu', 'add_manage');

//后台加载css和js的函数
function load_table_admin_script_and_style() {
	// print_r($_SERVER['SCRIPT_NAME']);
	// var_dump(strpos( $_SERVER['SCRIPT_NAME'] , "/wp-admin/post.php") );
	if(strpos($_SERVER['REQUEST_URI'] , "/wp-admin/admin.php?page=create_table") !== false){//只有文章编辑页的后台才加载css和js
		//后台加载css和js的函数
		wp_enqueue_style('list_product_plus_style_bg_2',plugins_url('css/bootstrap.min.css',__FILE__),'jquery');
		wp_enqueue_style('list_product_plus_style_bg_4',plugins_url('css/list_misc_product_plus.css',__FILE__),'jquery');
		wp_enqueue_script('list_product_plus_script_bg_4',plugins_url('js/bootstrap.min.js',__FILE__),'jquery');
	}
}

//后台加载js/css
add_action('admin_enqueue_scripts', 'load_table_admin_script_and_style');


function create_table($atts) {
	//获得数据库操作对象
	global $wpdb;
	//参数传递
	/*abpbio.com的默认参数*/
	$atts =	shortcode_atts(array(
			'id' => '',
			'order' => 'ASC'
		), $atts);
	//查询数据库
	if(!empty($atts['id'])){
	$result = $wpdb->get_results(sprintf("SELECT datetable,content from wp_create_table where id='%s'",$atts['id']));
	if(!empty($result)){
	$table=$result[0]->datetable;
	$column='';
	if($table=='_cs_misc_product'){
		$option=get_option('misc_c');
		$pro='catalog';
		$url='url';
	}else if($table=='vector'){
		$option=get_option('vector_c');
		$pro='vector_name';
		$url='vector_link';
	}
	$i=0;
	foreach ($option as $key => $value) {
		if(!empty($value[0])){
			$i++;
			$column.="`".$key."`,";
			$arr3[$key]=$value;
			if($value[1]==2){
				$arr2[]=$key.",".$i;
			}
		}
	}
	$column=rtrim($column,",");
	$arr=explode(',', $result[0]->content);
	if(!empty($arr)){
	foreach($arr as $key=>$ar){
		if($key==0){
			$str=$pro."='".$ar."'";
		}else{
			$str.=" or ".$pro."='".$ar."'";
		}
	}
	$wheresql="and (".$str.")";	
	}else{
		$wheresql='and is_published <> 1';	
	}
	//echo sprintf("SELECT %s,%s FROM `%s` WHERE is_published = 1 %s",$column,$url,$table,$wheresql);
	$products = $wpdb->get_results(sprintf("SELECT %s,%s FROM `%s` WHERE is_published = 1 %s",$column,$url,$table,$wheresql));
	ob_start();

	//加入显示内容
	//不同显示内容可以定义不同的template文件
	include(dirname(__FILE__)."/create_table_template.php");
	$output = ob_get_clean();
	return $output;
	}else{
		echo '';
	}
	}else{
		echo '';
	}
}
//添加短码[create_table id='?']
add_shortcode('create_table', 'create_table');



