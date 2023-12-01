<link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/authentification.css">


<header class="header">
    <style>
    .none {
            display:none;
        }
        .account-dropdown-wrapper {
            position:relative;
            display:block;
        }
        .account-dropdown {
            background: #fff;
            box-shadow: 0 3px 5px #ccc;
            display: block;
            right: 0;
            position: absolute;
            top: 52px;
            width: 220px;
            padding:3px;
            z-index: 99999999;
            display:none;
        }
        .account-dropdown li {
            display:block;
            padding:10px;
        }</style>
        <nav class="header--nav">

            
            <ul>
              <li >
                <a href="#" class="header--nav--link header--logo">Allmarathon</a>
             </li>
             <li class="menu-item-categories">
                <i class="fa fa-bars"></i>
                <ul class="nav-category">
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="ACTUALITE" href="#" class="header--nav--link">ACTUS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RESULTATS" href="#" class="header--nav--link">RESULTATS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="CALENDRIER" href="#" class="header--nav--link">CALENDRIER</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="athlèteS" href="#" class="header--nav--link">athlèteS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="VIDEOS" href="#" class="header--nav--link">VIDEOS</a></li>
                    <!-- <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="Marathon-Talk" href="#" class="header--nav--link">Marathon-Talk</a></li> -->
                </ul>
            </li>
<li class="menu-item-search " >
    <a data-category="Header_Links" id="search_1" data-action="Search Click" data-label="Search" href="#" class="header--nav--link header--loop" >
            <i class="fa fa-search" title="Search"></i> <!--  style="margin-bottom: 30px;" -->
        </a>
    <form class="navbar-form navbar-left navbar-custom">
        <a data-category="Header_Links" id="search_2" data-action="Search Click" data-label="Search" href="#" class="header--nav--link header--loop" >
            <i class="fa fa-search" title="Search"></i> <!--  style="margin-bottom: 30px;" -->
        </a>
        <div class="form-group form-group-search">
          <input type="text" class="form-control search_header" placeholder="">
        </div>
        
      </form>
</li>
<li class="header--pull pull-right">
    <ul>
    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="DIRECT" href="#" class="header--nav--link">DIRECT</a></li>
    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="Découvrez allmarathon shop" href="#" class="header--nav--link" style="line-height: 0.8em; font-size: .7em;padding-bottom: 20px;text-align: center;"><div style="color: #fee616;">Découvrez</div><br>allmarathonshop</a></li>
    <li class="header--edition-dropdown">
        <a href="#" data-toggle="modal" data-target="#SigninModal" class="header--nav--link header--nav--border signin"><div class="header-signin">SIGN IN</div><i class="fa fa-user"></i></a>
        <!-- Mariam -->
   <div class="modal fade" id="SigninModal" tabindex="-1" role="dialog" aria-labelledby="SigninModal" aria-hidden="true">
         <div class="auth registration">

            <div class="auth--header">
                Sign in
            </div>
            <div class="auth--body">
                <div class="col-md-12 fail" style="display:none;">

                </div>
                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> We couldn't find your email address or username in our system, please double check that you entered it correctly.
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> The password you entered did not match the email address or username provided, please try again.
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> Your account has been blocked by our moderator, please contact emea-support@newsweek.com to regain access to the site.
                </div>
                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> Your account needs to be activated. <a href="" class="resend">Resend activation email.</a>
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> Due to suspicious activity, your account has been temporarily disabled. Please contact email address to organise the reactivation of your account
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> Due to previous suspicious activity, your account has been temporarily disabled. Please contact email address to organise the reactivation of your account
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> Activation email has been sent.
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> We can not send you an activation email this time. Please register with another email address.
                </div>

                <div class="col-md-12 fail" style="display:none;background:#249b3d !important;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-success-icon.jpg" /> You are already registered, please sign in below.
                </div>

                <div class="col-md-12 fail" style="display:none;background:#249b3d !important;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-success-icon.jpg" /> Hi , You're signed in with . Please log into your Newsweek account.
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> The password you entered did not match the confirm password, please try again.
                </div>

                <div class="col-md-12 fail" style="display:none;">
                    <img src="https://g.europe.newsweek.com/www/img/home/error-messaging-failure-icon.jpg" /> The password should be at least 7 letters, please try again.
                </div>
                <form name="signup" id="form" method="post" class="clearfix signup-form relative " action="/auth/login/SYSTEM">
                        <input type="hidden" name="referer" id="referer" value="" />
                        <input type="hidden" name="option" id="option" value="" />
                        <div class="col-md-12 form-wrapper">
                            <label for="email">Nom d'utilisateur </label>
                            <input type="text" class="input_auth" name="name_user" id="name_user" value="" required />
                        </div>
                        <div class="col-md-12 form-wrapper" style="margin-top: 20px !important;">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="input_auth" name="password" id="password_auth" value="" required /><br><br>
                            <a href="/forgot.php" class="link">Mot de passe oublié?</a>
                        </div>
                        <div class="col-md-12 t-align--right form-wrapper">
                            <input type="submit" name="submit" id="submit" value="Se connecter" class="btn_auth button__red" />
                        </div>
                    </form>

                
            </div>

                            <div class="auth--footer">
                    Pas encore inscrit ? <a href="/formulaire-inscription.php" class="link">Inscrivez-vous aujourd'hui ! </a>
                </div>
                    </div>
                </div>
                    <!-- Mariam -->


        <div class="none" id="myaccount-box">
            <a class="header--nav--link header--nav--border account-dropdown-wrapper" href="" data-name="" data-sub=""> MY ACCOUNT<i
            class="fa fa-user"></i> </a>
            <ul class="account-dropdown">
                <li> <a href="#">My account</a></li>
                <li> <a href="#">Subscription settings</a></li>
                <li> <a href="#">Contact support</a></li>
            <li> <a href="#">Sign out</a></li>
        </ul></div> <script>var sion_ok=sion_ok||0;sion_ok=0;</script> </li>
    

    </ul>
</li>
</ul>
</nav>
<form action="#" method="get" accept-charset="UTF-8" class="d--none" id="frm-search">
     <label for="header-search" class="d--none"></label>
     <input id="header-search" placeholder="Recherche" type="text" name="q" value="">
     <input type="submit" name="op" value="Search"><!--  A revoir! -->
</form>
</header>

<style type="text/css">
.header--nav--link:visited, .header--nav--link:focus, .header--nav--link:active{
    color: #fff;
    text-decoration: none;
    outline: none;
}
a.link:visited,a.link:active,a.link:focus{
    color: #ff0500 ;
    text-decoration: none;
    outline: none;
}
.header {
    background: #fcb614;
    /*box-shadow: 0 3px 3px #e5e5e5;*/
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 999999999;
}
.header nav.header--nav ul li:first-child {
       -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
}
.header li {
    font-size: 16px;
    line-height: 100%;
    display: inline-block;
    font-family: 'Source Sans Pro',lucida sans,arial,sans-serif;
    position: relative;
}
input[type=text],input[type=email],input[type=password]{border:1px solid red} 
.header--nav ul {
    font-size: 0;
    line-height: 0;
}
input#header-search:focus {
    outline: none;
}

@media (min-width: 1093px)
.nav-category {
    display: block !important;
}

.header--nav--link {
    border-bottom: 2px solid #fcb614;
    color: #fff;
    float: left;
    font-size: .8em;
    font-weight: 700;
    line-height: 5em;
    padding: 0 1em;
    letter-spacing: .5px;
    text-transform: uppercase;
    transition: all ease .3s;
}
.header--nav--link:hover {
    border-bottom-color: #fee616;
    color: #fff;
    text-decoration: none;
}
.header--logo:hover {
    border-bottom-color: #fff;
}

.header--nav--link__subscribe {
    color: #fee616;
}

#header-search {
    background: white none repeat scroll 0 0;
    box-shadow: 0 2px 4px rgba(0,0,0,.25);
    color: #666;
    font-size: 20px;
    height: 53px;
    line-height: 53px;
    padding: 0;
    text-align: center;
    text-indent: 0;
    width: 100%;
    font-family: MuseoSans-500;
    border: 1px solid transparent;
}
.d--none {
    display: none;
}
li.menu-item-categories .fa-bars {
    display: none;
}

/*li.menu-item-categories  {
    box-shadow: 0 3px 3px #e5e5e5;
}*/

.navbar-custom{
    padding: 0; 
    width: 100%;
}
.header--logo {
    background: url("../../images/Divers/sprite.png") 0px 5px no-repeat #fff;
    text-indent: -9999px;
    width: 178px;
    border-bottom: 2px solid #fff;
}
.pull-right{float:right !important}.pull-left{float:left !important}

.menu-item-search{
    width: 31.2%;
}
#search_1{
    display: none;
}
#frm-search{
    margin-bottom: -6px !important;
}
#header-search+[type=submit] {
    visibility: hidden;
    width: 0;
    height: 0;
    font-size: 0;
    line-height: 0;
}
.header--loop{
    margin-top: 9px;
}.header--loop:hover{
    border-bottom-color: transparent;
}
.search_header{
    border-radius: inherit;
    border: none;
    height: 33px;
    background-color: #ff6666;
    width: 100% !important;
}

@media (max-width: 1320px){
   .signin .fa-user {
    display: block;
    font-size: 16px;
    line-height: 4em;
    padding: 0 3px;
} 
.header-signin{
    display: none;
}

}
.form-group-search{
     margin-bottom: 8px !important;
     width: 90%;
}
/*(min-width: 768px) and*/
@media  (max-width: 1280px) {
  .header--logo {
    background: url(../../images/Divers/sprite.png) 5px -65px no-repeat #fff;
    width: 72px;

}
.form-group-search{
    width: 80%;
}
.menu-item-search{
    width: 31%;
}
}
@media (min-width: 1321px){
    .signin .fa-user {
    display: none;
}
}
@media (max-width: 700px){
     .auth{
        top:120px  !important;
    }
    #header-search{
        margin-bottom: 0px;
    }
}
@media (max-width: 767px){
     .form-group-search {
        display: none !important;
    }    .navbar-custom{
       padding: 0 20px 37px;
}
.menu-item-search {
    width: 7% !important;
}
#search_1{
    display: block;
}
#search_2{
    display: none;
}
.header--loop {
    margin-top: 15px;
}
}
@media (max-width: 1120px){
  li.menu-item-categories .fa-bars {
    color: white;
    cursor: pointer;
    display: inline-block;
    float: right;
    padding: 9px;
}  
li.menu-item-categories .nav-category {
    background-color: #0e0e0e;
    box-shadow: 0 2px 3px #e5e5e5;
    clear: both;
    display: none;
    position: absolute;
    right: -10px;
    text-align: center;
    top: 51px;
    width: 100vw;
    z-index: 100;
}
li.menu-item-categories .nav-category li {
    margin: 0;
    overflow: hidden;
    text-align: center;
}
li.menu-item-categories .nav-category li a {
    color: #fff;
    display: inline-block;
    float: none;
    white-space: nowrap;
}
li.menu-item-categories {
    float: right;
    margin: 15px 10px 0 5px;
    position: relative;
    visibility: visible;
}
.menu-item-search {
    width: 50%;
}
.header--nav--link {
    border-bottom: 2px solid transparent;
}
.nav-category li .header--nav--link:hover {
    border-bottom-color: red !important;
}
}

</style>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script>window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.jcarousel.min.js"></script>
<script src="../../js/jquery.sliderPro.min.js"></script>
<script src="../../js/main.js"></script>
<script type="text/javascript">
$('.bootpopup').click(function(){
    var frametarget = $(this).attr('href');
  var targetmodal = $(this).attr('target');
  if (targetmodal == undefined) {
    targetmodal = '#popupModal';
  } else { 
    targetmodal = '#'+targetmodal;
  }
  if ($(this).attr('title') != undefined) {
    $(targetmodal+ ' .modal-header h3').html($(this).attr('title'));
    $(targetmodal+' .modal-header').show();
  } else {
     $(targetmodal+' .modal-header h3').html('');
    $(targetmodal+' .modal-header').hide();
  }  
    $(targetmodal).on('show', function () {
        $('iframe').attr("src", frametarget );   
    });
    $(targetmodal).modal({show:true});
  return false;
    
});</script>
 <script>
 function hideWhenClickedOnBody(elm,ns){
    if(elm.is(':visible'))
        {$(document).on('click.'+ns,function(){
            elm.hide();
            $(document).off('click.'+ns);
        });
    
    }else{
        $(document).off('click.'+ns);
    }
}

$('.menu-item-search > a.header--nav--link').on('click',function(e){
        e.preventDefault();
        var elm=$('#frm-search');
        elm.slideToggle(100,function(){
            hideWhenClickedOnBody(elm,'searchdd');
        });
        $("#header-search").focus();
    });

// $(".menu-item-categories").click(function(){
//     console.log("click");
// });
$('li.menu-item-categories .fa-bars').on('click',function(e){
        var elm=$(this).parent().find('.nav-category');
        elm.slideToggle(100,function(){
            hideWhenClickedOnBody(elm,'menudd')});
    });
// doFir.push(function(){
//     $('.header--edition-dropdown > a.header--nav--link').on('click',function(e){
//         e.preventDefault();
//         var elm=$(this).parent().find('.header--edition-dropdown--container');
//         elm.slideToggle(100,function(){
//             hideWhenClickedOnBody(elm,'editiondd');
//         });
//     });
//     $('.menu-item-search > a.header--nav--link').on('click',function(e){
//         // e.preventDefault();
//         // var elm=$('#frm-search');
//         // elm.slideToggle(100,function(){
//         //     hideWhenClickedOnBody(elm,'searchdd');
//         // });
//         // $("#header-search").focus();
//         console.log("search");
//     });
//     $('li.menu-item-categories .fa-bars').on('click',function(e){
//         var elm=$(this).parent().find('.nav-category');
//         elm.slideToggle(100,function(){
//             hideWhenClickedOnBody(elm,'menudd')});
//     });
//     $('#frm-search input').on('click',function(e){
//         e=e||window.event;e.stopPropagation();
//     });
//     $(".signin").unbind('click').click(function(e){
//         e.preventDefault();openSignin(0);
//     });
//     $(".account-dropdown-wrapper").click(function(e){
//             e.preventDefault();
//         $(".account-dropdown").slideToggle(100,function(){
//             hideWhenClickedOnBody($(this),'signdd');
//         });
//     });
//     $(".account-dropdown-wrapper a").click(function(e){e=e||window.event;e.stopPropagation();
//     });
// });

</script>                
