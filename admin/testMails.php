<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}






function envoyerEmail($dest,$sujet,$contenu_html,$contenu_text){
    require_once ('../content/libs/PHPMailer-master/PHPMailerAutoload.php');
    try {
        $mail = new PHPMailer(true); // true active les exceptions
        // Paramètres du serveur SMTP de Gmail
        $mail->isSMTP(); // Utiliser SMTP
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true; // Activer l'authentification SMTP
        $mail->Username = 'fnietzsche636@gmail.com'; // Votre adresse Gmail
        $mail->Password = 'ltiryvtcoodddwhf'; // Votre mot de passe Gmail
        $mail->SMTPSecure = 'tls'; // Protocole de sécurité (tls ou ssl)
        $mail->Port = 587; // Port SMTP de Gmail
    
        // Destinataire et expéditeur
        $mail->setFrom('allmarathon703@gmail.com', 'dev.allmarathon.fr');
        $mail->addAddress('fnietzsche636@gmail.com', 'Destinataire');
    
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



?>