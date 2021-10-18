<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/create_table_functions.php");
foreach ($products as $key=>$product){
	if($table=='_cs_misc_product'){
		$products_arr[$key]['checkbox'] =sprintf("<input name=\"cat_nos[]\" type=\"checkbox\" value=\"%s\">",$product->$pro);
	}
	foreach ($arr3 as $k=>$value) {
		if($k=='catalog'||$k=='vector_name'){
			$products_arr[$key][$k]=checkLink_plus($k,$k,$product->$k,$product->url);
		}else if($k=='price'||$k=='dis_price'){
			$products_arr[$key][$k]='$'.$product->$k;
		}else{
			$products_arr[$key][$k]=$product->$k;
		}
	}
	
}
if(!empty($products_arr)){?>
<form action="/order/cart_add.php" method="get">
<table class="table_org">
	<tr>
		<?php
		if($table=='_cs_misc_product'){
			echo '<td nowrap>Buy</td>';
		}
		foreach($arr3 as $key=>$value){
			echo '<td nowrap id="'.$key.'">'.$value[0].'</td>';
		}
		?>
	</tr>
	<?php foreach($products_arr as $product) { 
		echo '<tr>';
		foreach($product as $key=>$value){
			echo '<td nowrap>'.$value.'</td>';
		}
		echo '</tr>';
	} ?>
</table>
<button type='submit'>Add to cart</button>
</form>
<?php } ?>
<style>
@media only screen and (max-width: 768px) {
	<?php
	foreach ($arr2 as $value) {
		$st=explode(',', $value);
		if($table=='_cs_misc_product'){
			$st[1]=$st[1]+1;
		}
		echo '#'.$st[0].', tr td:nth-child('.$st[1].')	{ display:none; visibility:hidden; }
		';
	}
	?>
}
</style>