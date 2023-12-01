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

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

require_once '../database/connexion.php';

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET ['page']))
    $page = $_GET['page'];



$erreur = "";
if( isset($_POST['extern_actif'])) {
    if($_POST['extern_id']=="")
        $erreur .= "Erreur id.<br />";
    if($erreur == "") {
         try {
                $actif=($_POST['extern_actif'])?0:1;
                 $req2 = $bdd->prepare("UPDATE club_admin_externe SET actif=:actif WHERE id =:ID");
                 $req2->bindValue('actif',$actif, PDO::PARAM_INT);
                 $req2->bindValue('ID',$_POST['extern_id'], PDO::PARAM_INT);
                 $req2->execute();
                 if(!$_POST['extern_actif']){
                     $req3 = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, club_id) VALUES ('new_admin_club', :user_id, :club_id)");
                     $req3->bindValue('user_id',$_POST['user_id'], PDO::PARAM_INT);
                     $req3->bindValue('club_id',$_POST['club_id'], PDO::PARAM_INT);
                     $req3->execute();
                }
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }

    }
}
if( isset($_POST['extern_refus_id'])) {
    try {
                 $req = $bdd->prepare("DELETE FROM club_admin_externe WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_POST['extern_refus_id'], PDO::PARAM_INT);
                 $req->execute();
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
}

try{
  
  $req = $bdd->prepare("SELECT p.fonction, p.nom, p.prenom, u.email, p.telephone,p.actif,u.username,p.id,u.id as user_id,c.club,p.club_id FROM users u INNER JOIN club_admin_externe p ON u.id = p.user_id INNER JOIN clubs c ON c.ID=p.club_id ORDER BY p.date_creation DESC");
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

// $query1    = sprintf('SELECT p.fonction, p.nom, p.prenom, u.email, p.telephone,p.actif,u.username,p.id,u.id as user_id,c.club,p.club_id FROM users u INNER JOIN club_admin_externe p ON u.id = p.user_id INNER JOIN clubs c ON c.ID=p.club_id ORDER BY p.date_creation DESC');

// $result1   = mysql_query($query1) or die(mysql_error());


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
       
        <title>allmarathon admin externe champion</title>



       
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>


        <fieldset style="float:left;">
            <legend>Liste des administrateurs de club externes</legend>
            <div align="center">
                <div id="pager" class="pager">
                    <form>
                        <img src="../fonction/tablesorter/first.png" class="first"/>
                        <img src="../fonction/tablesorter/prev.png" class="prev"/>
                        <input type="text" class="pagedisplay"/>
                        <img src="../fonction/tablesorter/next.png" class="next"/>
                        <img src="../fonction/tablesorter/last.png" class="last"/>
                        <select class="pagesize">
                            <option selected="selected"  value="10">10</option>

                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option  value="40">40</option>
                        </select>
                    </form>
                </div>
                <br />

                <table class="tablesorter" id="tbl1">
                    <thead>
                        <tr><th>Champion</th><th>Utilisateur</th><th>Nom</th><th>Prenom</th><th>Fonction</th><th>Mail</th><th>t&eacute;l&eacute;phone</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result1 as $l) {?>
                        <tr>
                            <td><a href="../club-marathon-<?php echo $l['club_id'] ?>.html"><?php echo $l['club'] ?></a></td>
                            <td><a href=""><?php echo $l['username'] ?></a></td>
                            <td><?php echo $l['nom'] ?></td>
                            <td><?php echo $l['prenom'] ?></td>
                            <td><?php echo $l['fonction'] ?></td>
                            <td><a href="mailto:<?php echo $l['email'] ?>"><?php echo $l['email'] ?></a></td>
                            <td><?php echo $l['telephone'] ?></td>
                            <td>
                                <form action="" method="post" style="float: left;">
                                    <input type="hidden" name="extern_id" value="<?php echo $l['id']?>"/>
                                    <input type="hidden" name="extern_actif" value="<?php echo $l['actif']?>"/>
                                    <input type="hidden" name="user_id" value="<?php echo $l['id']?>"/>
                                    <input type="hidden" name="club_id" value="<?php echo $l['club_id']?>"/>
                                    <input name="extern_active" type="image" src="../images/<?php echo ($l['actif'])?'valid.gif':'invalid.png'; ?>" style="width: 20px" title="autoriser utilisateur &agrave; administrer le club"/>
                                </form>
                                <form action="" style="float: left;" method="post" onsubmit="if(window.confirm('Refuser la demande ?'))return true; else return false;">
                                    <input type="hidden" name="extern_refus_id" value="<?php echo $l['id']?>"/>
                                    <input name="extern_refus" type="image" src="../images/refus.png" style="width: 20px" title="refuser la demande" />
                                </form>
                            </td>
                        </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </body>
    <!-- InstanceEnd --></html>


