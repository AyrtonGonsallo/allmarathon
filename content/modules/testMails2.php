<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function envoyerEmail($dest, $sujet, $contenu_html, $contenu_text) {
    require_once ('../libs/PHPMailer-master/PHPMailerAutoload.php');
    try {
        $mail = new PHPMailer(true); // true active les exceptions
        // Paramètres du serveur SMTP de Gmail
        $mail->isSMTP(); // Utiliser SMTP
        $mail->Host = 'dev.allmarathon.fr'; // Serveur SMTP
        $mail->SMTPAuth = true; 
        $mail->Username = 'nash@dev.allmarathon.fr'; // Votre adresse email
        $mail->Password = 'ltiryvtcoodddwhf'; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 
        // Encodage
        $mail->CharSet = 'UTF-8'; 
        // Destinataire et expéditeur
        $mail->setFrom('nash@dev.allmarathon.fr', 'dev.allmarathon.fr');
        $mail->addAddress($dest, 'Destinataire');

        // Contenu du message
        $mail->isHTML(true); 
        $mail->Subject = $sujet;
        $mail->Body = $contenu_html;
        $mail->AltBody = $contenu_text;

        // Envoyer l'e-mail
        $mail->send();
        $res = true;
    } catch (Exception $e) {
        $res = false;
    }
    return $res;
}

$sujet = "Inscription sur Allmarathon";
$contenu_html = "
    <html>
    <head>
    <style>
            a{color:#000 !important;text-decoration:none !important;}
            a:hover { color: #FBFF06 !important; }
            .home-link:hover { background-color: #95d7fe !important; color: #000 !important; }
            .icon-hov:hover img{filter: invert(98%) sepia(24%) saturate(6709%) hue-rotate(357deg) brightness(108%) contrast(107%)!important;}
        </style>
    </head>
    <body style='font-family: Arial, sans-serif;'>
        <div style='margin: 20px;'>
            <h1 style='color: #95d7fe;font-family: 'Montserrat';font-weight: 900;'>Bonjour User 557,</h1>
            <p>Merci pour votre inscription sur allmarathon, voici vos identifiants de connexion :</p>
            <p><strong>Nom d'utilisateur:</strong> User 557</p>
            <p>Votre mot de passe est celui que vous avez renseigné lors de l'inscription.</p>
            <p>Vous pouvez vous connecter en cliquant sur ce lien :</p>
            <a class='home-link' href='https://dev.allmarathon.fr' style='background-color: #fbff0b; color: #000 !important; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px;'>Connectez-vous</a>

            <p>L'équipe allmarathon est heureuse de vous compter parmi ses membres.</p>
            <p>Cordialement,</p>
            
            <!-- Footer -->
            <div style='background-color: #95D7FE; padding: 20px; border-radius: 5px; font-size: 12px;'>
                <div style='text-align: center;'>
                    <a href='https://www.facebook.com/allmarathon.fr' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/facebook.png' alt='Facebook' style='width: 13px; margin: 0 5px;'>
                    </a>
                    <a href='https://www.instagram.com/allmarathon.fr' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/instagra.png' alt='Instagram' style='width: 23px; margin: 0 5px;'>
                    </a>
                    <a href='https://www.pinterest.fr/allmarathon/' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/pinterest.png' alt='Pinterest' style='width: 20px; margin: 0 5px;'>
                    </a>
                    <a href='https://whatsapp.com/channel/0029Va3y67f0G0Xmv2z5jC2A' class='icon-hov'>
                        <img src='https://dev.allmarathon.fr/images/whatsapp.png' alt='WhatsApp' style='width: 24px; margin: 0 5px;'>
                    </a>
                </div>
                <div style='text-align: center; margin-top: 10px;'>
                    <a href='https://dev.allmarathon.fr/contact.html' style='color: #000; text-decoration: none;'>Contact</a> |
                    <a href='https://dev.allmarathon.fr/politique-de-confidentialite.html' style='color: #000; text-decoration: none;'>Politique de confidentialité</a>
                </div>
                <div style='text-align: center; margin-top: 10px;'>
                    <a href='mailto:nash@dev.allmarathon.fr'>nash@dev.allmarathon.fr</a> | 
                    <a href='https://allmarathon.fr'>www.allmarathon.fr</a>
                </div>
                <div style='border-top: 1px solid #fff !important;margin-top:10px;'></div>
                <div style='text-align: center;  background-color:#fff;    padding: 14px 10px 9px 10px;border-radius:5px;width:fit-content;    margin: auto;margin-top: 19px;'>
                    <a href='https://dev.allmarathon.fr/contact.html'><img src='https://dev.allmarathon.fr/images/logo-allmarathon.png' alt='logo' style='width: 140px;'></a>
                </div>
            </div>
        </div>
    </body>
    </html>
";

$contenu_text = "Merci pour votre inscription sur allmarathon. Votre nom d'utilisateur est User 557.";

$res = envoyerEmail("aitbellasoukaina17@gmail.com", $sujet, $contenu_html, $contenu_text);
if ($res) {
    echo "mail envoyé";
} else {
    echo "Échec de l'envoi du mail.";
}

?>
