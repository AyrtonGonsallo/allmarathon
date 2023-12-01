<?php
 include('../content/classes/newsgalerie.php');
 $newsgalerie=new newsgalerie();
if(isset($_POST['file'])){
    $file = '../images/news/' . $_POST['file'];
    $thumb='../images/news/thumb_' . $_POST['file'];
    if(file_exists($file)){
        // $chamion_id=preg_replace('/\D/', '', $_SERVER['HTTP_REFERER']);
        $newsgalerie->deleteGalerie($_POST['newsID'], $_POST['file']);
        unlink($file);
        unlink($thumb);
		
    }
    else{
    	print_r("makaynch");die;
    }
}
?>
