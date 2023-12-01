<?php
 include('../classes/image.php');
 $image=new image();

 if(isset($_POST['file']) && isset($_POST['championID'])){
    $file = '../../images/galeries/26/' . $_POST['file'];
    if(file_exists($file)){
        $chamion_id=preg_replace('/\D/', '', $_SERVER['HTTP_REFERER']);
        $image->delete_image_admin($_POST['file'],$chamion_id);
        unlink($file);
		
    }
    header('Location: /champion-detail-admin-'.$_POST['championID'].'.html');
}
if(isset($_POST['file'])){
    $file = '../../images/galeries/26/' . $_POST['file'];
    if(file_exists($file)){
        $chamion_id=preg_replace('/\D/', '', $_SERVER['HTTP_REFERER']);
        $image->delete_image_admin($_POST['file'],$chamion_id);
        unlink($file);
		
    }
}

?>
