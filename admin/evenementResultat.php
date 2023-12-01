<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

require_once '../database/connexion.php';

if(!(isset($_GET['evenementID'])or isset($_GET['evenement_filsID']))){
    header("Location: evenement.php");
}
if(isset($_GET['evenementID'])){
    $eventID=$_GET['evenementID'];
    $table="evenements";
}else if(isset($_GET['evenement_filsID'])){
    $eventID=$_GET['evenement_filsID'];
    $table="evenements_fils";
}


    try{
              $req = $bdd->prepare("SELECT * FROM ".$table." WHERE ID =:id");
              $req->bindValue('id',$eventID, PDO::PARAM_INT);
              $req->execute();
              $evenement=$req->fetch(PDO::FETCH_ASSOC) ;
             

            $req1 = $bdd->prepare("SELECT * FROM evresultats WHERE EvenementID=:id");
            $req1->bindValue('id',$eventID, PDO::PARAM_INT);
            $req1->execute();
            $result3= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result3, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

     function getChampion($idChamp)
         {
         //     try {
         //         $req = $bdd->prepare('SELECT * FROM champions WHERE ID=:id');
         //         $req->bindValue('id',$idChamp, PDO::PARAM_INT);
         //         $req->execute();
         //         $champ= $req->fetch(PDO::FETCH_ASSOC);
         //         return $champ;
         //     }
         // catch(Exception $e)
         //     {
         //        die('Erreur : ' . $e->getMessage());
         //     }
         } 

    // $query1    = sprintf('SELECT * FROM evenements WHERE ID = %s',mysql_real_escape_string($eventID));
    // $result1   = mysql_query($query1);
    // $evenement = mysql_fetch_array($result1);

    // $query3    = sprintf('SELECT * FROM evresultats WHERE EvenementID=%s',mysql_real_escape_string($eventID));
    // $result3   = mysql_query($query3);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>R&eacute;sultat : </legend>
<div>
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Champion</th><th>Rang</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($resultat = mysql_fetch_array($result3)){
            foreach ($result3 as $resultat) {
                 try {
                 $req = $bdd->prepare('SELECT * FROM champions WHERE ID=:id');
                 $req->bindValue('id',$resultat['ChampionID'], PDO::PARAM_INT);
                 $req->execute();
                 $champion= $req->fetch(PDO::FETCH_ASSOC);
             }
         catch(Exception $e)
             {
                die('Erreur : ' . $e->getMessage());
             }
               // $champion=getChampion($resultat['ChampionID']);

            // $query5    = sprintf('SELECT Nom FROM champions WHERE ID=%s',mysql_real_escape_string($resultat['ChampionID']));
            // $result5   = mysql_query($query5);
            // $champion  = mysql_fetch_array($result5);
            echo "<tr align=\"center\" ><td>".$resultat['ID']."</td><td>".$champion['Nom']."</td><td>".$resultat['Rang']."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='editerLigneResultat.php?evResultID=".$resultat['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer la ligne correspondante &agrave; ".$champion['Nom']." ?')) { location.href='supprimerLigneResultat.php?evResultID=".$resultat['ID'].'&eventID='.$resultat['EvenementID']."';} else { return 0;}\" /></td></tr>";
               
        } ?>

    </tbody>
    </table>
</div>
</fieldset>
<fieldset style="float:left;">
    <legend><?php echo $evenement['Nom'];?></legend>
    <br />

    <a href="modelCsv.php">telecharger le model</a>

    <p>Envoie fichier r&eacute;sultat</p>
    <?php
        if(isset($_GET['evenementID'])){
            echo '<form action="uploadCsv.php?evenementID='.$eventID.'" method="post" enctype="multipart/form-data" name="form1">';
        }else if(isset($_GET['evenement_filsID'])){
            echo '<form action="uploadCsv.php?evenement_filsID='.$eventID.'" method="post" enctype="multipart/form-data" name="form1">';

        }
    ?>
    
    <p><input type="file" name="file" /></p>
    <input type="submit" name="Submit" value="Envoyer">
    </form>

</fieldset>
</body>
</html>