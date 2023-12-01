<?php
    include('class.uploader.php');
    include('../content/classes/newsgalerie.php');

    $newsgalerie=new newsgalerie();
    // $champion_admin_externe_journal=new champion_admin_externe_journal();
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
    (!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
    $newsID=$_GET['newsID'];



    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../images/news/', //Upload directory {String}
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

        $x = 130;
        $destination_path="../images/news/";
        $filename=$_FILES['files']['name'][0];
        $fichierSource = $destination_path. $filename;
        $size = getimagesize($fichierSource);
        $y = ($x * $size[1]) / $size[0];
        $img_new = imagecreatefromjpeg($fichierSource);

        $img_mini = imagecreatetruecolor($x, $y);
        imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);


         imagejpeg($img_mini, $destination_path. "thumb_" . $filename);


         $uploader2 = new Uploader();
    $data2 = $uploader2->upload($img_mini, array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../images/news/', //Upload directory {String}
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

    if($data2['isComplete']){
        echo "thumb complete";die;
    }
        if($data2['hasErrors']){
        $errors = $data2['errors'];
        echo "thumb incomplete";
       

    }

        // $champion_id=preg_replace('/\D/', '', $_SERVER['HTTP_REFERER']);
         // $image->add_image_admin($files['metas'][0]['name'],$champion_id);
        $newsgalerie->addGalerie($newsID, $filename);
        echo "complete";
      
        // $champion_admin_externe_journal->update_fiche_athlète($champion_id,$user_id,'photo');
        
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        echo "incomplete";
        

    }
    exit;
?>
