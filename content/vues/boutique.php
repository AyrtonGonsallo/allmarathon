<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}

if(!empty($_SESSION['auth_error'])) {
   $erreur_auth=$_SESSION['auth_error'];
   unset($_SESSION['auth_error']);
}else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user_session=$_SESSION['user'];
$erreur_auth='';
}  else $user_session=''; 

if (isset($_POST['g-recaptcha-response']))
$response = $_POST['g-recaptcha-response'];
else
$response = "";
$Url = "https://www.google.com/recaptcha/api/siteverify";
$SecretKey = "6LdfeOISAAAAAEdzzgHzgm_pmZLLVimC-h8wTGYz";

$data = array('secret' => $SecretKey, 'response' => $response);

$ch = curl_init($Url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$verifyResponse = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($verifyResponse);


require '../../PHPMailerAutoload.php';
$emailStat =  false;
if ($responseData->success && isset($_POST["send"])) {//&& $resp->is_valid
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $body = "  <html><head><title>Nouveau message allmarathon - bons plans</title></head><body>
    Bonjour vous avez reçu un nouveau message de  " . $nom . ",<br>
    Email :   " . $email . ",<br>
    Message : <br>".$message.".<br>
     </body></html>";

    $phpmail = new PHPMailer; // create a new object
    $phpmail->IsSMTP(); // enable SMTP
    $phpmail->Host = "tls://smtp.gmail.com";
    $phpmail->Port = 587; // or 587
    $phpmail->SMTPSecure = 'tls';
    $phpmail->SMTPAuth = true; // authentication enabled
    $phpmail->Username = "alljudo.net@gmail.com";
    $phpmail->Password = "jujigatame";
    $phpmail->SetFrom("alljudo.net@gmail.com",$_POST["nom"]);
    //$phpmail->AddAddress('alljudo.net@gmail.com');
    $phpmail->AddAddress('ilanssari@ippon.fr');
    $phpmail->IsHTML(true);
    $phpmail->Subject = 'nouveau message de allmarathon bons plans';
    $phpmail->Body = $body;
    if ($phpmail->Send()) {
        $emailStat = true;
    }
    
}

include("../classes/pub.php");

require_once '../../database/connexion.php';

$normale = $bdd->prepare("SELECT * FROM article WHERE type = 'normale' ORDER BY id DESC limit 4");
$normale->execute();

$normale_counter = $bdd->prepare("SELECT count(*) as nbr FROM article WHERE type = 'normale' ORDER BY id DESC limit 4");
$normale_counter->execute();
$normale_counter  = $normale_counter->fetch(PDO::FETCH_ASSOC)['nbr'];

$excep = $bdd->prepare("SELECT * FROM article WHERE type = 'excep' ORDER BY id DESC limit 4");
$excep->execute();

$excep_counter = $bdd->prepare("SELECT count(*) as nbr FROM article WHERE type = 'excep' ORDER BY id DESC limit 4");
$excep_counter->execute();
$excep_counter  = $excep_counter->fetch(PDO::FETCH_ASSOC)['nbr'];

$perma = $bdd->prepare("SELECT * FROM article WHERE type = 'perma' ORDER BY id DESC limit 4");
$perma->execute();

$perma_counter = $bdd->prepare("SELECT count(*) as nbr FROM article WHERE type = 'perma' ORDER BY id DESC limit 4");
$perma_counter->execute();
$perma_counter  = $perma_counter->fetch(PDO::FETCH_ASSOC)['nbr'];


//$pub=new pub();
?>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Allmarathon - Bons Plans</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">

</head>
<body>

<?php include_once('nv_header-integrer.php'); ?>

    <section id="catalogueAllmarathon" class="container">
        <h1 style="font-size: 1.1em;font-weight: bold;text-transform: lowercase;">Bons Plans produits Marathon, Ventes Événementielles</h1>
        <div id="offersBlock" class="row no_margin">
            <?php if ( $excep_counter > 0) { ?>
            <h2> OFFRES EXCEPTIONNELLES </h2>
            <?php while ( $row  = $excep->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <a class="offreContainer" href="/bon-plan-marathon-<?php echo $row["id"]; ?>-<?php echo slugify($row["nom"]); ?>.html">
                <div class="imgProduct">
                    <img alt="" src="/images/articles/<?php echo $row["image_pre"]; ?>">
                </div>
                <div class="infoProduct">
                <h3 class="name"><?php echo $row["nom"]; ?></h3>
                        <h3 class="name" style="background: #ff0001;font-family: MuseoSans-900;color: #fff;padding: 2px;font-size: 1.2em;"><?php echo $row["offre"]; ?></h3> 
                    <div class="pricebloc">
                    <?php if($row["old_prix"] != 0){ ?>                        
                        <span class="pricebarred"><?php echo $row["old_prix"]; ?> €</span>
                         <?php } if($row["prix"] != 0) { ?>  
                        <span class="price"><?php echo $row["prix"]; ?> €</span>
                        <?php }?>  
                    </div>
                    
                </div>
                </a>
            </div>
            <?php }} ?>
        </div>
        <div id="offersBlock" class="row no_margin">
            <?php if ( $normale_counter > 0) { ?>
            <h2> NOUVELLES OFFRES</h2>
            <?php while ( $row  = $normale->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <a class="offreContainer" href="/bon-plan-marathon-<?php echo $row["id"]; ?>-<?php echo slugify($row["nom"]); ?>.html">
                    <div class="imgProduct">
                        <img alt="" src="/images/articles/<?php echo $row["image_pre"]; ?>">
                    </div>
                    <div class="infoProduct">
                        <h3 class="name"><?php echo $row["nom"]; ?></h3>
                        <h3 class="name" style="background: #ff0001;font-family: MuseoSans-900;color: #fff;padding: 2px;font-size: 1.2em;"><?php echo $row["offre"]; ?></h3> 
                        <div class="pricebloc">
                        
                            <?php if($row["old_prix"] != 0){ ?>                        
                           <span class="pricebarred"><?php echo $row["old_prix"]; ?> €</span>
                            <?php } if($row["prix"] != 0) { ?>  
                           <span class="price"><?php echo $row["prix"]; ?> €</span>
                           <?php }?>  
                        </div>
                        
                    </div>
                    </a>
                </div>
                <?php }} ?>
        </div>
        <div id="offersBlock" class="row no_margin">
        <?php if ( $perma_counter > 0) { ?>
            <h2> OFFRES PERMANENTES</h2>
            <?php while ( $row  = $perma->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <a class="offreContainer" href="/bon-plan-marathon-<?php echo $row["id"]; ?>-<?php echo slugify($row["nom"]); ?>.html">
                    <div class="imgProduct">
                        <img alt="" src="/images/articles/<?php echo $row["image_pre"]; ?>">
                    </div>
                    <div class="infoProduct">
                    <h3 class="name"><?php echo $row["nom"]; ?></h3>
                        <h3 class="name" style="background: #ff0001;font-family: MuseoSans-900;color: #fff;padding: 2px;font-size: 1.2em;"><?php echo $row["offre"]; ?></h3> 
                        <div class="pricebloc">
                        <?php if($row["old_prix"] != 0){ ?>                        
                            <span class="pricebarred"><?php echo $row["old_prix"]; ?> €</span>
                             <?php } if($row["prix"] != 0) { ?>  
                            <span class="price"><?php echo $row["prix"]; ?> €</span>
                            <?php }?>  
                        </div>
                        
                    </div>
                    </a>
                </div>
                <?php }} ?>
        </div>
    </section>

<?php include_once('footer.inc.php'); ?>
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-1833149-1', 'auto');
        ga('send', 'pageview');

      </script>




</body>
</html>

