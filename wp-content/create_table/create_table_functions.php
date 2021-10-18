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

function create_table_list($wpdb,$id='',$page=1,$datetable='_cs_misc_product',$search=''){
		$star=($page-1)*10;
		$cou=0;
		if($datetable=='_cs_misc_product'){
			$sort='catalog';
			$url='url';
		}else if($datetable=='vector'){
			$sort='vector_name';
			$url='vector_link';
		}
		if(!empty($search)){
			$num=$wpdb->get_var("SELECT count(*) FROM `{$datetable}` where {$sort} like '%{$search}%'");
		}else{
			$num=$wpdb->get_var("SELECT count(*) FROM `{$datetable}`");
		}
		$maxpage=ceil($num/10);
		if(!empty($id)){//提供id，列出全部产品，并选中相关项
			$products2=$wpdb->get_results(sprintf("SELECT * FROM `wp_create_table` where id='%s'",$id));
			if(!empty($products2)){
				$datetable=$products2[0]->datetable;
				if($datetable=='_cs_misc_product'){
					$sort='catalog';
					$url='url';
				}else if($datetable=='vector'){
					$sort='vector_name';
					$url='vector_link';
				}
				$arr2=explode(',', $products2[0]->content);
				$cou=count($arr2);
				foreach ($arr2 as $key => $value) {
					if($key==0){
						$str1=$sort."='".$value."'";
						$str2=$sort."<>'".$value."'";
					}else{
						$str1.="or ".$sort."='".$value."'";
						$str2.="and ".$sort."<>'".$value."'";
					}
				}
				if(!empty($search)){
					$str1="(".$str1.") and ".$sort." like '%".$search."%'";
					$str2="(".$str2.") and ".$sort." like '%".$search."%'";
					$num=$wpdb->get_var("SELECT count(*) FROM `{$datetable}` where {$sort} like '%{$search}%'");
				}else{
					$num=$wpdb->get_var("SELECT count(*) FROM `{$datetable}`");
				}
				$maxpage=ceil($num/10);
			}
			if($cou>=($page*10)){
				//var_dump($arr2);
				$products2 = $wpdb->get_results(sprintf("SELECT * FROM `%s` where %s ORDER BY %s ASC limit %s,10",$datetable,$str1,$sort,$star));
			}
			else if($cou<($page*10)&&$cou>(($page-1)*10)){
				$products2=$wpdb->get_results(sprintf("SELECT * FROM `%s` where %s ORDER BY %s ASC limit %s,10",$datetable,$str1,$sort,$star));
				$end=$page*10-$cou;
				$products = $wpdb->get_results(sprintf("SELECT * FROM `%s` where %s ORDER BY %s ASC limit 0,%s",$datetable,$str2,$sort,$end));
				//var_dump(sprintf("SELECT * FROM `%s` %s ORDER BY %s ASC limit %s,10",$datetable,$str1,$sort,$star));
			}else{
				//var_dump($str2);
				$star=$star-$cou;
				$products2='';
				$products = $wpdb->get_results(sprintf("SELECT * FROM `%s` where %s ORDER BY %s ASC limit %s,10",$datetable,$str2,$sort,$star));
			}
		}else{
			if(!empty($search)){
				//echo 1;
			$str="where ".$sort." like '%".$search."%'";
			$products = $wpdb->get_results(sprintf("SELECT * FROM `%s` %s ORDER BY %s ASC limit %s,10",$datetable,$str,$sort,$star));
			}else{
				$products = $wpdb->get_results(sprintf("SELECT * FROM `%s` ORDER BY %s ASC limit %s,10",$datetable,$sort,$star));
			}
		}

		$return_string = '';
		$return_string .= sprintf('<table class="c_table">');
		$return_string .= sprintf("
			<thead>
				<tr>
					<th class=\"tal fr\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal se\">".$sort."</th>
					<th class=\"tal th\">URL</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class=\"tal fr\"><input name=\"select_all\" type=\"checkbox\" class=\"select_all\" /></th>
					<th class=\"tal se\">".$sort."</th>
					<th class=\"tal th\">URL</th>
				</tr>
			</tfoot>
		");
		if(!empty($products2)){
		foreach ($products2 as $product) {
			$return_string .= sprintf("
			<tr>
				<td class=\"tal fr\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" checked=\"checked\"/></td>
				<td class=\"tal se\"><label for=\"%s\">%s</label></td>
				<td class=\"tal th\"><div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" title=\"Double Click To Use This Value\" /></div></td>
			</tr>
			",$product->{$sort},$product->{$sort},$product->{$sort},$product->{$sort},$product->{$sort},$product->{$url});
		}
		}
		if(!empty($products)){
			foreach ($products as $product) {
			$return_string .= sprintf("
			<tr>
				<td class=\"tal fr\"><input type=\"checkbox\" name=\"cat_nos[]\" value=\"%s\" id=\"%s\" /></td>
				<td class=\"tal se\"><label for=\"%s\">%s</label></td>
				<td class=\"tal th\"><div class=\"fl link\"><span class=\"link_icon\"></span><input type=\"text\" class=\"ln\" name=\"link[%s]\" placeholder=\"%s\" title=\"Double Click To Use This Value\" /></div></td>
			</tr>
			",$product->{$sort},$product->{$sort},$product->{$sort},$product->{$sort},$product->{$sort},$product->{$url});

		}
		}
		$return_string .= '</table><div class="page"><input type="button" class="jump current" value="1"/>';
		if($maxpage>6){
		if($page<=4){
			$str2="<input type='button' class='jump ' value='2'/><input type='button' class='jump' value='3'/><input type='button' class='jump' value='4'/><input type='button' class='jump' value='5'/><span class='ellipsis'>…</span>";
		}else if($page<=$maxpage-4){
			$str2="<span class='ellipsis'>…</span><input type='button' class='jump' value='".($page-1)."'/><input type='button' class='jump' value='".$page."'/><input type='button' class='jump' value='".($page+1)."'/><span class='ellipsis'>…</span>";
		}else{
			$str2="<span class='ellipsis'>…</span><input type='button' class='jump' value='".($maxpage-4)."'/><input type='button' class='jump' value='".($maxpage-3)."'/><input type='button' class='jump' value='".($maxpage-2)."'/><input type='button' class='jump' value='".($maxpage-1)."'/>";
		}
		$return_string .= $str2."<input type='button' class='jump'value='".$maxpage."'/></div>";
		}else{
			for($i=2;$i<=$maxpage;$i++){
				$return_string.="<input type='button' class='jump' value='".$i."'/>";
			}
			$return_string.="</div>";
		}
		return $return_string;
}

function get_all_table($wpdb){
	$all_array = array();
	$products = $wpdb->get_results("SELECT `title` FROM `wp_create_table` ORDER BY create_date ASC limit 0,20");
	foreach ($products as $product) {
		$all_array[] = $product->title;
	}
	 return array_unique($all_array);
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