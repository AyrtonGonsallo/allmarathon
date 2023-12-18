<?php

 $uri = array("/contact.html","/formulaire-inscription.php","/partenaires.php","/sites-partenaires.php","/bons-plans-marathon.html","/formulaire-calendrier.php");
 if (!isset($bdd)) {

    require_once '../../database/connexion.php';

 try{

        $req1 = $bdd->prepare("SELECT * FROM bannieremobile where actif = 'actif' ORDER BY RAND() LIMIT 1");

        $req1->execute();

        $pub_mobile = $req1->fetch(PDO::FETCH_ASSOC);
    }

    catch(Exception $e)

    {
        die('Erreur : ' . $e->getMessage());
    }

}

?>
<style>
/*google*/
    .google .btn{
      display: flex;
      justify-content: center;
      padding: 0px 26px;
    }
    .google a{
      all: unset;
      cursor: pointer;
      padding: 5px;
      display: flex;
      width: 250px;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      background-color: #f9f9f9;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: 3px;
    }
    .google a:hover{
      background-color: #ffffff;
    }
    .google img{
      width: 34px;
      margin-right: 5px;
    
    }
    /*facebook*/
    .facebook .btn{
      display: flex;
      justify-content: center;
      padding: 0px 26px;
    }
    .facebook a{
      all: unset;
      cursor: pointer;
      padding: 5px;
      display: flex;
      width: 250px;
      color: white;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    background-color: #1977f3;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: 3px;
    }
    .facebook a:hover{
      background-color: #1977f3;
    }
    .facebook img{
      width: 34px;
      margin-right: 5px;
    
    }
  </style>
