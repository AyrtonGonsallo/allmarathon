<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// (!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
if(!empty($_SESSION['auth_error'])) {
 $erreur_auth=$_SESSION['auth_error'];
 unset($_SESSION['auth_error']);
}else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
    $user_session=$_SESSION['user'];
    $erreur_auth='';
}  else $user_session='';


$departement = "";
if(isset($_GET['clubID'])) {$clubID = $_GET['clubID'];}
else{
    header('Location: /contact-clubs-de-marathon.html');
    exit();
}

include("../classes/pub.php");
include("../classes/club.php");
include("../classes/pays.php");
include("../classes/club_horaires.php");
include("../classes/club_athlètes.php");
include("../classes/club_admin_externe.php");

$club_admin_externe=new club_admin_externe();

$club_athlètes=new club_athlètes();
$athlètes=$club_athlètes->getAllByClubID($clubID)['donnees'];

$club_horaires=new club_horaires();
$horaire=$club_horaires->getAllByClubID($clubID)['donnees'];

$pays=new pays();
$liste_pays=$pays->getAllPaysWithClubs()['donnees'];
foreach($liste_pays as $p) {
    $paysNomTab[$p['Abreviation']]=$p['NomPays'];
}       


$cl=new club();

$club=$cl->getClubById($clubID)['donnees'];

