<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ('../libs/PHPMailer-master/PHPMailerAutoload.php');

function email_V($email)
{
    $atom   = '[-a-z0-9_]';   
    $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; 
    $regex = '/^' . $atom . '+' .   
    '(\.' . $atom . '+)*' .         // Suivis par z&eacute;ro point ou plus
                                    // s&eacute;par&eacute;s par des caract&egrave;res autoris&eacute;s avant l'arobase
    '@' .                           // Suivis d'un arobase
    '(' . $domain . '{1,63}\.)+' .  // Suivis par 1 &agrave; 63 caract&egrave;res autoris&eacute;s pour le nom de domaine
                                    // s&eacute;par&eacute;s par des points
    $domain . '{1,63}$/i';          // Suivi de 2 &agrave; 63 caract&egrave;res autoris&eacute;s pour le nom de domaine

    // test de l'adresse e-mail
    if (preg_match($regex, $email)):
        return true;
    else:
        return false;
    endif;
}
/*$phpmail = new PHPMailer(); // create a new object
//$phpmail->setLanguage('fr', '/optional/path/to/language/directory/');
$phpmail->IsSMTP(); // enable SMTP
$phpmail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$phpmail->SMTPAuth = true; // authentication enabled
$phpmail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
$phpmail->Host = "tls://smtp.gmail.com";
$phpmail->Port = 587; 
//$phpmail->IsHTML(true);
$phpmail->Username = "contact.allmarathon@gmail.com";
$phpmail->Password = "jujigatame";
$phpmail->AddAddress("ilanssari@ippon.fr");
$phpmail->SetFrom("contact.allmarathon@gmail.com","issam");
$phpmail->Subject = 'walo';
$phpmail->Body = "walo 2";
$phpmail->Send();*/

$phpmail = new PHPMailer; // create a new object
        $phpmail->IsSMTP(); // enable SMTP
        $phpmail->Host = "smtp.gmail.com";
        $phpmail->Port = 465; // or 587
        $phpmail->SMTPDebug = 2;
        $phpmail->SMTPSecure = 'ssl';
        $phpmail->SMTPAuth = true; // authentication enabled
        $phpmail->Username = "allmarathon703@gmail.com";
        $phpmail->Password = "P!r99Ln&2wlUsRt2";
        //$phpmail->SetFrom("alljudo.net@gmail.com","issam");
        $phpmail->AddAddress('allmarathon703@gmail.com');
        $phpmail->IsHTML(true);
        /*if (!$phpmail->send()) {
            echo "Mailer Error: " . $phpmail->ErrorInfo;
        } else {
            echo "Message sent!";
        }*/

// $phpmail->AddAddress("lmathieu@alljudo.net");
if( isset($_POST['add_partenaire'])) {
    $nom = $_POST['nom_prenom'];
    $mail = $_POST['mail'];
    $telephone = $_POST['telephone'];
    $entreprise = $_POST['entreprise'];
    $message = $_POST['message'];
    $erreur=false;
    if($entreprise == "" || $nom == "" || $mail == "" || $telephone == "" || $message == ""){
        $_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">Tous les champs sont obligatoires !<br/><br/></span>';
        $erreur=true;
        header('Location: /partenaires.php');
    }
    if($mail == "" || !email_V($mail))
    {
        $_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">L\'adresse Email est invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /partenaires.php');

    }

    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    if (!$captcha) {
        $_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /partenaires.php');
    }
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lf4UxIUAAAAAD2diPAd3BH227Om0q76bVHqtL2T&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    if($response.success == false)
    {
        $_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /partenaires.php');
    }
    if(!$erreur) {

        $phpmail->SetFrom("allmarathon703@gmail.com",$nom);
        $phpmail->Subject = "Demande d'informations: " . $nom . " - " . $telephone . " - " . $mail . " - Entreprise: " . $entreprise;
        // $message_envoyer=utf8_encode($message);
        $phpmail->Body = $message;
        if(!$phpmail->Send()) {
            $_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">Mailer Error: ' . $phpmail->ErrorInfo.'</span>';
            header('Location: /partenaires.php');
        } else {
            $_SESSION['add_part']= '<br><span style="color:green; font-size:0.8em">Votre demande vient de nous être envoyé, nous vous répondrons dans les meilleurs délais, <br /><br />Cordialement<br/><br/></span>';
            header('Location: /partenaires.php');
        }
}
else{
    //$_SESSION['add_part']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, merci de réessayer.</span>';
            header('Location: /partenaires.php');
}
}

elseif( isset($_POST['add_site_partenaire'])) {
    $nom = $_POST['nom_prenom'];
    $mail = $_POST['mail'];
    $telephone = $_POST['telephone'];
    $site = $_POST['site'];
    $message = $_POST['message'];
    $erreur=false;
    if($site == "" || $nom == "" || $mail == "" || $telephone == "" || $message == ""){
        $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">Tous les champs sont obligatoires !<br/><br/></span>';
        $erreur=true;
        header('Location: sites-partenaires.php');
    }
    if($mail == "" || !email_V($mail))
    {
        $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">L\'adresse Email est invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /sites-partenaires.php');

    }

    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    if (!$captcha) {
        $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /sites-partenaires.php');
    }
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lf4UxIUAAAAAD2diPAd3BH227Om0q76bVHqtL2T&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    if($response.success == false)
    {
        $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">Captcha invalide.<br/><br/></span>';
        $erreur=true;
        header('Location: /sites-partenaires.php');
    }
    if(!$erreur) {

        $phpmail->SetFrom($mail,$nom);
        $phpmail->Subject = "Demande d'informations: " . $nom . " - " . $telephone . " - " . $mail . " - Site: " . $site;;
        // $message_envoyer=utf8_encode($message);
        $phpmail->Body = $message;
        if(!$phpmail->Send()) {
            $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">Mailer Error: ' . $phpmail->ErrorInfo.'</span>';
            header('Location: /sites-partenaires.php');
        } else {
            $_SESSION['add_site_part']= '<br><span style="color:green; font-size:0.8em">Votre demande vient de nous être envoyé, nous vous répondrons dans les meilleurs délais, <br /><br />Cordialement<br/><br/></span>';
            header('Location: /sites-partenaires.php');
        }
}
else{
    $_SESSION['add_site_part']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, merci de réessayer.</span>';
            header('Location: /sites-partenaires.php');
}

}

?>