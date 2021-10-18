<?php
require_once(dirname(__FILE__).'../../../../wp-config.php'); 
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
require_once(dirname(__FILE__)."/create_table_functions.php");
header('HTTP/1.1 200 OK');
global $wpdb;
if(!empty($_POST['data'])&&!empty($_POST['datetable'])){
	update_option($_POST['datetable'],$_POST['data']);
	if(!empty($_POST['data'])&&!empty($_POST['datetable'])){
		update_option($_POST['dt2'],$_POST['style']);
	}
	exit;
}
$options1 = get_option('misc_c');
$options2 = get_option('vector_c');
$td1='';
$td2='';
if(empty($options1)){
	$options1['catalog'][0]='Catalog';
	$options1['catalog'][1]=1;
	$options1['desc'][0]='Product Name';
	$options1['desc'][1]=2;
	$options1['desc2'][0]='Description2';
	$options1['desc2'][1]=2;
	$options1['desc3'][0]='Description3';
	$options1['desc3'][1]=2;
	$options1['price'][0]='Price';
	$options1['price'][1]=1;
	$options1['dis_price'][0]='';
	$options1['dis_price'][1]=2;
	add_option("misc_c",$options1);
	add_option("misc_style",'');
}
if(empty($options2)){
	$options2['vector_name'][0]='Vector';
	$options2['vector_name'][1]=1;
	$options2['promoter'][0]='Promoter';
	$options2['promoter'][1]=1;
	$options2['host_cell'][0]='Host Cell';
	$options2['host_cell'][1]=1;
	$options2['transfection'][0]='Selection Marker';
	$options2['transfection'][1]=1;
	$options2['tag'][0]='Tag';
	$options2['tag'][1]=1;
	$options2['protease_site'][0]='Protease Site';
	$options2['protease_site'][1]=1;
	add_option("vector_c",$options2);
	add_option("vector_style",'');
}
$i=0;
foreach($options1 as $key=>$value){
	$i++;
	$sel[$key]='<select class="se" name="cp'.$i.'" id="cp'.$i.'">';
	if($value[1]==1){
		$sel[$key].= '<option  value="1" selected>1</option><option  value="2">2</option></select>';
	}else{
		$sel[$key].= '<option  value="1" >1</option><option  value="2" selected>2</option></select>';
	}
	$td1.='<td>'.$key.': <br /><input type="text" placeholder="'.$key.'" name="cn'.$i.'" value="'.$value[0].'" title="Leave blank to hide" class="ti" /><br />'.$sel[$key].'</td>';
}
$i=0;
foreach($options2 as $key=>$value){
	$i++;
	$sel2[$key]='<select class="se" name="cp'.$i.'" id="cp'.$i.'">';
	if($value[1]==1){
		$sel2[$key].= '<option  value="1" selected>1</option><option  value="2">2</option></select>';
	}else{
		$sel2[$key].= '<option  value="1" >1</option><option  value="2" selected>2</option></select>';
	}
	$td2.='<td>'.$key.': <br /><input type="text" placeholder="'.$key.'" name="cn'.$i.'" value="'.$value[0].'" title="Leave blank to hide" class="ti" /><br />'.$sel2[$key].'</td>';
}
?>
<h1>Other Functions</h1>
<div class='set'>
	<nav><a>Set table columns</a></nav>
	<div class='ll'>
	select datetable:
<select class="dt1" name="dt1" id="dt1">
	<option  value="_cs_misc_product" >misc</option>
	<option value="vector">vector</option>
</select>
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<a href="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/manual_of_plugin[create_table]2.docx'?>" class="help" target="_blank" title="Down manual here">?</a>
<input type="submit" value="Save" class="save" />
<div class='table'>
<table class="filter_table" >
	<tr>
		<td>date column:<br /><br />table column:<br /><br />column priority:</td>
		<?php echo $td1;?>
	</tr>
	<tr>
	<td>table style:</td><td>
	<select class="table_style" name="table_style" id="table_style">
	<option  value="">no style</option>
	<option value="1">style1</option>
	</select>
	</td>
	</tr>
</table>
</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	$("#dt1").live('change',function(){
		dt1=$("#dt1").val();
		var td= '<tr><td>date column:<br /><br />table column:<br /><br />column priority:</td>';
		if(dt1=='_cs_misc_product'){
			td+=<?php echo "'".$td1."'";?>+'</tr>';
		}else if(dt1=='vector'){
			td+=<?php echo "'".$td2."'";?>+'</tr>';
		}else{
			td='';
		}
		console.log(td);
		$(".filter_table").html("").append(td).show();
	});

	$(".se").live('change',function(){
		dt1=$("#dt1").val();
		least=2;
		if(dt1=='_cs_misc_product'){
			least=2;
		}else if(dt1=='vector'){
			least=3;
		}
		j=0;
		sum=0;
		for(i=1;i<7;i++){
			if($("input[name=cn"+i+"]").val()!=''){
				sum+=parseInt($("#cp"+i).val());
				console.log($("#cp"+i).val());
				j++;
			}
		}
		if(j>3){
			max=(j-least)*2+least;
			min=(j-least-1)*2+least+1;
		}else{
			max=3;
			min=3;
		}
		console.log(sum+","+max+","+min+","+j);
		if(sum>max){
			alert('Display at least '+least+' columns');
		}else if(sum<min){
			alert('Display up to '+(least+1)+' columns');
		}
	});

	$(document).on("click",".save",function(){
		dt1=$("#dt1").val();
		style=$("#table_style").val();
		if(dt1=='_cs_misc_product'){
			dt1='misc_c';
			dt2='misc_style';
			options=<?php echo json_encode($options1);?>;
		}else if(dt1=='vector'){
			dt1='vector_c';
			dt2='vector_style';
			options=<?php echo json_encode($options2);?>;
		}
		var i=0;
		
		if(options!=''){
		for(key in options){  
			i++;
   			options[key][0]=$("input[name=cn"+i+"]").val();
   			options[key][1]=$("#cp"+i+" option:selected").val();
		}
		console.log(options);
		$.ajax({
			url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/other_functions_ajax.php';?>",
			type:"POST",
			async:true,
			data:{data:options,datetable:dt1,dt2:dt2,style:style},
			success:function(msg){console.log(msg);}
		});
		}
	});
});
</script>
<style>
.ll{
	width:1130px;
	height:400px;
	background-color:#fff;
	margin:6px 0 0 0;
	padding: 10px 0 0 10px;
}
.set{
	margin:10px;
}
.ti{
	margin:10px 0 12px 0;
}
.save{
	background: #0a0; border-color: #0a0; float: right; -webkit-box-shadow: inset 0 1px 0 rgba(0,170,0,.6); box-shadow: inset 0 1px 0 rgba(0,170,0,.6); color: #fff; border-radius: 3px;margin: 0 20px 0 0;
}
.help{ font-weight: bold; color:#f00; font-size: 20px; text-decoration: none;}

select,input{border-radius: 3px;border-width: 1px;}
nav>a{
	color:black;
	margin:0 0 0 15px;
position: relative;padding:2px;
text-decoration: none;
display: inline-block;} 
             
nav>a::before{ 
                 content: '';
                 position: absolute;
                 top: 0;
                 left: 0;
                 right: 0;
                 bottom: 0;
                 background: #fff;
                 border: 1px solid #b4b9be;
				 border-bottom: none;
				 -webkit-transform:perspective(0.5em) scale(1.1,1.3) rotateX(5deg);
				 z-index: -1;     
             }
</style>