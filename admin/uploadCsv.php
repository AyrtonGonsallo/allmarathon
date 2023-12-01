<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: text/html; charset=utf-8');
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
        header('Location: login.php');
                exit();
    }

function convert( $str ) {
    return iconv( "Windows-1252", "UTF-8", $str );
}
if(isset($_GET['evenementID'])){
    $eventID=$_GET['evenementID'];
    $table="evenements";
}
if (isset($_FILES['file']) && isset($eventID)) {
    $file = $_FILES['file']['tmp_name'];
    require_once '../database/connexion.php';
    ini_set('auto_detect_line_endings',1);

    $handle = fopen($file, 'r');

    fgetcsv($handle, 1000);

    $insertChampion = 0;
    $insertResultat = 0;
    $i = 0;
    while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {

      $data = array_map( "convert", $data );

        $evenementID = $data[0];               
        $Nom = $data[1];
        $Sexe = $data[2];
        $Pays = $data[3];
        $Rang = $data[4];
        $Temps = $data[5];
        
       /* if(!is_numeric($evenementID) && $evenementID!="" || is_numeric($Pays) || !is_numeric($Rang) || $Sexe!='M' && $Sexe!='F')
            $erreur[$Nom] = "";
        if(!is_numeric($evenementID) && $evenementID!="")
            $erreur[$Nom] .= 'Evenement Id doit être composer de chiffre. ';
        if(is_numeric($Pays))
            $erreur[$Nom] .= 'Pays doit être composer de lettre. ';
        if(!is_numeric($Rang))
            $erreur[$Nom] .= 'le Rang doit être composer de chiffre. ';
        if( $Sexe!='M' && $Sexe!='F')
            $erreur[$Nom] .= 'le sexe doit etre M ou F. ';
        if(isset($erreur[$Nom]))
            continue;
            */
        //recherche de la présence du champion dans la table champions
        try{
            
            $req1 = $bdd->prepare("SELECT ID FROM champions WHERE Nom LIKE :Nom");
            $req1->bindValue('Nom',$Nom, PDO::PARAM_STR);
            $req1->execute();
            }
            catch(Exception $e)
        {
            $erreur[$Nom] = 'erreur SQL recherche champions : '.$e->getMessage();
        }
        $numberChampions = $req1->rowCount();
         
        //recherche de la présence de l'événement

        if($evenementID != ""){

            try{

                $req4 = $bdd->prepare("SELECT ID FROM ".$table." WHERE ID= :id");
                $req4->bindValue('id',$evenementID, PDO::PARAM_STR);
                $req4->execute();
            }
            catch(Exception $e)
            {
                // die('Erreur : ' . $e->getMessage());
                $erreur[$Nom] = 'erreur pas d\'evenement corespondant a l\'ID '.$evenementID;
                continue;
            }

        }else{
           $evenementID = $eventID;
        }


        if(isset($erreur[$Nom]))
            continue;
            
        if($numberChampions==0){
            //insertion du nouveau champion

            try {
                 $req2 = $bdd->prepare("INSERT INTO champions (Nom,Sexe,PaysID) VALUES (:Nom,:Sexe,:Pays)");

                 $req2->bindValue('Nom',$Nom, PDO::PARAM_STR);
                 $req2->bindValue('Sexe',$Sexe, PDO::PARAM_STR);
                 $req2->bindValue('Pays',$Pays, PDO::PARAM_STR);
                 $req2->execute();
            }
            catch(Exception $e)
            {
                $erreur[$Nom] = 'erreur SQL insertion champions : '. $e->getMessage();
                continue;
            }
            $idChampion=$bdd->lastInsertId();
            $insertChampion++;
        }else{
            $champion=$req1->fetch(PDO::FETCH_ASSOC);
            $idChampion = $champion['ID'];
        }

        //insertion du résultat

        try {
                 $req3 = $bdd->prepare("INSERT INTO evresultats (Rang,EvenementId,ChampionID,Temps) VALUES (:Rang,:evenementID,:idChampion,:Temps)");

                 $req3->bindValue('Rang',$Rang, PDO::PARAM_INT);
                 $req3->bindValue('evenementID',$evenementID, PDO::PARAM_INT);
                 $req3->bindValue('idChampion',$idChampion, PDO::PARAM_INT);
                 $req3->bindValue('Temps',$Temps, PDO::PARAM_STR);
                 $req3->execute();
            }
            catch(Exception $e)
            {
                $erreur[$Nom] = 'erreur SQL insertion résultat : '. $e->getMessage();
                continue;
            }

            try{
              $req5 = $bdd->prepare("SELECT u.email,c.Nom FROM champions c, abonnement a,users u WHERE a.champion = c.ID and u.id=a.user and  a.champion=:idChampion");
              $req5->bindValue('idChampion',$idChampion, PDO::PARAM_INT);

              $req5->execute();
              $result5= array();
              while ( $row  = $req5->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result5, $row);
              }
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }

            foreach ($result5 as $r) {
                  
                  $email = 'contact@alljudo.net';
                  $subject = "Il y a du nouveau sur la fiche de ".$r['Nom'];
                  $headers = "From: ".$email."\r\n";
                  $headers .= "Reply-To: ". $r['email'] . "\r\n";
                  $headers .= "MIME-Version: 1.0\r\n";
                  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                  $message = '
<html><body>Bonjour,
Vous recevez ce message pour vous pr&eacute;venir qu\'un nouveau r&eacute;sultat, une nouvelle vid&eacute;o ou une nouvelle photo a &eacute;t&eacute; ajout&eacute;e sur la fiche de '.$r['Nom'].'
Cordialement
L\'&eacute;quipe de Allmarathon</body></html>';
                  // mail('sabilmariam91@gmail.com', $subject,$message,$headers);
                   mail($r['email'], $subject,$message,$headers);
           }

        $insertResultat++;
        
        $i++;
    }
    echo '<head><link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico"></head><body>';
    require_once "menuAdmin.php";
    echo " nbr nouveau champions : ".$insertChampion." nbr resultat inserés : ".$insertResultat;
    echo "<br><pre>";
    if(isset($erreur))
        print_r($erreur);
    echo "</pre></body>";
}
?>
