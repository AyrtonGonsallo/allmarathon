<?php
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

include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/grade.php");



$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];



?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Formulaire d'inscription</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/slider-pro.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>


    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes form-inscription">
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
                        <h1>FORMULAIRE D'INSCRIPTION À ALLMARATHON</h1>
                        <br />
                        <br />

                        <?php if (isset($_SESSION['msg_inscription'])) {
                        echo $_SESSION['msg_inscription'];
                        echo '<button class="btn btn-pager-mar-list" id="next-link" style="pointer-events: all; background-color: rgb(252, 182, 20); cursor: pointer;"> <a style="color:black;" href="formulaire-google.html">Associer un compte google</a></button>';
                        unset($_SESSION['msg_inscription']);
                    } ?>

                        <form action="/content/modules/verification-user.php" method="post" class="form-horizontal"
                            id="target">
                            <div class="form-group">
                                <label for="pseudo" class="col-sm-5">Pseudo *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="pseudo" required
                                        data-msg-required="Ce champ est obligatoire!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Mot_de_passe" class="col-sm-5">Mot de passe *</label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" name="mot_de_passe" id="password"
                                        required data-msg-required="Ce champ est obligatoire!">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmePW" class="col-sm-5">Confirmation du mot de passe *</label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" name="confirmePW" required
                                        data-msg-required="Ce champ est obligatoire!" data-rule-equalto="#password"
                                        data-msg-equalto="Les mots de passe doivent se correspondre !">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nom" class="col-sm-5">Nom *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="nom" required
                                        data-msg-required="Ce champ est obligatoire!" name="nom">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="prenom" class="col-sm-5">Prénom *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="prenom"
                                        data-msg-required="Ce champ est obligatoire!" required name="prenom">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-5">E-mail *</label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" id="email" required
                                        data-msg-email="Votre adresse e-mail n'est pas valide."
                                        data-msg-required="Ce champ est obligatoire!" name="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="naissance" class="col-sm-5">Date de naissance *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="date_naissance" name="date_naissance">
                                </div>
                            </div>

                            <div class="form-group">
                            <label for="naissance" class="col-sm-5">Sexe * </label>
                                <div class="col-sm-7">
                                    <input type="radio" name="Sexe"  value="M" /><span>homme</span><input type="radio" name="Sexe" value="F"  /><span >femme</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pays" class="col-sm-5">Pays </label>
                                <div class="col-sm-7">
                                    <select name="pays" id="pays" class="form-control">
                                        <?php
                                foreach ($liste_pays as $p) {
                                    $selected = ($p->getAbreviation()=='FRA') ? "selected" : "";
                                    echo '<option value="'.$p->getAbreviation().'"'.$selected.'>'.$p->getNomPays().'</option>';
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="LieuNaissance">Lieu de Naissance</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="LieuNaissance" id="LieuNaissance" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="Equipementier">Equipementier</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="Equipementier" id="Equipementier" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="lien_equip">Lien site équipementier</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="lien_equip" id="lien_equip" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="Instagram">Instagram</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="Instagram" id="Instagram" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="poids">poids</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="poids" id="poids" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="taille">taille</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="taille" id="taille" value="" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5" for="Facebook">Facebook</label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="Facebook" id="Facebook" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5" for="Bio">Bio</label>
                                <div class="col-sm-7">
                                    <textarea name="Bio" id="Bio" cols="50" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-11">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="recevoir_mail" id="recevoir_mail" value="1">
                                            Recevoir un mail ?
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="newsletter" id="newsletter" value="1">
                                            J'accepte de recevoir la newsletter hebdomadaire de allmarathon
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="offres" id="offres" value="1"> J'accepte de
                                            recevoir des offres commerciales des partenaires de allmarathon
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="g-recaptcha" id="g-recaptcha-response" name="g-recaptcha-response" data-sitekey="6Lc-wRsTAAAAAP8A0DXsgrJhboYw05SPxJlWYuRY"></div> -->
                            <div class="g-recaptcha" id="g-recaptcha"
                                data-sitekey="6LdcITUpAAAAAJNe_-kxs-4q4Xy9_HrQnk3FvTkx"></div><br>
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-11">
                                    <input value="Valider" type="submit" name="register_button" class="btn_custom" />
                                </div>
                            </div>
                        </form>


                    </div>
                </div>




            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"></p>
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width=""
                     data-adapt-container-width="true"
                     data-hide-cover="false"
                     data-show-facepile="true">
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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/jquery.jcarousel.min.js"></script>
    <script src="js/jquery.sliderPro.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/jquery.ui.totop.min.js"></script>
    <script src="js/herbyCookie.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>

    <script type="text/javascript">
    $('#target').validate();
    </script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript">
    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $("#date_naissance").datepicker();
    $('#date_naissance').datepicker('option', {
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
            'Octobre', 'Novembre', 'Décembre'
        ],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.',
            'Nov.', 'Déc.'
        ],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd'
    });
    </script>

    <!-- <script src="/content/scripts/identification_user.js" type="text/javascript"></script> -->


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
<style type="text/css">
label.error {
    color: red;
}
</style>