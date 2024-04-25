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
      margin:20px 0px;
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
<link rel="alternate" type="application/rss+xml" title="allmarathon.fr - RSS feed" href="https://dev.allmarathon.fr/flux-rss.xml" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
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
        if (!class_exists('user')) { 
            include("../classes/user.php");
        }
        if (!class_exists('champion')) { 
            include("../classes/champion.php");
        }
        if (!class_exists('pays')) { 
            include("../classes/pays.php");
        }
        $pays=new pays();
        $liste_pays=$pays->getAll()['donnees'];
            $href_modal="/membre-profil.php";

            $target_modal="";

            $signin_compte='<span class="user-connect"><i class="fa fa-user user_compte"></i></span>';
            $champion=new champion();
            $user=new user();
            $profil = $user->getUserById($user_id)['donnees'];
            $added_res = $user->getAddedResults($profil->getUsername())['donnees'];
            $user_champ=($user_id)?$champion->getUserChampion($user_id)['donnees']:null;
        }

        else{

            $href_modal="#";

            $target_modal='data-target="#SigninModal"';

            $signin_compte='<span class="material-symbols-outlined">
            account_circle
            </span>';

        }

    ?>
    <nav class="header--nav">
        <ul>

            <li class="logo">

            <a href="/" class="header--nav--link header--logo"><img src="/images/logo-allmarathon.svg" alt="logo"></a>

            </li>

            <li class="menu-item-categories">



                <button id="hamburger-menu" data-toggle="ham-navigation" aria-label="hamburger-menu" class="hamburger-menu-button"><span

                        class="hamburger-menu-button-open"></span></button>

                <ul class="nav-category">







                    <div class="formobile">

                        <li>
                            <? if($user_id){?>
                                <label for="openSidebarMenu" class="sidebarIconToggle mobile">
                                    <?php echo $signin_compte; ?>
                                </label>
                                <span class="tag_name_m">Utilisateur</span>
                            <?}else{?>
                                <a href=<?php echo $href_modal;?> data-toggle="modal" <?php echo $target_modal;?> class=" ">

                                <span class="material-symbols-outlined">
                                    account_circle
                                    </span>

                                <span class="tag_name_m">se connecter</span>

                                </a>
                            <?}?>
                            
                            
                        </li>

                        <li class="menu-item-search formobile ">

                            <a data-category=" Header_Links" id="search_1" data-action="Search Click"

                                data-label="Search" href="#" class="header--nav--link header--loop">
                                
                                <span class="material-symbols-outlined">
                                search
                                </span>

                                <span class="tag_name_m">chercher</span>

                            </a>

                            <form method="post" action="/resultats-recherche.html" 

                                class="navbar-form navbar-left navbar-custom">

                               

                                <div class="form-group form-group-search">

                                    <input type="text" name="recherche_glob" class="form-control search_header"

                                        placeholder="">

                                </div>
                                <a data-category="Header_Links" id="search_2" data-action="Search Click"

                                    data-label="Search" href="#" class="header--nav--link header--loop">

                                    <span class="material-symbols-outlined">
search
</span>

                                </a>


                            </form>

                        </li>
                        
                    </div>

                    <li class="visible-xs"> <a data-category="Header_Links" data-action="Top Menu Click" id="home"

                            data-label="DECONNEXION" href="/" class="header--nav--link pull-left1">Accueil</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="ACTUALITE"

                            href="/actualites-marathon.html" class="header--nav--link pull-right1" id="news">actualités</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="MARATHON"

                            href="/calendrier-agenda-marathons.html" class="header--nav--link pull-left1" id="marathons">Marathons</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RESULTATS"

                            href="/resultats-marathon.html" class="header--nav--link pull-left1" id="resultats">Résultats</a></li>

                    

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="Athlètes"

                            href="/liste-des-athletes.html" class="header--nav--link pull-left1" id="athletes">Athlètes</a></li>

                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="VIDEOS"

                            href="/videos-de-marathon.html" class="header--nav--link pull-right1" id="videos">Vidéos</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RECORDS"

                    href="/records-marathon-masculins.html" class="header--nav--link pull-right1" id="records">Records</a></li>
                    
                </ul>

            </li>
           
            <li class="menu-item-search ">

                <a data-category="Header_Links" id="search_1" data-action="Search Click" data-label="Search" 

                  href="#"  class="header--nav--link header--loop">

                    <span class="material-symbols-outlined">
search
</span>

                </a>

                <form method="post" action="/resultats-recherche.html" class="navbar-form navbar-left navbar-custom" id="recherche-form">

                   

                    <div class="form-group form-group-search">

                        <input type="text" name="recherche_glob" class="form-control search_header" placeholder="">

                    </div>

                    <a data-category="Header_Links" id="search_2" data-action="Search Click" data-label="Search" href="javascript:{}" onclick="document.getElementById('recherche-form').submit(); return false;"

                    class="header--nav--link header--loop">

                    <span class="material-symbols-outlined">
