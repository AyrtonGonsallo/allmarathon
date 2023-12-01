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
include("../classes/club_admin_externe.php");
include("../classes/user.php");
include("../classes/pays.php");
include("../classes/club_horaires.php");
include("../classes/club_athlètes.php");

$club_athlètes=new club_athlètes();
$athlètes=$club_athlètes->getAllByClubID($clubID)['donnees'];

$club_horaires=new club_horaires();
$horaire=$club_horaires->getAllByClubID($clubID)['donnees'];

$pays=new pays();

$user=new user();

$demandeEnCours ="";
$userAdmin="";

$club_admin_externe = new club_admin_externe();
if(isset($user_id)){
    $demandeEnCours = $club_admin_externe->getDemandesByUser($user_id,$clubID,0)['donnees'];
    $userAdmin=($club_admin_externe->getDemandesByUser($user_id,$clubID,1)['donnees']) ? true: false;
}

$admins=$club_admin_externe->getClubAdmins($clubID)['donnees'];

$cl=new club();

$club=$cl->getClubById($clubID)['donnees'];

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
    <title>Trouver un club de marathon</title>
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <meta name="description" content="">
    
    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <link href="../../tab/sb-admin-2.css" rel="stylesheet">

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>


</head>

<body>


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes">
        <div class="row banniere1"> <?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">

                    <div class="col-sm-12">

                        <h1><?php echo $club->getClub(); 
                                if ($demandeEnCours) { echo "<span class='demandeEnCours'>(Votre demande est en attente de validation.)</span>";}
                                elseif ($userAdmin)  {
                                    echo '<a href="/club-admin-'.$clubID.'.html" class="btn new_btn" >Modifier la fiche '.$club->getClub().'</a>';
                                }else{
                                    echo '<a href="#" id="new_admin" class="btn new_btn"> Devenir administrateur de la fiche du  '.$club->getClub().'</a>';
                                } 
                                ?>
                        </h1>
                        <hr class="hr_customized">
                        <?php if((sizeof($admins)) != 0 && !$userAdmin){
                                $liste="";
                                foreach( $admins as $admin){
                                    $ad=$user->getUserById($admin->getUser_id())['donnees'];
                                    $liste.= ($ad) ? $ad->getUsername().' - ' : "";
                                }
                                echo ($liste!="") ? "Cette fiche est administrée par : ".substr_replace($liste, "", -2)."<br><br>" :"";

                            }?>
                    </div>
                    <div class="col-sm-12">
                        <?php if($club->getGaddress() != ""){?>

                        <script>
                        function initialize() {
                            var myLatlng = new google.maps.LatLng(<?php echo $club->getGcoo1() ?>,
                                <?php echo $club->getGcoo2() ?>);
                            var mapOptions = {
                                zoom: 10,
                                center: myLatlng
                            };

                            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                            var contentString =
                                '<table><tbody><tr><td width="200px" valign="top" align="left"><br><strong>Club : </strong><?php echo $club->getClub() ?><br><strong>Adresse : </strong><?php echo $club->getGaddress() ?><br><strong>Telephone : </strong><?php echo $club->getTelephone() ?><br></td><td width="200px" valign="top" align="right"><a target="blank" href="http://www.fjepmeyzieumarathon.fr/"><img border="0" alt="apercu" src="http://www.apercite.fr/api/apercite/240x180/oui/oui/<?php echo $club->getSite() ?>"></a><br></td></tr></tbody></table>';

                            var infowindow = new google.maps.InfoWindow({
                                content: contentString
                            });

                            var marker = new google.maps.Marker({
                                position: myLatlng,
                                map: map,
                                title: '<?php echo $club->getClub() ?>'
                            });
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open(map, marker);
                            });
                        }

                        google.maps.event.addDomListener(window, 'load', initialize);
                        </script>
                        <div id="map-canvas" style="width:642px; height:350px;"></div>

                        <?php } ?>
                    </div>
                    <div class="col-sm-12">
                        <br>
                        <?php 
                           echo ($club->getResponsable() != "") ? "<strong>Responsable : </strong>".$club->getResponsable()."<br />" : "";
                           echo ($club->getTelephone() != "") ? "<strong>Téléphone : </strong>".$club->getTelephone()."<br/>" : "";
                           echo ($club->getMel() != "") ? '<strong>Email : </strong><a href="mailto:'.$club->getMel().'" class="link_customized">'.$club->getMel().'</a><br />': "";
                           echo ($club->getAdresse() != "") ? "<strong>Adresse : </strong>".$club->getAdresse()."<br />":"";
                           if($club->getVille() != ""){
                                echo "<strong>Ville : </strong>".$club->getVille();
                                    if($club->getCP() != ""){
                                        echo "(".$club->getCP().")";
                                        } 
                                    // echo ' - '.$club->getPays().'<br/>';
                                    echo ' - '.$pays->getFlagByAbreviation($club->getPays())["donnees"]['NomPays'].'<br/>';
                                    }
                           ?>
                        <hr class="hr_customized" />
                        <?php echo ($club->getSite() != "") ? '<a href="'.$club->getSite().'" target="blank" class="link_customized">'.$club->getSite().'</a><br/>' : "";
                            echo ($club->getDescription() != "") ? $club->getDescription()."<br>" : "<br>";
                            echo ($club->getSite() != "") ? '<a href="'.$club->getSite().'" target="blank" ><img src="http://www.apercite.fr/api/apercite/320x240/oui/oui/'.$club->getSite().'" width="336" height="248" border="0" alt="apercu" /></a><br /><br />' :"";?>
                        <hr class="hr_customized" /><br />

                    </div>

                    <?php if (sizeof($horaire)>0) {
  ?>
                    <!-- Bootstrap Core CSS -->
                    <h1>Les horaires des cours - [<?php echo $club->getClub(); ?>]</h1>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="horaires_club">
                                <thead>
                                    <tr>
                                        <th>Numero</th>
                                        <th>Jour</th>
                                        <th>Debut</th>
                                        <th>Fin</th>
                                        <th>Description</th>
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

                                    </tr>

                                    <?php } ?>


                                </tbody>
                            </table>
                        </div>
                        <hr class="hr_customized" /><br />
                    </div>

                    <?php } ?>



                    <?php 

if (sizeof($athlètes)>0) {
  ?>
                    <!-- Bootstrap Core CSS -->


                    <h1>Les athlètes - [<?php echo $club->getClub(); ?>]</h1>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="athlètes_club">
                                <thead>
                                    <tr>
                                        <th>Nom</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach($athlètes as $athlète) { ?>

                                    <tr class="odd gradeX">
                                        <td><?php echo $athlète->getNom_athlète(); ?></td>

                                    </tr>


                                    <?php } ?>


                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php } ?>





                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></p>
                <p class="ban ban_160-600"><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></p>
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

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>

    <?php
if($user_id==""){
 echo "<script type='text/javascript'>
            $('#new_admin').on('click',function(e){
                    $('#SigninModal').modal('show');});
            </script>";
    }
    else{
         echo "<script type='text/javascript'>
        $('#new_admin').on('click',function(e){
            document.location.href='/formulaire-administration-club-".$clubID.".html';
            
            });


                </script>";
    }
?>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>




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