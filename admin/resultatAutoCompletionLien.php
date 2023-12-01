<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

require_once '../database/connexion.php';
header('Content-Type: text/html; charset=utf-8');

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
        // $nomLien = trim(preg_replace('~[^\\pL\d]~', '-',strtr(strtolower($champion['Nom']), 'אבגדהועףפץצרטיךכחלםמןשתûüÿס', 'aaaaaaooooooeeeeciiiiuuuuyn')), '-');
        // $tab     = explode(' ',$champion['Nom']);
        // $nom     = ucfirst(strtolower(trim($tab[0])));
        // $prenom  = ucfirst(strtolower(trim($tab[1])));
        // echo '<a id="rep'.$i.'" onclick="addCompletion(\''.$champion['ID'].':'.$prenom.' '.$nom.':'.$nomLien.'\',\''.$_GET['id'].'\')">'.$prenom.' '.$nom.'</a>';
    foreach ($result1 as $champion) {
        $nomLien = strtolower($champion['Nom']);
        $tab     = explode(' ',$champion['Nom']);
        $nom     = ucfirst(strtolower(trim($tab[0])));
        $prenom  = ucfirst(strtolower(trim($tab[1])));
        echo '<a id="rep'.$i.'" onclick="addCompletion(\''.$champion['ID'].':'.$prenom.' '.$nom.':'.$nomLien.'\',\''.$_GET['id'].'\')">'.$prenom.' '.$nom.'</a>';
        $i++;

    }
    if(sizeof($result1)==0){
        echo '<a id="rep'.$i.'">aucun rיsultat</a>';
    }
}
?>

