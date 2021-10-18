<?php
require_once(dirname(__FILE__)."/../../../../wp-blog-header.php");
header('HTTP/1.1 200 OK');
$create_table_service_url = plugins_url('',__FILE__).'/../create_table_admin_ajax.php';
echo <<<EOA
jQuery(document).ready(function(\$){
	\$.ajax({
		type: "GET",
		//dataType: "html",
		url: "{$create_table_service_url}",
		data: "action=init",
		beforeSend: function(){\$("#postexcerpt").after('<div id="create_table" class="postbox "><div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>List Products</span></h3><div class="inside">Loading...</div></div>');},
		success: function(msg){
			$("#create_table .inside").html('').append(msg);
		}
	});
});
EOA;
?>