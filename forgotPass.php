<?php
/**
 * Created by PhpStorm.
 * User: Mariam
 * Date: 25/03/2016
 * Time: 14:57
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('functions.php');
require_once 'database/connexion.php';
require 'PHPMailerAutoload.php';

$response = $_POST['g-recaptcha-response'];
$Url = "https://www.google.com/recaptcha/api/siteverify";
$SecretKey = "6Lf2-bwlAAAAADuE8YsrNoV5QRlYgc3x6QV4awPP";

$data = array('secret' => $SecretKey, 'response' => $response);

$ch = curl_init($Url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$verifyResponse = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($verifyResponse);

//var_dump($responseData->success);
// (!empty($_GET['pager'])) ? $_GET['pager'] : 1;
if ($responseData->success && $_POST["mail"] != "") {//&& $resp->is_valid
    $error = "";

    $mail = (!empty($_POST["mail"])) ? $_POST['mail'] : "";


    $users = "SELECT * FROM   users where LOWER(email) LIKE LOWER('$mail')";
    $user = mysqli_query($mysqli, $users) or die(mysql_error());
    $row = mysqli_fetch_assoc($user);
    if ($row) {

        $id = $row['id'];
        $username = $row['username'];
        $mail = $row['email'];
        $pass = generer_mot_de_passe();
        $hash = encrypt($pass);


        $query1 = "UPDATE `users` SET `password` = '$hash' WHERE `id` =$id;";
        $error .= mysqli_query($mysqli, $query1) or die(mysql_error());

        $to = $mail; //'lmathieu@alljudo.net, rodolphe1160@gmail.com'; //lmathieu@alljudo.net
        $subject = 'Nouveau mot de passe';
        $message = '  <html><head><title>Nouveau mot de passe</title></head><body>
                           Bonjour ' . $username . ',<br>
  Vous recevez cet avertissement car vous (ou quelqu\'un se faisant passer pour vous) a demand&eacute; qu\'un nouveau mot de passe soit envoy&eacute; pour votre compte sur "alljudo.net". <br>
  Si vous n\'avez pas demand&eacute; cette modification, veuillez l\'ignorer.

  Si vous recevez de nouveau cet avertissement, sans l\'avoir sollicit&eacute;, veuillez contacter l\'administrateur du site.<br>
  vous pourrez vous connecter &agrave; votre compte en utilisant le mot de passe : ' . $pass . '

                            </body></html>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // En-têtes additionnels
        $headers .= 'From: alljudo.net' . "\r\n";
        $phpmail = new PHPMailer; // create a new object
        $phpmail->IsSMTP(); // enable SMTP
        $phpmail->Host = "authsmtp.securemail.pro";
        $phpmail->Port = 465; // or 587
        $phpmail->SMTPSecure = 'ssl';
        $phpmail->SMTPAuth = true; // authentication enabled
        $phpmail->Username = "martinodegaardnash@gmail.com";
        $phpmail->Password = "zJX8CvbTgAH7";
        $phpmail->SetFrom("martinodegaardnash@gmail.com","Alljudo Contact");
        $phpmail->AddAddress($mail);
        $phpmail->IsHTML(true);
        $phpmail->Subject = $subject;
        $phpmail->Body = $message;
        

        if(!$phpmail->Send()) {
            session_unset();
            session_start ();
            $_SESSION['mail'] = "forgot";
            echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('une erreur est survenue !')
            window.location.href='https://dev.allmarathon.fr';
            </SCRIPT>");
        }
        else {
            //echo '<html><body> Un message de modification de mot de passe vous a  &eacute;t&eacute; envoy&eacute; &agrave; l\'adresse: '.$mail.'</body></html>';
            session_unset();
            session_start ();
            $_SESSION['message'] = "forgot";
            echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('un nouveau code a été envoyé, vérifiez votre boite email !')
            window.location.href='http://dev.allrathon.fr';
            </SCRIPT>");    
        }
    }
    //debug($msg);
    
else
    echo '<h2>Aucun utilisateur avec cet email!</h2>';


}
else//$response.success==false
    echo '<h2>Problème avec le captcha.</h2>'.' - '.$responseData->success;



function VerifierAdresseMail($adresse)
{
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(preg_match($Syntaxe,$adresse))
        return true;
    else
        return false;
}
function debug($var)

{
    echo '<pre>'.print_r($var,true).'</pre>';
}
?>