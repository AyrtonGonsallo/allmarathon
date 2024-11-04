<?php
/**
 * Created by PhpStorm.
 * User: Mariam
 * Date: 04/05/2016
 * Time: 12:07
 */

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }



// if(isset($_POST['submit']) && !empty($_POST['submit'])):
// if((isset($_POST['g-recaptcha-response'])) && !empty($_POST['g-recaptcha-response'])){

    
//         //your site secret key
//     $secret = '6Lf4UxIUAAAAAD2diPAd3BH227Om0q76bVHqtL2T';
//         //get verify response data
//     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
//     $responseData = json_decode($verifyResponse);
//     if($responseData->success)
//     {
//         $name = !empty($_POST['pseudo'])?$_POST['pseudo']:'';
//         $email = !empty($_POST['mot_de_passe'])?$_POST['mot_de_passe']:'';
//         $message = !empty($_POST['email'])?$_POST['email']:'';

//         $to = 'sabilmariam91@gmail.com';
//         $subject = 'New contact form have been submitted';
//         $htmlContent = "
//         <h1>Contact request details</h1>
//         <p><b>Name: </b>".$name."</p>
//         <p><b>Email: </b>".$email."</p>
//         <p><b>Message: </b>".$message."</p>
//         ";
//                         // Always set content-type when sending HTML email
//         $headers = "MIME-Version: 1.0" . "\r\n";
//         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//                         // More headers
//         $headers .= 'From:'.$name.' <'.$email.'>' . "\r\n";
//                         //send email
//         @mail($to,$subject,$htmlContent,$headers);

//         $succMsg = 'Your contact request have submitted successfully.';
//         $errMsg="khawi";
//     }
//     else
//             {
//                 $errMsg = 'Robot verification failed, please try again.';
//                 $succMsg ="succes message";
//              }

//     }
    
//     else
//          { $errMsg = 'Please click on the reCAPTCHA box.';
//       $succMsg ="succès message";
//  }
 
// require_once('../database/connexion.php');
// require_once('functions.php');
echo "test";die;

$response_array['status'] = 'error';
$response_array['message'] = '';

$captcha=$_POST['captchaResponse'];
 // $captcha=$_POST['datajson']['captchaResponse'];

// $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc-wRsTAAAAAIN3xfn3HlOQUxk3C3e89tbYkTNk&amp;amp;response=" . $captcha);
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lf4UxIUAAAAAD2diPAd3BH227Om0q76bVHqtL2T&amp;amp;response=" . $captcha);
// print_r($captcha);
// print_r($response);die;
$response_decoded=json_decode($response, true);
// print_r($response_decoded);die;

