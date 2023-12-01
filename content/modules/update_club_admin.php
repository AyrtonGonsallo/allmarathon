<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!isset($_GET['clubID']))
{
    header('Location: /contact-clubs-de-marathon.html');
    exit();
}
else{
    $clubID=$_GET['clubID'];
}

include("../classes/club.php");
include("../classes/club_horaires.php");
include("../classes/club_athlètes.php");
include("../classes/pays.php");
include("../classes/club_admin_externe.php");

$club_admin_externe=new club_admin_externe();

$p=new pays();

function VerifierAdresseMail($adresse)  
{  
 $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';  
 if(preg_match($Syntaxe,$adresse))  
  return true;  
else  
   return false;  
}

// function to geocode address, it will return false if unable to geocode address
function geocode($address){
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);
    if($resp['status']=='OK'){
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
        if($lati && $longi && $formatted_address){
            $data_arr = array();
            array_push(
                $data_arr,$lati,$longi,$formatted_address);
            return $data_arr;

        }else{
            return false;
        }

    }else{
        return false;
    }
}

$cl=new club();
$club=$cl->getClubById($clubID);

$clh=new club_horaires();
$clj=new club_athlètes();

if(isset( $_POST['edit_club_admin'])){
	
	$pays=(isset($_POST['pays']))? $_POST['pays'] : "";
	$responsable = (isset($_POST['responsable']))? $_POST['responsable'] : "";
    $telephone =(isset($_POST['telephone']))? $_POST['telephone'] : "";
    $site = (isset($_POST['site']))? $_POST['site'] : "";
    $description=(isset($_POST['description']))? $_POST['description'] : "";
    $ville=(isset($_POST['ville']))? $_POST['ville'] : "";
    $CP=(isset($_POST['cp']))? $_POST['cp'] : "";
    $adresse=(isset($_POST['adresse']))? $_POST['adresse'] : "";
    $email=(isset($_POST['email']))? $_POST['email'] : "";
    $departement=substr(trim($_POST['cp']), 0, 2);

    $nom_pays=$p->getFlagByAbreviation($pays)['donnees']['NomPays'];
    $adresse_complete= array('','','' );
    $adresse_complete= geocode($adresse.','.$ville.','.$nom_pays);
    $gcoo1=$adresse_complete[0];
    $gcoo2=$adresse_complete[1];
    $gaddress=$adresse_complete[2];

    if (!VerifierAdresseMail($email)) {
        $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Votre adresse e-mail n\'est pas valide.<br/></span>';
        header('Location: /club-admin-'.$clubID.'.html');
        
    }else{
        $club_updated=$cl->editClub($clubID,$pays,$responsable,$telephone,$email,$site,$description,$ville,$CP,$departement,$adresse,$gcoo1,$gcoo2,$gaddress);
    }
// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $club_updated['validation']==true) {

        $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Modification réussie !<br/><br/></span>';
        header('Location: /club-admin-'.$clubID.'.html');} 

        else {

            $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer !<br/><br/></span>';
            header('Location: /club-admin-'.$clubID.'.html');
        };
    }

    elseif (isset($_POST['supprimer_horaire_club'])){
        for($i=0,$n=count($_POST["h_sup"]);$i<$n;$i++){
            $supprimer_horaires=$clh->deleteHoraireClub($_POST["h_sup"][$i]);
        }
        if( $supprimer_horaires['validation']==true) {

            $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Modification des horaires réussie !<br/><br/></span>';
            header('Location: /club-admin-'.$clubID.'.html');} 

            else {

                $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer !<br/><br/></span>';
                header('Location: /club-admin-'.$clubID.'.html');
            };
        }

        elseif (isset($_POST['supprimer_athlète_club'])){
            for($i=0,$n=count($_POST["j_sup"]);$i<$n;$i++){
                $supprimer_horaires=$clj->deleteJuokasClub($_POST["j_sup"][$i]);
            }
            if( $supprimer_horaires['validation']==true) {

                $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Modification des juokas réussie !<br/><br/></span>';
                header('Location: /club-admin-'.$clubID.'.html');} 

                else {

                    $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer !<br/><br/></span>';
                    header('Location: /club-admin-'.$clubID.'.html');
                };
            }

            elseif(isset($_POST['ajouter_horaire_club'])){
                header('Location: /club-admin-horaires-'.$clubID.'.html');
            }

            elseif(isset($_POST['ajouter_athlète_club'])){
               header('Location: /club-admin-athlètes-'.$clubID.'.html');
           }
           elseif(isset($_POST['ajout_horaire'])){
            
            $h_jour=(isset($_POST['h_jour'])) ? $_POST['h_jour'] : "" ;
            $h_hdeb=(isset($_POST['h_hdeb'])) ? $_POST['h_hdeb'] : "" ;
            $h_hfin=(isset($_POST['h_hfin'])) ? $_POST['h_hfin'] : "" ;
            $h_desc=(isset($_POST['h_desc'])) ? $_POST['h_desc'] : "" ;
            $h_num=(isset($_POST['h_num'])) ? $_POST['h_num'] : "" ;

            if($h_jour=="" || $h_hdeb=="" || $h_hfin=="" || $h_num==""){
               $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Veuillez remplir les champs obligatoires !<br/><br/></span>';
               header('Location: /club-admin-horaires-'.$clubID.'.html');

           }
           else{
               $new=$clh->addHoraireClub($clubID,$_POST['h_jour'],$_POST['h_hdeb'],$_POST['h_hfin'],$_POST['h_desc'],$_POST['h_num']);
               if($new['validation']){
                $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Modification des horaires réussie !<br/><br/></span>';
                header('Location: /club-admin-horaires-'.$clubID.'.html');
            }
            else{
                $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer !<br/><br/></span>';
                header('Location: /club-admin-horaires-'.$clubID.'.html');

            }
        }
        
        

    }
    elseif(isset($_POST['ajout_athlète'])){
        
        $j_idchamp=(isset($_POST['j_idchamp'])) ? $_POST['j_idchamp'] : "" ;
        $j_name=(isset($_POST['j_name'])) ? $_POST['j_name'] : "" ;

        if($j_idchamp=="" || $j_name==""){
               $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Veuillez renseigner le champion !<br/><br/></span>';
               header('Location: /club-admin-athlètes-'.$clubID.'.html');

           }
           else{
               $new=$clj->addJuokasClub($clubID,$j_idchamp,$j_name);
               if($new['validation']){
                $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Modification des athlètes réussie !<br/><br/></span>';
                header('Location: /club-admin-athlètes-'.$clubID.'.html');
            }
            else{
                $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer !<br/><br/></span>';
                header('Location: /club-admin-athlètes-'.$clubID.'.html');

            }
        }


        
    }

      elseif(isset($_POST['add_club_admin'])){

                $prenom=(isset($_POST['prenom'])) ? $_POST['prenom'] : "" ;
                $nom=(isset($_POST['nom'])) ? $_POST['nom'] : "" ;
                $fonction=(isset($_POST['fonction'])) ? $_POST['fonction'] : "" ;
                $telephone=(isset($_POST['telephone'])) ? $_POST['telephone'] : "" ;

                if($prenom=="" || $nom=="" || $fonction=="" || $telephone==""){
                 $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Veuillez renseigner les champs obligatoires.<br/><br/></span>';
                 header('Location: /formulaire-administration-club-'.$clubID.'.html');
                
                    }
             else{
                 if (isset($_POST['g-recaptcha-response'])) {
                    $captcha = $_POST['g-recaptcha-response'];
                    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lf4UxIUAAAAAD2diPAd3BH227Om0q76bVHqtL2T&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
                    $response = json_decode($verifyResponse);
                    if($response->success == false)
                        {
                            $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
                            header('Location: /formulaire-administration-club-'.$clubID.'.html');
                        }
                        else{
                            $club_admin_externe->addAdminClub($nom,$prenom,$telephone,$fonction,$user_id,$clubID,$_SERVER["REMOTE_ADDR"],date('Y-m-d H:i:s'));
                            $_SESSION['update_club_msg']= '<br><span style="color:green; font-size:0.8em">Votre demande a été envoyée a l\'administrateur du site.<br/><br/></span>';
                            header('Location: /formulaire-administration-club-'.$clubID.'.html');
                            
                        }
                }
                else{
                 $_SESSION['update_club_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
                 header('Location: /formulaire-administration-club-'.$clubID.'.html');
                
                }
                
                
            }



        }


    ?>