<?php error_reporting(E_ERROR | E_PARSE);
    include('class.uploader.php');
    include('../classes/image.php');
    include('../classes/champion_admin_externe_journal.php');

    $image=new image();
    $champion_admin_externe_journal=new champion_admin_externe_journal();
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
    (!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => array('jpg','JPG','jpeg', 'png'), //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../../images/galeries/26/', //Upload directory {String}
        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'replace' => false, //Replace the file if it already exists  {Boolean}
        'perms' => null, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));
    if($data['isComplete']){
        $files = $data['data'];
        $champion_id=preg_replace('/\D/', '', $_SERVER['HTTP_REFERER']);
        $image->add_image_admin($files['metas'][0]['name'],$champion_id);
        $champion_admin_externe_journal->update_fiche_athlète($champion_id,$user_id,'photo',0);
        echo json_encode($files['metas'][0]['name']);
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        echo json_encode($errors);
    }
    exit;
?>
