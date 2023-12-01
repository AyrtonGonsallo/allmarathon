<?php

?>
<link rel="stylesheet" href="/css/authentification.css"> 


<header class="header">
        <nav class="header--nav">

            
            <ul>
              <li >
                <a href="/" class="header--nav--link header--logo">Allmarathon</a>
             </li>
             <li class="menu-item-categories">
                <i class="fa fa-bars"></i>
                <ul class="nav-category">
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="ACTUALITE" href="/actualites-marathon.html" class="header--nav--link">ACTUS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="RESULTATS" href="/resultats-marathon.html" class="header--nav--link">RESULTATS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="CALENDRIER" href="/calendrier-marathon.html" class="header--nav--link">CALENDRIER</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="athlèteS" href="/cv-champions-de-marathon.html" class="header--nav--link">athlèteS</a></li>
                    <li> <a data-category="Header_Links" data-action="Top Menu Click" data-label="VIDEOS" href="/videos-de-marathon.html" class="header--nav--link">VIDEOS</a></li>
                    <!-- <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="Marathon-Talk" href="#" class="header--nav--link">Marathon-Talk</a></li> -->
                </ul>
            </li>
<li class="menu-item-search " >
    <a data-category="Header_Links" id="search_1" data-action="Search Click" data-label="Search" href="#" class="header--nav--link header--loop" >
            <i class="fa fa-search" title="Search"></i> <!--  style="margin-bottom: 30px;" -->
        </a>
    <form method="post" action="/resultats-recherche.html" class="navbar-form navbar-left navbar-custom">
        <a data-category="Header_Links" id="search_2" data-action="Search Click" data-label="Search" href="#" class="header--nav--link header--loop" >
            <i class="fa fa-search" title="Search"></i> <!--  style="margin-bottom: 30px;" -->
        </a>
        <div class="form-group form-group-search">
          <input type="text" name="recherche_glob" class="form-control search_header" placeholder="">
        </div>
        
      </form>
</li>
<li class="header--pull pull-right">
    <ul>
    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="DIRECT" href="https://www.facebook.com/France1D/" target="_blank" class="header--nav--link pas_direct">DIRECT</a></li>
    <li><a data-category="Header_Links" data-action="Top Menu Click" data-label="Découvrez allmarathon shop" href="https://shop.alljudo.net/" target="_blank" class="header--nav--link" style="line-height: 0.8em; font-size: .7em;padding-bottom: 20px;text-align: center;"><div style="color: #fee616;">Découvrez</div><br>allmarathonshop</a></li>
    <li class="header--edition-dropdown">
        <?php
        if($user_session!=''){
            $href_modal="/membre-profil.php";
            $target_modal="";
            $signin_compte='<i class="fa fa-user user_compte"></i>';
        }
        else{
            $href_modal="#";
            $target_modal='data-target="#SigninModal"';
            $signin_compte="SIGN IN";
        }
        ?>
        <a href=<?php echo $href_modal;?> data-toggle="modal" <?php echo $target_modal;?> class="header--nav--link header--nav--border signin"><div class="header-signin"><?php echo $signin_compte; ?></div><i class="fa fa-user"></i></a>
        <!-- Mariam -->
   <div class="modal fade" id="SigninModal" tabindex="-1" role="dialog" aria-labelledby="SigninModal" aria-hidden="true">
         <div class="auth registration">

            <div class="auth--header">
                Sign in
            </div>
            <div class="auth--body">
                <?php if($erreur_auth!='') echo '<div class="col-md-12 fail">'.$erreur_auth.'</div> <br> <br>';?>
                
                <form name="signup" id="form" method="post" class="clearfix signup-form relative " action="/content/modules/login.php">
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
                         <?php echo '<input type="hidden" name="previous_url" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'">';?>
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

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script>window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')</script>

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
    
});
</script>
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

$('li.menu-item-categories .fa-bars').on('click',function(e){
        var elm=$(this).parent().find('.nav-category');
        elm.slideToggle(100,function(){
            hideWhenClickedOnBody(elm,'menudd')});
    });


</script>                
<?php
if($erreur_auth!='')
{
    echo "<script type='text/javascript'>
$(document).ready(function(){
$('#SigninModal').modal('show');
});
</script>";
    
}

?>