if(!$club_admin_externe->isAdmin($clubID,$user_id)) {header('Location: /contact-clubs-de-marathon.html');}

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub300x60=$pub->getBanniere300_60("calendrier")['donnees'];
$pub300x250=$pub->getBanniere300_250("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("calendrier")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Fiche Administration du club <?php echo $club->getClub(); ?></title>
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <meta name="description" content="">
    
    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link href="../../tab/sb-admin-2.css" rel="stylesheet">
    <link href="../../styles/annuaire2009.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
    label.error {
        color: red;
    }

    input.update_athlète_input.error {
        border: 1px solid red !important;
    }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

    <script type="text/javascript">
    function addCompletion(str, index) {
        tab = str.split(':');
        idChamp = tab[0];
        name = tab[1];
        nameLink = tab[2];
        document.getElementById("autocomp" + index).style.display = "none";
        $('#temp1').val('' + name + '');
        $('#result').html('<input type="hidden" name="j_idchamp" value="' + idChamp +
            '"/><input type="hidden" name="j_name" value="' + name + '"/>');
    }

    $(document).ready(function() {
        $('#temp1').keyup(function() {
            if ($(this).val().length > 3)
                $.get('../../admin/resultatAutoCompletionLien.php', {
                    id: 1,
                    str: $(this).val()
                }, function(data) {
                    $('#autocomp1').show();
                    $('#autocomp1').html(data);
                });
        });

        $('#cut').click(function() {
            $('#temp1').select();

        });
    });
    </script>

</head>

<body>


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes">
        <div class="row banniere1">
            <a href="" class="col-sm-12"> <?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">

                    <div class="col-sm-12">

                        <h1>Fiche Administration du club <?php echo $club->getClub();
                            if (isset($_GET['add'])) {
                                    echo '<a href="club-admin-'.$clubID.'.html" class="btn new_btn"> Retour à la fiche d\'administration </a>';
                                } 
                                 ?>


                        </h1>
                        <hr class="hr_customized">

                        <?php 
                            if(isset($_SESSION['update_club_msg'])){
                                echo $_SESSION['update_club_msg'];
                                unset($_SESSION['update_club_msg']);
                            }
                            if(isset($_GET['add']) && $_GET['add']=="horaires"){
                            echo "<h1>Administration des horaires du club</h1>";
                            echo '<form method="post" id="ajouter_horaire" action="/content/modules/update_club_admin.php?clubID='.$club->getId().'"><table>
                            <tr class="row" ><td class="col-md-3" align="left" ><label for="h_jour">Jour : </label></td><td class="col-md-7" align="left"><input type="text" name="h_jour" class="update_athlète_input" data-rule-required="true" data-msg-required="Ce champ est obligatoire" /></td><td class="col-md-2"></td></tr>
                            <tr class="row" ><td class="col-md-3" align="left" ><label>Heure de début : </label></td><td class="col-md-7" align="left"><input type="text" name="h_hdeb" class="update_athlète_input" data-rule-required="true" data-msg-required="Ce champ est obligatoire" /></td></tr>
                            <tr class="row" ><td class="col-md-3" align="left" ><label>Heure de fin : </label></td><td class="col-md-7" align="left"><input type="text" name="h_hfin" class="update_athlète_input" data-rule-required="true" data-msg-required="Ce champ est obligatoire" /></td></tr>
                            <tr class="row" ><td class="col-md-3" align="left" ><label>Descriptif du cours (facultatif) : </label></td><td class="col-md-7" align="left"><textarea name="h_desc" class="update_athlète_input" ></textarea></td></tr>
                            <tr class="row" ><td class="col-md-3" align="left" ><label>Numéro du cours : </label></td><td class="col-md-7" align="left"><input type="text" name="h_num" class="update_athlète_input" data-rule-required="true" data-msg-required="Ce champ est obligatoire" /></td></tr>
                            <tr class="row" ><td class="col-md-3" align="left" >&nbsp;</td><td class="pull-right"><input type="submit" class="btn_custom"  name="ajout_horaire" value="Ajouter"/></td></tr>
                            </form></table> </div>';
                            // echo '<br/><a href="club-admin.php?clubID='.$clubID.'">Retour à la fiche d\'administration</a>';
                            //echo '<meta http-equiv="Refresh" content="3;URL=club-admin.php?clubID='.$club_id.'">';
                        }
                        
                        elseif(isset($_GET['add']) && $_GET['add']=="athlètes"){
                            echo "<h1>Administration des athlètes du club</h1>";
                            echo '<form method="post" id="ajouter_athlète" action="/content/modules/update_club_admin.php?clubID='.$club->getId().'"><table>
                            <tr class="row">
                                <td class="col-md-3" align="left" ><label>Liens champions </label></td>
                                <td class="col-md-7" align="left">
                                <div id="autoCompChamp1">
                                    <input autocomplete="off" data-rule-required="true" data-msg-required="Ce champ est obligatoire"  type="text" id="temp1" value="" class="update_athlète_input" />
                                    <div id="autocomp1" style="display:none;" class="autocomp"></div>
                                    <input style="display:none;" id="champion1" name="Champion_id" type="text" value="" />
                                    <div id="result" style="display: inline;"></div>
                                </div>
                                </td><td class="col-md-2"></td>
                            </tr>
                            <tr class="row"><td class="col-md-3" align="left">&nbsp;</td><td class="pull-right"><input type="submit" name="ajout_athlète" class="btn_custom" value="Ajouter"/></td></tr>
                            </form></table></div>';
                            // echo '<br/><a href="club-admin.php?clubID='.$clubID.'">Retour à la fiche d\'administration</a>';
                            
                        }
                        else{
                            ?>


                        <form action="/content/modules/update_club_admin.php?clubID=<?php echo $club->getId(); ?>"
                            method="post">

                            <table style="font-size: 0.9em;">
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="responsable">Responsable : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" align="left" class="update_athlète_input" required
                                            name="responsable" id="responsable"
                                            value="<?php echo $club->getResponsable(); ?>" />
                                    </td>
                                    <td class="col-md-2"></td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="telephone">Téléphone : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" class="update_athlète_input"
                                            required name="telephone" id="telephone"
                                            value="<?php echo $club->getTelephone(); ?>" />
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="site">Site : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="site" id="site" required
                                            value="<?php echo $club->getSite(); ?>" />
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="description">Description du site : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <textarea name="description" class="update_athlète_input" id="description"
                                            cols="50" rows="4"><?php echo $club->getDescription(); ?></textarea>
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="email">Email : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="email" required id="email"
                                            value="<?php echo $club->getMel(); ?>" />
                                    </td>

                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="adresse">Adresse : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="adresse" required
                                            id="adresse" value="<?php echo $club->getAdresse(); ?>" />
                                    </td>

                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="cp">Code Postal : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="cp" id="cp" required
                                            value="<?php echo $club->getCP(); ?>" />
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="ville">Ville : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="ville" id="ville" required
                                            value="<?php echo $club->getVille(); ?>" />
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="TokuiWaza">Pays : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <select name="pays" class="update_athlète_input" id="pays" required>
                                            <?php
                                                 foreach($paysNomTab as $id => $nom)
                                                    echo ($id==$club->getPays())?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
                                                ?>

                                        </select>
                                    </td>
                                </tr>
                                <input type="hidden" id="gcoo1" name="gcoo1" value="" /><input type="hidden" id="gcoo2"
                                    name="gcoo2" value="" /><input type="hidden" id="gaddress" name="gaddress"
                                    value="" />

                                <tr class="row">
                                    <td class="col-md-3" align="left"></td>
                                    <td class="pull-right">
                                        <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                        <button type="submit" name="edit_club_admin"
                                            class="btn_custom">Modifier</button>
                                    </td>
                                </tr>
                            </table>
                        </form>


                    </div>
                    <?php if (sizeof($horaire)>0) {
  ?>
                    <!-- Bootstrap Core CSS -->
                    <h1>Les horaires des cours - [<?php echo $club->getClub(); ?>]</h1>
                    <div class="col-sm-12">
                        <form action="/content/modules/update_club_admin.php?clubID=<?php echo $club->getId(); ?>"
                            method="post">
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="horaires_club">
                                    <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Jour</th>
                                            <th>Debut</th>
                                            <th>Fin</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php foreach($horaire as $cour) { ?>

                                        <tr class="odd gradeX">
                                            <td><?php echo $cour->getNum_cours(); ?></td>
                                            <td><?php echo $cour->getJour(); ?></td>
                                            <td><?php echo $cour->getH_deb(); ?></td>
                                            <td><?php echo $cour->getH_fin(); ?></td>
                                            <td><?php echo $cour->getDesc(); ?></td>
                                            <td><input type="checkbox" name="h_sup[]"
                                                    value="<?php echo $cour->getId();?>" /></td>

                                        </tr>

                                        <?php } ?>


                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" name="ajouter_horaire_club" class="btn_custom">Ajouter des
                                horaires</button>
                            <button type="submit" name="supprimer_horaire_club" class="btn_custom">Supprimer</button>


                        </form>
                        <hr class="hr_customized" /><br />
                    </div>

                    <?php } ?>



                    <?php 

if (sizeof($athlètes)>0) {
  ?>
                    <!-- Bootstrap Core CSS -->


                    <h1>Les athlètes - [<?php echo $club->getClub(); ?>]</h1>
                    <div class="panel-body">
                        <form action="/content/modules/update_club_admin.php?clubID=<?php echo $club->getId(); ?>"
                            method="post">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="athlètes_club">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php foreach($athlètes as $athlète) { ?>

                                        <tr class="odd gradeX">
                                            <td class="col-sm-10"><?php echo $athlète->getNom_athlète(); ?></td>
                                            <td class="col-sm-2"><input type="checkbox" name="j_sup[]"
                                                    value="<?php echo $athlète->getId_club_marathon();?>" /></td>

                                        </tr>


                                        <?php } ?>


                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" name="ajouter_athlète_club" class="btn_custom">Ajouter des
                                juokas</button>
                            <button type="submit" name="supprimer_athlète_club" class="btn_custom">Supprimer</button>
                        </form>
                    </div>

                    <?php }
    } ?>





                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
    <dd class="facebook">
        <div class="fb-page"
        data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
        data-width="310"
        data-hide-cover="false"
        data-show-facepile="true">
    </div>
</dd> -->
                <br>

            </aside>
        </div>

    </div> <!-- End container page-content -->

    <?php include('footer.inc.php'); ?>

    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="../../tab/jquery.dataTables.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>
    <script type="text/javascript">
    $('#ajouter_horaire').validate();
    $('#ajouter_athlète').validate();
    </script>

    <script>
    $(document).ready(function() {
        $('#horaires_club').dataTable();
        $('#athlètes_club').dataTable();

    });
    </script>



    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
    /*
     (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
     function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
     e=o.createElement(i);r=o.getElementsByTagName(i)[0];
     e.src='https://www.google-analytics.com/analytics.js';
     r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
     ga('create','UA-XXXXX-X','auto');ga('send','pageview');
     */
    </script>
    <!--FaceBook-->
    <script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <!--Google+-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>