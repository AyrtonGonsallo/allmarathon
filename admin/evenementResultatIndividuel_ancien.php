<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

require_once '../database/connection.php';

if(!isset($_GET['evenementID'])){
    header("Location: evenement.php");
}

    $query1    = sprintf('SELECT * FROM evenements WHERE ID = %s',mysql_real_escape_string($_GET['evenementID']));
    $result1   = mysql_query($query1);
    $evenement = mysql_fetch_array($result1);

    $query3    = sprintf('SELECT * FROM evresultats WHERE EvenementID=%s',mysql_real_escape_string($_GET['evenementID']));
    $result3   = mysql_query($query3);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>R�sultat : </legend>
<div>
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Champion</th><th>Rang</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php while($resultat = mysql_fetch_array($result3)){
            $query5    = sprintf('SELECT Nom FROM champions WHERE ID=%s',mysql_real_escape_string($resultat['ChampionID']));
            $result5   = mysql_query($query5);
            $champion  = mysql_fetch_array($result5);
            echo "<tr align=\"center\" ><td>".$resultat['ID']."</td><td>".$champion['Nom']."</td><td>".$resultat['Rang']."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='championDetail.php?championID=".$champion['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$champion['nom']." ?')) { location.href='delConcour.php?concourID=".$concours['ID']."';} else { return 0;}\" /></td></tr>";
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
<fieldset style="float:left;">
    <legend><?php echo $evenement['Nom'];?></legend>
    <br />

    <a href="modelCsv.php">telecharger le model</a>

    <p>Envoie fichier r�sultat</p>

    <form action="uploadCsv.php?evenementID=<?php echo $_GET['evenementID'];?>" method="post" enctype="multipart/form-data" name="form1">
    <p><input type="file" name="file" /></p>
    <input type="submit" name="Submit" value="Envoyer">
    </form>

</fieldset>
</body>
</html>