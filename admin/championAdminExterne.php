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
         $req4 = $bdd->prepare("UPDATE champion_admin_externe SET actif=:actif WHERE id = :id");
         $req4->bindValue('actif',($_POST['extern_actif'])?0:1, PDO::PARAM_INT);
         $req4->bindValue('id',$_POST['extern_id'], PDO::PARAM_INT);
         $req4->execute();
         if(!$_POST['extern_actif']){
            $req5 = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('new_admin', :user_id, :champion_id)");
            $req5->bindValue('user_id',$_POST['user_id'], PDO::PARAM_STR);
             $req5->bindValue('champion_id',$_POST['champion_id'], PDO::PARAM_INT);
             $req5->execute();
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
         $req_refus = $bdd->prepare("DELETE FROM champion_admin_externe WHERE id = :id LIMIT 1");
         $req_refus->bindValue('id',$_POST['extern_refus_id'], PDO::PARAM_INT);
         $req_refus->execute();
     }
     catch(Exception $e)
     {
        die('Erreur : ' . $e->getMessage());
    }
}

if( isset($_POST['video_sub']) ) {
    try {
        
         if($_POST['journal'] == 'true'){
             $req_journal = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('video', :user_id, :champion_id)");
             $req_journal->bindValue('user_id',$_POST['user_id'], PDO::PARAM_INT);
             $req_journal->bindValue('champion_id',$_POST['champion_id'], PDO::PARAM_STR);
             $req_journal->execute();
    }
            $req_add_video = $bdd->prepare("INSERT INTO videos (Titre ,Date ,Duree,Objet ,Vignette ,A_la_une, Champion_id ) VALUES (:Titre,:Date,:Duree,:Objet,:Vignette,:A_la_une,:Champion_id)");
            $req_add_video->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
            $req_add_video->bindValue('Date',date("Y-m-d G:i:s"), PDO::PARAM_STR);
            $req_add_video->bindValue('Duree',$_POST['Duree'], PDO::PARAM_STR);
            $req_add_video->bindValue('Objet',$_POST['Objet'], PDO::PARAM_STR);

            $req_add_video->bindValue('Vignette',$_POST['Vignette'], PDO::PARAM_STR);
            $req_add_video->bindValue('A_la_une',$_POST['A_la_une'], PDO::PARAM_STR);
            $req_add_video->bindValue('Champion_id',$_POST['champion_id'], PDO::PARAM_INT);
            $req_add_video->execute();

            $req_refus = $bdd->prepare("UPDATE champion_admin_externe SET video=:video WHERE user_id = :admin_id");
            $req_refus->bindValue('admin_id',$_POST['user_id'], PDO::PARAM_INT);
            $req_refus->bindValue('video','', PDO::PARAM_STR);
            $req_refus->execute();
     }
     catch(Exception $e)
     {
        die('Erreur : ' . $e->getMessage());
      }
}


try{
  $req = $bdd->prepare("SELECT p.situation, p.nom, p.prenom, u.email, p.telephone,p.actif,u.username,p.id,u.id as user_id,c.Nom,p.champion_id FROM users u INNER JOIN champion_admin_externe p ON u.id = p.user_id INNER JOIN champions c ON c.ID=p.champion_id ORDER BY p.date_creation DESC");
  $req->execute();
  $result1= array();
  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result1, $row);
}

$req2 = $bdd->prepare("SELECT p.video, p.date_mod, u.email, u.username,p.id,u.id as user_id,c.Nom,p.champion_id FROM users u INNER JOIN champion_admin_externe p ON u.id = p.user_id INNER JOIN champions c ON c.ID=p.champion_id WHERE p.date_mod != '0000-00-00 00:00:00' ORDER BY p.date_mod DESC");
$req2->execute();
$result2= array();
while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result2, $row);
}

