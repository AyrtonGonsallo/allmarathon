<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Etc/UTC');

require_once('functions.php');
require_once('testMails.php');// envoyerEmail($dest,$sujet,$contenu_html,$contenu_text)
require_once('../classes/user.php');
require_once ('../libs/PHPMailer-master/PHPMailerAutoload.php');

$user=new user();
$page=$_COOKIE["page_when_creating_account"];
$response_array['status'] = 'error';
$response_array['message'] = '';

if(isset($_POST['register_button']) && !empty($_POST['register_button'])){
    if(1==1){

        //$former_secret = '6Lf2-bwlAAAAADuE8YsrNoV5QRlYgc3x6QV4awPP';
        $secret = '6LdcITUpAAAAALEmLKI-oW8aMZdXAKHhJxd-V8B6';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $response = json_decode($verifyResponse);
        $errors = "";
    if (1==1) {

        $error = "";

        
        $pass1 = (!empty($_POST["mot_de_passe"])) ? $_POST['mot_de_passe'] : "";
        $pass2 = (!empty($_POST["confirmePW"])) ? $_POST['confirmePW'] : "";
        $nom = (!empty($_POST["nom"])) ? $_POST['nom'] : "";
        $prenom = (!empty($_POST["prenom"])) ? $_POST['prenom'] : "";
        $username =  $nom." ".$prenom;
        $mail = (!empty($_POST["email"])) ? $_POST['email'] : "";
       
       
        $newsletter = (!empty($_POST['newsletter'])) ? $_POST['newsletter'] : 0;
        $offres = (!empty($_POST['offres'])) ? $_POST['offres'] : 0;
        $password = encrypt($pass1);

        $t = time();

        if ($pass1 == "" or $pass2 == "" or $pass1 != $pass2) {

            $response_array['message'] .= "<span style='color:#cc0000; font-size:0.8em'>Les mots de passe doivent correspondre</span><br>";
            $error = "faux";


        }
        // or $dn == "" or $cp == "" or $ville == "" or $pays == ""
        if ($username == "" or $nom == "" or $prenom == "" or $mail == "" ) {
            $response_array['message'] .= "<span style='color:#cc0000;'>Merci de remplir les champs obligatoire *</span><br><br>";
            $error = "faux";

        }
        if (!email_V($mail)) {

            $response_array['message'] .= "<span style='color:#cc0000;'>Votre adresse e-mail n'est pas valide.</span><br><br>";
            $error = "faux";
        }

        if(empty($error)){
            $check_user=$user->checkEmailUsername($mail,$username);

            if(!$check_user['validation'] ){
                
                $new_user=$user->addNewUser2($username,$password,$nom,$prenom,$mail);
                
                $_SESSION['user_id'] = $new_user['id'];
                $_SESSION['user']=$username;
                
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $message = '<html>
                    <head>
                    <title>Identifiants allmarathon </title>
                    </head>
                    <body>
                    Bonjour ' . $username . ',<br><br>

                    Merci pour votre inscription, nous sommes ravis de vous compter parmi la communauté de allmarathon.fr<br>
                    Votre compte vous permet de vous abonner à notre newsletter, de revendiquer et d\'administrer la fiche d\'un coureur (infos personnelles, résultats).<br>
                    Si vous rencontrez des difficultés n\'hésitez pas à nous en faire part en réponse à ce mail.<br>
                    Vous pouvez également nous retrouver sur les réseaux sociaux :<br><br>
                    Instagram : https://www.instagram.com/allmarathon.fr<br>
                    Facebook : https://www.facebook.com/allmarathon.fr<br>
                    Pinterest : https://www.pinterest.fr/allmarathon/<br><br>
                    Très Cordialement<br>
                    L\'équipe de allmarathon.fr<br>
                    </body></html>';
                  
            }else{ 
                $response_array['status'] = 'error';
                $response_array['message'] .= "<span style='color:#cc0000;'> Pseudo ou e-mail existe déjà s'il vous plaît choisir un autre</span><br><br>";
            }

        }else{
            print_r($response_array['message'] );
        }

        
    } else {
        $response_array['message'] .= 'Captcha invalide';
    }

    }else{
        $response_array['message']= "<span style='color:#cc0000;'>Captcha invalide</span>";
    }

    $res=envoyerEmail($mail,'Identifiants allmarathon',$message,'This is a plain-text message body');
   
    if (!$res) {
        $response_array['status'] = 'error';
        $response_array['message'] .= "<span style='color:#cc0000;'> Erreur lors de l'envoi du mail ".$php_mail->ErrorInfo."</span><br><br>";
        $_SESSION['msg_inscription']=$response_array['message'];
        header('Location: /formulaire-inscription.php');
    } else {
        $response_array['status'] = 'success';
        $response_array['message'] .= "<span style='color:green;'>Votre compte vient d'être créé, vous avez reçu un mail.</span><br><span style='color:green;'>merci et bienvenu parmi les membres de la communauté allmarathon! </span><br><span style='color:green;'>Nous vous conseillons d'associer votre compte à google pour vous connecter plus rapidement.</span><br>";
        header('Location:  '.$page);                    }
    
}