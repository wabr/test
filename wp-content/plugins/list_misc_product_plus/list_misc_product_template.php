<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
$options = get_option($atts['class']);

foreach ($products as $product){
	$products_arr[] = array(
		'checkbox'=> sprintf("<input name=\"cat_nos[]\" type=\"checkbox\" value=\"%s\">",$product->c_catalog),
		'catalog'=>$product->c_catalog,
		'group_id'=>$product->group_id,
		'old_catalog'=>$product->old_catalog,
		'class'=>$product->class,
		'order'=>$product->order,
		'url'=>$product->c_url,
		'desc'=>$product->desc,
		'desc2'=>$product->desc2,
		'desc3'=>$product->desc3,
		'classify'=>$product->classify,
		'price'=>$product->price,
		'dis_price'=>$product->dis_price,
		'priority'=>$product->priority,
		'is_published'=>$product->is_published
	);
}

$new_array = array();
$products_arr_clone = $products_arr;
for ($i=0;$i<count($products_arr);$i++) {
	if($products_arr[$i]['group_id']!=''){
		pick_the_same_group($products_arr_clone,$new_array,$products_arr[$i]['group_id']);
	}else{
		array_push($new_array, $products_arr[$i]);
	}
}
$products_arr = $new_array;

$new_array = array();
$products_arr_clone = $products_arr;//先复制一份
for ($i=0;$i<count($products_arr);$i++) {
	if($products_arr[$i]['group_id']!=''){
		combine_the_same_group($products_arr_clone,$new_array,$products_arr[$i]['group_id']);
	}else{
		array_push($new_array, $products_arr[$i]);
	}
}
$products_arr_new = $new_array;

?>
<?php if(!empty($products_arr_new)){?>
<form action="/order/cart_add.php" method="get">
<table class="table_org">
	<tr>
		<?php echo $options['column1']==''?'':'<td>Buy</td>';?>
		<?php echo $options['column1']==''?'':'<td nowrap>'.$options['column1'].'</td>';?>
		<?php echo $options['column2']==''?'':'<td>'.$options['column2'].'</td>';?>
		<?php echo $options['column3']==''?'':'<td>'.$options['column3'].'</td>';?>
		<?php echo $options['column4']==''?'':'<td>'.$options['column4'].'</td>';?>
		<?php echo $options['column5']==''?'':'<td>'.$options['column5'].'</td>';?>
		<?php echo $options['column6']==''?'':'<td>'.$options['column6'].'</td>';?>
	</tr>
	<?php foreach ($products_arr_new as $product) { 
		if($product['catalog']!='') {?>
	<tr>
		<?php echo $options['column1']==''?'':'<td>'.($product['is_published']==0 ? '' : $product['checkbox']).'</td>';?>
		<?php echo $options['column1']==''?'':'<td nowrap>'.checkLink('catalog',$options['linkat'],$product['catalog'],$product['url']).'</td>';?>
		<?php echo $options['column2']==''?'':'<td>'.checkLink('desc',$options['linkat'],$product['desc'],$product['url']).'</td>'; ?>
		<?php echo $options['column3']==''?'':'<td>'.checkLink('desc2',$options['linkat'],$product['desc2'],$product['url']).'</td>'; ?>
		<?php echo $options['column4']==''?'':'<td>'.checkLink('desc3',$options['linkat'],$product['desc3'],$product['url']).'</td>'; ?>
		<?php echo $options['column5']==''?'':'<td class="text-right">'.login2show($product['is_published']==0 ? 'Coming Soon' : '$'.$product['price']).'</td>';?>
		<?php echo $options['column6']==''?'':'<td class="text-right">'.login2show($product['is_published']==0 ? 'Coming Soon' : '$'.$product['dis_price']).'</td>';?>
	</tr>
		<?php } 
	} ?>
</table>
<table border="0" width="100%" style="margin-top:15px;">
	<tbody>
		<tr>
			<td><input border="0" src="/images/add_t_s_c.png" type="image"> <input name="prt" type="hidden" value="1"></td>
			<td align="right" width="120"><?php if($options['manual2']!=''){printf('<a href="%s" target="_blank"><img src="/images/userManual.gif" /></a>',$options['manual2']);}?></td>
			<td align="right" width="120"><?php if($options['manual1']!=''){printf('<a href="%s" target="_blank"><img src="/images/userManual.gif" /></a>',$options['manual1']);}?></td>
		</tr>
	</tbody>
</table>
</form>
<?php } ?>