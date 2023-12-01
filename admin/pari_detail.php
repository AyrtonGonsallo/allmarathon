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

$pariID = (isset($_GET['pariID']))?(int)$_GET['pariID']:exit('error');

require_once '../database/connexion.php';

$erreur = "";
if( isset($_POST['sub'])) {
    if($_POST['evenement_id']==0)
        $erreur .= "Erreur evenement.<br />";

    if($_POST['date_debut']=="")
        $erreur .= "Erreur date de debut vide.<br />";

    if($_POST['date_fin']=="")
        $erreur .= "Erreur date de fin vide.<br />";

    if($erreur == "" ) {
       try {
        $req = $bdd->prepare("UPDATE pari SET date_debut=:date_debut,date_fin=:date_fin,actif=:actif,description=:description,evenement_id=:evenement_id WHERE id=:ID");

        $req->bindValue('date_debut',$_POST['date_debut'], PDO::PARAM_STR);
        $req->bindValue('date_fin',$_POST['date_fin'], PDO::PARAM_STR);
        $req->bindValue('actif',$_POST['actif'], PDO::PARAM_INT);
        $req->bindValue('description',$_POST['description'], PDO::PARAM_STR);
        $req->bindValue('evenement_id',$_POST['evenement_id'], PDO::PARAM_INT);
        $req->bindValue('ID',$pariID, PDO::PARAM_INT);
        $req->execute();

    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
        // $query2    = sprintf("UPDATE pari SET date_debut='%s',date_fin='%s',actif='%s',description='%s',evenement_id='%s' WHERE id=%s"
        //     ,mysql_real_escape_string($_POST['date_debut'])
        //     ,mysql_real_escape_string($_POST['date_fin'])
        //     ,mysql_real_escape_string($_POST['actif'])
        //     ,mysql_real_escape_string($_POST['description'])
        //     ,mysql_real_escape_string($_POST['evenement_id'])
        //     ,$pariID);
        // //exit($query2);
        // $result2   = mysql_query($query2) or die(mysql_error());
}

}

try{
  $req1 = $bdd->prepare("SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id WHERE p.id=:ID");
  $req1->bindValue('ID',$pariID, PDO::PARAM_INT);
  $req1->execute();
  $pari=$req1->fetch(PDO::FETCH_ASSOC);

$req2 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
$req2->execute();
$result5= array();
while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result5, $row);
}

$req6 = $bdd->prepare("SELECT * FROM pari_composition ORDER BY id DESC");
$req6->execute();
$result6= array();
while ( $row  = $req6->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result6, $row);
}
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

// $query1    = sprintf('SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id WHERE p.id=%s ',$pariID);
// $result1   = mysql_query($query1);
// $pari      = mysql_fetch_array($result1);

// $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
// $result5   = mysql_query($query5);

// $query6    = sprintf('SELECT * FROM pari_composition ORDER BY id DESC');
// $result6   = mysql_query($query6);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="../Scripts/tiny_mce_init.js"></script>
    <title>allmarathon admin</title>
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
    <legend>Modifier pari <?php echo $pari['Nom'] ?></legend>
        <form action="" method="post">
            <p id="pErreur" align="center"><?php echo $erreur; ?></p>
            <table>
            <tr><td align="right"><label for="Nom">Date d�but : </label></td><td><input id="datepicker" type="text" name="date_debut" value="<?php echo $pari['date_debut']?>"/></td></tr>
                <tr><td align="right"><label for="Text1">Date fin : </label></td><td><input id="datepicker2" type="text" name="date_fin" value="<?php echo $pari['date_fin']?>" /></td></tr>
                <tr><td align="right"><label for="Text2">actif : </label></td><td><input type="radio" name="actif" value="1" id="is_actif" <?php if($pari['actif'] == 1): ?>checked="checked"<?php endif; ?> /><label for="is_actif">oui</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="actif" id="is_not_actif" <?php if($pari['actif'] == 0): ?>checked="checked"<?php endif; ?>/><label for="is_not_actif">non</label></td></tr>
                <tr><td align="right"><label for="Text3">Description : </label></td><td><textarea cols="50" rows="10" name="description"><?php echo $pari['description'] ?></textarea></td></tr>
                <tr><td align="right"><label for="evenement_id">Evenement : </label></td><td>
                    <select name="evenement_id" >
                        <option value="0">aucun</option>
                        <?php //while($eventa = mysql_fetch_array($result5)) {
                            foreach ($result5 as $eventa) {
                               $str = ($eventa['ID']==$pari['evenement_id']) ? '<option value="'.$eventa['ID'].'" selected="selected" >'.$eventa['Intitule'].' '.$eventa['Nom'].' '.substr($eventa['DateDebut'],0,4).'</option>':'<option value="'.$eventa['ID'].'">'.$eventa['Intitule'].' '.$eventa['Nom'].' '.substr($eventa['DateDebut'],0,4).'</option>';
                            echo $str;
                        } ?>
                    </select></td></tr>
                    <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr�er" /></td></tr>
                </table>
            </form>
        </fieldset>


    </body>
    </html>