search
</span>

                    </a>
                </form>
            </li>
            
            



            <li class="header--pull pull-right">

                <ul>

                    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="DIRECT"

                            href="https://www.facebook.com/France1D/" target="_blank"

                            class="header--nav--link pas_direct">DIRECT</a></li>

                    <li class="header--edition-dropdown ">
                    <? if($user_id){?>
                        <input type="checkbox" class="openSidebarMenu" id="openSidebarMenu">
                        <label for="openSidebarMenu" class="sidebarIconToggle bureau">
                            <?php echo $signin_compte; ?>
                        </label>
                        <?}else{?>
                            <input type="checkbox" class="openSidebarMenu" id="openSidebarMenu">
                        <label for="openSidebarMenu" class="sidebarIconToggle bureau">
                            <?php echo $signin_compte; ?>
                        </label>
                        <?}?>
                    
                        <div id="sidebarMenu">
                        <? if($user_id){?>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="call-to-action mx-auto" href="<? echo $href_modal;?>"> Mon compte</a>
                                </div>
                                <div class="col-md-12 text-center">
                                    <a class="call-to-action new-logout mx-auto" href="/logout.php"><i class="fa fa-sign-out"></i> Déconnexion</a>
                                </div>
                            </div>
                        <? }else{?>
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

                                <div class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="auth--body">

                                                <?php if($erreur_auth!='') echo '<div class="col-md-12 fail">'.$erreur_auth.'</div> <br> <br>';?>



                                                <form name="signup" id="form" method="post" class="clearfix signup-form relative "

                                                    action="/content/modules/login.php">

                                                    <input type="hidden" name="referer" id="referer" value="" />

                                                    <input type="hidden" name="option" id="option" value="" />

                                                    <div class="col-md-12 form-wrapper text-left">

                                                        <label for="email" >E-mail ou Nom d’utilisateur</label>

                                                        <input type="text" class="input_auth" name="name_user" id="name_user"

                                                            value="" required />

                                                    </div>

                                                    <div class="col-md-12 form-wrapper text-left" style="margin-top: 20px !important;">

                                                        <label for="password" >Mot de passe</label>

                                                        <input type="password" class="input_auth" name="password" id="password_auth"

                                                            value="" required />



                                                        <a onclick="forgot();" data-toggle="modal" data-target="#forgot"

                                                            class="forgot mt-10">Mot de passe oublié?</a>

                                                    

                                                    </div>

                                                    <div class="col-md-12 form-wrapper button-modal text-center mb-30">

                                                        <input type="submit" name="submit" id="submit" value="Se connecter"

                                                            class="call-to-action large-round mx-auto" />

                                                    </div>

                                                    
                                                    <div class="mx-auto" style="width:fit-content">ou connecte toi avec 👇</div>
                                                    <?php
                                                        require('./config.php');
                                                        # the createAuthUrl() method generates the login URL.
                                                        $login_url = $client->createAuthUrl();
                                                    ?>
                                                    <div class="col-sm-12 google button-social">
                                                        <div class="btn">
                                                        <a href="<?= $login_url ?>"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login with Google</a>
                                                        </div>
                                                    </div>
                                                   

                                                    <?php echo '<input type="hidden" name="previous_url" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'">';?>

                                                </form>

                                            </div>
                                            
                                            
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <h4>Créer un compte</h4>
                                                    
                                                    

                                                    <?php if (isset($_SESSION['msg_inscription'])) {
                                                        
                                                    echo $_SESSION['msg_inscription'];
                                                    echo '<button class="btn btn-pager-mar-list" id="next-link" style="pointer-events: all; background-color: rgb(252, 182, 20); cursor: pointer;"> <a style="color:black;" href="formulaire-google.html">Associer un compte google</a></button>';
                                                    unset($_SESSION['msg_inscription']);
                                                } ?>

                                                    <form action="/content/modules/verification-user.php" method="post" class="form-horizontal"
                                                        id="target">
                                                    
                                                        <div class="form-group text-left">
                                                            <label for="nom" class="col-sm-12">Nom *</label>
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control input_auth" id="nom" required
                                                                    data-msg-required="Ce champ est obligatoire!" name="nom">
                                                            </div>
                                                        </div>

                                                        <div class="form-group text-left">
                                                            <label for="prenom" class="col-sm-12">Prénom *</label>
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control input_auth" id="prenom"
                                                                    data-msg-required="Ce champ est obligatoire!" required name="prenom">
                                                            </div>
                                                        </div>
                                                        <div class="form-group  text-left">
                                                            <label for="email" class="col-sm-12">E-mail *</label>
                                                            <div class="col-sm-12">
                                                                <input type="email" class="form-control input_auth" id="email" required
                                                                    data-msg-email="Votre adresse e-mail n'est pas valide."
                                                                    data-msg-required="Ce champ est obligatoire!" name="email">
                                                            </div>
                                                        </div>

                                                        <div class="form-group  text-left">
                                                            <label for="Mot_de_passe" class="col-sm-12">Mot de passe *</label>
                                                            <div class="col-sm-12">
                                                                <input type="password" class="form-control input_auth" name="mot_de_passe" id="password"
                                                                    required data-msg-required="Ce champ est obligatoire!">
                                                            </div>
                                                        </div>

                                                        <div class="form-group text-left">
                                                            <label for="confirmePW" class="col-sm-12">Confirmation du mot de passe *</label>
                                                            <div class="col-sm-12">
                                                                <input type="password" class="form-control input_auth" name="confirmePW" required
                                                                    data-msg-required="Ce champ est obligatoire!" data-rule-equalto="#password"
                                                                    data-msg-equalto="Les mots de passe doivent se correspondre !">
                                                            </div>
                                                        </div>

                                                        <!-- <div class="g-recaptcha" id="g-recaptcha-response" name="g-recaptcha-response" data-sitekey="6Lc-wRsTAAAAAP8A0DXsgrJhboYw05SPxJlWYuRY"></div> -->
                                                        <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LdcITUpAAAAAJNe_-kxs-4q4Xy9_HrQnk3FvTkx"></div>
                                                        <br>
                                                        <div class="form-group">
                                                            <div class="col-sm-12" style="top:-20px">
                                                                <input id="create_account" value="Je m'inscris" type="submit" name="register_button" class="call-to-action large-round mx-auto" />
                                                            </div>
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>

                                <!-- Swiper JS -->
                                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

                                <!-- Initialize Swiper -->
                                <script>
                                    
                                    var swiper = new Swiper(".mySwiper", {
                                    pagination: {
                                        el: ".swiper-pagination",
                                        clickable: true,
                                        renderBullet: function (index, className) {
                                            if(index==0){
                                                return '<div id="bseconnecter" class="' + className + ' swipe-buttons">' +"Tu as déja un compte ?<br><span> Connecte-toi !</span>"+ "</div>";
                                            }else if(index==1){
                                                return '<div id="bsinscrire" class="' + className + ' swipe-buttons">' +"Pas encore de compte ?<br><span> Inscris-toi-vite !</span>"+ "</div>";
                                            }
                                       
                                        },
                                    },
                                    });
                                </script>
                            <? }?>
                    </div>

                   


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

                                            <label for="email">Nom d'utilisateur ou email</label>

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

                                        <div class="col-md-12 form-wrapper button-modal text-center">

                                            <input type="submit" name="submit" id="submit" value="Se connecter"

                                                class="btn_auth button__red" />

                                        </div>

                                        
                                        <div class="button-modal separateur mx-auto" style="width:fit-content">-ou-</div>
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

                                data-name="" data-sub=""> MY ACCOUNT<span class="material-symbols-outlined">
account_circle
</span> </a>

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

        <input type="submit" name="op" id="mobile-search-button" value="Chercher"><!--  A revoir! -->
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
function getCurrentURL() {

   return window.location.href.split("/")

 }
 
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
$('#bseconnecter').hide()
$('#bsinscrire').on('click', function(e) {
    $(this).hide()
    $('#bseconnecter').show()
})
$('#bseconnecter').on('click', function(e) {
    $(this).hide()
    $('#bsinscrire').show()
})
$('.modal-last-link').on('click', function(e) {
    cname="page_when_creating_account"
    cvalue=window.location.href
    exdays=1
    //e.preventDefault();
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)

});
$('#create_account').on('click', function(e) {
    cname="page_when_creating_account"
    cvalue=window.location.href
    exdays=1
    //e.preventDefault();
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)

});


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
    const regex_url_actus = new RegExp(/actualite(.|-)*.html/);
    const regex_url_marathons = new RegExp(/(.|-)*marathons(.|-)*.html/);
    const regex_url_resultats = new RegExp(/(.|-)*resultats-marathon(.|-)*.html/);
    const regex_url_athletes = new RegExp(/(.|-)*athlete(.|-)*.html/);
    const regex_url_videos = new RegExp(/(.|-)*video(.|-)*.html/);
    const regex_url_records = new RegExp(/records-marathon(.|-)*.html/);
    urlparts = getCurrentURL()
    console.log("url parts",urlparts)
    url_actuelle=urlparts[3]
    if("" == url_actuelle){
        console.log("home page")
        $('#home').addClass("active-menu-link");
    }else if(regex_url_actus.test(url_actuelle)){
        console.log("news page")
        $('#news').addClass("active-menu-link");
    }else if(regex_url_marathons.test(url_actuelle)){
        console.log("marathons page")
        $('#marathons').addClass("active-menu-link");
    }else if( regex_url_resultats.test(url_actuelle)){
        console.log("resultats page")
        $('#resultats').addClass("active-menu-link");
    }else if( regex_url_athletes.test(url_actuelle)){
        console.log("athletes page")
        $('#athletes').addClass("active-menu-link");
    }
    else if( regex_url_videos.test(url_actuelle)){
        console.log("videos page")
        $('#videos').addClass("active-menu-link");
    }
    else if( regex_url_records.test(url_actuelle)){
        console.log("records page")
        $('#records').addClass("active-menu-link");
    }
    
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
<!-- Matomo -->
<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//outils.cquoi.fr/matomo/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
<!-- End Matomo Code -->