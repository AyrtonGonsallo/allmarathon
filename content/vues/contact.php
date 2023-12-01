<?php
//die();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
        $body = "  <html><head><title>Nouveau message allmarathon</title></head><body>
        Bonjour vous avez reçu un nouveau message de  " . $nom . ",<br>
        Email :   " . $email . ",<br>
        Message : <br>".$message.".<br>
         </body></html>";

        $phpmail = new PHPMailer; // create a new object
        $phpmail->IsSMTP(); // enable SMTP
        $to = "lmathieu@alljudo.net";
        $phpmail->Host = "authsmtp.securemail.pro";
        $phpmail->Port = 465; // or 587
        $phpmail->SMTPSecure = 'ssl';
        $phpmail->SMTPAuth = true; // authentication enabled
        $phpmail->Username = "martinodegaardnash@gmail.com";
        $phpmail->Password = "zJX8CvbTgAH7";
        $phpmail->SetFrom($email,$nom);
        $phpmail->AddAddress("lmathieu@alljudo.net");
        $phpmail->IsHTML(true);
        $phpmail->Subject = 'Nouveau message de allmarathon contact';
        $phpmail->Body = $body;
        if ($phpmail->Send()) {
            $emailStat = true;
        }
        
    }

include("../classes/pub.php");

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Nous contacter</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes">
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">

                    <div class="col-sm-12">

                        <h1>NOUS CONTACTER</h1>
                        <?php if ($emailStat){ ?>
                        <h4 style="color: green;">Votre message a été bien envoyé</h4>
                        <?php } ?>
                        <?php if(!$emailStat && isset($_POST['send']) ){  ?>
                        <h4 style="color: red;">une erreur est survenue</h4>
                        <?php } ?>
                        <br />
                        <br />
                        <form class="form-horizontal" method="POST">



                            <div class="form-group">
                                <label for="nom" class="col-sm-3 control-label">Nom Prénom *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nom" placeholder="Nom" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">E-mail *</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" placeholder="E-mail" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col-sm-3 control-label">Téléphone *</label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" name="phone" placeholder="Téléphone"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Message *</label>
                                <div class="col-sm-9">
                                    <!--<input type="number" class="form-control" id="message" placeholder="Message">-->
                                    <textarea name="message" id="" class="form-control" id="message" cols="30" rows="10"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                <input type="checkbox" id="accept" name="accept">   
                                En soumettant ce formulaire, j'accepte que les informations saisies soient exploitées dans le cadre strict de ma demande*                                 
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class='g-recaptcha left'
                                        data-sitekey='6Lf2-bwlAAAAAFZRxwBD28d2vUPvDT-MPfBEgZJx'></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" id="soumission" name="send" value="send"
                                        class="btn btn-warning btn-lg">Valider</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>




            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
                <dd class="facebook">
                    <div class="fb-page" data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                        data-width="310" data-hide-cover="false" data-show-facepile="true">
                    </div>
                </dd>
                <div class="marg_bot"></div> -->
            </aside>

        </div>

    </div> <!-- End container page-content -->

    <?php include_once('footer.inc.php'); ?>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
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
        $("#soumission"). attr("disabled", true);
        $("#accept").change(function() {
            if(this.checked) {
                $("#soumission"). attr("disabled", false);
            }else{
                $("#soumission"). attr("disabled", true);

            }
        });

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