<link rel="stylesheet" href="/css/authentification.css">
<link rel="alternate" type="application/rss+xml" title="allmarathon.fr - RSS feed" href="https://allmarathon.fr/flux-rss.xml" />
<script>
{
    const load = () => {
        document.querySelectorAll("script[data-type='lazy']").forEach(el => el.setAttribute("src", el.getAttribute("ddata-src")));
        document.querySelectorAll("script[data-type='lazy']").forEach(el => console.log(el.getAttribute("ddata-src")+" chargé en différé"));
        
    }
    const timer = setTimeout(load, 15000);
    const trigger = () => {
        load();
        clearTimeout(timer);
    }
    const events = ["mouseover","keydown","touchmove","touchstart"];
    events.forEach(e => window.addEventListener(e, trigger, {passive: true, once: true}));
}
</script>
<script ddata-src='https://www.google.com/recaptcha/api.js'  data-type='lazy'></script>
<header class="header">
    <?php

        if($user_session!=''){

            $href_modal="/membre-profil.php";

            $target_modal="";

            $signin_compte='<span class="user-connect"><i class="fa fa-user user_compte"></i></span>';

        }

        else{

            $href_modal="#";

            $target_modal='data-target="#SigninModal"';

            $signin_compte="SIGN IN";

        }

    ?>
    <nav class="header--nav">
        <ul>

            <li class="logo">

                <a href="/" class="header--nav--link header--logo"><img src="/images/allmarathon.png" 

                        alt="logo"></a>

            </li>

            <li class="menu-item-categories">



                <button id="hamburger-menu" data-toggle="ham-navigation" aria-label="hamburger-menu" class="hamburger-menu-button"><span

                        class="hamburger-menu-button-open"></span></button>

                <ul class="nav-category">







                    <div class="formobile">

                        <li>

                            <a href=<?php echo $href_modal;?> data-toggle="modal" <?php echo $target_modal;?> class=" ">

                                <div class="header-signin"><?php echo $signin_compte; ?></div><i class="fa fa-user"></i>

                                <span

                                    class="tag_name_m"><?php echo ($user_session!='') ? 'Mon Compte':'Connexion'?></span>

                            </a>

                        </li>

                        <li class="menu-item-search formobile ">

                            <a data-category=" Header_Links" id="search_1" data-action="Search Click"

                                data-label="Search" href="#" class="header--nav--link header--loop">
                                
                                <i class="fa fa-search" title="Search"></i>

                                <span class="tag_name_m">Chercher</span>

                            </a>

                            <form method="post" action="/resultats-recherche.html" 

                                class="navbar-form navbar-left navbar-custom">

                                <a data-category="Header_Links" id="search_2" data-action="Search Click"

                                    data-label="Search" href="#" class="header--nav--link header--loop">

                                    <i class="fa fa-search" title="Search"></i> 

                                </a>

                                <div class="form-group form-group-search">

                                    <input type="text" name="recherche_glob" class="form-control search_header"

                                        placeholder="">

                                </div>



                            </form>

                        </li>
                        
                    </div>

                    <li class="visible-xs"> <a data-category="Header_Links" data-action="Top Menu Click"

                            data-label="DECONNEXION" href="/" class="header--nav--link pull-left1">Accueil</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="ACTUALITE"

                            href="/actualites-marathon.html" class="header--nav--link pull-right1">actualités</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="MARATHON"

                            href="/calendrier-agenda-marathons.html" class="header--nav--link pull-left1">Marathons</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RESULTATS"

                            href="/resultats-marathon.html" class="header--nav--link pull-left1">Résultats</a></li>

                    

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="Athlètes"

                            href="/cv-champions-de-marathon.html" class="header--nav--link pull-left1">Athlètes</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="VIDEOS"

                            href="/videos-de-marathon.html" class="header--nav--link pull-right1">Vidéos</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RECORDS"

                    href="/records-marathon-masculins.html" class="header--nav--link pull-right1">Records</a></li>
                    
                </ul>

            </li>
           
            <li class="menu-item-search ">

                <a data-category="Header_Links" id="search_1" data-action="Search Click" data-label="Search" 

                  href="#"  class="header--nav--link header--loop">

                    <i class="fa fa-search" title="Search"></i> 

                </a>

                <form method="post" action="/resultats-recherche.html" class="navbar-form navbar-left navbar-custom" id="recherche-form">

                    <a data-category="Header_Links" id="search_2" data-action="Search Click" data-label="Search" href="javascript:{}" onclick="document.getElementById('recherche-form').submit(); return false;"

                         class="header--nav--link header--loop">

                        <i class="fa fa-search" title="Search"></i> 

                    </a>

                    <div class="form-group form-group-search">

                        <input type="text" name="recherche_glob" class="form-control search_header" placeholder="">

                    </div>
                </form>
            </li>
            
            



            <li class="header--pull pull-right">

                <ul>

                    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="DIRECT"

                            href="https://www.facebook.com/France1D/" target="_blank"

                            class="header--nav--link pas_direct">DIRECT</a></li>

                    <li class="header--edition-dropdown">

                        <a id="<?php echo ($user_session!='')?'connected_user':''?>" href=<?php echo $href_modal;?>

                            data-toggle="modal" <?php echo $target_modal;?>

                            class="header--nav--link header--nav--border signin visible-lg visible-md visible-sm">

                            <div class="header-signin"><?php echo $signin_compte; ?></div><i class="fa fa-user"></i>

                        </a>


                        <ul class="dropdown_menu_user">

                            <li><a class="link" href="/membre-profil.php"><i class="fa fa-user"></i> Mon compte</a></li>

                            <li><a class="link" href="/membre-profil.php"><i class="fa fa-comment"></i> Commentaires</a>

                            </li>

                            <li><a class="link" href="/logout.php"><i class="fa fa-sign-out"></i> Déconnexion</a></li>

                        </ul>


                        <div class="modal fade" id="SigninModal" tabindex="-1" role="dialog"

                            aria-labelledby="SigninModal" aria-hidden="true">

                            <div class="auth registration">



                                <div class="auth--header">

                                   Connectez-vous

                                </div>

                                <div class="auth--body">

                                    <?php if($erreur_auth!='') echo '<div class="col-md-12 fail">'.$erreur_auth.'</div> <br> <br>';?>



                                    <form name="signup" id="form" method="post" class="clearfix signup-form relative "

                                        action="/content/modules/login.php">

                                        <input type="hidden" name="referer" id="referer" value="" />

                                        <input type="hidden" name="option" id="option" value="" />

                                        <div class="col-md-12 form-wrapper">

                                            <label for="email">Nom d'utilisateur </label>

                                            <input type="text" class="input_auth" name="name_user" id="name_user"

                                                value="" required />

                                        </div>

                                        <div class="col-md-12 form-wrapper" style="margin-top: 20px !important;">

                                            <label for="password">Mot de passe</label>

                                            <input type="password" class="input_auth" name="password" id="password_auth"

                                                value="" required />

                                  

                                            <a onclick="forgot();" data-toggle="modal" data-target="#forgot"

                                                class="link forgot">Mot de passe oublié?</a>

                                          

                                        </div>

                                        <div class="col-md-6 form-wrapper button-modal">

                                            <input type="submit" name="submit" id="submit" value="Se connecter"

                                                class="btn_auth button__red" />

                                        </div>

                                        
                                        <div class="button-modal separateur" style="width:fit-content">-ou-</div>
                                        <?php
                                            require('./config.php');
                                            # the createAuthUrl() method generates the login URL.
                                            $login_url = $client->createAuthUrl();
                                        ?>
                                        <div class="col-sm-6 google button-social">
                                            <div class="btn">
                                            <a href="<?= $login_url ?>"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login with Google</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 facebook button-social">
                                            <div class="btn">
                                            <?php
                                            require('./index2.php');
                                            # the createAuthUrl() method generates the login URL.
                                            $loginUrl = $helper->getLoginUrl('https://tutsmake.com/Demos/php-facebook', $permissions);
                                        ?>
                                            <a href="<?= $loginUrl?>"><img src="/images/pictos/face.png" alt="Facebook Logo"> Continue with Facebook</a>
                                            </div>
                                        </div>

                                        <?php echo '<input type="hidden" name="previous_url" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'">';?>

                                    </form>
                                    
                                </div>

                                <div class="auth--footer1">

                                    Pas encore inscrit ? 

                                </div>
                                <div class="auth--footer">

                                   <a href="/formulaire-inscription.php"

                                        class="modal-last-link">inscrivez-vous </a>

                                </div>
                                   

                            </div>

                        </div>

                        <div class="modal fade" id="forgot" tabindex="-1" role="dialog" aria-labelledby="forgotModal"

                            aria-hidden="true">

                            <div class="auth registration">

                                <div class="auth--header">

                                    Mot de passe oublié

                                </div>

                                <div style="padding: 0px 60px;" class="auth--body forgot_18">



                                    <form onsubmit="blockSubmit()" action='/forgotPass.php'

                                        class="clearfix signup-form relative" method='post'>

                                        <div id='formulaireligne' class='texte4 form-group'>

                                            <div class='left' style='padding: 5px 0;'><strong>E-mail *</strong></div>

                                            <div class='right'><input type='text' class="form-control" name='mail'

                                                    required></div>

                                            <div style='clear:both'></div>

                                        </div>

                                        <div id='formulaireligne' class='texte4 form-group'>

                                            <?php if(!in_array($_SERVER['REQUEST_URI'], $uri)){ ?>

                                            <div class='g-recaptcha left'

                                                data-sitekey='6LdcITUpAAAAAJNe_-kxs-4q4Xy9_HrQnk3FvTkx'></div>

                                            <?php } ?>

                                            <div class='right' style='padding: 5px 0;'> <input id="submitForgot"

                                                    type='submit' class="btn_auth button__red" name='Envoyer'

                                                    value='Envoyer' /> </div>

                                            <div style='clear:both'></div>

                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="none" id="myaccount-box">

                            <a class="header--nav--link header--nav--border account-dropdown-wrapper" href=""

                                data-name="" data-sub=""> MY ACCOUNT<i class="fa fa-user"></i> </a>

                            <ul class="account-dropdown">

                                <li> <a href="#">My account</a></li>

                                <li> <a href="#">Subscription settings</a></li>

                                <li> <a href="#">Contact support</a></li>

                                <li> <a href="#">Sign out</a></li>

                            </ul>

                        </div>

                        <script>

                        var sion_ok = sion_ok || 0;

                        sion_ok = 0;

                        </script>

                    </li>
                </ul>

            </li>

        </ul>
    </nav>
    <form action="/resultats-recherche.html" method="post" accept-charset="UTF-8" class="d--none" id="frm-search">

        <label for="header-search" class="d--none"></label>

        <input id="header-search" placeholder="Recherche" type="text" name="recherche_glob" value="">

        <input type="submit" name="op" value="Search"><!--  A revoir! -->
    </form>
