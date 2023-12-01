<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
require_once("../database/connexion.php");
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
//$mysqli = new mysqli("localhost", "root", "", "c1ajnet_prod");

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
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];

?>
<?php
    $nom            = (isset($_POST['nom']))? $mysqli->real_escape_string($_POST['nom']):"";
    $prenom         = (isset($_POST['prenom']))? $mysqli->real_escape_string($_POST['prenom']):"";
    $telephone2	= (isset($_POST['telephone2']))? $mysqli->real_escape_string($_POST['telephone2']):"";
    $fonction       = (isset($_POST['fonction']))? $mysqli->real_escape_string($_POST['fonction']):"";
    $pays           = (isset($_POST['pays']))? $mysqli->real_escape_string($_POST['pays']):"";
    $club		= (isset($_POST['club']))? $mysqli->real_escape_string($_POST['club']):"";
    $responsable	= (isset($_POST['responsable']))? $mysqli->real_escape_string($_POST['responsable']):"";
    $telephone	= (isset($_POST['telephone']))? $mysqli->real_escape_string($_POST['telephone']):"";
    $mel		= (isset($_POST['mel']))? $mysqli->real_escape_string($_POST['mel']):"";
    $site           = (isset($_POST['site']))? $mysqli->real_escape_string($_POST['site']):"https://";
    $departement	= (isset($_POST['departement']))? $mysqli->real_escape_string($_POST['departement']):"";
    $description	= (isset($_POST['description']))? $mysqli->real_escape_string($_POST['description']):"";
    $ville          = (isset($_POST['ville']))? $mysqli->real_escape_string($_POST['ville']):"";
    $CP             = (isset($_POST['CP']))? $mysqli->real_escape_string($_POST['CP']):"";
    $adresse	= (isset($_POST['adresse']))? $mysqli->real_escape_string($_POST['adresse']):"";
    $erreur = "";
    
    if( isset($_POST['sub'])) {
    
    
        if($club == "")
            $erreur .= "Vous n'avez par renseign&eacute; le club.<br />";
        if($responsable == "")
            $erreur .= "Vous n'avez par renseign&eacute; le nom du responsable.<br />";
        if($telephone == "")
            $erreur .= "Vous n'avez par renseign&eacute; le t&eacute;l&eacute;phone personnel.<br />";
        if($mel == "")
            $erreur .= "Vous n'avez par renseign&eacute; l'adresse e-mail.<br />";
        if($nom == "")
            $erreur .= "Vous n'avez par renseign&eacute; le nom.<br />";
        if($prenom == "")
            $erreur .= "Vous n'avez par renseign&eacute; le pr&eacute;nom.<br />";
        if($fonction == "")
            $erreur .= "Vous n'avez par renseign&eacute; votre fonction au sein du club.<br />";
        if($telephone2 == "")
            $erreur .= "Vous n'avez par renseign&eacute; le t&eacute;l&eacute;phone du club.<br />";
            
    
        if($erreur == "" ) {
    
            $query2    = sprintf("INSERT INTO clubs (pays,club,responsable,telephone,mel,site,description, ville, CP, departement, adresse, gcoo1, gcoo2, gaddress) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                ,$pays
                ,$club
                ,$responsable
                ,$telephone
                ,$mel
                ,$site
                ,$description
                ,$ville
                ,$CP
                ,substr(trim($CP), 0, 2)
                ,$adresse
                ,$mysqli->real_escape_string($_POST['gcoo1'])
                ,$mysqli->real_escape_string($_POST['gcoo2'])
                ,$mysqli->real_escape_string($_POST['gaddress']));
            $result2   = mysqli_query($mysqli, $query2) or die(mysql_error());
            $clubID = $mysqli->insert_id;
            //echo $clubID;
            $query2    = sprintf("INSERT INTO club_admin_externe (nom,prenom,telephone,fonction, user_id, club_id, ip_creation, date_creation, ip_mod, date_mod) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                ,$nom
                ,$prenom
                ,$telephone2
                ,$fonction
                ,$_SESSION['user_id']
                ,$clubID
                ,$_SERVER["REMOTE_ADDR"]
                ,date('Y-m-d H:i:s')
                ,""
                ,date('Y-m-d H:i:s'));
               // echo $query2;
            $result2   = mysqli_query($mysqli, $query2) or die(mysql_error());
            
            //echo $_SESSION['user_id'];
        }
    }
    
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Formulaire de Contact</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">

    <style type="text/css">
    #result a {
        color: black;
        text-decoration: none;
    }

    #result a:hover {
        text-decoration: underline
    }
    </style>

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
    <script type="text/javascript">
    function geocode() {
        if ($("#ville").val() == "")
            return true;
        $.ajax({
            async: false,
            cache: false,
            type: "POST",
            data: ({
                adresse: $("#adresse").val().replace(new RegExp(" ", "g"), "+") + ',+' + $("#ville")
                    .val().replace(new RegExp(" ", "g"), "+") + ',+' + $("#pays option:selected").text()
            }),
            url: 'ajaxgeocoder.php',
            success: function(data) {
                var doc = typeof JSON != 'undefined' ? JSON.parse(data) : eval('(' + data + ')');
                if (doc.status == 'OK') {
                    $('#gcoo1').val(doc.results[0].geometry.location.lat);
                    $('#gcoo2').val(doc.results[0].geometry.location.lng);
                    $('#gaddress').val(doc.results[0].formatted_address);
                }

                return true;
            },
            error: function(xhr, textstatus, thrownError) {
                alert(textstatus);
                return false;
            }
        });

    }
    </script>

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
    <link rel="stylesheet" href="../../css/responsive.css">
