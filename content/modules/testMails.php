<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}






function envoyerEmail($dest,$sujet,$contenu_html,$contenu_text){
    require_once ('../libs/PHPMailer-master/PHPMailerAutoload.php');
    try {
        $mail = new PHPMailer(true); // true active les exceptions
        // Paramètres du serveur SMTP de Gmail
        $mail->isSMTP(); // Utiliser SMTP
        $mail->Host = 'dev.allmarathon.fr'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true; // Activer l'authentification SMTP
        $mail->Username = 'nash@dev.allmarathon.fr'; // Votre adresse Gmail
        $mail->Password = 'ltiryvtcoodddwhf'; // Votre mot de passe Gmail
        $mail->SMTPSecure = 'ssl'; // Protocole de sécurité (tls ou ssl)
        $mail->Port = 465; // Port SMTP de Gmail
    
        // Destinataire et expéditeur
        $mail->setFrom('nash@dev.allmarathon.fr', 'dev.allmarathon.fr');
        $mail->addAddress($dest, 'Destinataire');
    
        // Contenu du message
        $mail->isHTML(true); // Définir le format du message HTML
        $mail->Subject = $sujet;
        $mail->Body    = $contenu_html;
        $mail->AltBody = $contenu_text;
    
        // Envoyer l'e-mail
        $mail->send();
        $res= true;
    } catch (Exception $e) {
        $res= false;
    }
    return $res;
}
//$res=envoyerEmail('fnietzsche636@gmail.com','test email','test fnietzsche636@gmail.com','fnietzsche636@gmail.com');
//echo $res;

?>