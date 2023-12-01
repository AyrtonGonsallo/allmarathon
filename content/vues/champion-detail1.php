<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
}  else {
    $user_session='';
}

$id=$_GET['championID'];

include("../classes/pub.php");
include("../classes/champion.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../classes/video.php");
include("../classes/commentaire.php");
include("../classes/user.php");
include("../classes/evenement.php");
include("../classes/championPopularite.php");
include("../classes/abonnement.php");
include("../classes/evCategorieAge.php");

$ev_cat_age=new evcategorieage();

$champ_pop=new championPopularite();
$event=new evenement();
$user=new user();
$champ_abonnement=new abonnement();
$champion=new champion();

$champ=$champion->getChampionById($id)['donnees'];
$photos=$champion->getChampionsPhotos($id)['donnees'];
$resultats_champ=$champion->getChampionResults($id)['donnees'];
$resultats_fans=$champion->getChampionFans($id)['donnees'];
$tab_med=$champ->getTabMedailleByChampion($id)['donnees'];

$page=0;

$commentaire=new commentaire();
$coms=$commentaire->getCommentaires(0,0,$id)['donnees'];
// $coms=$commentaire->getCommentairesChampion($id,$page)['donnees'];

$ev_res=new evresultat();
$ev_res_poids=$ev_res->getPoidsByChampID($id)['donnees'];
$poids="";
foreach ($ev_res_poids as $key => $p) {
	$poids.=$p['p']." ";
}

$video=new video();
$videos=$video->getVideosByChamp($id)['donnees'];

$pays=new pays();
$pays_intitule=$pays->getFlagByAbreviation($champ->getPaysID())['donnees']['NomPays'];

if($champ->getSexe()=="F") {$sexe="Femme"; $ne="Née";} else{ $sexe="Homme"; $ne="Né";}

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];



// if((!empty($_SESSION['user']))&& (($champ_pop->isUserFan($id,$user_id)['donnees']) || (empty($_SESSION['plus_fan'])))){
if((!empty($_SESSION['user']))&& ($champ_pop->isUserFan($id,$user_id)['donnees'])){
$img_fan_src="/images/pictos/fan_1.png";
$img_fan_alt="Ne plus être fan";
}else{
   $img_fan_src="/images/pictos/fan.png";
   $img_fan_alt="Devenir Fan";
}  


// if((!empty($_SESSION['user'])) && (($champ_abonnement->isUserAbonne($id,$user_id)['donnees'])|| (empty($_SESSION['plus_abonnee'])))){
if((!empty($_SESSION['user'])) && ($champ_abonnement->isUserAbonne($id,$user_id)['donnees'])){
$img_abonnement_src="/images/pictos/abonnement_1.png";
$img_abonnement_alt="Ne plus s'abonner";
}else{
   $img_abonnement_src="/images/pictos/abonnement.png";
   $img_abonnement_alt="S'abonner";
}  

if(!empty($_SESSION['fan_error'])){
	$erreur=" <span style='color:red' > ".$_SESSION['fan_error']."</span>";
	unset($_SESSION['fan_error']);}else{$erreur="";}

if(!empty($_SESSION['abonnement_error'])){
    $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
    unset($_SESSION['abonnement_error']);}else{$erreur="";}

if(!empty($_SESSION['abonnement_error'])){
    $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
    unset($_SESSION['abonnement_error']);}else{$erreur="";}

if(!empty($_SESSION['abonnement_error'])){
    $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
    unset($_SESSION['abonnement_error']);}else{$erreur="";}

if(!empty($_SESSION['commentaire_error'])){
    $msg_com=" <span style='color:red' > ".$_SESSION['commentaire_error']."</span>";
    unset($_SESSION['commentaire_error']);}else{$msg_com="";}

if(!empty($_SESSION['commentaire_success'])){
    $msg_com=" <span style='color:green' > ".$_SESSION['commentaire_success']."</span>";
    unset($_SESSION['commentaire_success']);}else{$msg_com="";}

    function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}
$afficher_tab_medaille=false;
   foreach ($tab_med as $key => $value) {
            
    $equipe = ($value['Type']=="Equipe")?true:false;
    $key  = $value['Intitule'];
    $key .= ($equipe)?" par equipes ":"";
    $key .= ' '.$value['Nom'].' '.substr($value['DateDebut'], 0,4);

    $tabResult[$key] = $value['ID'].'$'.$value['Rang'].'$'.$value['DateDebut'].'$'.$value['PoidID'];
    if($value['Rang'] < 4)
    {
        $afficher_tab_medaille=true;
        if(empty($tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][$value['Rang']]))
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][$value['Rang']] = 1;
        else
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][$value['Rang']]++;
    }
        

} 


