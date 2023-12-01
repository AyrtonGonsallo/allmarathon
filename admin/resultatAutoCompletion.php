<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //verif de validiter session
    

require_once '../database/connexion.php';
header('Content-Type: text/html; charset=utf-8');
include("../content/classes/evCategorieEvenement.php");
setlocale(LC_TIME, "fr_FR","French");

$ev_cat_event=new evCategorieEvenement();
if(isset($_GET['str']) && isset($_GET['id']) && strlen($_GET['str'])>3){

    try{
            $req = $bdd->prepare("SELECT * FROM champions WHERE UPPER(Nom) LIKE :nom");
            $req->bindValue('nom',"%%".strtoupper($_GET['str'])."%", PDO::PARAM_STR);
            $req->execute();
            $result1= array();
            while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

    // $query1    = sprintf("SELECT * FROM champions WHERE UPPER(Nom) LIKE '%%%s%%'",mysql_real_escape_string(strtoupper($_GET['str'])));
    // $result1   = mysql_query($query1);
    $i = 1;
    // while($champion = mysql_fetch_array($result1)){
    foreach ($result1 as $champion) {
      echo '<a id="rep'.$i.'" onclick="addCompletion(\''.$champion['ID'].':'.$champion['Nom'].'\',\''.$_GET['id'].'\')">'.$champion['Nom'].'</a>';
        $i++;

    }
    if(sizeof($result1)==0){
        echo '<a id="rep'.$i.'" onclick="addCompletion(\'0:\',\''.$_GET['id'].'\')">aucun r&eacute;sultat</a>';
    }
}

if(isset($_GET['even_key']) && isset($_GET['id']) && strlen($_GET['even_key'])>3){

    try{

            $req = $bdd->prepare("SELECT * FROM evenements WHERE UPPER(Nom) LIKE :nom");
            $req->bindValue('nom',"%%".strtoupper($_GET['even_key'])."%", PDO::PARAM_STR);
            $req->execute();
            $result1= array();
            while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

    // $query1    = sprintf("SELECT * FROM champions WHERE UPPER(Nom) LIKE '%%%s%%'",mysql_real_escape_string(strtoupper($_GET['str'])));
    // $result1   = mysql_query($query1);
    $i = 1;
    // while($champion = mysql_fetch_array($result1)){
    foreach ($result1 as $champion) {            
        $cat_event=$ev_cat_event->getEventCatEventByID($champion['CategorieID'])['donnees']->getIntitule();
        $nom_res_lien=$champion['ID'].':'.$cat_event.' - '. $champion['Nom'].' - '.strftime("%A %d %B %Y",strtotime( $champion['DateDebut']));

      echo '<a id="rep'.$i.'" onclick="addCompletion(\''.$nom_res_lien.'\',\''.$_GET['id'].'\')">'.$nom_res_lien.'</a>';
        $i++;

    }
    if(sizeof($result1)==0){
        echo '<a id="rep'.$i.'" onclick="addCompletion(\'0:\',\''.$_GET['id'].'\')">aucun r&eacute;sultat</a>';
    }
}
?>

