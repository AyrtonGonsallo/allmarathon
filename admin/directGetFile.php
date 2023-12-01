<?php

$id = (isset($_GET['direct_id']))?addslashes($_GET['direct_id']):false;

if(!$id)
exit('error');

$destination_path = "../images/direct/".$id."/";

$js  = 'var tinyMCEImageList = new Array(
	// Name, URL';

  $MyDirectory = opendir($destination_path) or die('Erreur');
	while($Entry = @readdir($MyDirectory)) {
		if(!is_dir($destination_path.$Entry) && $Entry != '.' && $Entry != '..') {
                        $js .= '
	["'.$Entry.'", "'.$destination_path.$Entry.'"],';
		}
	}
  closedir($MyDirectory);

$js = rtrim($js, ',').');';
echo $js;
?>