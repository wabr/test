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
if(!empty($products_arr)){
	$num='';
	if($table=='vector'){
		$num=" (".count($arr)." vector)";
	}
	?>
<div class='table_menu'>
<?php 
$pattern = "/^[0-9-]*\-/"; 
$title=preg_split($pattern, $result[0]->title)[1];
if($result[0]->is_hide==1)
	echo "<a class='table_title'>".$title.$num."</a>";
else
	echo $title.$num; 
?>
<div class='table' <?php if($result[0]->is_hide==1) echo 'style="display: none;"';?>>
<form action="/order/cart_add.php" method="get">
<table class="table_org" id='<?php echo $table; ?>'>
	<tr class="frist">
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
<?php if($table=='_cs_misc_product'){ ?>
<button type='submit'>Add to cart</button>
<?php } ?>
</form>
</div>
</div>
<?php } ?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$(".table_title").unbind('click').click(function(){
		index=$(".table_title").index(this);
		if($(".table_title").eq(index).is('.active')){
			$(".table").eq(index).attr('style','display:none');
			$(".table_title").eq(index).removeClass('active');
		}else{
		$(".table").eq(index).attr('style','display:block');
		$(".table_title").eq(index).addClass('active');
		}
	});
});
</script>
<style>
	.frist{
		background-color: #555 !important;
		color:#fff;
	}
.table_title{
		line-height: 25px;
		font-size: 13px;
		color: #036AC8;
	}
	.table_title:hover{
        cursor: pointer;
    }
.table_title:after {
    content: "\002B";
    font-size: 18px;
    font-weight: bold;
    float: left;
    margin: 0 5px 0 3px;
}
.table_title.active{
    color: black;
}
.table_title.active:after {
    content: "\2212";
    color: black;
    float: left;
}
	
	<?php

	if($table=='_cs_misc_product'){
		$op='misc_style';
	}else if($table=='vector'){
		$op='vector_style';
	}
	if(get_option($op)==1){ ?>
	table tr:nth-child(odd) {
  background-color:#F5F5F5;
}
table tr:nth-child(even) {
  background-color:#fff;
}
<?php }?>
@media only screen and (max-width: 768px) {
	<?php
	foreach ($arr2 as $value) {
		$st=explode(',', $value);
		if($table=='_cs_misc_product'){
			$st[1]=$st[1]+1;
		}
		echo '#'.$table.' tr td:nth-child('.$st[1].')	{ display:none; visibility:hidden; }
		';
	}
	?>
}
</style>