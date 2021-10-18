<?php
// 如果不存在json_encode这个函数，就加上去
if (!function_exists('json_encode')){
	function json_encode($a=false){
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a)){
			if (is_float($a)){
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($a)));
			}
			if (is_string($a)){
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			}else
				return $a;
		}
			$isList = true;
			for ($i = 0, reset($a); $i < count($a); $i++, next($a)){
				if (key($a) !== $i){
					$isList = false;
					break;
				}
			}
			$result = array();
			if ($isList){
				foreach ($a as $v) $result[] = json_encode($v);
				return '[' . join(',', $result) . ']';
			}else{
				foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
				return '{' . join(',', $result) . '}';
		}
	}
}


function list_misc_product_list($wpdb,$products_checkin=false,$links=false){
	// $page = is_numeric($page)?$page-1:0;
	// $per = 10;
	// $start = $page * $per;
	if(empty($products_checkin)){//不提供$products_checkin,即不需要选中相应产品
		// $products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` ORDER BY catalog ASC LIMIT %d,%d",$start,$per));
		$products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` ORDER BY catalog ASC"));
		foreach ($products as $product) {
			$return_string .= sprintf("
			<div class=\"catalog\">
				<div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" /></div>
				<div class=\"fl label\"><label for=\"%s\">%s</label></div>
				<div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" /></div>
			</div>
			",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->url);
		}
		return $return_string;

	}else{//提供classify，列出全部产品，并选中相关项
		//products_checkin格式：
		//array(catalog=>url,...)


/**************************************************************
sql 语句要合并成这样子
SELECT *,1 as ob FROM `_cs_misc_product` WHERE catalog IN ('108501-001','108501-002','108501-003','108501-004','108501-005','108501-006','108501-007','108501-009','108501-011','AMRT-0060','AOMD-Q060','AOPR-0600','AOPR-4000','AORT-0050','C001','C002','C004','CGAB-RFP-0050') union SELECT *,2 as ob FROM `_cs_misc_product` WHERE catalog NOT IN ('108501-001','108501-002','108501-003','108501-004','108501-005','108501-006','108501-007','108501-009','108501-011','AMRT-0060','AOMD-Q060','AOPR-0600','AOPR-4000','AORT-0050','C001','C002','C004','CGAB-RFP-0050') ORDER BY ob asc, catalog ASC LIMIT 0,10;

**************************************************************/


		$catalog_arr = array_keys($products_checkin);//取出catalog
		$part_of_sql = implode("','", $catalog_arr);
		// $products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog IN ('%s') ORDER BY catalog ASC LIMIT %d,%d",$part_of_sql,$start,$per));
		$products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog IN ('%s') ORDER BY catalog ASC",$part_of_sql));
		//echo sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog IN ('%s') ORDER BY catalog ASC LIMIT %d,%d",$part_of_sql,$start,$per);
		//echo "<br />";
		foreach ($products as $product) {//已经选中的
			$return_string .= sprintf("
			<div class=\"catalog\">
				<div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" checked=\"checked\" /></div>
				<div class=\"fl label\"><label for=\"%s\">%s</label></div>
				<div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" value=\"%s\" /></div>
			</div>
			",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->catalog,($products_checkin[$product->catalog]=='' ? $product->url : $products_checkin[$product->catalog]) , $products_checkin[$product->catalog]);
		}

		// $products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog NOT IN ('%s') ORDER BY catalog ASC LIMIT %d,%d",$part_of_sql,$start,$per));
		$products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog NOT IN ('%s') ORDER BY catalog ASC",$part_of_sql));
		//echo sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog NOT IN ('%s') ORDER BY catalog ASC LIMIT %d,%d",$part_of_sql,$start,$per);

		foreach ($products as $product) {//未选中
			$return_string .= sprintf("
			<div class=\"catalog\">
				<div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" /></div>
				<div class=\"fl label\"><label for=\"%s\">%s</label></div>
				<div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" /></div>
			</div>
			",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->url);
		}
		return $return_string;
	}
}


function list_misc_product_list_v2($wpdb,$classify=false){
	if(empty($classify)){//不提供$classify,即不需要选中相应产品
		$products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` ORDER BY catalog ASC"));
		$return_string = '';
		$return_string .= sprintf('<table class="filter_table">');
		$return_string .= sprintf("
			<thead>
				<tr>
					<th class=\"tal\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal\">Catalog</th>
					<th class=\"tal\">Description</th>
					<th class=\"tal\">URL</th>
					<th class=\"tar\">Group</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class=\"tal\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal\">Catalog</th>
					<th class=\"tal\">Description</th>
					<th class=\"tal\">URL</th>
					<th class=\"tar\">Group</th>
				</tr>
			</tfoot>
		");
		foreach ($products as $product) {
			$return_string .= sprintf("
			<tr>
				<td class=\"tal\">
				<div class=\"catalog\"><div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" /></div></div>
				</td>
				<td class=\"tal\"><div class=\"fl label\"><label for=\"%s\">%s</label></div></td>
				<td class=\"tal\">%s</td>
				<td class=\"tal\"><div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" title=\"Double Click To Use This Value\" /></div></td>
				<td class=\"tar\"><span class=\"gid\"></span><a href=\"#\" class=\"check\" data-id=\"%s\" data-gid=\"%d\" title=\"Merge The Line \"></a></td>
			</tr>
			",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->desc,$product->catalog,$product->url,$product->catalog,0);

		}

		$return_string .= sprintf('</table>');
		return $return_string;

	}else{//提供classify，列出全部产品，并选中相关项

		$return_string = '';
		$hidden_gid_string = '';
		$return_string .= sprintf('<table class="filter_table">');
		$return_string .= sprintf("
			<thead>
				<tr>
					<th class=\"tal\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal\">Catalog</th>
					<th class=\"tal\" class=\"tal\">URL</th>
					<th class=\"tar\">Group</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class=\"tal\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal\">Catalog</th>
					<th class=\"tal\">URL</th>
					<th class=\"tar\">Group</th>
				</tr>
			</tfoot>
		");

		$products = $wpdb->get_results(sprintf("SELECT *,p.catalog AS p_catalog, c.url AS c_url FROM `_cs_misc_product` as p RIGHT JOIN `_cs_misc_product_class` AS c ON p.catalog=c.catalog WHERE c.class='%s' ORDER BY group_id ASC, p.catalog ASC",$classify));
		foreach ($products as $product) {//已经选中的
			if($product->group_id!=''){
				$gid_string = sprintf("Group: %s <a href=\"#\" data-id=\"%s\" class=\"remove_gid\"></a>",$product->group_id,$product->catalog);
				$hidden_gid_string = sprintf("<input type=\"hidden\" data-id=\"%s\" name=\"group_id[%s]\" value=\"%d\">",$product->catalog,$product->catalog,$product->group_id);
				
			}else{
				$gid_string='';
			}

			if($product->p_catalog!=''){
				$return_string .= sprintf("
				<tr>
					<td class=\"tal\"><div class=\"catalog\"><div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" checked=\"checked\" /></div></div></td>
					<td class=\"tal\"><div class=\"fl label\"><label for=\"%s\">%s</label></div></td>
					<td class=\"tal\"><div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" value=\"%s\" title=\"Double Click To Use This Value\" /></div></td>
					<td class=\"tar\"><span class=\"gid\">%s</span><a href=\"#\" class=\"check\" data-id=\"%s\" data-gid=\"%d\" title=\"Merge The Line \"></a>%s</td>
				</tr>
				",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->catalog, $product->c_url , $product->c_url,$gid_string,$product->catalog,$product->group_id,$hidden_gid_string);
			}
		}

		$products = $wpdb->get_results(sprintf("SELECT * FROM `_cs_misc_product` WHERE catalog NOT IN (SELECT catalog FROM `_cs_misc_product_class` WHERE class='%s') ORDER BY catalog ASC",$classify));

		foreach ($products as $product) {//未选中
			$return_string .= sprintf("
			<tr>
				<td class=\"tal\"><div class=\"catalog\">
					<div class=\"fl cat_nos\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" /></div></div></td>
				<td class=\"tal\"><div class=\"fl label\"><label for=\"%s\">%s</label></div></td>
				<td class=\"tal\"><div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" title=\"Double Click To Use This Value\" /></div></td>
				<td class=\"tar\"><span class=\"gid\"></span><a href=\"#\" class=\"check\" data-id=\"%s\" data-gid=\"%s\" title=\"Merge The Line \"></a></td>
			</tr>
			",$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->catalog,$product->url,$product->catalog,0);
		}
		$return_string .= sprintf('</table>');
		return $return_string;
	}
}




function get_list_misc_product_all_classify($wpdb,$postid){
	$all_array = array();
	$products = $wpdb->get_results(sprintf("SELECT `class`,CAST(substring_index(class,'-',-1) AS SIGNED) as ob FROM `_cs_misc_product_class` WHERE `class` like '%s-%%' ORDER BY ob ASC",$postid));
	foreach ($products as $product) {
		$all_array[] = $product->class;
	}
	 return array_unique($all_array);
}

function get_list_misc_product_max_classify($wpdb,$postid){
	$max_array = array();
	$products = $wpdb->get_results(sprintf("SELECT `class` FROM `_cs_misc_product_class` WHERE `class` like '%s-%%'",$postid));
	foreach ($products as $product) {
		if($product->class!=''){//分解classify
			$max_array[] = substr($product->class,strrpos($product->class,'-')+1);
		}
	}
	if(empty($max_array)){
		return $postid.'-1';
	}else{
		return $postid.'-'.(max($max_array)+1);
	}
}
/*
function get_list_misc_product_class_products($wpdb,$classify){
	$products_arr = array();
	$products = $wpdb->get_results(sprintf("SELECT `catalog` FROM `_cs_misc_product_class` WHERE `class` = '%s' ORDER BY `catalog` ASC",$classify));
	foreach ($products as $product) {
		$products_arr[] = $product->catalog;
	}
	return $products_arr;
}
*/

function get_list_misc_product_class_products($wpdb,$classify){
	$products_arr = array();
	$products = $wpdb->get_results(sprintf("SELECT `catalog`,`url` FROM `_cs_misc_product_class` WHERE `class` = '%s'  ORDER BY `catalog` ASC",$classify));
	foreach ($products as $product) {
		$products_arr[$product->catalog] = $product->url;
	}
	return $products_arr;
}


if(!function_exists("pick_the_same_group")){
	function pick_the_same_group(&$array,&$array_to_add,$group_id){
		for ($i=0; $i < count($array); ) {
			if($array[$i]['group_id']==$group_id){
				array_push($array_to_add, $array[$i]);//把数据压入数组最后
				array_splice($array, $i,1);//删掉一个，指针不增加
			}else{
				$i++;
			}
		}
	}
}


if(!function_exists("combine_the_same_group")){
	function combine_the_same_group(&$array,&$new_array,$group_id){
		$tmp = array();
		for ($i=0; $i < count($array); ) {
			if($array[$i]['group_id']==$group_id){
				$tmp[] = $array[$i];//存储到一个数组，在最后要合并的
				array_splice($array, $i,1);//删掉一个，指针不增加
			}else{
				$i++;
			}
		}
		if(!empty($tmp)){
			combine_sub_array($tmp);
			array_push($new_array, combine_sub_array($tmp));
		}
	}
}

if(!function_exists("combine_sub_array")){
	function combine_sub_array($array){
		$new_array = array();
		foreach ($array as $key1 => $value1) {
			if(is_array($value1)){
				foreach ($value1 as $key2 => $value2) {
					$new_array[$key2] = add_html_shell($key2,$new_array[$key2],$value2);
				}
			}
		}
		return $new_array;
	}	
}

if(!function_exists("add_html_shell")){
	function add_html_shell($key,$old_content,$add_content){
		if($old_content!=''){
			$div = '<br />';
		}else{
			$div = '';
		}
		if($add_content!=''){
			switch ($key) {
				case 'checkbox': return $old_content.$div.$add_content;
					break;
				case 'catalog': return $old_content.$div.$add_content;
					break;
				case 'group_id': return $add_content;
					break;
				case 'old_catalog': return $old_content.$div.$add_content;
					break;
				case 'class': return $add_content;
					break;
				case 'order': return $add_content;
					break;
				case 'url': return $add_content;
					break;
				case 'desc': return $add_content;
					break;
				case 'desc2': return $add_content;
					break;
				case 'desc3': return $add_content;
					break;
				case 'classify': return $add_content;
					break;
				case 'price': return $old_content.$div.$add_content;
					break;
				case 'dis_price': return $old_content.$div.$add_content;
					break;
				case 'priority': return $add_content;
					break;
				default:
					break;
			}
		}else{
			return '';
		}
	}	
}

/*Some functions in Template*/
/*
column:当前列名称，可选值：catalog,desc,desc2,desc3
linkat:加入链接的列
content:被包含的内容
url:链接
*/
if(!function_exists('checkLink_plus')){
	function checkLink_plus($column,$linkat,$content,$url){
		if(preg_match("/\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.pdf/", $url)){
			$target = '_blank';
		}else{
			$target = '_self';
		}
		if($column==$linkat and $url!=''){
			return sprintf("<a href=\"%s\" target=\"%s\">%s</a>",$url,$target,$content);
		}else{
			return sprintf("%s",$content);
		}
	}
}
?>