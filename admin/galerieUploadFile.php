<?php

ini_set('display_errors', 0);

ini_set('display_startup_errors', 0);

error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {

    session_start();

}



header('Content-Type: text/html; charset=utf-8');

var_dump($_FILES);

if(isset($_POST['folder'])){



   $tab = explode(":", $_POST['folder']);

    $galID = $tab[1];



    $galerieID = (is_numeric($galID))?$galID:0;



    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
    $path = "../images/galeries/".$galerieID."/"; // upload directory

    if (!is_dir($path) && !mkdir($path)){
        die("Erreur: creation du repertoire $path");
      }
    $fileNames=array_filter($_FILES['image']['name']);

   

    if( !empty($fileNames))

    {

        $i=0;

        foreach($_FILES['image']['name'] as $key=>$val){

            // File upload path 

            $fileName = basename($_FILES['image']['name'][$key]); 

            $targetFilePath = $path . $fileName; 

            // Check whether file type is valid 

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if(in_array($fileType, $valid_extensions)){ 

                // Upload file to server 

                if(move_uploaded_file($_FILES["image"]["tmp_name"][$key], $targetFilePath)){ 

                    // Image db insert sql 

                    $insertValuesSQL= "('".$fileName."','".$galerieID."'),";

                    if(!empty($insertValuesSQL)){

                        $insertValuesSQL = trim($insertValuesSQL, ','); 

                        // Insert image file name into database 

                        require_once '../database/connexion.php';

                        $req4 = $bdd->prepare("INSERT INTO images (Nom,Galerie_id) VALUES $insertValuesSQL");

                        $req4->execute();

                        if($req4){

                            $i++;

                        }
                            
                    }

                    else{

                        echo 'Erreur : insertion du fichier dans la base de données'; 

                    }

                }

                else{

                    echo 'Erreur : transfert sur le serveur de '.$_FILES["image"]["tmp_name"][$key].' dans '.$targetFilePath; 

                }

            }

            else{ 

                echo 'Erreur: Mauvaise extension de fichier '.$_FILES['image']['name'][$key].'<br>';

            }

        }

        echo '<br>le nombre de fichier enregistré est : '.$i;
        header('Location: https://dev.allmarathon.fr/admin/galerieDetail.php?galerieID='.$galID);
        die();
    }

    else{

        echo 'Veulliez selectionner un fichier <br>';

    }

}

?>