$req3 = $bdd->prepare("SELECT i.*,c.Nom as nom_athlète FROM images i INNER JOIN champions c ON i.Champion_id = c.ID WHERE i.Galerie_id = 24 AND i.champion_id in (SELECT Champion_id FROM images WHERE i.Galerie_id = 24 group by Champion_id ) ORDER BY i.ID DESC limit 40");
$req3->execute();
$result3= array();
while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result3, $row);
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
            $("#tbl2")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager2")});

        }
        );

    </script>
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin externe champion</title>

    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker({
                changeMonth: true,
                changeYear: true
            });
            $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
        });
    </script>

    <!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>


    <fieldset style="float:left;">
        <legend>Liste des administrateurs externes</legend>
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
        <tr><th>Champion</th><th>Utilisateur</th><th>Nom</th><th>Prenom</th><th>Lien</th><th>Mail</th><th>t&eacute;l&eacute;phone</th><th>Action</th></tr>
    </thead>
    <tbody>
                        <?php 
                            foreach ($result1 as $l) {?>
                            <tr>
                                <td><a href="../athlete-<?php echo $l['champion_id'] ?>.html"><?php echo $l['Nom'] ?></a></td>
                                <td><a href=""><?php echo $l['username'] ?></a></td>
                                <td><?php echo $l['nom'] ?></td>
                                <td><?php echo $l['prenom'] ?></td>
                                <td><?php echo $l['situation'] ?></td>
                                <td><a href="mailto:<?php echo $l['email'] ?>"><?php echo $l['email'] ?></a></td>
                                <td><?php echo $l['telephone'] ?></td>
                                <td>
                                    <form action="" method="post" style="float: left;">
                                        <input type="hidden" name="extern_id" value="<?php echo $l['id']?>"/>
                                        <input type="hidden" name="extern_actif" value="<?php echo $l['actif']?>"/>
                                        <input type="hidden" name="user_id" value="<?php echo $l['user_id']?>"/>
                                        <input type="hidden" name="champion_id" value="<?php echo $l['champion_id']?>"/>
                                        <input name="extern_active" type="image" src="../images/<?php echo ($l['actif'])?'valid.gif':'invalid.png'; ?>" style="width: 20px" title="Autoriser utilisateur &agrave; administrer le champion" />
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
            <fieldset>
                <legend>Liste des derni&egrave;res modifications</legend>
                <div id="pager2" class="pager">
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
                <table class="tablesorter" id="tbl2">

                    <thead>
                        <tr><th>Champion</th><th>Utilisateur</th><th>Date modification</th><th>Mail</th><th>Video</th></tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($result2 as $l) {?>
                        <tr>
                            <td><a href="../athlete-<?php echo $l['champion_id'] ?>.html"><?php echo $l['Nom'] ?></a></td>
                            <td><a href=""><?php echo $l['username'] ?></a></td>
                            <td><?php echo $l['date_mod'] ?></td>
                            <td><a href="mailto:<?php echo $l['email'] ?>"><?php echo $l['email'] ?></a></td>
                            <td>
                                <?php if($l['video'] != ""):?>
                                    <form action="" method="post">
                                        
                                        <input type="hidden" name="admin_id" value="<?php echo $l['id'] ?>"/>
                                        <input type="hidden" name="user_id" value="<?php echo $l['user_id']?>"/>
                                        <input type="hidden" name="champion_id" value="<?php echo $l['champion_id']?>"/>
                                        <input type="hidden"  name="video" value="<?php echo $l['video'] ?>"/>
                                        
                                        <iframe width="200" height="145" src="https://www.youtube.com/embed/<?php echo explode("?v=", $l['video'])[1]; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                        <br><br>publier dans le journal : <select name="journal"><option value="true">oui</option><option value="false">non</option></select>
                                        <br>
                                        <label for="Titre">Titre : </label>
                                            <input type="text" name="Titre" value="" /><br>
                                        <label for="Duree">Duree : </label>
                                            <input type="text" name="Duree" value="" /><br>
                                        <label for="Vignette">Vignette : </label>
                                            <input size="50" type="text" id="vignette" name="Vignette" value="https://i1.ytimg.com/vi/XXX/default.jpg" /><br>
                                        <label for="Vignette">A la une : </label>
                                        <select name="A_la_une" >
                                                <option value="1" selected="selected">oui</option>
                                                <option value="0">non</option>
                                        </select>
                                        <br>
                                        <label for="Objet">Objet : </label>
                                        <textarea onblur="fillPathPicture();" cols="50" rows="10" id="objet" name="Objet"><iframe width="640" height="345" src="https://www.youtube.com/embed/<?php echo explode("?v=", $l['video'])[1]; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                        </textarea>
                                

                                        <input type="submit" name="video_sub" value="publier" />
                                    </form>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Les derni&egrave;res photos</legend>
                <a href="galerieDetail.php?galerieID=24">Galerie 24</a><br />
                <table class="tab1">
                    <thead>
                        <tr>
                            <th>Champion</th><th>Images</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    foreach ($result3 as $image) {?>
                    <tr>
                        <td><a href="../athlete-<?php echo $image['Champion_id'] ?>.html" ><?php echo $image['nom_athlète'] ?></a></td>
                        <td><img src="../images/galeries/24/<?php echo $image['Nom'] ?>" alt="<?php echo $image['nom_athlète'] ?>" width="80px" /></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </fieldset>
    </body>
    <!-- InstanceEnd --></html>


