<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
		//header('Location: login.php');
        //        exit();
    }

    if($_SESSION['admin'] == false){
       // header('Location: login.php');
       // exit();
    }

    require_once '../database/connexion.php';

    if (isset($_POST["commentaire"])) {
    if ($_POST["com"] == "commentaire_article") {
        $id_user = $_POST["id_user"];
        $id_art = $_POST["id_art"];
        $commentaire = htmlentities($_POST["commentaire"]);
        $date = date('Y-m-d H:i:s');

        $addCom = $bdd->prepare("INSERT INTO article_com (date, commentaire, valide, art_id, user_id) 
        VALUES ('".$date."', '".$commentaire."','0','".$id_art."','".$id_user."');");
        $addCom->execute();

        header('Location: /produit-'.$id_art.'.html');
        exit();
    }
    }

    if (isset($_POST["data"])) {
    if ($_POST["data"] == "art_img") {
        $id = $_POST["id"];
        $getImg = $bdd->prepare(" SELECT image_pre from article where id = ".$id." LIMIT 1");
        $getImg->execute();
        $img = $getImg->fetch(PDO::FETCH_ASSOC);
        $return["json"] = json_encode($img);
        header('Content-Type: application/json');
        echo json_encode($img);
        //echo $param;
        die();
    }

    if ($_POST["data"] == "art_media") {
        $id = $_POST["id"];
        $getGalery = $bdd->prepare(" SELECT nom from article_img where id_art = ".$id);
        $getGalery->execute();
        $media["galery"] = array() ;
        $media["img"] = "";
        $getImg = $bdd->prepare(" SELECT image_pre from article where id = ".$id." LIMIT 1");
        $getImg->execute();
        $media["img"] = $getImg->fetch(PDO::FETCH_ASSOC)["image_pre"];

        
        while ($img = $getGalery->fetch(PDO::FETCH_ASSOC)) {
            array_push($media["galery"], $img["nom"]);
        }
        $return["json"] = json_encode($media);
        header('Content-Type: application/json');
        echo json_encode($media);
        //echo $param;
        die();
    }

    if ($_POST["data"] == "delete_galery") {
        $nom = $_POST["nom"];
        unlink("../images/articles/".$nom);
        $delGalery = $bdd->prepare("DELETE FROM article_img WHERE nom=:nom");
        $delGalery->bindValue('nom',$nom, PDO::PARAM_INT);
        $delGalery->execute();

        header('Content-Type: application/json');
        echo $nom;
        die();
    }   
    }

   // if ($_POST) {
        $destination_path = "../images/articles/";
        if ($_POST["submit"] == "add_article") {
            $nom = mysqli_real_escape_string($mysqli, $_POST["nom"]);
            $prix = $_POST["prix"];
            $old_prix = $_POST["old_prix"];
            $descr = mysqli_real_escape_string($mysqli, $_POST["descr"]);
            //$descr = $_POST["descr"];
            $code_paypal = $_POST["paypal"];
            $offre = $_POST["offre"];
            $marque = $_POST["marque"];
            $livraison = $_POST["livraison"];
            $transporteur = $_POST["transporteur"];
            $image_pre = $_FILES['fileToUpload']['name'];
            $video = $_POST["video"];
            $type = $_POST["type"];
            $fileGeneratedName = "";
                if ($_FILES['files']) {
                    $getNext = $bdd->prepare(" SELECT AUTO_INCREMENT as next FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'article'");
                    $getNext->execute();
                    $next = $getNext->fetch(PDO::FETCH_ASSOC);
                    for ($i=0; $i < count($_FILES['files']['name']) ; $i++) { 
                        $fichierSource = $_FILES['files']['tmp_name'][$i];
                        $fileExtension = explode('.', $_FILES['files']['name'][$i]);
                        $fileExtension = end($fileExtension);
                        $fileGeneratedName = generateRandomString().'.'.$fileExtension;
                        move_uploaded_file($fichierSource,$destination_path.$fileGeneratedName);
                        $add_article_img = $bdd->prepare("INSERT INTO article_img (id_art, nom) 
                        VALUES ('".$next['next']."', '".$fileGeneratedName."');");
                        if ($fileExtension && !empty($fileExtension)) {
                            $add_article_img->execute();
                        }
                        
                    }
                    //var_dump($_FILES['files']);
                }

                if(!empty($_FILES['fileToUpload']['name'])){
                    $fileinfo = $_FILES['fileToUpload'];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];
                    $fileExtension = explode('.', $fileinfo['name']);
                    $fileExtension = end($fileExtension);
                    if ( $fileinfo['error']) {
                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    echo "'Le fichier ".$fichierName." d�passe la limite autoris�e par le serveur (fichier php.ini) !'<br />";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    echo  "'Le fichier ".$fichierName." d�passe la limite autoris�e dans le formulaire HTML !'<br />";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     echo"'L'envoi du fichier ".$fichierName." a �t� interrompu pendant le transfert !'<br />";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    echo "'Le fichier ".$fichierName." que vous avez envoy� a une taille nulle !'<br />";
                                   break;
                          }
                    }else{
                        $fileGeneratedName = generateRandomString().'.'.$fileExtension;
                        if(move_uploaded_file($fichierSource,$destination_path.$fileGeneratedName)) {
                            $result = "Fichier ".$fichierName." corectement envoy� !";
                        }else{
                            echo "Erreur phase finale fichier ".$fichierName."<br />";
                        }
                        }
    
                    }
                
            $add_article = $bdd->prepare("INSERT INTO article (nom, prix, descr, image_pre, video, type, code_paypal, offre, marque, livraison, transporteur, old_prix) 
                        VALUES ('".$nom."', '".$prix."', '".$descr."', '".$fileGeneratedName."', '".$video."', '".$type."','".$code_paypal."', '".$offre."', '".$marque."', '".$livraison."', '".$transporteur."', '".$old_prix."');");
            $add_article->execute();

            header('Location: boutique.php');
            exit();
        } else {
            if ($_POST["submit"] == "edit_article") {
                $nom = mysqli_real_escape_string($mysqli, $_POST["nom"]);
                $id = $_POST["id"];
                $prix = $_POST["prix"];
                $old_prix = $_POST["old_prix"];
                $descr = mysqli_real_escape_string($mysqli, $_POST["descr"]);
                //$descr = $_POST["descr"];
                $video = $_POST["video"];
                $type = $_POST["type"];
                $paypal = $_POST["paypal"];
                $offre = $_POST["offre"];
                $marque = $_POST["marque"];
                $livraison = $_POST["livraison"];
                $transporteur = $_POST["transporteur"];
                $edit_art = $bdd->prepare("UPDATE article set 
                             nom = '".$nom."', prix = '".$prix."', descr = '".$descr."',video = '".$video."', 
                             type = '".$type."', code_paypal = '".$paypal."', old_prix = '".$old_prix."',
                             offre = '".$offre."',
                             marque = '".$marque."',
                             livraison = '".$livraison."',
                             transporteur = '".$transporteur."' where id = ".$id.";");
                $edit_art->execute();
                header('Location: boutique.php');
                exit();
            } else {
                if ($_POST["submit"] == "edit_article_img") {
                    $id = $_POST["id"];
                    if(!empty($_FILES['fileToUpload']['name'])){
                        $qry = $bdd->prepare("SELECT image_pre from article WHERE id=:id");
                        $qry->bindValue('id',$id, PDO::PARAM_INT);
                        $qry->execute();
                        $art  = $qry->fetch(PDO::FETCH_ASSOC);
                        unlink($destination_path.$art['image_pre']);

                        $fileinfo = $_FILES['fileToUpload'];
                        $fichierSource = $fileinfo['tmp_name'];
                        $fichierName   = $fileinfo['name'];
                        $fileExtension = explode('.', $fileinfo['name']);
                        $fileExtension = end($fileExtension);
                        if ( $fileinfo['error']) {
                              switch ( $fileinfo['error']){
                                       case 1: // UPLOAD_ERR_INI_SIZE
                                        echo "'Le fichier ".$fichierName." d�passe la limite autoris�e par le serveur (fichier php.ini) !'<br />";
                                       break;
                                       case 2: // UPLOAD_ERR_FORM_SIZE
                                        echo  "'Le fichier ".$fichierName." d�passe la limite autoris�e dans le formulaire HTML !'<br />";
                                       break;
                                       case 3: // UPLOAD_ERR_PARTIAL
                                         echo"'L'envoi du fichier ".$fichierName." a �t� interrompu pendant le transfert !'<br />";
                                       break;
                                       case 4: // UPLOAD_ERR_NO_FILE
                                        echo "'Le fichier ".$fichierName." que vous avez envoy� a une taille nulle !'<br />";
                                       break;
                              }
                        }else{
                            $fileGeneratedName = generateRandomString().'.'.$fileExtension;
                            if(move_uploaded_file($fichierSource,$destination_path.$fileGeneratedName)) {
                                $result = "Fichier ".$fichierName." corectement envoy� !";
                            }else{
                                echo "Erreur phase finale fichier ".$fichierName."<br />";
                            }
                            }
                            $qry = $bdd->prepare("UPDATE article SET image_pre = '".$fileGeneratedName."' WHERE article.id = :id;");
                            $qry->bindValue('id',$id, PDO::PARAM_INT);
                            $qry->execute();

                    }
                    //###################
                    if ($_FILES['files']) {
                        for ($i=0; $i < count($_FILES['files']['name']) ; $i++) { 
                            $fichierSource = $_FILES['files']['tmp_name'][$i];
                            $fileExtension = explode('.', $_FILES['files']['name'][$i]);
                            $fileExtension = end($fileExtension);
                            $fileGeneratedName = generateRandomString().'.'.$fileExtension;
                            move_uploaded_file($fichierSource,$destination_path.$fileGeneratedName);
                            $add_article_img = $bdd->prepare("INSERT INTO article_img (id_art, nom) 
                            VALUES ('".$id."', '".$fileGeneratedName."');");
                            if ($fileExtension) {
                                $add_article_img->execute();
                            }
                        }
                        //var_dump($_FILES['files']);
                    }
                    header('Location: boutique.php');
                    exit();
                } else {
                    try {
                        $id = $_POST["id"];
                        $qry = $bdd->prepare("select image_pre from article WHERE id=:id");
                        $qry->bindValue('id',$id, PDO::PARAM_INT);
                        $qry->execute();
                        $art  = $qry->fetch(PDO::FETCH_ASSOC);
                        unlink($destination_path.$art['image_pre']);
        
                        $qry = $bdd->prepare("select * from article_img WHERE id_art=:id");
                        $qry->bindValue('id',$id, PDO::PARAM_INT);
                        $qry->execute();
        
                        while ( $art_img  = $qry->fetch(PDO::FETCH_ASSOC)) {
                            unlink($destination_path.$art_img['nom']);
                        }
                        
                        $del_art = $bdd->prepare("DELETE FROM article WHERE id=:id");
                        $del_art->bindValue('id',$id, PDO::PARAM_INT);
                        $del_art->execute();
        
                        $del_img = $bdd->prepare("DELETE FROM article_img WHERE id_art=:id");
                        $del_img->bindValue('id',$id, PDO::PARAM_INT);
                        $del_img->execute();
        
                        header('Location: boutique.php');
                        exit();
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                }
                
            }
            
        }
        
   // }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }