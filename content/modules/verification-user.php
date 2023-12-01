<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Etc/UTC');

require_once('functions.php');
require_once('../classes/user.php');
require_once ('../libs/PHPMailer-master/PHPMailerAutoload.php');

$user=new user();

$response_array['status'] = 'error';
$response_array['message'] = '';

if(isset($_POST['register_button']) && !empty($_POST['register_button'])){
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        
        $secret = '6Lf2-bwlAAAAADuE8YsrNoV5QRlYgc3x6QV4awPP';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $response = json_decode($verifyResponse);
        $errors = "";
    if ($response->success == true) {

        $error = "";

        $username = (!empty($_POST["pseudo"])) ? $_POST['pseudo'] : "";
        $pass1 = (!empty($_POST["mot_de_passe"])) ? $_POST['mot_de_passe'] : "";
        $pass2 = (!empty($_POST["confirmePW"])) ? $_POST['confirmePW'] : "";
        $nom = (!empty($_POST["nom"])) ? $_POST['nom'] : "";
        $prenom = (!empty($_POST["prenom"])) ? $_POST['prenom'] : "";
        $mail = (!empty($_POST["email"])) ? $_POST['email'] : "";
        $dn = (!empty($_POST["date_naissance"])) ? $_POST['date_naissance'] : "";
        $sexe = (!empty($_POST["Sexe"])) ? $_POST['Sexe'] : "";
        $pays = (!empty($_POST["pays"])) ? $_POST['pays'] : "";
        $LieuNaissance=$_POST['LieuNaissance'];
        $Equipementier=$_POST['Equipementier'];
        $lien_equip=$_POST['lien_equip'];
       $Instagram=$_POST['Instagram'];
       $p=$_POST['poids'];
       $taille=$_POST['taille'];
       $Facebook=$_POST['Facebook'];
       $Bio=$_POST['Bio'];
       
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
                
                $new_user=$user->addNewUser($username,$password,$nom,$prenom,$mail,$dn,$sexe,$pays,$newsletter,$offres,$t,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$p,$taille,$Facebook,$Bio);
                
                $_SESSION['user_id'] = $new_user['id'];
                $_SESSION['user']=$username;
                if( $_POST['recevoir_mail']==1){
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $message = '<html>
                    <head>
                    <title>Identifiants allmarathon </title>
                    </head>
                    <body>
                    Bonjour ' . $username . ',<br>

                    Merci pour votre inscription sur allmarathon, voici vos identifiants de connexion :<br>
                    Pseudo : ' . $username . '<br>
                    Votre mot de passe est celui que vous avez renseign&eacute; lors de l\'inscription.<br>
                    Pour vous connecter veuillez cliquer sur <a href="">ce lien</a><br><br>
                    L\'&eacute;quipe allmarathon est heureuse de vous compter parmi ses membres.<br><br>


                    Cordialement.
                    </body></html>';
                    // En-têtes additionnels
                    $headers .= 'From: allmarathon.fr' . "\r\n";
                    $to = $mail;
                    $php_mail = new PHPMailer;
                    
                    $php_mail->isSMTP();
                    $php_mail->Host = 'authsmtp.securemail.pro';
                    $php_mail->Port = 465;
                    $php_mail->SMTPSecure = 'ssl';
                    $php_mail->SMTPAuth = true;
                    $php_mail->Username = "martinodegaardnash@gmail.com";
                    $php_mail->Password = "zJX8CvbTgAH7";
                    $php_mail->SetFrom("martinodegaardnash@gmail.com","Allmarathon Contact");
                    //$php_mail->addAddress($mail, $nom.' '.$prenom);
                    $php_mail->AddAddress($mail);
                    $php_mail->IsHTML(true);
                    $php_mail->Subject = 'Identifiants allmarathon';
                    // $php_mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
                    $php_mail->AltBody = 'This is a plain-text message body';
                    $php_mail->Body=$message;
                    if (!$php_mail->send()) {
                        $response_array['status'] = 'error';
                        $response_array['message'] .= "<span style='color:#cc0000;'> Erreur lors de l'envoi du mail ".$php_mail->ErrorInfo."</span><br><br>";
                    } else {
                        $response_array['status'] = 'success';
                        $response_array['message'] .= "<span style='color:green;'>Votre compte vient d'être créé, vous avez reçu un mail.</span><br><span style='color:green;'>merci et bienvenu parmi les membres de la communauté allmarathon! </span><br><span style='color:green;'>Nous vous conseillons d'associer votre compte à google pour vous connecter plus rapidement.</span><br>";
                    }
                    }
                    $response_array['status'] = 'success';
                    $response_array['message'] .= "<span style='color:green;'>Votre compte vient d'être créé, vous n'avez pas demandé à recevoir un mail.</span><br><span style='color:green;'>Merci et bienvenu parmi les membres de la communauté allmarathon! </span><br><span style='color:green;'>Nous vous conseillons d'associer votre compte à google pour vous connecter plus rapidement.</span><br>";
                   
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

    $_SESSION['msg_inscription']=$response_array['message'];
    header('Location: /formulaire-inscription.php');
}