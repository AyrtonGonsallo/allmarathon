<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

if (isset($_FILES['file']) && isset($_GET['pariID'])) {
    $file = $_FILES['file']['tmp_name'];
    $pariID = (isset($_GET['pariID']))?(int)$_GET['pariID']:exit('error');
    require_once '../database/connexion.php';
    ini_set('auto_detect_line_endings',1);

    $handle = fopen($file, 'r');

    fgetcsv($handle, 1000);

    $insertCategorie   = 0;
    $insertParticipant = 0;
    $i = 0;
    while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
        $i++;
        $Nom  = $data[0];
        $Sexe = $data[1];
        $Poid = $data[2];
        $Date = $data[3];
        if($Nom == "" || $Poid == "" || $Sexe!='M' && $Sexe!='F' )
            $erreur[$Nom] = "";
        if( $Nom == "")
            $erreur[$Nom] = 'Ligne sans Nom';
        if($Poid == "")
            $erreur[$Nom] .= 'le poid de la catégorie est obligatoire. ';
        if( $Sexe!='M' && $Sexe!='F')
            $erreur[$Nom] .= 'le sexe doit etre M ou F. ';
        if(isset($erreur[$Nom]))
            continue;

        //recherche de la présence du champion dans la table champions
        try{
            
            $req1 = $bdd->prepare("SELECT ID,Nom FROM champions WHERE UPPER(TRIM(Nom)) LIKE :Nom");
            $req1->bindValue('Nom',strtoupper(trim($Nom)), PDO::PARAM_STR);
            $req1->execute();
            $champion = $req1->fetch(PDO::FETCH_ASSOC);
            $newChampion = $champion['ID'].':'.$champion['Nom'].';';

            }
            catch(Exception $e)
        {
            $erreur[$Nom] = 'erreur SQL recherche champions : '.$e->getMessage();
        }

        $numberChampions = $req1->rowCount();

        if($numberChampions==0) {
            $erreur[$Nom] = 'Est absent de la base de données ( vérifier l\'orthographe )';
            continue;
        }

        //recherche de la présence de la categorie
        try{
            
            $req4 = $bdd->prepare("SELECT id FROM pari_composition WHERE sexe=:Sexe AND poid=:Poid AND pari_id=:pari_id");
            $req4->bindValue('pari_id',$pariID, PDO::PARAM_INT);
            $req4->bindValue('Sexe',$Sexe, PDO::PARAM_STR);
            $req4->bindValue('Poid',$Poid, PDO::PARAM_STR);
            $req4->execute();

             if(($req4->rowCount())==0) {
                $req2 = $bdd->prepare("INSERT INTO pari_composition (sexe,poid,date,pari_id) VALUES (:sexe,:poid,:date,:pari_id)");

                $req2->bindValue('sexe',$Sexe, PDO::PARAM_STR);
                $req2->bindValue('poid',$Poid, PDO::PARAM_STR);
                $req2->bindValue('date',$Date.':00', PDO::PARAM_STR);
                $req2->bindValue('pari_id',$pariID, PDO::PARAM_INT);
                $req2->execute();

                $compID = $bdd->lastInsertId();
                $insertCategorie++;
        }else {
            $comp=$req4->fetch(PDO::FETCH_ASSOC);
            $compID = $comp['id'];
        }

        }
        catch(Exception $e)
        {
            $erreur[$Nom] = 'erreur SQL recherche evenement : '.$e->getMessage();
        }

        if(isset($erreur[$Nom]))
            continue;

        //insertion du participant
        try {
        $req3 = $bdd->prepare("UPDATE pari_composition SET participant=CONCAT(participant,:newChampion) WHERE id=:id");

        $req3->bindValue('newChampion',$newChampion, PDO::PARAM_STR);
        $req3->bindValue('id',$compID, PDO::PARAM_INT);
        $req3->execute();

    }
    catch(Exception $e)
    {
        $erreur[$Nom] = 'erreur SQL insertion participant : '.$e->getMessage();
            continue;
    }
        $insertParticipant++;


    }
    echo '<head><link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
    require_once "menuAdmin.php";
    echo " nbr nouvelle catégorie : ".$insertCategorie." Nbr participant à insérer : ".$i." Nbr de participant insérer avec succès : ".$insertParticipant;
    echo "<br /><a href=\"pariCompo.php?pariID=$pariID\">retour page pari</a>";
    echo "<br><pre>";
    if(isset($erreur))
        print_r($erreur);
    echo "</pre></body>";
}

?>
