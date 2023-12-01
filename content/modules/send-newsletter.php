<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
{
  header('Location: login.php');
  exit();
}

if($_SESSION['admin'] == false && $_SESSION['ev'] == false){
    header('Location: login.php');
    exit();
}
include 'functions.php';

    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="../../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
        <link href="../../styles/admin2009.css" rel="stylesheet" type="text/css" />
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>Allmarathon admin</title>


<!-- InstanceEndEditable -->
</head>
<body>

<?php

/*
 * Include  sendgrid php libs
 */
require("sendgrid-php.php");

require_once '../classes/user.php';


$user=new user();
$clients=$user->getAbonnesNewsLetter()['donnees'];


//  $clients = [
//     ['nom'=>'LANSARI','prenom'=>'Issam','email'=>'ilanssari@ippon.fr '],['nom'=>'SABIL','prenom'=>'Mariam','email'=>'sabilmariam91@gmail.com']
// ];

// var_dump($clients);die;
/* USER CREDENTIALS
/  Fill in the variables below with your SendGrid
/  apiKey
====================================================*/

$apiKey = "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";

/* CREATE THE SENDGRID INSTANCE
====================================================*/
$sendgrid = new SendGrid($apiKey);//api_key

/* CREATE THE SENDGRID MAIL OBJECT
====================================================*/
$mail = new SendGrid\Email();


/* SEND MAIL
====================================================*/
try {
//GET HTML FROM PHP PAGE
ob_start(); //Init the output buffering
include('../vues/newsletter-allmarathon.php');
$emailContent = ob_get_clean(); //Get the buffer and erase it
$emails_recus=0;
//Prepare and send email to all newsletter users 
foreach ($clients as $client){

$unsuscribe='<table class="row"
                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">
                                <tbody>
                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                    <th class="marathon-unsubscribe small-12 large-12 columns first last"
                                        style="width: 564px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 16px;"
                                        align="left">
                                        <p style="color: #333333; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: center; line-height: 13px; font-size: 0.7em; margin: 0 0 10px; padding: 0;"
                                           align="center">Vous ne voulez plus recevoir d\'autres emails? Vous pouvez vous désinscrire  
                                            <a href="/unsubscribe-'.encrypt_newsletter($client["email"]).'"
                                               style="color: #333333; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; text-decoration: underline; margin: 0; padding: 0;">ici</a>
                                        </p>
                                    </th>
                                    <th class="expander"
                                        style="visibility: hidden; width: 0; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"
                                        align="left"></th>
                                </tr>
                                </tbody>
                            </table>    
                        </td>
                    </tr>
                    </tbody>
                </table>
            </center>
        </td>
    </tr>
    </tbody>
</table>

</html>
';
// PREPARE MAIL
$mail = new SendGrid\Email();
$mail->
setFrom("newsletter@alljudo.net")->
setSubject("AllMarathon Newsletter")->
setHtml($emailContent.$unsuscribe);
$mail->addTo($client['email'],$client['nom'].' '.$client['prenom']);
$response = $sendgrid->send($mail);
$response = $response->body;
if (!$response) {
throw new Exception("Did not receive response.");
} else if ($response['message'] && $response['message'] == "error") {
throw new Exception("Received error: " . join(", ", $response->errors));
} else {
$emails_recus++;
}
}
$_SESSION['msg_newsletter']= ($emails_recus==sizeof($clients)) ? "Tous les abonnés ont reçu la newsletter." : "Une erreur est survenue";
header('Location: ../../admin/newsletter.php');

} catch (Exception $e) {
var_dump($e);
// var_export($e);
}

?>
</body>
</html>
