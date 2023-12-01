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
if(isset($_GET['departement']))
    $departement = $_GET['departement'];

$pays = "";
if(isset($_GET['pays']))
    $pays = addslashes($_GET['pays']);

$search = "";
if(isset($_POST['search']))
    $search = addslashes($_POST['search']);

$no_sort = (!isset($_GET['pays']) && !isset($_POST['search']) )? true:false;



include("../classes/pub.php");
include("../classes/club.php");
include("../classes/pays.php");
include("../classes/departement.php");

$dept=new departement();
if($pays!="")
    {
        $depts=$dept->getClubDepartements($pays)['donnees'];
        foreach($depts as $d){ 
            $departements[$d['CP']] = $d['NomDepartement'];
        }
    }

$pays_class=new pays();
$liste_pays=$pays_class->getAllPaysWithClubs()['donnees'];
foreach($liste_pays as $p) {
    $paysNomTab[$p['Abreviation']]=$p['NomPays'];
}

$cl=new club();

if($search != ""){
    $all_clubs=$cl->getValidsClubsViaSearch($search)['donnees'];
}else if($pays != ""){
    if($departement == ""){
        $all_clubs=$cl->getValidsClubsViaPays($pays)['donnees'];
    }else{
        $all_clubs=$cl->getValidsClubsViaDepartement($pays,$departement)['donnees'];
    }
}else{
    $all_clubs=$cl->getValidsClubs()['donnees'];
}