if (isset($_POST)) {

    $errors = "";
    if ($response_decoded['success'] == true) {

        $error = "";

        $username = (!empty($_POST['datajson']["username"])) ? $_POST['datajson']['username'] : "";
        $pass1 = (!empty($_POST['datajson']["pass1"])) ? $_POST['datajson']['pass1'] : "";
        $pass2 = (!empty($_POST['datajson']["pass2"])) ? $_POST['datajson']['pass2'] : "";
        $nom = (!empty($_POST['datajson']["nom"])) ? $_POST['datajson']['nom'] : "";
        $prenom = (!empty($_POST['datajson']["prenom"])) ? $_POST['datajson']['prenom'] : "";
        $mail = (!empty($_POST['datajson']["mail"])) ? $_POST['datajson']['mail'] : "";
        $dn = (!empty($_POST['datajson']["dn"])) ? $_POST['datajson']['dn'] : "";
        $cp = (!empty($_POST['datajson']["cp"])) ? $_POST['datajson']['cp'] : "";
        $ville = (!empty($_POST['datajson']["ville"])) ? $_POST['datajson']['ville'] : "";
        $pays = (!empty($_POST['datajson']["pays"])) ? $_POST['datajson']['pays'] : "";
        $grade = (!empty($_POST['datajson']['grade'])) ? $_POST['datajson']['grade'] : "";
        $club = (!empty($_POST['datajson']['club'])) ? $_POST['datajson']['club'] : "";
        $newsletter = (!empty($_POST['datajson']['newsletter'])) ? $_POST['datajson']['newsletter'] : "";
        $offres = (!empty($_POST['datajson']['offres'])) ? $_POST['datajson']['offres'] : "";
        $password = encrypt($pass1);

        $t = time();

        // if ($pass1 == "" or $pass2 == "" or $pass1 != $pass2) {

        //     $response_array['message'] .= '<br> Les mots de passe doivent correspondre';
        //     $error = "faux";


        // }
        // if ($username == "" or $nom == "" or $prenom == "" or $mail == "" or $dn == "" or $cp == "" or $ville == "" or $pays == "") {
        //     $response_array['message'] .= '<br> Merci de remplir les champs obligatoire *';
        //     $error = "faux";

        // }
        // if (!email_V($mail)) {

        //     $response_array['message'] .= '<br> Votre adresse e-mail n\'est pas valide.';
        //     $error = "faux";
        // }

        try {
           $req = $bdd->prepare('SELECT * FROM users WHERE email LIKE :email OR username LIKE :username');
           $req->bindValue('email',$mail, PDO::PARAM_INT);
           $req->bindValue('username',$username, PDO::PARAM_INT);
           $req->execute();
           $users= array();
           while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
             $user = self::constructWithArray($row);
             array_push($users, $user);
         }
         $bdd=null;
         return array('validation'=>true,'donnees'=>$users,'message'=>'');
     }
     catch(Exception $e)
     {
        die('Erreur : ' . $e->getMessage());
    }

    if (empty($error)) {
        if (!$users) {
        	echo "mzyane";die;
        //    try {
        //     $req = $bdd->prepare("INSERT INTO users (nom ,prenom ,username ,email ,date_naissance ,code_postale ,ville ,pays ,grade ,club ,newsletter ,offres ,password,user_regdate)
        //         VALUES (:nom, :prenom, :username, :mail, :dn, :cp, :ville, :pays, :grade, :club, :newsletter, :offres, :password,:t)");

        //     $req->bindValue('nom',$nom, PDO::PARAM_STR);
        //     $req->bindValue('prenom',$prenom, PDO::PARAM_STR);
        //     $req->bindValue('username',$username, PDO::PARAM_STR);
        //     $req->bindValue('mail',$mail, PDO::PARAM_STR);
        //     $req->bindValue('dn',$dn, PDO::PARAM_INT);

        //     $req->bindValue('cp',$cp, PDO::PARAM_STR);
        //     $req->bindValue('ville',$ville, PDO::PARAM_STR);
        //     $req->bindValue('pays',$pays, PDO::PARAM_STR);
        //     $req->bindValue('grade',$grade, PDO::PARAM_INT);

        //     $req->bindValue('club',$club, PDO::PARAM_INT);
        //     $req->bindValue('newsletter',$newsletter, PDO::PARAM_INT);
        //     $req->bindValue('offres',$offres, PDO::PARAM_INT);
        //     $req->bindValue('password',$password, PDO::PARAM_INT);
        //     $req->bindValue('grade',$t, PDO::PARAM_INT);

        //     $req->execute();
        //     $idVerif=$bdd->lastInsertId();
        // }
        // catch(Exception $e)
        // {
        //             // die('Erreur : ' . $e->getMessage());
        //     $error .=$e->getMessage();
        // }

        $_SESSION['user_idVerif'] = $idVerif;
        $_SESSION['userVerif'] = $username;

                //$to = $mail; //lmathieu@alljudo.net
                $to = 'sabilmariam91@gmail.com'; //lmathieu@alljudo.net
                // $to = $mail; //lmathieu@alljudo.net
                $subject = 'Indentifiants allmarathon';
                $message = '  <html>
                <head>
                <title>Indentifiants allmarathon </title>
                </head>
                <body>
                Bonjour ' . $username . ',<br>

                Merci pour votre inscription, nous sommes ravis de vous compter parmi la communauté de allmarathon.fr<br>
                Votre compte vous permet de vous abonner à notre newsletter, de revendiquer et d\'administrer la fiche d\'un coureur (infos personnelles, résultats).<br>
                Si vous rencontrez des difficultés n\'hésitez pas à nous en faire part en réponse à ce mail.<br>
                Vous pouvez également nous retrouver sur les réseaux sociaux :<br>
                Instagram : https://www.instagram.com/allmarathon.fr<br>
                Facebook : https://www.facebook.com/allmarathon.fr<br>
                Pinterest : https://www.pinterest.fr/allmarathon/<br>
                Très Cordialement<br>
                L\'équipe de allmarathon.fr<br>
                </body></html>';
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // En-têtes additionnels
                $headers .= 'From: Allmarathon.net <contact.allmarathon@gmail.com>' . "\r\n";
                mail($to, $subject, $message, $headers);

                $response_array['status'] = 'success';
                $response_array['message'] .= 'Votre compte vient d\'être créé, merci et bienvenu parmi les membres de la communauté allmarathon!';


            } else {
                $response_array['status'] = 'error';
                $response_array['message'] .= '<br> pseudo ou e-mail existe déjà s\'il vous plaît choisir un autre';

            }
        }
    } else {
       $response_array['message'] .= 'invalid recaptcha';

   }


echo json_encode($response_array);
}
?>