</head>

<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->

    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content">
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
                        <?php 
                            if (!empty($_SESSION) && !isset($_POST['sub'])) { 
                        ?>

                        <h2>Référencez votre club</h2>
                        <form action="" method="post" onsubmit="return geocode()" class="form-horizontal">
                            <fieldset>

                                <!-- Form Name -->
                                <legend>Vous (informations privées)</legend>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Votre Nom*</label>
                                    <div class="col-md-4">
                                        <input id="nom" name="nom" type="text" placeholder="Nom"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Votre Prénom*</label>
                                    <div class="col-md-4">
                                        <input id="prenom" name="prenom" type="text" placeholder="Prénom"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Téléphone personnel*</label>
                                    <div class="col-md-4">
                                        <input id="telephone2" name="telephone2" type="text" placeholder="Téléphone"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Fonction au sein du
                                        club*</label>
                                    <div class="col-md-4">
                                        <input id="fonction" name="fonction" type="text" placeholder="Fonction"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>
                                <legend>Le club (information publiée sur le club)</legend>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Nom du club*</label>
                                    <div class="col-md-4">
                                        <input id="club" name="club" type="text" placeholder="Nom du club"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Nom du responsable* </label>
                                    <div class="col-md-4">
                                        <input id="responsable" name="responsable" type="text"
                                            placeholder="Nom du responsable" class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Téléphone du club*</label>
                                    <div class="col-md-4">
                                        <input id="telephone" name="telephone" type="text"
                                            placeholder="Téléphone du club" class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Adresse e-mail du
                                        club*</label>
                                    <div class="col-md-4">
                                        <input id="mel" name="mel" type="text" placeholder="Adresse e-mail du club"
                                            class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Site du club</label>
                                    <div class="col-md-4">
                                        <input id="site" name="site" type="text" placeholder="Site du club"
                                            class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textarea">Description du club</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput"> Adresse</label>
                                    <div class="col-md-5">
                                        <input id="adresse" name="adresse" type="text" placeholder=" Adresse"
                                            class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Search input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Code Postal</label>
                                    <div class="col-md-4">
                                        <input id="CP" name="CP" type="text" placeholder="Code Postal"
                                            class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Ville</label>
                                    <div class="col-md-4">
                                        <input id="ville" name="ville" type="text" placeholder="Ville"
                                            class="form-control input-md">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Pays*</label>
                                    <div class="col-md-4">
                                        <select name="pays">
                                            <?php foreach($paysNomTab as $id => $nom)
                                            echo ($pays==$id)?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="singlebutton"></label>
                                    <div class="col-md-4">
                                        <input type="hidden" id="gcoo1" name="gcoo1" value="" /><input type="hidden"
                                            id="gcoo2" name="gcoo2" value="" /><input type="hidden" id="gaddress"
                                            name="gaddress" value="" />
                                        <button type="submit" id="singlebutton" name="sub"
                                            class="btn btn-primary">Créer</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>

                        <?php 
                            }
                            if ($erreur == "" && isset($_POST['sub'])) {
                                echo "<h3 style='color: green;'>Votre demande a été bien enregistré !</h3>";
                                echo "<a href='/'>page d'accueil</a>";
                            }
                    ?>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
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
                    </dd>
                    <br> -->

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
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    <?php
                if (empty($_SESSION)) {
                   echo "$('#SigninModal').modal();";
                   echo "$('#SigninModal').on('hide.bs.modal', function(e){
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            return false; 
                                });";
                }
                ?>
    </script>
    <!--Google+-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>