<?php

session_start();
header('Content-Type: text/html; charset=ISO-8859-1');
/* if(!isset($_SESSION['connect']) || !isset($_SESSION['id_connect']) || !isset($_SESSION['admin'])){
  header('Location: ../../index.php');
  } */

if (isset($_GET['folder'])) {

    $tab = explode(":", $_GET['folder']);

    $championID = (int) $tab[1];
    $user_id = (int) $tab[2];
    $destination_path = "../images/galeries/24/";
    foreach ($_FILES as $tagname => $fileinfo) {

        require_once '../database/connection.php';

        /*  cr�ation de l'mage au bon format */
        $fichierSource = $fileinfo['tmp_name'];
        $fichierName = $fileinfo['name'];

        if ($fileinfo['error']) {
            switch ($fileinfo['error']) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $result = "'Le fichier d&eacute;passe la limite autoris&eacute;e par le serveur (fichier php.ini) !'";
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $result = "'Le fichier d&eacute;passe la limite autoris&eacute;e dans le formulaire HTML !'";
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $result = "'L'envoi du fichier a &eacute;t&eacute; interrompu pendant le transfert !'";
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $result = "'Le fichier que vous avez envoy&eacute; a une taille nulle !'";
                    break;
            }
        } else {

            $file = $fichierSource;
            $y = 750;
            $size = getimagesize($file);
            $x = ($y * $size[0]) / $size[1];
            if ($size) {
                $img_big = imagecreatefromjpeg($file);
                $img_new = imagecreate($x, $y);
                $img_mini = imagecreatetruecolor($x, $y)
                        or $img_mini = imagecreate($x, $y);
                imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                $fichierNameRand = rand(100, 99999) . $championID . ".jpg";
            }


            //$fichierNameRand = rand(100, 99999) . $fichierName;
            if (imagejpeg($img_mini, $destination_path . $fichierNameRand)) { //move_uploaded_file($fichierSource, $destination_path . $fichierNameRand)
                $result = "2";
            } else {
                $result = "Erreur pendant le d&eacute;placement de '" . $fichierName . "'.";
            }

            if ($result == "2") {
                $sql2 = sprintf("INSERT INTO images (Nom,Galerie_id,champion_id) VALUES ('%s','24','%s')"
                                , mysql_real_escape_string($fichierNameRand)
                                , mysql_real_escape_string($championID));
                $tmp2 = mysql_query($sql2) or die(mysql_error());
                if ($tmp2) {
                    $result = "Fichier '" . $fichierName . "' corectement envoy&eacute; ! ";
                    $query3 = sprintf("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('photo', '%s', '%s')"
                                    , $user_id
                                    , $championID);
                    $result3 = mysql_query($query3) or die(mysql_error());
                }else
                    $result = "Erreur pendant l'insertion en base de donn�e.";
            }
        }
        echo $result;
    }
}else {
    echo 0;
}
?>

