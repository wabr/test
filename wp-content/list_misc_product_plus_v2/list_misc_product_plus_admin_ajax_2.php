<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;

if($_REQUEST['action']=='add'){
$title = date("ymd.His");
ob_start();
?>
<input type="submit" value="Save" class="save" />
select datetable:
<select class="datetable" name="datetable" id="datetable">
	<option  value="_cs_misc_product">mis</option>
	<option value="vector">vector</option>
</select>
title:
<input type="hidden" name="title2" value="<?php echo $title; ?>" />
<input type="hidden" name="action" value="insert" />
<input type="text" name="title1">
<div class="list_catalog_box">
	<?php //echo list_misc_product_list($wpdb,1);?>
	<?php echo list_misc_product_list_v2($wpdb);?>
</div>
<input type="submit" value="Save" class="save" />
<?php
$output = ob_get_clean();
echo json_encode(array('notice' => "<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus title='".$title."']\" />",'msg'=>$output));
}elseif($_REQUEST['action']=='update' AND $_REQUEST['title']!=''){
	$title  =  $_REQUEST['title'];
ob_start();
?>
<input type="submit" value="Save" class="save" />
<input type="button" value="Delete" data-title="<?php echo $title;?>" class="delete" />
<input type="hidden" name="title2" value="<?php echo $title; ?>" />
<input type="hidden" name="action" value="update" />
title:
<input type="text" name="title1">
<div class="list_catalog_box">
	<?php
	echo list_misc_product_list_v2($wpdb,$title);
	?>
</div>
<input type="submit" value="Save" class="save" />
<input type="button" value="Delete" data-title="<?php echo $title;?>" class="delete" />
<?php
$output = ob_get_clean();
$product=$wpdb->get_results(sprintf("SELECT * FROM `wp_create_table` where title='%s'",$title));
echo json_encode(array('notice' => "<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus title='".$title."']\" />",'cat_nos'=>$product[0]->catalog,'msg'=>$output));
}
?>