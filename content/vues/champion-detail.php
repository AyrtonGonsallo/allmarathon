<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
setlocale(LC_TIME, "fr_FR","French");

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
include("../classes/news.php");
include("../classes/evenement.php");
include("../classes/championPopularite.php");
include("../classes/abonnement.php");
include("../classes/evCategorieAge.php");
include("../classes/championAdminExterneClass.php");
include("../classes/champion_admin_externe_palmares.php");
include("../classes/evCategorieEvenement.php");

$champion_admin_externe_palmares=new champion_admin_externe_palmares();
$resultsPerso=$champion_admin_externe_palmares->getResultsPerso($id)['donnees'];

$champAdmin=new championAdminExterne();
$isAdmin=$champAdmin->isAdmin($user_id,$id)['donnees'];
$ev_cat_age=new evcategorieage();
$ev_cat_event=new evCategorieEvenement();
$champ_pop=new championPopularite();
$event=new evenement();
$user=new user();
$news=new news();
$actus=$news->getNewsByChampId($id)['donnees'];
$admins=$champAdmin->getAdminsExterneByChampion($id)['donnees'];
$liste_admin="";
if(sizeof($admins)!=0) {
    $liste_admin ="Fiche administrée par : ";
    foreach ($admins as $ad) {
        $profil_admin=$user->getUserById($ad->getUser_id())['donnees'];
        if($profil_admin)
            $liste_admin .=" ".$profil_admin->getUsername().",";
    }
}

if (isset($_SESSION['user_id'])) {
    $user_auth=$user->getUserById($user_id)['donnees'];
}

$champ_abonnement=new abonnement();
$champion=new champion();
$resultats_champ=$champion->getChampionResults($id)['donnees'];

$champ=$champion->getChampionById($id)['donnees'];
$photos=$champion->getChampionsPhotos($id)['donnees'];

$tab_med=$champ->getTabMedailleByChampion($id)['donnees'];

$page=0;


$ev_res=new evresultat();


$video=new video();
$videos=$video->getVideosByChamp($id)['donnees'];

$pays=new pays();
$pays_intitule=('9999-12-31'!=$champ->getDateChangementNat() && '0000-00-00'!=$champ->getDateChangementNat() )?$pays->getFlagByAbreviation($champ->getNvPaysID())['donnees']['NomPays']:$pays->getFlagByAbreviation($champ->getPaysID())['donnees']['NomPays'];
$pays_prefixe=('9999-12-31'!=$champ->getDateChangementNat() && '0000-00-00'!=$champ->getDateChangementNat() )?$pays->getFlagByAbreviation($champ->getNvPaysID())['donnees']['prefixe']:$pays->getFlagByAbreviation($champ->getPaysID())['donnees']['prefixe'];

if($champ->getSexe()=="F") {$sexe="Femme"; $ne="née";} else{ $sexe="Homme"; $ne="né";}
if($champ->getSexe()=="F") {$il="elle";} else{ $sexe="Homme"; $il="il";}
if($champ->getSexe()=="F") {$Il="Elle";} else{ $sexe="Homme"; $Il="Il";}
if($champ->getSexe()=="F") {$sponsorise="sponsorisée";} else{ $sexe="Homme"; $sponsorise="sponsorisé";}
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("athlètes")['donnees'];

