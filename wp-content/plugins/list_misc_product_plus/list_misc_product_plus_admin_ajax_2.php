<?php
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/list_misc_product_plus_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;

if($_REQUEST['action']=='add'){
$postid = $_REQUEST['postid'];
$classify = get_list_misc_product_max_classify($wpdb,$postid);
ob_start();
?>
<input type="submit" value="Save" class="save" />
<input type="button" value="Close" class="close" />
<input type="button" value="Line merge" class="merge" />
<input type="hidden" name="classify" value="<?php echo $classify; ?>" />
<input type="hidden" name="action" value="insert" />
<table class="column_message">
	<tr>
		<td>Column 1: <br /><input type="text" placeholder="catalog" name="cn1" value="Catalog" title="Leave blank to hide"  /></td>
		<td>Column 2:<br /><input type="text" placeholder="desc" name="cn2" value="Product Name" title="Leave blank to hide"  /></td>
		<td>Column 3:<br /><input type="text" placeholder="desc2" name="cn3" value="Description" title="Leave blank to hide"  /></td>
		<td>Column 4:<br /><input type="text" placeholder="desc3" name="cn4" value="Description2" title="Leave blank to hide"  /></td>
		<td>Column 5:<br /><input type="text" placeholder="price" name="cn5" value="Price" title="Leave blank to hide"  /></td>
		<td>Column 6:<br /><input type="text" placeholder="dis_price" name="cn6" value="" title="Leave blank to hide"  /></td>
		<td>URL link at:<br />
			<select name="linkat" id="linkat">
				<option value=""></option>
				<option value="catalog">Column 1</option>
				<option value="desc">Column 2</option>
				<option value="desc2">Column 3</option>
				<option value="desc3">Column 4</option>
			</select>
		</td>
	</tr>
</table>
<div class="list_catalog_box">
	<?php //echo list_misc_product_list($wpdb,1);?>
	<?php echo list_misc_product_list_v2($wpdb);?>
</div>

<div class="user_manual">
	User Manual Under Table:
	<input type="text" placeholder="User Manual 1 URL" name="manual1" value="" title="Leave blank to hide" class="right input-text" />
	<input type="text" placeholder="User Manual 2 URL" name="manual2" value="" title="Leave blank to hide" class="right input-text" />
</div>
<input type="submit" value="Save" class="save" />
<input type="button" value="Close" class="close" />
<input type="button" value="Line merge" class="merge" />
<?php
$output = ob_get_clean();
echo json_encode(array('notice' => "<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus class='".$classify."']\" />",	'msg'=>$output));
}elseif($_REQUEST['action']=='update' AND $_REQUEST['classify']!=''){
	$classify  =  $_REQUEST['classify'];
	$options = get_option($classify);

ob_start();
?>
<input type="submit" value="Save" class="save" />
<input type="button" value="Close" class="close" />
<input type="button" value="Delete" data-classify="<?php echo $classify;?>" class="delete" />
<input type="button" value="Line merge" class="merge" />
<input type="hidden" name="classify" value="<?php echo $classify; ?>" />
<input type="hidden" name="action" value="update" />
<table class="column_message">
	<tr>
		<td>Column 1: <br /><input type="text" placeholder="catalog" name="cn1" value="<?php echo $options['column1'];?>" title="Leave blank to hide" /></td>
		<td>Column 2:<br /><input type="text" placeholder="desc" name="cn2" value="<?php echo $options['column2'];?>" title="Leave blank to hide"  /></td>
		<td>Column 3:<br /><input type="text" placeholder="desc2" name="cn3" value="<?php echo $options['column3'];?>" title="Leave blank to hide"  /></td>
		<td>Column 4:<br /><input type="text" placeholder="desc3" name="cn4" value="<?php echo $options['column4'];?>" title="Leave blank to hide"  /></td>
		<td>Column 5:<br /><input type="text" placeholder="price" name="cn5" value="<?php echo $options['column5'];?>" title="Leave blank to hide"  /></td>
		<td>Column 6:<br /><input type="text" placeholder="dis_price" name="cn6" value="<?php echo $options['column6'];?>" title="Leave blank to hide"  /></td>
		<td>URL link at:<br />
			<select name="linkat" id="linkat">
				<option value=""></option>
				<option value="catalog" <?php echo $options['linkat']=='catalog'?' selected="selected"':'';?>>Column 1</option>
				<option value="desc" <?php echo $options['linkat']=='desc'?' selected="selected"':'';?>>Column 2</option>
				<option value="desc2" <?php echo $options['linkat']=='desc2'?' selected="selected"':'';?>>Column 3</option>
				<option value="desc3" <?php echo $options['linkat']=='desc3'?' selected="selected"':'';?>>Column 4</option>
			</select>
		</td>
	</tr>
</table>
<div class="list_catalog_box">
	<?php
	$products_checkin = get_list_misc_product_class_products($wpdb,$classify);
	echo list_misc_product_list_v2($wpdb,$classify);
	?>
</div>
<div class="user_manual">
	User Manual Under Table:
	<input type="text" placeholder="User Manual 1 URL" name="manual1" value="<?php echo $options['manual1'];?>" title="Leave blank to hide" class="right input-text" />
	<input type="text" placeholder="User Manual 2 URL" name="manual2" value="<?php echo $options['manual2'];?>" title="Leave blank to hide" class="right input-text" />
</div>
<input type="submit" value="Save" class="save" />
<input type="button" value="Close" class="close" />
<input type="button" value="Delete" data-classify="<?php echo $classify;?>" class="delete" />
<input type="button" value="Line merge" class="merge" />
<?php
$output = ob_get_clean();
echo json_encode(array('notice' => "<strong>Use this short tag in the post</strong>: <input type='text' class='shortcode' value=\"[list_misc_product_plus class='".$classify."']\" />", 'msg'=>$output));
}
?>