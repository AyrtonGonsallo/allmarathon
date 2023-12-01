<?php
session_start();
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

require_once '../database/connection.php';

$erreur = "";
if( isset($_POST['sub'])) {
    if($_POST['evenement_id']==0)
        $erreur .= "Erreur evenement.<br />";

    if($_POST['date_debut']=="")
        $erreur .= "Erreur date de debut vide.<br />";

    if($_POST['date_fin']=="")
        $erreur .= "Erreur date de fin vide.<br />";   

    if($erreur == "" ) {
        $query2    = sprintf("INSERT INTO pari (date_debut,date_fin,actif,description,evenement_id) VALUES ('%s','%s','%s','%s','%s')"
            ,mysql_real_escape_string($_POST['date_debut'])
            ,mysql_real_escape_string($_POST['date_fin'])
            ,mysql_real_escape_string($_POST['actif'])
            ,mysql_real_escape_string($_POST['description'])
            ,mysql_real_escape_string($_POST['evenement_id']));
        //exit($query2);
        $result2   = mysql_query($query2) or die(mysql_error());
    }

}

$query1    = sprintf('SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id ORDER BY ID DESC ');
$result1   = mysql_query($query1);

$query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
$result5   = mysql_query($query5);
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
        <script type="text/javascript">
            $(function() {
                $('#datepicker').datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
                $('#datepicker2').datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $('#datepicker2').datepicker('option', {dateFormat: 'yy-mm-dd'});
            });

        </script>
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>
        <fieldset style="float:left;">
            <legend>Ajouter pari</legend>
            <form action="" method="post">
                <p id="pErreur" align="center"><?php echo $erreur; ?></p>
                <table>
                    <tr><td align="right"><label for="Nom">Date d�but : </label></td><td><input id="datepicker" type="text" name="date_debut" /></td></tr>
                    <tr><td align="right"><label for="Text1">Date fin : </label></td><td><input id="datepicker2" type="text" name="date_fin" /></td></tr>
                    <tr><td align="right"><label for="Text2">actif : </label></td><td><input type="radio" name="actif" value="1" id="is_actif" /><label for="is_actif">oui</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="actif" id="is_not_actif"  checked="checked"/><label for="is_not_actif">non</label></td></tr>
                    <tr><td align="right"><label for="Text3">Description : </label></td><td><textarea cols="50" rows="10" name="description"></textarea></td></tr>
                    <tr><td align="right"><label for="Evenement_id">Evenement : </label></td><td>
                            <select name="evenement_id" >
                                <option value="0">aucun</option>
                                <?php while($event = mysql_fetch_array($result5)) {
                                    $str = '<option value="'.$event['ID'].'">'.$event['ID'].' : '.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';
                                    echo $str;
                                } ?>
                            </select></td></tr>
                    <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr�er" /></td></tr>
                </table>
            </form>
        </fieldset>

        <fieldset style="float:left;">
            <legend>Pari : </legend>
            <div align="center">
                <table class="tab1">
                    <thead>
                        <tr><th>ID</th><th>Nom</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php while($pari = mysql_fetch_array($result1)) {
                            echo "<tr align=\"center\" ><td>".$pari['id']."</td><td>".$pari['Nom']."</td>
                <td>
                <img style=\"cursor:pointer;\" src=\"../images/dl.png\" width=\"16\" alt=\"composition\" title=\"composition\" onclick=\"location.href='pariCompo.php?pariID=".$pari['id']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='pari_detail.php?pariID=".$pari['id']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/podium.png\" alt=\"resultat\" title=\"resultat\" onclick=\"location.href='pariResultat.php?pariID=".$pari['id']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$pari['Nom']." ?')) { location.href='supprimerPari.php?pariID=".$pari['id']."';} else { return 0;}\" />
                </td></tr>";

                        } ?>

                    </tbody>
                </table>

            </div>
        </fieldset>
    </body>
</html>

