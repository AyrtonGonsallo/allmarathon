<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
$championID = (isset($_GET['championID'])) ? (int)$_GET['championID'] : exit('error');
//verifier que l'utilisateur n'a pas deja fais une demande

//require_once('Templates/_sessionCheck.php');
//header('Content-Type: text/html; charset=ISO-8859-1');
//$connected = (isset($_SESSION['user']) && $_SESSION['user']!="") ? true:false;

require_once '../database/connexion.php';



function changeDate($date)
{
    $day = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
    $month = array("janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre");
    $timestamp = mktime(substr($date, 11, 2), substr($date, 14, 2), 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
    return date("d/m/Y", $timestamp);
}

$query3 = sprintf("SELECT Nom FROM champions WHERE ID='%s'", $championID);

$adminMarathon = sprintf("SELECT * FROM champion_admin_externe WHERE champion_id='%s'", $championID);
//$result3 =  $mysqli->real_escape_string($query3);
//$champion = mysqli_fetch_array($result3);

$result = $mysqli->query($query3);
$champion = $result->fetch_array();

$resultAdmin = $mysqli->query($adminMarathon);
$adminMarathon = $resultAdmin->fetch_array();
//var_dump($adminMarathon);
include("../classes/pub.php");
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub300x60=$pub->getBanniere300_60("calendrier")['donnees'];
$pub300x250=$pub->getBanniere300_250("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];

if(!empty($_SESSION['auth_error'])) {
    $erreur_auth=$_SESSION['auth_error'];
    unset($_SESSION['auth_error']);
    }else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user_session=$_SESSION['user'];
$erreur_auth='';
}  else $user_session='';
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Formulaire d'administration</title>
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
    $(function() {
        $('#datepicker').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#datepicker').datepicker('option', {
            dateFormat: 'yy-mm-dd'
        });
        $('#datepicker2').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#datepicker2').datepicker('option', {
            dateFormat: 'yy-mm-dd'
        });
    });

    function hideSexe() {
        document.getElementById("mixte").checked = true;
        document.getElementById("sexeRow").style.display = "none"
    }

    function showSexe() {
        document.getElementById("sexeRow").style.display = ""
    }
    </script>
    <script type="text/javascript" src="fonction/identificationCom.js"></script>

</head>

<body>
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

                        <?php $nom = (isset($_POST['nom'])) ? $mysqli->real_escape_string($_POST['nom']) : "";
        $prenom = (isset($_POST['prenom'])) ? $mysqli->real_escape_string($_POST['prenom']) : "";
        $telephone = (isset($_POST['telephone'])) ? $mysqli->real_escape_string($_POST['telephone']) : "";
        $situation = (isset($_POST['situation'])) ? $mysqli->real_escape_string($_POST['situation']) : "";

        $erreur = "";
        if (isset($_POST['sub'])) {
            if ($nom == "")
                $erreur .= "Nom doit &ecirc;tre renseign&eacute;.<br />";
            if ($prenom == "")
                $erreur .= "Rensponsable doit &ecirc;tre renseign&eacute;.<br />";
            if ($telephone == "")
                $erreur .= "T&eacute;l&eacute;phone doit &ecirc;tre renseign&eacute;.<br />";

            if ($erreur == "") {
                $query2 = sprintf("INSERT INTO champion_admin_externe (date_mod,ip_mod,video,nom,prenom,telephone,situation, user_id, champion_id, ip_creation, date_creation) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                    , date('Y-m-d H:i:s')    
                    ,''
                    ,''    
                    , $nom
                    , $prenom
                    , $telephone
                    , $situation
                    , $_SESSION['user_id']
                    , $championID
                    , $_SERVER["REMOTE_ADDR"]
                    , date('Y-m-d H:i:s'));
                //$result2 =  $mysqli->real_escape_string($query2) or die(mysql_error());
                //$result = $mysqli->query($result2);
                //$championddd = $result->fetch_array();
                //var_dump($query2);
                //$result2   = mysqli_query($mysqli, $query2) or die(mysql_error());
                $result2   = mysqli_query($mysqli, $query2) or die(mysql_error());
            }
        }?>

                        <?php if (!empty($_SESSION)) {  ?>
                        <h1>Administrez la fiche de <?php echo $champion['Nom'] ?></h1><br />
                        <br />
                        <?php if (!isset($_POST['sub']) ) {
                        ?>

                        <form action="" method="post" onsubmit="">
                            <p id="pErreur" align="center">
                                <FONT color="red"><?php echo $erreur; ?></FONT>
                            </p>
                            <table class="texte1">
                                <tr>
                                    <td align="right"><label for="nom">Nom* : </label></td>
                                    <td><input type="text" name="nom" value="<?php echo $nom; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td align="right"><label for="prenom">Pr&eacute;nom* : </label></td>
                                    <td><input type="text" name="prenom" value="<?php echo $prenom; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td align="right"><label for="telephone">T&eacute;l&eacute;phone* : </label></td>
                                    <td><input type="text" name="telephone" value="<?php echo $telephone; ?>"
                                            required /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right"><label>Situation par
                                            raport &aacute; <?php echo $champion['Nom'] ?>* : </label>
                                        <label for="situation_athlète">Vous &ecirc;tes
                                            <?php echo $champion['Nom'] ?></label><input id="situation_athlète"
                                            type="radio" name="situation" value="athlète" checked="checked" /><br />
                                        <label for="situation_parent">parent</label><input id="situation_parent"
                                            type="radio" name="situation" value="parent" /><br />
                                        <label for="situation_ami">ami</label><input id="situation_ami" type="radio"
                                            name="situation" value="ami" /><br />
                                        <label for="situation_entraineur">entraineur</label><input
                                            id="situation_entraineur" type="radio" name="situation"
                                            value="entraineur" /><br />

                                    </td>
                                </tr>
                                <tr align="center">
                                    <td colspan="2"><input type="submit" name="sub" value="envoyer" /></td>
                                </tr>
                            </table>
                        </form>
                        <?php }   
                            if ($erreur == "" && isset($_POST['sub']) ) {
                                echo "<h3 style='color: green;'>Votre demande a été bien enregistré !</h3>";
                                echo "<a href='/'>page d'accueil</a>";
                            }
                        }
                    ?>

                    </div>
                </div>

            </div>
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