?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Le palmarès de l'athlète <?php  echo $champ->getNom().' ';?> : résultats, vidéos, photos</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="../../images/favicon.ico">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlète-detail">
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
                        <h1 style="border-bottom: 2px solid #cccccc;padding-bottom: 10px;">
                            <?php echo $champ->getNom()." ".$erreur; ?> <span style="float: right;margin-top: -12px;">
                                <?php echo '<a class="btn info-bulle" href="/formulaire-administration-athlète.php?championID='.$id.'"><img src="/images/pictos/admin.png" title="Devenir administrateur"  /></a>'; ?>
                                <?php echo '<a class="btn info-bulle" href="#"><img src="'.$img_abonnement_src.'" id="abonnement_id"title="'.$img_abonnement_alt.'"   /></a>'; ?>
                                <?php echo '<a class="btn info-bulle" href="#"><img src="'.$img_fan_src.'" id="fan_id" title="'.$img_fan_alt.'"  /></a>'; ?>
                        </h1>


                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">1<br>CV</a></li>
                            <li><a href="#tab2" role="tab"
                                    data-toggle="tab"><?php echo sizeof($resultats_champ); ?><br>Résultats</a></li>
                            <li><a href="#tab3" role="tab"
                                    data-toggle="tab"><?php echo sizeof($photos); ?><br>PHOTOS</a></li>
                            <li><a href="#tab4" role="tab"
                                    data-toggle="tab"><?php echo sizeof($videos); ?><br>VIDEOS</a></li>
                            <li><a href="#tab5" role="tab"
                                    data-toggle="tab"><?php echo sizeof($resultats_fans); ?><br>FANS</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="tab1">
                                <br />
                                <?php ($champ->getTaille()!="" && $champ->getTaille()!=0 ) ? $taille="<li><strong>Taille : </strong>".$champ->getTaille()." cm</li>" : $taille="";
                            ($champ->getDateNaissance()!="0000-00-00" && $champ->getDateNaissance()!="" ) ? $date_naissance="<li><strong>".$ne." le : </strong>".$champ->getDateNaissance()."</li>" : $date_naissance="";
                            ( $champ->getTokuiWaza()!="" ) ? $tokuiWaza="<li><strong>TokuiWaza : </strong>".$champ->getTokuiWaza()."</li>" : $tokuiWaza="";
                            ( $champ->getMainDirectrice()!="" ) ? $mainDirectrice="<li><strong>Main Directrice : </strong>".$champ->getMainDirectrice()."</li>" : $mainDirectrice="";
                            ($champ->getAnecdote()!="") ? $anecdote="<p><strong>Anecdote : </strong>".$champ->getAnecdote()."</p>" : $anecdote="";
                            ($poids!="") ? $poids_cham="<li><strong>Catégorie de Poids : </strong>".$poids."</li>" : $poids_cham="";
                             echo'

                            <ul>
                                <li><strong>Sexe : </strong>'.$sexe.'</li>
                                <li><strong>Pays : </strong>'.$pays_intitule.'</li>
                                '.$date_naissance.'
                                <li><strong>Grade : </strong>'.$champ->getGrade().'</li>
                                <li><strong>Clubs : </strong>'.$champ->getClubs().'</li>
                                '.$taille.$poids_cham.$tokuiWaza.$mainDirectrice.'
                            </ul>

                            <br/>

                            '.$anecdote; ?>

                                <?php if($afficher_tab_medaille){  ?>
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Les médailles de <?php echo $champ->getNom(); ?></th>
                                            <th><img src="/images/pictos/or.png" alt="" /></th>
                                            <th><img src="/images/pictos/argent.png" alt="" /></th>
                                            <th><img src="/images/pictos/bronze.png" alt="" /></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php krsort($tabMedal, SORT_NUMERIC);
                               foreach($tabMedal as $CategorieageID => $tab){
                               // print_r($CategorieageID);echo '<hr>';
                               ?> <tr style="background: #eee">
                                            <td colspan="4">
                                                <?php echo $ev_cat_age->getEventCatAgeByID($CategorieageID)['donnees']->getIntitule(); ?>
                                            </td>
                                        </tr>
                                        <?php   
                                foreach ($tab as $key => $value) {
                                     foreach($value as $cat => $rangs){?>
                                        <tr>
                                            <td><?php echo $cat?></td>
                                            <td><?php echo (empty ($rangs[1]))?0:' '.$rangs[1]; ?></td>
                                            <td><?php echo (empty ($rangs[2]))?0:' '.$rangs[2]; ?></td>
                                            <td><?php echo (empty ($rangs[3]))?0:' '.$rangs[3]; ?></td>
                                        </tr>
                                        <?php }
                                }
                                
                                 }
                                 // foreach($tabMedal as $tri => $tab){
                                 //    foreach($tab as $cat => $rangs){?>
                                        <!--  <tr>
                                                    <td><?php echo $cat?></td>
                                                     <td><?php echo (empty ($rangs[1]))?0:' '.$rangs[1]; ?></td>
                                                     <td><?php echo (empty ($rangs[2]))?0:' '.$rangs[2]; ?></td>
                                                     <td><?php echo (empty ($rangs[3]))?0:' '.$rangs[3]; ?></td>
                                                 </tr> -->
                                        <?php //}
                                 // }
                                   ?>
                                    </tbody>
                                </table>
                                <?php }?>
                            </div>
                            <div class="tab-pane fade" id="tab2">

                                <table id="classement_resultats" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th class=" headerSortDown">Année</th>
                                            <th class="">Rang</th>
                                            <th class="">Evenement</th>
                                            <th class="">Catégorie</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                	foreach ($resultats_champ as $key => $value) {
                                        $cat_age=$ev_cat_age->getEventCatAgeByID($value['CategorieageID'])['donnees']->getIntitule();
                                        $name_res=$value['Intitule'].' - '.$cat_age.' '.$value['Nom'].' '.$value['DateDebut'];
                                        $name_res=slugify($name_res);
                                        $par_equipe= ($value['Type']=="Equipe") ? "par équipe ":"";
                                		echo '<tr>
			                                    <td>'.$value['DateDebut'].'</td>
			                                    <td align="center">'.$value['Rang'].'</td>
			                                    <td><a href="/resultats-marathon-'.$value['ID'].'-'.$name_res.'.html">'.$value['Intitule'].' '.$par_equipe.$cat_age.' - '.$value['Nom'].'</a></td>
			                                    <td align="center">'.$value['PoidID'].'</td>
			                                </tr>';
                                	}
                                	 ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <?php 
								if(sizeof($photos)!=0){
									foreach ($photos as $key => $photo) {
                            			echo '<li><a href="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" class="fancybox" rel="group" ><img src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="77" alt=""/></a></li>';
                            		}} ?>

                                </ul>
                            </div>

                            <div class="tab-pane fade" id="tab4">
                                <ul class="videos-tab">
                                    <?php
                            	foreach ($videos as $key => $vd) {
                            		
                            		$event_intitule="";
	                                if($vd->getEvenement_id()!=0){
	                                $annee_event=substr($event->getEvenementByID($vd->getEvenement_id())['donnees']->getDateDebut(),0,4);
                                    $video_intitule=$event->getEvenementByID($vd->getEvenement_id())['donnees']->getNom()." ".$annee_event;
	                                $event_intitule="<li><a href='/resultats-marathon-".$vd->getEvenement_id()."-".slugify($video_intitule).".html' class='video_event'>".$video_intitule."</a></li>";
	                                }
                            		$duree="<li style='list-style-type: none;'></li>";
	                                if($vd->getDuree()!='')
	                                $duree="<li>durée : ".$vd->getDuree()."</li>";

                            		echo '<li class="row">
                                    <ul>
                                        <li class="col-sm-6">
                                            <ul>
                                                <li><a href="video-de-marathon-'.$vd->getId().'.html" class="video_titre">'.$vd->getTitre().'</a></li>'.$event_intitule.$duree.'
                                            </ul>
                                        </li>
                                        <li class="col-sm-6"><a href="video-de-marathon-'.$vd->getId().'.html"><img src="'.$vd->getVignette().'" width="120" heigh="90" alt="" class="pull-right img-responsive"/></a></li>
                                    </ul>
                                </li>';
                            	}
                            	 ?>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="tab5">
                                <ul class="fans-tab">
                                    <?php
                            	foreach ($resultats_fans as $key => $value) {
                            		echo '<li>'.$value['username'].'</li>';
                            	}
                            	?>
                                </ul>
                            </div>
                        </div>
                        <span style="height:100px;width: 20px;opacity:0;">span</span>

                        <h2 class="bordered" style="margin-top:95px;">laissez un message à
                            <?php echo $champ->getNom(); ?></h2>
                        <?php
                        if(sizeof($coms)!=0){
                            echo '<ul class="comments">';
                            foreach ($coms as $key => $value) {
                                $user_name=$user->getUserById($value->getUser_id())['donnees']->getUsername();
                                $d_m=date("d/m/Y", strtotime($value->getDate()));
                                $h_s=date("h:s", strtotime($value->getDate()));
                                echo'<li class="line-content">
                                        <span class="meta"><strong>'.$user_name.'</strong> - le '.$d_m.' à '.$h_s.'</span>
                                        <p>'.$value->getCommentaire().'</p>

                                    </li>';
                            }
                            echo '</ul> 
                            <ul id="pagin_com" class="pager"> </ul>';
                        }
                    ?>
                        <ul class="comments">
                            <li class="clearfix">
                                <div class="form-group">
                                    <label for="message">Commentaire (500 caractères maximum)
                                        <?php echo $msg_com; ?></label>
                                    <textarea class="form-control" rows="4" name="message_champion"
                                        id="message_champion"></textarea>
                                </div>
                                <button id="com_but" type="button" class="view-all pull-right">Envoyer</button>

                            </li>
                        </ul>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <!-- <p class="ban"><a href=""><?php //echo $pub300x60; ?></a></p>
            <p class="ban"><a href=""><?php //echo $pub300x250; ?></a></p> -->
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
                     data-width="310"
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
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/jquery.jcarousel.min.js"></script>
    <script src="/js/jquery.sliderPro.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox({
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(0, 0, 0, 0.4)'
                    }
                }
            },
            margin: [110, 60, 30, 60]
        });
    });
    </script>
    <script type="text/javascript">
    pageSize = 5;

    $(function() {
        var pageCount = Math.ceil($(".line-content").size() / pageSize);

        for (var i = 0; i < pageCount; i++) {
            if (i == 0)
                $("#pagin_com").append('<li><a class="curent_page_com" href="#">' + (i + 1) + '</a></li>');
            else
                $("#pagin_com").append('<li><a href="#">' + (i + 1) + '</a></li>');
        }


        showPage(1);

        $("#pagin_com li a").click(function() {
            $("#pagin_com li a").removeClass("curent_page_com");
            $(this).addClass("curent_page_com");
            showPage(parseInt($(this).text()))
        });

    })

    showPage = function(page) {
        $(".line-content").hide();

        $(".line-content").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });
    }
    </script>


    <script type="text/javascript">
    $(document).ready(function() {
        $('#classement_resultats').DataTable({
            "paging": false,
            "bFilter": false,
            "info": false,
            "order": [
                [0, "desc"]
            ]
        });
    });
    </script>

    <?php
	if($user_id!=""){

        if($champ_pop->isUserFan($id,$user_id)['donnees']){
            $path_fan="champion_pop-moins";
            }else{
               $path_fan="champion_pop";
            }  
            // || (empty($_SESSION['plus_abonnee'])) 
        if($champ_abonnement->isUserAbonne($id,$user_id)['donnees']){
            $path_abonnement="champion_desabonnement";
            }else{
               $path_abonnement="champion_abonnement";
            } 
        
		 echo "<script type='text/javascript'>
		$('#fan_id').on('click',function(e){
					// console.log('user id : '+".$user_id.");
            document.location.href='/content/modules/".$path_fan.".php?champ_id=".$id."';
		 	
			});
        $('#abonnement_id').on('click',function(e){
            document.location.href='/content/modules/".$path_abonnement.".php?champ_id=".$id."';
            });
        $('#com_but').on('click',function(e){
            document.location.href='/content/modules/add_commentaire.php?champ_id=".$id."&commentaire='+$('#message_champion').val();
       });
       

				</script>";
	}else{
		 echo "<script type='text/javascript'>
			$('#fan_id').on('click',function(e){
					$('#SigninModal').modal('show');});
            $('#abonnement_id').on('click',function(e){
                    $('#SigninModal').modal('show');});
            $('#com_but').on('click',function(e){
                    $('#SigninModal').modal('show');});
			</script>";
	}
?>
    <script type="text/javascript">
    function fan_fct() {


    }

    // $('#fan_id').on('click',function(e){
    // 		console.log(" user : ");
    //   //       if(user_id=='') {
    // 		// console.log("khawi");
    // 		// } else{
    // 		// console.log("user id : "+user_id);

    // 			// $('#SigninModal').modal('show');
    // 		}
    });
    </script>
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-1833149-1', 'auto');
    ga('send', 'pageview');
    </script>>

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