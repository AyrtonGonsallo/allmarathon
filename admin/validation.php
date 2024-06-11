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

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    try{
              $req = $bdd->prepare("SELECT j.*,c.Nom as c_name,u.nom,u.prenom,u.username FROM champion_admin_externe_journal j, users u, champions c where j.type like '%upload-image%' and j.champion_id=c.ID and j.user_id=u.id");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }
            $req2 = $bdd->prepare("SELECT j.*,c.Nom as c_name,u.nom,u.prenom,u.username FROM champion_admin_externe_journal j, users u, champions c where j.type like '%upload-video%' and j.champion_id=c.ID and j.user_id=u.id");
            $req2->execute();
            $result2= array();
            while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {  
              array_push($result2, $row2);
          }

           


        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<!-- InstanceBeginEditable name="doctitle" -->
<title>allmarathon admin</title>

<!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>

<fieldset style="float:left;">
<legend>Liste des images en attente de validation</legend>
<div align="center">

<table class="tab1">
    <thead>
    <tr><th>ID</th><th>Date</th><th>Utilisateur</th><th>Champion</th><th>Image</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php 
    //while($res = mysql_fetch_array($result1)){
    foreach ($result1 as $res) {
        $imagePath = $res['image'];
        $thumbnail = '<a href="../images/galeries/0/' . $imagePath . '" target="_blank">'.$imagePath.'</a>';

        echo "<tr align=\"center\">
                <td>".$res['ID']."</td>
                <td>".$res['date']."</td>
                <td>".$res['nom'].' '.$res['prenom']."</td>
                <td>".$res['c_name']."</td>
                <td>".$thumbnail."</td>
                <td>
                    <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/valid.gif\" alt=\"resultat\" title=\"valider image\" onclick=\"location.href='valider_image_externe.php?ID=".$res['ID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\" onclick=\"if(confirm('Voulez vous vraiment supprimer  ?')) { location.href='supprimer_action_externe.php?ID=".$res['ID']."';} else { return 0;}\" />
                </td>
              </tr>";
    } 
    ?>
    </tbody>
</table>


</div>
</fieldset>
    <fieldset style="float:left;">
<legend>Liste des vidéos en attente de validation</legend>
<div align="center">

<table class="tab1">
    <thead>
    <tr><th>ID</th><th>Date</th><th>Utilisateur</th><th>Champion</th><th>Vidéo</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php 
    function getYouTubeVideoId($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        return isset($vars['v']) ? $vars['v'] : null;
    }

    //while($res = mysql_fetch_array($result1)){
    foreach ($result2 as $res) {
        $videoId = getYouTubeVideoId($res['video']);
        if ($videoId) {
            $iframe = '<iframe width="300" height="155" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
        } else {
            $iframe = 'Invalid video URL';
        }

        echo "<tr align=\"center\">
                <td>".$res['ID']."</td>
                <td>".$res['date']."</td>
                <td>".$res['nom'].' '.$res['prenom']."</td>
                <td>".$res['c_name']."</td>
                <td>".$iframe."</td>
                <td>
                    <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/valid.gif\" alt=\"resultat\" title=\"valider image\" onclick=\"location.href='valider_video_externe.php?ID=".$res['ID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\" onclick=\"if(confirm('Voulez vous vraiment supprimer  ?')) { location.href='supprimer_action_externe.php?ID=".$res['ID']."';} else { return 0;}\" />
                </td>
              </tr>";
    } ?>
    </tbody>
</table>


</div>
</fieldset>
   
</body>
<!-- InstanceEnd --></html>

