<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
//verif de validiter session
if (!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}
require_once('testMails.php');// envoyerEmail($dest,$sujet,$contenu_html,$contenu_text)
require_once '../database/connexion.php';

if (isset($_POST['valider'])) {
    try {
        $req_suppr = $bdd->prepare("DELETE FROM `evresultats` WHERE Rang=:r and EvenementID=:e");

        $req_suppr->bindValue('r',$_POST['rang'], PDO::PARAM_INT);
        $req_suppr->bindValue('e',$_POST['EvenementID'], PDO::PARAM_INT);


        $req_suppr->execute();
    }
    catch(Exception $e)
    {
       die('Erreur : ' . $e->getMessage());
   }
    try {
         $req_add = $bdd->prepare("INSERT INTO `evresultats`( `Rang`,  `Temps`, `EvenementID`, `ChampionID`) VALUES (:rang,:Temps,:ev,:champ)");

         $req_add->bindValue('rang',$_POST['rang'], PDO::PARAM_INT);
         $req_add->bindValue('Temps',$_POST['Temps'], PDO::PARAM_STR);
         $req_add->bindValue('ev',$_POST['EvenementID'], PDO::PARAM_INT);
         $req_add->bindValue('champ',$_POST['ChampionID'], PDO::PARAM_INT);


         $req_add->execute();
         
         $req_add2 = $bdd->prepare("select email,nom,prenom from users where username like :u");
         $req_add2->bindValue('u','%'.$_POST['utilisateur'].'%', PDO::PARAM_STR);
         $req_add2->execute();
         $mail2= $req_add2->fetch(PDO::FETCH_ASSOC);
         $mail=$mail2["email"];
         $nom_complet=$mail2["nom"].' '.$mail2["prenom"];
         $req_add3 = $bdd->prepare("select e.ID,m.prefixe,m.Nom,Year(e.DateDebut) as annee from `evenements` e,marathons m WHERE m.id=e.marathon_id and  e.id = :id");
         $req_add3->bindValue('id',$_POST['EvenementID'], PDO::PARAM_INT);
         $req_add3->execute();
         $mail3= $req_add3->fetch(PDO::FETCH_ASSOC);
         $nom_event="Marathon ".(($mail3["prefixe"])?$mail3["prefixe"]:'')." ".$mail3["Nom"]." ".$mail3["annee"];
         $message="<html>
    <head>
    <style>
            a{color:#000 !important;text-decoration:none !important;}
            a:hover { color: #FBFF06 !important; }
            .home-link:hover { background-color: #95d7fe !important; color: #000 !important; }
            .icon-hov:hover img{filter: invert(98%) sepia(24%) saturate(6709%) hue-rotate(357deg) brightness(108%) contrast(107%)!important;}
        </style>
    </head>
    <body style='font-family: Arial, sans-serif;'>
        <div style='margin: 20px;'>
        <h1 style='color: #95d7fe;font-family: 'Montserrat';font-weight: 900;'>Bonjour ".$_POST['utilisateur'].",</h1>
         
         Le résultat que vous nous avez soumis a été validé :<br>".$nom_complet." - ".$_POST['rang']."e - ".$nom_event." - ".$_POST['Temps']."<br><br>
            Merci pour votre contribution,<br><br>
            Très Cordialement<br>
            L'équipe de allmarathon.fr<br><br><br><br>
            <!-- Footer -->
            <div style='background-color: #95D7FE; padding: 20px; border-radius: 5px; font-size: 12px;'>
                <div style='text-align: center;'>
                    <a href='https://www.facebook.com/allmarathon.fr' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/facebook.png' alt='Facebook' style='width: 13px; margin: 0 5px;'>
                    </a>
                    <a href='https://www.instagram.com/allmarathon.fr' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/instagra.png' alt='Instagram' style='width: 23px; margin: 0 5px;'>
                    </a>
                    <a href='https://www.pinterest.fr/allmarathon/' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/pinterest.png' alt='Pinterest' style='width: 20px; margin: 0 5px;'>
                    </a>
                   
                </div>
                <div style='text-align: center; margin-top: 10px;'>
                    <a href='https://dev.allmarathon.fr/contact.html' style='color: #000; text-decoration: none;'>Contact</a> |
                    <a href='https://dev.allmarathon.fr/politique-de-confidentialite.html' style='color: #000; text-decoration: none;'>Politique de confidentialité</a>
                </div>
                <div style='text-align: center; margin-top: 10px;'>
                    
                    <a href='https://allmarathon.fr'>www.allmarathon.fr</a>
                </div>
                <div style='border-top: 1px solid #fff !important;margin-top:10px;'></div>
                <div style='text-align: center;  background-color:#fff;    padding: 14px 10px 9px 10px;border-radius:5px;width:fit-content;    margin: auto;margin-top: 19px;'>
                    <a href='https://dev.allmarathon.fr/contact.html'><img src='https://dev.allmarathon.fr/images/logo-allmarathon.png' alt='logo' style='width: 140px;'></a>
                </div>
            </div>
        </div>
    </body>
    </html>";
         $res=envoyerEmail($mail,'Validation de résultat sur allmarathon',$message,'This is a plain-text message body');


     }
     catch(Exception $e)
     {
        die('Erreur : ' . $e->getMessage());
    }
    try {
        $req_del_from_palm = $bdd->prepare("update champion_admin_externe_palmares set Status=1 WHERE ID = :id");
        $req_del_from_palm->bindValue('id',$_POST['ID'], PDO::PARAM_INT);
        $req_del_from_palm->execute();
    }
    catch(Exception $e)
    {
     die('Erreur : ' . $e->getMessage());
 }

    header('location: resultAdminExterne.php?email='.$res);
}



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#tbl1")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager")});
        }
        );

    </script>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker({
                changeMonth: true,
                changeYear: true
            });
            $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
        });
    </script>
    <title>allmarathon admin externe resultats</title>




</head>

<body>
    <?php require_once "menuAdmin.php"; ?>

    <fieldset style="float:left;">
        <legend>Validation des resultats administr&eacute;s</legend>
        
        <form method="post" action="">
            <table>
                <tr>
                    <td>Rang: </td>
                    <td><input type="text" name="rang" value="<?php echo $_POST['rang'] ?>" /></td>
                </tr>
               
                <tr>
                    <td>utilisateur: </td>
                    <td><input type="text" name="utilisateur" value="<?php echo $_POST['utilisateur'] ?>" /></td>
                </tr>
                <input type="hidden" name="EvenementID" value="<?php echo $_POST['EvenementID'] ?>" />
                <input type="hidden" name="ID" value="<?php echo $_POST['ID'] ?>" />
                <input type="hidden" name="ChampionID" value="<?php echo $_POST['ChampionID'] ?>" />
                <tr>
                    <td>Temps: </td>
                    <td><input type="text" name="Temps" value="<?php echo $_POST['Temps'] ?>" /></td>
                </tr>
                
                        <tr>
                            <td>Date: </td>
                            <td><input type="text" name="date" id="datepicker" value="<?php echo $_POST['date'] ?>" /></td>
                        </tr>
                        
                       
                     <tr>
                        <input type="hidden" name="resultID" value="<?php echo $_POST['extern_edit_id'] ?>" />
                        <td colspan="2"><input type="submit" name="valider" value="Valider"/></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
    </html>