</header>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js" ><\/script>')
</script>
<script src="/js/bootstrap.min.js" ></script>
<script type="text/javascript">
$('.bootpopup').click(function() {

    var frametarget = $(this).attr('href');

    var targetmodal = $(this).attr('target');

    if (targetmodal == undefined) {

        targetmodal = '#popupModal';

    } else {

        targetmodal = '#' + targetmodal;

    }

    if ($(this).attr('title') != undefined) {

        $(targetmodal + ' .modal-header h3').html($(this).attr('title'));

        $(targetmodal + ' .modal-header').show();

    } else {

        $(targetmodal + ' .modal-header h3').html('');

        $(targetmodal + ' .modal-header').hide();

    }

    $(targetmodal).on('show', function() {

        $('iframe').attr("src", frametarget);

    });

    $(targetmodal).modal({

        show: true

    });
    return false;
});

</script>

<script>

function forgot() {

    $('#SigninModal').trigger("click");

};

function blockSubmit() {

    $("#submitForgot").prop("disabled", true);

    console.log("blocked");

}

function hideWhenClickedOnBody(elm, ns) {

    if (elm.is(':visible')) {

        $(document).on('click.' + ns, function() {
            elm.hide();
            $(document).off('click.' + ns);
        });
    } else {
        $(document).off('click.' + ns);
    }
}
$('.menu-item-search > a.header--nav--link').on('click', function(e) {
    e.preventDefault();
    var elm = $('#frm-search');
    elm.slideToggle(100, function() {
        hideWhenClickedOnBody(elm, 'searchdd');
    });
    $("#header-search").focus();

});

