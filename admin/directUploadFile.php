<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
/*if(!isset($_SESSION['connect']) || !isset($_SESSION['id_connect']) || !isset($_SESSION['admin'])){
    header('Location: ../../index.php');
}*/

if(isset($_GET['folder'])){

    $tab       = explode(":", $_GET['folder']);
    $galID = $tab[1];

    $directID = (is_numeric($galID))?$galID:0;
    $destination_path = "../images/direct/".$directID."/";

    if(!is_dir($destination_path))
        mkdir($destination_path);


    foreach($_FILES as $tagname=>$fileinfo) {

        require_once '../database/connexion.php';

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

            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "fichier envoy� ";
                            }else{
                                $result = "Erreur pendant le d�placement de '".$fichierName."'.";
                            }

//            if( $result=="2" ){
//                $sql2   = sprintf("INSERT INTO images (Nom,Galerie_id) VALUES ('%s','%s')"
//                    ,mysql_real_escape_string($fichierName)
//                    ,mysql_real_escape_string($galerieID));
//                $tmp2   = mysql_query($sql2) or die(mysql_error());
//                if($tmp2)
//                    $result = "Fichier '".$fichierName."' corectement envoy� ! ";
//                else
//                    $result = "Erreur pendant l'insertion en base de donn�e.";
//            }
        }
        echo $result;
    }

}else{
    echo 0;
}
?>