$clubs = array();
foreach ($all_clubs as $club) {
    if($club->getGaddress() != ""){
            $latitude_array[]  = $club->getGcoo1();
            $longitude_array[] = $club->getGcoo2();
    }
        $clubs[] = $club;
}
if(!empty($latitude_array)){
    $minimal_latitude  = min ($latitude_array);
    $maximal_latitude  = max ($latitude_array);
    $minimal_longitude = min ($longitude_array);
    $maximal_longitude = max ($longitude_array);
    $central_latitude  = $minimal_latitude + ($maximal_latitude - $minimal_latitude) / 2;
    $central_longitude = $minimal_longitude + ($maximal_longitude - $minimal_longitude) / 2;

    $miles = (3958.75 * acos(sin($minimal_latitude / 57.2958) * sin($maximal_latitude / 57.2958) + cos($minimal_latitude / 57.2958) * cos($maximal_latitude / 57.2958) * cos($maximal_longitude / 57.2958 - $minimal_longitude / 57.2958)));

    switch ($miles)
    {
    case ($miles < 0.2):
    $zoom = 2;
    break;
    case ($miles < 0.5):
    $zoom = 3;
    break;
    case ($miles < 1):
    $zoom = 3;
    break;
    case ($miles < 2):
    $zoom = 4;
    break;
    case ($miles < 3):
    $zoom = 5;
    break;
    case ($miles < 7):
    $zoom = 6;
    break;
    case ($miles < 15):
    $zoom = 8;
    break;

    case ($miles < 50):
    $zoom = 9;
    break;

    case ($miles < 300):
    $zoom = 12;
    break;

    case ($miles < 600):
    $zoom = 14;
    break;
    default:
    $zoom = 16;
    break;
    }
}


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
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">


    <script type="text/javascript">
    function sortPays() {
        selected = document.getElementById('reroutage').selectedIndex;
        sort = document.getElementById('reroutage')[selected].value;
        window.location.href = '/contact-clubs-de-marathon-' + sort + '.html';
    }

    function sortDepartement() {
        selected = document.getElementById('reroutage2').selectedIndex;
        sort = document.getElementById('reroutage2')[selected].value;
        window.location.href = '/contact-clubs-de-marathon-<?php echo $pays; ?>-' + sort + '.html';
    }
    </script>

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes annuaire_contact">
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

                        <h1>TROUVER UN CLUB DE JUDO</h1>

                        <form action="/contact-clubs-de-marathon.html" method="post" role="form" class="form-inline"
                            style="margin-bottom: 5px;">

                            <div class="form-group" style="width: 93.3%">
                                <label class="sr-only" for="exampleInputEmail1">Recherche sur le nom du club</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="Recherche sur le nom du club" style="width:100%">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </form>

                        <!-- <form action="" method="post" role="form"> -->
                        <div class="form-group">
                            <label class="sr-only" for="reroutage">Filtrer par pays</label>
                            <select name="reroutage" class="form-control" id="reroutage" onchange="sortPays();">
                                <option value="">Filtrer par pays</option>
                                <?php foreach($paysNomTab as $id => $nom)
                                        echo ($id==$pays)?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
                                    ?>
                            </select>
                        </div>

                        <?php if(!empty($departements)) { ?>

                        <div id="form-group">
                            <div class="sr-only" for="reroutage2">Filtrer par département</div>
                            <select id="reroutage2" name="reroutage2" class="form-control"
                                onchange="sortDepartement();">
                                <option value="">Liste des départements</option>
                                <?php foreach($departements as $id => $nom)
                                            echo ($id==$departement)?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
                                        ?>
                            </select>
                        </div>
                        <?php } ?>
                        <!-- </form> -->

                        <?php if(!$no_sort){?>
                        <h1>Résultats de votre recherche : <?php echo count($all_clubs)  ?> clubs de marathon</h1>
                        <div id="result" class="res_club">
                            <?php
                        if(isset($all_clubs))
                            $clubs_a_afficher="";
                            foreach($all_clubs AS $cl) {
                                $clubs_a_afficher.= '<a href="/club-marathon-'.$cl->getID().'.html" >'.$cl->getClub().'</a> - ';
                            }
                            echo substr_replace($clubs_a_afficher, "", -2);
                        ?>

                        </div><br>
                        <?php } ?>

                        <?php if(!empty($latitude_array)){ ?>
                        <div class="col-sm-12 text-center">
                            <a href="/formulaire-contact.php" class="btn_custom text-center">Ajouter votre club</a>
                        </div>
                        <div class="col-sm-12">
                            <br />
                            <h1>CARTE DES CLUBS DE JUDO DANS LE MONDE</h1>
                            <div id="google_map" style="width: 642px;height: 400px;">
                                <script type="text/javascript"
                                    src="http://www.google.com/jsapi?key=ABQIAAAABViXIHiGZVTpberw8QWIXBTbXMVTG-lx21G4BZgI-Y3P0Y3vmRRi1XMgPDMsQqAVWCa84JecpI3lTA">
                                </script>
                                <script type="text/javascript">
                                google.load("maps", "2.x", {
                                    "other_params": "sensor=false"
                                });
                                var baseIcon;
                                var map;
                                // Call this function when the page has been loaded
                                function initialize() {
                                    map = new google.maps.Map2(document.getElementById("google_map"));
                                    map.setCenter(new google.maps.LatLng(<?php echo $central_latitude ?>,
                                        <?php echo $central_longitude ?>), <?php echo 18 - $zoom ?>);

                                    map.addControl(new GLargeMapControl3D());
                                    map.addControl(new GMapTypeControl());

                                    baseIcon = new GIcon(G_DEFAULT_ICON);
                                    baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
                                    baseIcon.iconSize = new GSize(20, 34);
                                    baseIcon.shadowSize = new GSize(37, 34);
                                    baseIcon.iconAnchor = new GPoint(9, 34);
                                    baseIcon.infoWindowAnchor = new GPoint(9, 2);
                                    <?php foreach($clubs as $club): if($club->getGaddress() != ""):?>

                                    // var point = new GLatLng('-21.2285098','55.3115843');
                                    var point = new GLatLng('<?php echo $club->getGcoo1(); ?>',
                                        '<?php echo $club->getGcoo2(); ?>');

                                    if (point !== false) {
                                        // map.addOverlay(createMarker(point, "Nom","A", "Adresse "));
                                        map.addOverlay(createMarker(point, "<?php echo $club->getClub()?>", "A",
                                            "<img src='http://www.apercite.fr/api/apercite/240x180/oui/oui/<?php echo $club->getSite(); ?>' alt='' style='float:left;margin-right:5px;margin-top:7px;' width='50' /><div style='float:left;width:167px;margin-top:7px;'><b><a href='/club-marathon-<?php echo $club->getID() ?>.html'><?php echo htmlentities(str_replace('\'','?',$club->getClub())) ?></a></b><br /><i><?php echo htmlentities(str_replace('\'','?',$club->getGaddress())) ?></i><br /><?php echo str_replace('\'','?',$club->getTelephone()) ?></div>"
                                            ));
                                        // map.addOverlay(createMarker(point, "<?php echo addslashes(str_replace('\'','&apos;', $club->getClub() ))?>","A", "<img src='http://www.apercite.fr/api/apercite/240x180/oui/oui/<?php echo $club->getSite(); ?>' alt='' style='float:left;margin-right:5px;margin-top:7px;' width='50' /><div style='float:left;width:167px;margin-top:7px;'><b><a href='/club-marathon-<?php echo $club->getID() ?>.html'><?php echo htmlentities(str_replace('\'','?',$club->getClub())) ?></a></b><br /><i><?php echo htmlentities(str_replace('\'','?',$club->getGaddress())) ?></i><br /><?php echo str_replace('\'','?',$club->getTelephone()) ?></div>"));

                                    }
                                    <?php endif; endforeach; ?>
                                }

                                function createMarker(point, titre, letter, html) {
                                    var letteredIcon = new GIcon(baseIcon);

                                    letteredIcon.image = "http://www.google.com/mapfiles/marker.png";
                                    markerOptions = {
                                        icon: letteredIcon
                                    };
                                    //alert("22");
                                    var marker = new GMarker(point, markerOptions);
                                    marker.value = titre;
                                    //alert("23");
                                    GEvent.addListener(marker, "click", function() {
                                        var myHtml = html;
                                        map.openInfoWindowHtml(point, myHtml);
                                    });
                                    return marker;
                                }
                                google.setOnLoadCallback(initialize);
                                </script>
                            </div>
                        </div>
                        <?php } ?>
                    </div>


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
                     data-width=""
                     data-adapt-container-width="true"
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
    <script src="../../js/easing.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
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