$('li.menu-item-categories .fa-bars, li.menu-item-categories .hamburger-menu-button').on('click', function(e) {
    var elm = $(this).parent().find('.nav-category');
    elm.slideToggle(100, function() {
        hideWhenClickedOnBody(elm, 'menudd')
    });

});
$(document).ready(function() {
    if (window.matchMedia("(min-width: 1121px)").matches) {
        $('header #connected_user').hover(function() {
            $('header .dropdown_menu_user').animate({
                opacity: 'show'
            }, 'slow');
        });
        $(".dropdown_menu_user").mouseleave(function() {
            $('header .dropdown_menu_user').animate({
                opacity: 'hide'
            }, 'fast');
        });
        $("body, header").click(function() {
            $('header .dropdown_menu_user').animate({
                opacity: 'hide'
            }, 'fast');
        });
    }
    if (window.matchMedia("(max-width: 1120px)").matches) {
        var clicksuser = 0;
        $('header #connected_user').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (clicksuser % 2 == 0) {

                $('header .dropdown_menu_user').animate({

                    opacity: 'show'

                }, 'slow');

            } else {

                $('header .dropdown_menu_user').animate({

                    opacity: 'hide'

                }, 'fast');

            }

            clicksuser++;

        });

        $("body, header").click(function() {



            $('header .dropdown_menu_user').animate({

                opacity: 'hide'

            }, 'fast');

            clicksuser = 0;

        });

    }
});

</script>
<script ddata-src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7261110840191217" data-type='lazy'
     crossorigin="anonymous"></script>
