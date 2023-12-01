<?php

session_start();
header('Content-Type: text/html; charset=ISO-8859-1');
/*if(!isset($_SESSION['connect']) || !isset($_SESSION['id_connect']) || !isset($_SESSION['admin'])){
    header('Location: ../../index.php');
}*/

if(isset($_GET['folder'])){

    $tab    = explode(":", $_GET['folder']);
    $newsID = (int)$tab[1];

    $destination_path = "../images/news/";


    foreach($_FILES as $tagname=>$fileinfo) {

        require_once '../database/connection.php';

        /*  cr�ation de l'mage au bon format */
        $fichierSource = $fileinfo['tmp_name'];
        $fichierName   = $fileinfo['name'];

        if ( $fileinfo['error']) {
              switch ( $fileinfo['error']){
                       case 1: // UPLOAD_ERR_INI_SIZE
                        $result = "'Le fichier d�passe la limite autoris�e par le serveur (fichier php.ini) !'";
                       break;
                       case 2: // UPLOAD_ERR_FORM_SIZE
                        $result =  "'Le fichier d�passe la limite autoris�e dans le formulaire HTML !'";
                       break;
                       case 3: // UPLOAD_ERR_PARTIAL
                         $result = "'L'envoi du fichier a �t� interrompu pendant le transfert !'";
                       break;
                       case 4: // UPLOAD_ERR_NO_FILE
                        $result = "'Le fichier que vous avez envoy� a une taille nulle !'";
                       break;
              }
        }else{

                            $x = 130;
                            $size = getimagesize($fichierSource);
                            $y = ($x * $size[1]) / $size[0];
                            $img_new = imagecreatefromjpeg($fichierSource);

                            $img_mini = imagecreatetruecolor($x, $y);
                            imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                            imagejpeg($img_mini, $destination_path . "thumb_" . $fichierName);

            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "2";
                            }else{
                                $result = "Erreur pendant le d�placement de '".$fichierName."'.";
                            }

            if( $result=="2" ){
                $sql2   = sprintf("INSERT INTO news_galerie (path,news_id) VALUES ('%s','%s')"
                    ,mysql_real_escape_string($fichierName)
                    ,mysql_real_escape_string($newsID));
                $tmp2   = mysql_query($sql2) or die(mysql_error());
                if($tmp2)
                    $result = "Fichier '".$fichierName."' corectement envoy� ! ";
                else
                    $result = "Erreur pendant l'insertion en base de donn�e.";
            }
        }
        echo $result;
    }

}else{
    echo 0;
}
?>

