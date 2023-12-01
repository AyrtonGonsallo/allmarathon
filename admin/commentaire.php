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
              $req = $bdd->prepare("SELECT C.*,U.username,U.email FROM commentaires C INNER JOIN users U ON U.id = C.user_id WHERE C.valide=0 ORDER BY C.ID DESC");
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
        foreach ($result1 as $com) {
        try{
          if($com['news_id']!=0) {
              $req = $bdd->prepare("SELECT titre FROM news WHERE ID=:ID");
              $req->bindValue('ID',$com['news_id'], PDO::PARAM_INT);
              $req->execute();
              $news= $req->fetch(PDO::FETCH_ASSOC);
              $source    = $news['titre'];
            }else{
              $req1 = $bdd->prepare("SELECT Titre FROM videos WHERE ID=:ID");
              $req1->bindValue('ID',$com['video_id'], PDO::PARAM_INT);
              $req1->execute();
              $video= $req1->fetch(PDO::FETCH_ASSOC);
              $source    = $video['Titre'];
            } 

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        $commentaires[$com['ID']]['source'] = str_replace("\\", "", $source);
        $commentaires[$com['ID']]['user']   = $com['username'];
        $commentaires[$com['ID']]['mail']   = $com['email'];
        $commentaires[$com['ID']]['text']   = str_replace("\\", "", $com['commentaire']);
        $commentaires[$com['ID']]['date']   = changeDate($com['date']);
        $commentaires[$com['ID']]['lien']     = ($com['champion_id']!=0)?'<a href="../athlète-'.$com['champion_id'].'.html" target="blank">lien site</a> <a href="championDetail.php?championID='.$com['champion_id'].'" target="blank">lien admin</a>':(($com['news_id']!=0)?'<a href="../actualite-marathon-'.$com['news_id'].'.html" target="blank">lien</a>':'<a href="../video-de-marathon-'.$com['video_id'].'.html" target="blank">lien</a>');
    }
    
function changeDate($date){
        $day    = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
        $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
        $timestamp = mktime(substr($date, 11, 2),substr($date, 14, 2),0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
        return "le ".date("j/n",$timestamp)." &agrave; ".date("G:i",$timestamp);
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
<legend>Liste des nouveaux commentaires</legend>

    <?php
    if(isset($commentaires)){
    foreach($commentaires as $id => $com ){?>

    <div style="width:600px;border:solid 1px gray;margin:4px;padding:4px;">
        <div style="border-bottom:dotted 1px black;padding:3px;margin-bottom:3px;"><?php echo $com['source']; ?></div>
        <span style="float:right;"><?php echo $com['date']; ?></span><?php if($com['mail'] != "") { ?><a href="mailto:<?php echo $com['mail']; ?>?subject=<?php echo 'Commentaire alljudo.net'; ?>&body=<?php echo  str_replace('
',"%0A",'Vous avez comment&eacute; un athlètes sur alljudo.net'); ?>"><?php echo $com['user']; ?></a><?php } ?> <?php echo $com['lien']; ?><br />
        <br />
        <span style="font-style:italic;margin:3px;"><?php echo $com['text']; ?></span>
        <div style="border-top:dotted 1px black;padding:1px;margin-top:3px;">
                <a style="float:left;cursor:pointer;color:red;" onclick="if(confirm('Voulez vous vraiment supprimer le commentaire <?php echo $id; ?> ?')) { location.href='supprimerCommentaire.php?commentaireID=<?php echo $id; ?>';} else { return 0;}">supprimer</a>
                
                <a style="float:right;" href="validerCommentaire.php?commentaireID=<?php echo $id; ?>">valider</a>
                
                <div style="clear:both;"></div></div>
    </div>
    <?php }}else{ echo "Aucun nouveau commentaire."; } ?>

</fieldset>
</body>
<!-- InstanceEnd --></html>

