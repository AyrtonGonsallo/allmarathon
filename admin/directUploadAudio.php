<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=ISO-8859-1');
/*if(!isset($_SESSION['connect']) || !isset($_SESSION['id_connect']) || !isset($_SESSION['admin'])){
    header('Location: ../../index.php');
}*/

if(isset($_GET['folder'])) {

    $tab       = explode(":", $_GET['folder']);
    $galID = $tab[1];

    $directID = (is_numeric($galID))?$galID:0;
    $destination_path = "../images/direct/".$directID;

    if(!is_dir($destination_path))
        mkdir($destination_path);

    $destination_path = "../images/direct/".$directID."/audio/";

    if(!is_dir($destination_path))
        mkdir($destination_path);


    foreach($_FILES as $tagname=>$fileinfo) {

        require_once '../database/connexion.php';

        /*  création de l'mage au bon format */
        $fichierSource = $fileinfo['tmp_name'];
        $fichierName   = $fileinfo['name'];

        if ( $fileinfo['error']) {
            switch ( $fileinfo['error']) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $result = "'Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !'";
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $result =  "'Le fichier dépasse la limite autorisée dans le formulaire HTML !'";
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $result = "'L'envoi du fichier a été interrompu pendant le transfert !'";
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $result = "'Le fichier que vous avez envoyé a une taille nulle !'";
                    break;
            }
        }else {

            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                $result = $fichierName.' : ok';
            }else {
                $result = "Erreur pendant le déplacement de '".$fichierName."'.";
            }

        }
        echo $result;
    }

}else {
    echo 0;
}
?>

