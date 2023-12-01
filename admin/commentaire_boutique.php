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

    $commentaires = $bdd->prepare("SELECT * FROM article_com where valide = 0");
    $commentaires->execute();

    if (isset($_GET["operation"])) {
        $id = $_GET["id"];
        if ($_GET["operation"] == "del") {
            $qry_del = $bdd->prepare("DELETE FROM article_com where id = ".$id);
            $qry_del->execute();
        } else {
            $qry_del = $bdd->prepare("UPDATE article_com set valide = 1 where id = ".$id);
            $qry_del->execute();
        }
        header('Location: commentaire_boutique.php');
        exit();
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

<?php while ( $com  = $commentaires->fetch(PDO::FETCH_ASSOC)) { 
    $qry_art = $bdd->prepare("SELECT * FROM article where id = ".$com["art_id"]);
    $qry_art->execute();
    $art  = $qry_art->fetch(PDO::FETCH_ASSOC);

    $qry_user = $bdd->prepare("SELECT * FROM users where id = ".$com["user_id"]);
    $qry_user->execute();
    $user  = $qry_user->fetch(PDO::FETCH_ASSOC);
    ?>
    <div style="width:600px;border:solid 1px gray;margin:4px;padding:4px;">
        <div style="border-bottom:dotted 1px black;padding:3px;margin-bottom:3px;"><a href="<?php echo '/produit-'.$art['id'].'.html'; ?>">Source</a></div>
        <span style="float:right;"><?php echo date("d/m/Y", strtotime($com["date"])); ?></span><?php echo $user['username'];  ?></a><br />
        <br />
        <span style="font-style:italic;margin:3px;"><?php echo $com['commentaire'];  ?></span>
        <div style="border-top:dotted 1px black;padding:1px;margin-top:3px;">
                <a style="float:left;cursor:pointer;color:red;" onclick="if(confirm('Voulez vous vraiment supprimer le commentaire 45678 ?')) { location.href='commentaire_boutique.php?operation=del&id=<?php echo $com['id'];  ?>';} else { return 0;}">supprimer</a>
                
                <a style="float:right;" href='commentaire_boutique.php?operation=valide&id=<?php echo $com['id'];  ?>'>valider</a>
                
                <div style="clear:both;"></div></div>
    </div>
<?php } ?>
</fieldset>
</body>
<!-- InstanceEnd --></html>