$getMobileAds=$pub->getMobileAds("athlètes")['donnees'];

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
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}
$afficher_tab_medaille=false;
   foreach ($tab_med as $key => $value) {
            
    $equipe = false;
    $key  = $value['Intitule'];
    $key .= ($equipe)?" par equipes ":"";
    $key .= ' '.$value['Nom'].' '.substr($value['DateDebut'], 0,4);

    $tabResult[$key] = $value['ID'].'$'.$value['Rang'].'$'.$value['DateDebut'].'$';
    if($value['Rang'] < 4)
    {
        $afficher_tab_medaille=true;
        if(empty($tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']]))
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']] = 1;
        else
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']]++;
    }
        

} 


?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title><?php echo $champ->getNom();?>, coureur de marathon. Résultats, vidéos, photos, record.</title>
    <meta name="Description" lang="fr" content="<?php echo $champ->getNom();?> est athlète, marathonien. Pays: <?php echo $pays_intitule;?>. Record de <?php echo $champ->getNom();?> sur marathon, résultats, photos, vidéos.">
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="<?php echo $champ->getNom();?>, coureur de marathon. Résultats, vidéos, photos, record." />
    <meta property="og:image" content="https://dev.allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="<?php echo 'https://dev.allmarathon.fr/athlete-'.$champ->getId().'-'.slugify($champ->getNom()).'.html';?>" />
    <meta property="og:description" content="<?php echo $champ->getNom();?> est athlète, marathonien. Pays: <?php echo $pays_intitule;?>. Record de <?php echo $champ->getNom();?> sur marathon, résultats, photos, vidéos." />


    <link rel="apple-touch-icon" href="../../images/favicon.ico">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <?php echo '<link rel="canonical" href="https://dev.allmarathon.fr/athlete-'.$champ->getId().'-'.slugify($champ->getNom()).'.html" />';?>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> -->
    <link href="https://cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail champion-detail mt-77">
        <div class="row banniere1 ban ban_728x90">
            
            <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
            <?php
                if($pub728x90 !="") {
                echo '<a target="_blank" href="'.$pub728x90["url"].'" class="col-sm-12">';
                    echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
                    echo '</a>';
                }else if($getMobileAds !="") {
                echo $getMobileAds["code"] ? $getMobileAds["code"] :  "<a href=".$getMobileAds["url"]." target='_blank'><img src=".'../images/pubs/'.$getMobileAds['image'] . " alt='' style=\"width: 100%;\" /></a>";
                }
                ?></div>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">
                    <div class="col-sm-12">
                        
                            <?php 
                            $pays_datas=('9999-12-31'!=$champ->getDateChangementNat() && '0000-00-00'!=$champ->getDateChangementNat())?$pays->getFlagByAbreviation($champ->getNvPaysID())['donnees']:$pays->getFlagByAbreviation($champ->getPaysID())['donnees'];
                            if($pays_datas){
                                $flag=$pays_datas['Flag'];  
                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                            }
                            
                            
                            echo '<div class="row">
                                <div class="col-sm-12 athlete-detail-no-padding-left">
                                <h1>'.$champ->getNom().'</h1>';?>
                                <span class="athlete-details-breadcumb">
                                    <a href="liste-des-athletes.html">Athlètes</a> > <? if($pays_datas){ echo $pays_datas["NomPays"];}?> > <? echo $champ->getNom();?>
                                </span>
                            <? echo '</div>
                                <div class="col-sm-5 athlete-detail-no-padding-left no-padding-right">'; ?> 
                                
                                </div>
                            </div>
                        <?php echo rtrim($liste_admin, ","); ?>
                        <?php echo ($liste_admin!='') ? '<hr>' : ''; ?>
                        <div>
                            <!-- TAB NAVIGATION -->
                            <ul class="nav nav-tabs sub-menu" role="tablist">
                                <li class="active"><a href="#tab1" role="tab" class="sub-menu-link" data-toggle="tab">CV</a></li>
                                <li ><a href="#tab2" role="tab" class="sub-menu-link"
                                        data-toggle="tab">Résultats</a>
                                </li>
                                <? if($actus){?>
                                    <li>
                                        <a href="#tab5" role="tab" class="sub-menu-link" data-toggle="tab">Actus</a>
                                    </li>
                                <? }?>
                                <? if($videos){?>
                                    <li>
                                        <a href="#tab4" role="tab" class="sub-menu-link"  data-toggle="tab">Vidéos</a>
                                    </li>
                                <? }?>
                                <? if($photos){?>
                                    <li>
                                        <a href="#tab3" role="tab" class="sub-menu-link" data-toggle="tab">Photos</a>
                                    </li>
                                <? }?>
                               
                            </ul>
                        </div> <br /> 
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="tab1">
                                <?php ($champ->getTaille()!="" && $champ->getTaille()!=0 ) ? $taille="<li><strong>Taille : </strong>".$champ->getTaille()." cm</li>" : $taille="";
                            ($champ->getDateNaissance()!="0000-00-00" && $champ->getDateNaissance()!="" ) ? $date_naissance="<li><strong>".$ne." le : </strong>".$champ->getDateNaissance()."</li>" : $date_naissance="";
                            $champ_facebook=($champ->getFacebook())?'<li><a href="'.$champ->getFacebook().'" target="_blank">Visitez le facebook de <strong>'.$champ->getNom().'</strong></a></li>':'';
                            $champ_instagram=($champ->getInstagram())?'<li><a href="'.$champ->getInstagram().'" target="_blank">Visitez le instagram de <strong>'.$champ->getNom().'</strong></a></li>':'';
                            $champ_ee=($champ->getEquipementier())?' <li><strong>Equipementier :  </strong>'.$champ->getEquipementier().'</li>':'';
                            $champ_lse=($champ->getLien_site_équipementier())?'<li><strong>Lien site équipementier :  </strong>'.$champ->getLien_site_équipementier().'</li>':'';
                            $champ_bio=$champ->getBio();
                            $champ_taille=($champ->getTaille())?' <li><strong>Taille : </strong>'.$champ->getTaille().'</li>':'';
                            $champ_poids=($champ->getPoids())?'<li><strong>Poids : </strong>'.$champ->getPoids().'</li>':'';
                            /*$champ_=($champ->get())?'':'';
                            $champ_=($champ->get())?'':'';*/
                            $texte=$champ->getNom()." est ".$ne;
                           
                            if($pays_intitule){
                                $texte.=" ".$pays_prefixe." ".$pays_intitule;
                            }
                            if($champ->getDateNaissance()!="0000-00-00" && $champ->getDateNaissance()!="" ){
                                $texte.=" le ".utf8_encode(strftime("%A %d %B %Y",strtotime($champ->getDateNaissance())));

                            }
                            $texte.=".<br>";
                            if($champ->getTaille()){
                                $texte.=$Il." mesure ".$champ->getTaille()." cm ";
                            }
                            if($champ->getTaille() && $champ->getPoids()){
                                $texte.=" et ";
                            }
                            if($champ->getPoids()){
                                $texte.=$il." pèse ".$champ->getPoids()." kg";
                            }
                            if($champ->getTaille() && $champ->getPoids()){
                                $texte.=".<br>";
                            }
                            
                            if($champ->getEquipementier()){
                                $texte.="Actuellement ".$il." est ".$sponsorise." par ".$champ->getEquipementier().".";
                            }
                           
                            if($champ->getFacebook() || $champ->getInstagram()){
                                $texte.="Pour en savoir plus sur ".$champ->getNom()." vous pouvez visiter :<br>";
                            }
                            if($champ->getFacebook()){
                                $texte.="- sa page  <a href='".$champ->getFacebook()."' target='_blank'>Facebook</a><br>";
                            }
                            if($champ->getInstagram()){
                                $texte.="- son compte <a href='".$champ->getInstagram()."' target='_blank'>Instagram</a><br>";
                            }
                            echo $texte; ?>
                            <?if($champ_bio){
                                echo "<br>".$champ_bio;
                            }?>
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
                                            
                                        </tr>
                                        <?php    $i=1;
                                foreach ($tab as $cat_age_ligne) {
                                     foreach($cat_age_ligne as $event_name=>$event_line){
                                        
                                         foreach ($event_line as $type => $rangs) {
                                            $type=($type=="Equipe") ? " par équipe " : "";
                                            ?>
                                        <tr <?php if($i%2!=0){echo 'class="odd"';}?>>
                                            <td><?php echo $event_name.$type?></td>
                                            <td><?php echo (empty ($rangs[1]))?0:' '.$rangs[1]; ?></td>
                                            <td><?php echo (empty ($rangs[2]))?0:' '.$rangs[2]; ?></td>
                                            <td><?php echo (empty ($rangs[3]))?0:' '.$rangs[3]; ?></td>
                                        </tr>
                                        <?php }$i++;

                                                
                                    }
                                }
                                
                                
                                 }
                                 ?>
                                    </tbody>
                                </table>
                                <?php }?>
                            </div>
                            <div class="tab-pane fade" id="tab5">
                                <div class="news-liste-grid">
                                    <?php //var_dump($actus);
                                    foreach($actus as $index =>  $article){
                                        $subheader="auteur : ".$article->getAuteur()." / ".utf8_encode(strftime("%A %d %B %Y",strtotime($article->getDate())))." / source : ".$article->getSource();
                                            $cat="";

                                            $lien_1="";

                                            $text_lien_1="";

                                            $lien_2="";

                                            $text_lien_2="";

                                            $lien_3="";

                                            $text_lien_3="";

                                                        if($article->getLien1()!=""){

                                                            $lien_1=$article->getLien1();

                                                            $text_lien_1="> ".$article->getTextlien1();

                                                        }

                                                        if($article->getLien2()!=""){

                                                            $lien_2=$article->getLien2();

                                                            $text_lien_2="> ".$article->getTextlien2();

                                                        }

                                                        if($article->getLien3()!=""){

                                                            $lien_3=$article->getLien3();

                                                            $text_lien_3="> ".$article->getTextlien3();

                                                        }

                                                        $url_text=slugify($article->getTitre());

                                                    


                                                    

                                                            $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                                            $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                                            $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                                        echo '<article class="news-list-element-grid">';?>
                                                            <?

                                                    

                                                            echo '<div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                                                    

                                                        <div class="desc-img">

                                                            <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2>';

                                                            
                                                            if($article->getLien1()  ){
                                                                if( $article->getEvenementID()>0 ){
                                                                    $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                                                    $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                                                    $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                                    $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                                    $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                                                }
                                                                $lien_perso=$article->getLien1();
                                                                $texte_perso=$article->getTextlien1();
                                                                
                                                                echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                                            }
                                                            echo '</div>
                                                        </article>';


                                                        

                                        }

                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2">

                                <table id="classement_resultats" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th class=" headerSortDown">Année</th>
                                            <th class="">Rang</th>
                                            <th class="">Course</th>
                                            <th class="">Temps</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                	foreach ($resultats_champ as $key => $value) {
                                        $cat_age=$ev_cat_age->getEventCatAgeByID($value['CategorieageID'])['donnees']->getIntitule();
                                        $cat_event=$ev_cat_event->getEventCatEventByID($value['CategorieID'])['donnees']->getIntitule();
                                        
                                        $nom_res=slugify($cat_event.' - '.$value['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($value['DateDebut']))));
                                		echo '<tr>
			                                    <td>'.$value['annee'].'</td>
			                                    <td align="left">'.$value['Rang'].'</td>
			                                    <td><a href="/resultats-marathon-'.$value['ID'].'-'.$nom_res.'.html">'.$value['Intitule'].' - '.$value['Nom'].'</a></td>
			                                    <td>'.$value['Temps'].'</td>
			                                </tr>';
                                	}
                                    /*if(sizeof($resultsPerso)!=0){
                                        foreach ($resultsPerso as $resultPerso) {
                                             $status=($resultPerso['Status'])?" (validé)":" (non validé)";
                                                echo '<tr>
                                                <td>'.$resultPerso['Date'].'</td>
                                                <td align="left">'.$resultPerso['Rang'].'</td>
                                                <td class="capitalize">'.$resultPerso['intitule'].$status.'</td>
                                                <td >'.$resultPerso['Temps'].'</td>
                                            </tr>';
                                           
                                        }
                                        }  */
                                	 ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <?php 
								if(sizeof($photos)!=0){
									foreach ($photos as $key => $photo) {
                            			echo '<li><a href="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" class="fancybox" rel="group" ><img  src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="auto" alt=""/></a></li>';
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
	                                if($vd->getDuree()!=''){
                                        $duree="<li><span class='material-symbols-outlined'>timer</span>Durée de la vidéo : ".$vd->getDuree()."</li>";
                                    }
                                    $res_event="";
                                    if($vd->getEvenement_id()){
                                        $evenement=$event->getEvenementByID($vd->getEvenement_id())["donnees"];
                                        $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                
                                        $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                        $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                
                                        $res_event= "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 disp-flex'><span class='material-symbols-outlined'>trophy</span> Résultats </a>";
                                        
                                    }
                                    $url_img1=str_replace("hqdefault","0",$vd->getVignette());
                                        $url_img=str_replace("default","0",$url_img1);
                                    
                                echo '<div class="video-align-top video-grid-tab">
                                    <div class="mr-5"><a href="video-de-marathon-'.$vd->getId().'.html"><div class="video-thumbnail" style="background-image: url('.$url_img.'"></div></a></div>
                                    <div class="video-t-d-res">
                                        <ul>
                                            <li><a href="video-de-marathon-'.$vd->getId().'.html" class="video_titre">'.$vd->getTitre().'</a></li>'.$duree.'
                                        </ul>
                                        '.$res_event.'
                                    </div>
                                </div>';
                                }
                            	 ?>
                                </ul>
                            </div>
                            
                        </div>
                        <span style="height:100px;width: 20px;opacity:0;">span</span>

                        <!--h2 class="bordered" style="margin-top:95px;">laissez un message à
                            <?php //echo $champ->getNom(); ?></h2-->
                        <?php
                       
                    ?>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <div class="ban ban_300x60 width-60 mb-30">
                    <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
                    <?php
                        if($pub300x60 !="") {
                        echo '<a target="_blank" href="'.$pub300x60["url"].'" >';
                            echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
                            echo '</a>';
                        }
                    ?>
                </div></div>
            <?php if (isset($_SESSION['msg_adm_fiche'])){?>
                    
                    <div class="modal fade in" id="AddResultResponseModal" tabindex="-1" role="dialog" aria-labelledby="AddResultResponseModal" aria-hidden="true">
                    <div class="add-result-box">
                        <?php echo $_SESSION['msg_adm_fiche'];?>
                        
                    </div>
                </div>
                <?php }?>
            <div class="modal fade" id="revendicationFicheModal" tabindex="-1" role="dialog" aria-labelledby="revendicationFicheModal" aria-hidden="true">
                    <div class="add-result-box">
                        <h2>Revendication de la fiche de <?php echo $champ->getNom();?></h2>
                    <ul class="mb-30">
                        <li>
                        <b>1.</b>  Pour revendiquer la fiche de <?php echo $champ->getNom();?>, remplissez le formulaire ci-dessous.
                        </li>
                        <li>
                        <b>2.</b>  Si votre demande est acceptée, vous pourrez alors modifier les informations personnelles concernant <?php echo $champ->getNom();?> et ajouter des résultats en vous rendant dans votre Espace Compte.
                        </li>
                    </ul>
                        <form action="/content/modules/administrer-fiche.php" enctype="multipart/form-data" method="post" class="form-horizontal"
                            id="target">
                            <input type="hidden" name="c" id="c_id" value="<?php echo $champ->getID(); ?>" />
                            <input type="hidden" name="u" id="u_id" value="<?php echo  $user_id; ?>" />
                            
                            <div class="form-group">
                                <label for="situation" class="col-sm-3">Votre situation par rapport à cet athlète <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <select name="situation" id="situation" required>
                                        <option value="moi">C'est moi</option>
                                        <option value="parent">Parent</option>
                                        <option value="ami">Ami</option>
                                        <option value="entraineur">Entraîneur</option>
                                        <option value="autre">Autre</option>
                                    </select>                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="justificatif" class="col-sm-3">Justificatif </label>
                                <div class="col-sm-9">
                                    <input type="file" id="justificatif" name="j" accept="image/png, image/jpeg, application/pdf" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3">Message</label>
                                <div class="col-sm-9">
                                    <textarea cols="50" rows="4" name="message" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                            <div class="col-sm-5">
                                </div>
                                <div class="col-sm-7">
                                <input id="ajouter-resultat"  type="submit" class="call-to-action" value="envoyer">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <?php if(!$isAdmin){?>
                    <div class="box-next-edition ">
                        <div class="mx-auto" style="width:80%">
                        Vous êtes le coureur présenté sur cette page ?
                        </div>
                        <div>
                            <?php if(empty($_SESSION['user_id'])){?>
                                <a  id="connecter-et-revendiquer" href="#"  class="call-to-action mx-auto">
                                    Revendiquer cette fiche
                                </a>
                            <? }else{?>
                                <a id="deja-connecte-et-revendiquer" href="#" data-toggle="modal" data-target="#revendicationFicheModal" class="call-to-action mx-auto">
                                    Revendiquer cette fiche
                                </a>
                            <? }?>
                        </div>
                    
                        
                    </div>
                <?php }?>
                <div class="marg_bot"></div>
                    <div class="ban ban_160-600">
                        <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
                        <?php
                        if($pub160x600 !="") {
                            //var_dump($pub160x600["url"]); exit;
                            if($pub160x600["code"]==""){
                                echo "<a href=".'https://allmarathon.net/'.$pub160x600["url"]." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
                            }
                            else{
                                echo $pub160x600["code"];
                            }
                        }?>
                    </div></div>
                    <div class="marg_bot"></div>
                    <div class="ban ban_300x250 to_hide_mobile">
                        <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
                        <?php
                        if($pub300x250 !="") {
                            //var_dump($pub300x250["url"]); exit;
                            if($pub300x250["code"]==""){
                                echo "<a href=".''.$pub300x250["url"]." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" /></a>";
                            }
                            else{
                                echo $pub300x250["code"];
                            }
                        }
                        ?>
                    </div></div>
                    
            </aside>
           
        </div>
        <div class="row banniere1 ban ban_768x90 ">
            
            
            <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             
                <div  class="col-sm-12 ads-contain">
                    <?php
                    if($pub768x90 !="") {
                    echo '<a target="_blank" href="'.$pub768x90["url"].'" class="col-sm-12">';
                        echo $pub768x90["code"] ? $pub768x90["code"] :  "<img src=".'../images/pubs/'.$pub768x90['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>
    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); 
    if (isset($_SESSION['msg_adm_fiche'])) {
        unset($_SESSION['msg_adm_fiche']);
    }
    ?>



    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/jquery.jcarousel.min.js"></script>
    <script src="/js/jquery.sliderPro.min.js"></script>
    <script src="/js/easing.js"></script>
    <script src="/js/jquery.ui.totop.min.js"></script>
    <script src="/js/herbyCookie.min.js"></script>
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
        function deleteCookie(cname) {
            document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            console.log("Cookie supprimé: " + cname);
        }
         function getCookieValue(name) {
            // Create a regular expression to search for the cookie name
            const cookieValue = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return cookieValue ? cookieValue.pop() : '';
        }
    $(document).ready(function() {
                $(window).load(function() {
                    setTimeout(function(e){ $('#AddResultResponseModal').modal('show'); }, 2000);
                });
                $('#deja-connecte-et-revendiquer').on('click',function(e){
                    cname="page_when_logging_to_rev_fiche"
                    cvalue=window.location.href
                    exdays=1
                    //e.preventDefault();
                    const d = new Date();
                    d.setTime(d.getTime() + (exdays*24*60*60*1000));
                    let expires = "expires="+ d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                    console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)
                });
                $('#connecter-et-revendiquer').on('click',function(e){
                    $('input[type="checkbox"].openSidebarMenu').prop( "checked", true );
                    cname="page_when_logging_to_rev_fiche"
                    cvalue=window.location.href
                    exdays=1
                    //e.preventDefault();
                    const d = new Date();
                    d.setTime(d.getTime() + (exdays*24*60*60*1000));
                    let expires = "expires="+ d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                    console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)
                });
            
            // Lire le cookie "open_rev_fiche_modal"
            var open_rev_fiche_modal = getCookieValue("open_rev_fiche_modal");

            // Vérifier si le cookie existe et afficher sa valeur
            if (open_rev_fiche_modal ) {
                console.log("La valeur du open_rev_fiche_modal monCookie est : " + open_rev_fiche_modal);
                $('#revendicationFicheModal').modal('show');
                deleteCookie("open_rev_fiche_modal")
            } else {
                console.log("Le cookie open_rev_fiche_modal n'existe pas.");
            }
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
            $('#gerer-cette').on('click',function(e){
                    $('#SigninModal').modal('show');});
                    
			</script>";
	}
?>
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