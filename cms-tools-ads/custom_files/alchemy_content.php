<?php
ini_set('max_execution_time',0);
function geturl($url, $method = 'get', $header = null, $postdata = null, $new = false, $timeout = 60) {
	$s = curl_init();

	curl_setopt($s, CURLOPT_URL, $url);

	if ($header) {
	    curl_setopt($s, CURLOPT_HTTPHEADER, $header);
	}

	curl_setopt($s, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($s, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($s, CURLOPT_MAXREDIRS, 5);
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);

	if (strtolower($method) == 'post') {
	    curl_setopt($s, CURLOPT_POST, true);
	    curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
	} else if (strtolower($method) == 'delete') {
	    curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
	    curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
	} else if (strtolower($method) == 'put') {
	    curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
	}

	curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

	$html = curl_exec($s);

	curl_close($s);
	return $html;
}
$alchemy_url = "http://54.169.116.235:4500/alchemy_web_service/type.php";
$type_data = json_decode(geturl($alchemy_url."?data_form=type"));

$content_data = "";
if(!empty($_REQUEST['content']) && !empty($_REQUEST['type']))
{
    if(isset($_REQUEST['content']) && trim($_REQUEST['type'])=='al_package')
    {
        $content_data = json_decode(geturl('http://54.169.116.235:4500/alchemy_web_service/package.php?name='.$_REQUEST['content']));
    }else{
 $content_data = json_decode(geturl($alchemy_url."?data_form=content&type=".$_REQUEST['type']."&content=".$_REQUEST['content']));
    }
}

?>
<!Doctype>
<html>
<head>
<title>Alchemy Contents list</title>
<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 100%;
    border-collapse: collapse;
}

#customers td, #customers th {
    font-size: 1em;
    border: 1px solid #ddd;
    padding: 3px 7px 2px 7px;
}

#customers th {
    font-size: 1.1em;
    text-align: left;
    padding-top: 5px;
    padding-bottom: 4px;
    background-color: #35aa47;
    color: #ffffff;
}

#customers tr.alt td {
    color: #000000;
    background-color: #f9f9f9;
}
#txt_alchemy_content
{
height:30px;
width:300px;	
	
}
#txt_alchemy_type
{
width:200px;	
height:30px;	
}
.green
{
padding: 5px;
color: #fff;
border:none;
background: #35aa47;	
height:30px;
}
</style>
<script>
function valid()
{
if(document.getElementById("txt_alchemy_content").value=="")
{
alert("Please enter content exact or partial title name!");	
document.getElementById("txt_alchemy_content").focus();
return false;
}	
if(document.getElementById("txt_alchemy_type").value=="")
{
alert("Please select content type!");	
document.getElementById("txt_alchemy_type").focus();
return false;
}	
document.getElementById("nodata").innerHTML='Loading...';
	
}
</script>
</head>
<body>
<div id="alchemy_content" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-body">
	    <div>
		<form method="POST" name="alchemy_content_form" action="">
		    <input type="text" id="txt_alchemy_content" name="content" value="<?php echo $_REQUEST['content'];?>" placeholder="Enter exact or partial content title"/>
		    <select id="txt_alchemy_type" name="type">
			<option value="">Select Content Type</option>
			<option <?php if($_REQUEST['type'] == 'al_package'){print 'SELECTED';}?> value="al_package">Package</option>
			<?php
			
			foreach($type_data as $key=>$val)
			{
				$selected="";
				if($_REQUEST['type'] == $key)
					$selected = "selected='selected'";
				echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
			}
			?>
		    </select>
		    <button type="submit" class="btn green" name="submit" onclick="return valid();">Search</button>
		</form>
	    </div>
		<?php if(trim($_REQUEST['type'])=='al_package' && trim($_REQUEST['content'])=='')
                    print '<p>Please enter keyword to search  package.</p>';
          ?>
	    <div id="alchemy_content_wrap">
		<?php
		if(!empty($content_data))
		{
			$html = '<table id="customers" class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Select</th><th class="hidden-phone">Type</th><th class="hidden-phone">Title</th><th class="hidden-phone">Preview</th><th class="hidden-phone">Verify Content</th></tr></thead><tbody id="data-table">';
			$selection_type='checkbox';
            if(isset($_REQUEST['content']) && trim($_REQUEST['type'])=='al_package')
			$selection_type='radio';
			foreach($content_data as $cd)
			{
				$html .= '<tr class="odd gradeX">';
				$html .= '<td><input type="'.$selection_type.'" id="'.$cd->content_id.'" name="content_id[]" class="content_ids" value="'.$cd->content_id.'"/></td>';
				$html .= '<td>'.$cd->content_type.'</td>';
				$html .= '<td>'.$cd->title.'</td>';
				$html .= '<td><img src="'.$cd->preview.'" alt="'.$cd->title.'"/></td>';
				$html .= '<td><a href="'.$cd->source.'" target="_parent">Download</a></td>';
				$html .= '</tr>';
			}
			$html .= '</tbody></table>';
			echo $html;
		}
		else{ print "<p style='color:red;text-align:center' id='nodata'>No data found.</p>";}
		?>
	    </div>		
	</div>
</div>
</body>
</html>
