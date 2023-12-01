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
		header('Location: login.php');
                exit();
    }

if($_POST['remplacant']!="" && $_GET['remplacer']!=""){
    // on attribut tout les r�sultat du remplacer au remplacant
    require_once '../database/connexion.php';
     try {
             $req = $bdd->prepare("UPDATE evresultats SET ChampionID=:id_remplacant WHERE ChampionID=:id_remplacer");

             $req->bindValue('id_remplacant',$_POST['remplacant'], PDO::PARAM_INT);
             $req->bindValue('id_remplacer',$_GET['remplacer'], PDO::PARAM_INT);
             $result=$req->execute();
             
             $req2 = $bdd->prepare("DELETE FROM champions WHERE ID=:idchamp");

             $req2->bindValue('idchamp',$_GET['remplacer'], PDO::PARAM_INT);
             $result2=$req2->execute();
         }
         catch(Exception $e)
         {
            die('Erreur : ' . $e->getMessage());
        }

         try {
             $req3 = $bdd->prepare("SELECT * FROM champions WHERE ID=:id_champ");

             $req3->bindValue('id_champ',$_GET['remplacer']-1, PDO::PARAM_INT);
             $result3=$req3->execute();
              header("Location: championDetail.php?championID=".($_GET['remplacer']-1));
         }
         catch(Exception $e)
         {
            header("Location: champion.php");
        }


    // $query1 = sprintf("UPDATE evresultats SET ChampionID=%s WHERE ChampionID=%s"
    //     ,mysql_real_escape_string($_POST['remplacant'])
    //     ,mysql_real_escape_string($_GET['remplacer']));
    // if(mysql_query($query1)or die(mysql_error())){
    //     //on supprime le remplacer
    //     $query2 = sprintf("DELETE FROM champions WHERE ID=%s",mysql_real_escape_string($_GET['remplacer']));
    //     mysql_query($query2) or die(mysql_error());
    // }
    // $query    = sprintf('SELECT * FROM champions WHERE ID=%s',(mysql_real_escape_string($_GET['remplacer'])-1));
    // $result   = mysql_query($query);
    // if(mysql_num_rows($result)==1)
    //     header("Location: championDetail.php?championID=".($_GET['remplacer']-1));
    // else
    //     header("Location: champion.php");
}else{
    echo "erreur de variable";
}

?>
