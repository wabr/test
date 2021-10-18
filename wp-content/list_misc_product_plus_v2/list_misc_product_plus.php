<?php
/*
 Plugin Name: List misc_product Plus
 Plugin URI: #
 Description: Enhanced Intelligence version of list_misc_product plugin
 Version: 3.0
 Author: RQQ & huimingdeng(DHM)
 Author URI: #
 License: none
 */

function add_manage_metabox(){
    add_meta_box(
        'list_misc',
        'List Products',
        'admin_ajax',
        array('post','page')
    );
}

function admin_ajax(){
	include 'list_misc_product_plus_admin_ajax.php';
}

add_action('add_meta_boxes', 'add_manage_metabox');

//后台加载css和js的函数
function load_background_script_and_style_list_product_plus() {
	// print_r($_SERVER['SCRIPT_NAME']);
	// var_dump(strpos( $_SERVER['SCRIPT_NAME'] , "/wp-admin/post.php") );
	if(strpos($_SERVER['SCRIPT_NAME'] , "/wp-admin/post.php") !== false){//只有文章编辑页的后台才加载css和js
		//后台加载css和js的函数
		wp_enqueue_style('list_product_plus_style_bg_1',plugins_url('css/jquery-ui.css',__FILE__),'jquery');
		wp_enqueue_style('list_product_plus_style_bg_2',plugins_url('css/bootstrap.min.css',__FILE__),'jquery');
		wp_enqueue_style('list_product_plus_style_bg_3',plugins_url('css/jquery.dataTables.min.css',__FILE__),'jquery');
		wp_enqueue_style('list_product_plus_style_bg_4',plugins_url('css/list_misc_product_plus.css',__FILE__),'jquery');
		wp_enqueue_script('list_product_plus_script_bg_1',plugins_url('js/jquery-ui.js',__FILE__),'jquery');
		wp_enqueue_script('list_product_plus_script_bg_2',plugins_url('js/jquery.form.js',__FILE__),'jquery');
		wp_enqueue_script('list_product_plus_script_bg_3',plugins_url('js/jquery.dataTables.min.js',__FILE__),'jquery');
		wp_enqueue_script('list_product_plus_script_bg_4',plugins_url('js/bootstrap.min.js',__FILE__),'jquery');
	}
}

//后台加载js/css
add_action('admin_enqueue_scripts', 'load_background_script_and_style_list_product_plus');


function list_misc_product_plus($atts) {
	//获得数据库操作对象
	global $wpdb;
	//参数传递
	/*abpbio.com的默认参数*/
	$atts =	shortcode_atts(array(
			'class' => 1,
			'order' => 'ASC'
		), $atts);
	//查询数据库
	$orderby = " order by `order` ".($atts['order']=='ASC'?'ASC':'DESC')." ,`c_catalog` ".($atts['order']=='ASC'?'ASC':'DESC');
	$atts_class_array = explode(',',$atts['class']);
	if(sizeof($atts_class_array)>=1){
		$i=1;
		foreach($atts_class_array as $atts_class_array_one){
			if($i==1){
				$wheresql = sprintf(" c.class = '%s' ",$atts_class_array_one);
			}else{
				$wheresql .= sprintf(" or c.class = '%s' ",$atts_class_array_one);
			}
			$i++;
		}
	}else{
		$wheresql = '0';
	}

	$products = $wpdb->get_results(sprintf("SELECT * ,c.catalog as c_catalog, c.url as c_url,p.url as p_url
	FROM  `_cs_misc_product_class` c
	LEFT JOIN _cs_misc_product p ON c.catalog = p.catalog OR c.catalog = p.old_catalog
	WHERE p.is_published = 1 and ".$wheresql.$orderby));

	ob_start();

	//加入显示内容
	//不同显示内容可以定义不同的template文件
	include(dirname(__FILE__)."/list_misc_product_template.php");
	$output = ob_get_clean();
	return $output;
}
//添加短码[list_misc_product_plus class='?']
add_shortcode('list_misc_product_plus', 'list_misc_product_plus');



