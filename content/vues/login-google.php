<?php
require('./config.php');
require_once('../modules/testMails.php');// envoyerEmail($dest,$sujet,$contenu_html,$contenu_text)

# the createAuthUrl() method generates the login URL.
$login_url = $client->createAuthUrl();
setlocale(LC_TIME, "fr_FR","French");
/* 
 * After obtaining permission from the user,
 * Google will redirect to the login-google.php with the "code" query parameter.
*/
if (isset($_GET['code'])):

  session_start();
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  if(isset($token['error'])){
    header('Location: login-google.php');
    exit;
  }
  $_SESSION['token'] = $token;
  (!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
  /* -- Inserting the user data into the database -- */

  # Fetching the user data from the google account
  $client->setAccessToken($token);
  $google_oauth = new Google_Service_Oauth2($client);
  $user_info = $google_oauth->userinfo->get();

  $google_id = trim($user_info['id']);
  $f_name = trim($user_info['given_name']);
  $l_name = trim($user_info['family_name']);
  $email = trim($user_info['email']);
  $gender = trim($user_info['gender']);
  $local = trim($user_info['local']);
  $picture = $user_info['picture'];
 
# Database connection
  require('../database/connexion.php');

  # Checking whether the email already exists in our database.
  $check_google_user_exists = $bdd->prepare("SELECT id FROM `users` WHERE `email` like :email");
  $check_google_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
  $check_google_user_exists->execute();
  $cond=$check_google_user_exists->fetch(PDO::FETCH_ASSOC);

  
  //var_dump($cond) ;exit(-1);
  if(!$cond){
    # Inserting the new user into the database
    $query_template = "INSERT INTO `users` (`id` ,`nom` ,`prenom` ,`username` ,`email` ,`newsletter` ,`offres` ,`password`,`user_regdate`,`oauth_uid`,`profile_pic`,`gender`,`local`,is_connected,user_google_id)
		VALUES (NULL , :nom, :prenom, :username, :email, :newsletter, :offres, :password,:t,:param1,:param2,:param3,:param4,:param5,:param6)";

    $insert_stmt = $bdd->prepare($query_template);
    $insert_stmt->bindValue('nom', $l_name, PDO::PARAM_STR);
    $insert_stmt->bindValue('prenom', $f_name, PDO::PARAM_STR);
    $insert_stmt->bindValue('username', $f_name." ".$l_name, PDO::PARAM_STR);
    $insert_stmt->bindValue('email', $email, PDO::PARAM_STR);
    
    $insert_stmt->bindValue('newsletter',0, PDO::PARAM_STR);
    $insert_stmt->bindValue('offres', 0, PDO::PARAM_STR);
    $insert_stmt->bindValue('password', 0, PDO::PARAM_STR);
    $insert_stmt->bindValue('t', strftime("%A %d %B %Y",strtotime(date()))." à ".date("h:i:sa"), PDO::PARAM_STR);
    $insert_stmt->bindValue("param1", $google_id, PDO::PARAM_STR);
    $insert_stmt->bindValue("param3",  $gender,  PDO::PARAM_STR);
    $insert_stmt->bindValue("param4", $local, PDO::PARAM_STR);
    $insert_stmt->bindValue("param2",  $picture, PDO::PARAM_STR);
    $insert_stmt->bindValue("param5",  1, PDO::PARAM_INT);
    $insert_stmt->bindValue("param6",  $google_id, PDO::PARAM_STR);
    $insert_stmt->execute();
	  $google_user_id=$bdd->lastInsertId();
    if(!$google_user_id){
      echo "Failed to insert user.";
    }

    $query_template2 = "INSERT INTO `champions`(user_id	,`Nom`, `Taille`, `Poids`, `Site`, DateChangementNat,`NvPaysID`, `Lien_site_équipementier`, `Facebook`, `Equipementier`, `Instagram`, `Bio`) VALUES (:user_id,:p1,180,65,'','9999-12-31','AFG','','','','','')";

    $insert_stmt2 = $bdd->prepare($query_template2);

    $insert_stmt2->bindValue('user_id', $google_user_id, PDO::PARAM_INT);

    $insert_stmt2->bindValue('p1', $f_name, PDO::PARAM_STR);
    
    $insert_stmt2->execute();
	  $google_user_id2=$bdd->lastInsertId();
    if(!$google_user_id2){
      echo "Failed to insert champion.";
    }
    $message = '<html>
    <head>
    <title>Identifiants allmarathon </title>
    </head>
    <body>
    Bonjour ' . $f_name.' '.$l_name . ',<br>

    Merci pour votre inscription sur allmarathon, voici vos identifiants de connexion :<br>
    Pseudo : ' . $f_name.' '.$l_name . '<br>
    Votre mot de passe est celui que vous avez renseign&eacute; lors de l\'inscription.<br>
    Pour vous connecter veuillez cliquer sur <a href="">ce lien</a><br><br>
    L\'&eacute;quipe allmarathon est heureuse de vous compter parmi ses membres.<br><br>


    Cordialement.
    </body></html>';
    envoyerEmail($email,'inscription sur allmarathon',$message,'Merci pour votre inscription sur allmarathon');
    /*
    $check_simple_user_exists = $bdd->prepare("SELECT id FROM `users` WHERE user_google_id =:user_google_id or email like :email");
    $check_simple_user_exists->bindValue("user_google_id", $google_user_id, PDO::PARAM_INT);
    $check_simple_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
    $check_simple_user_exists->execute();
    $result_id=$check_simple_user_exists->fetch(PDO::FETCH_ASSOC);
    $user_id=($result_id)?$result_id['id']:$user_id;
    */
  }/*
  else{
    $google_user_id=$cond['id'];
    $check_simple_user_exists = $bdd->prepare("SELECT id FROM `users` WHERE user_google_id =:user_google_id or email like :email");
    $check_simple_user_exists->bindValue("user_google_id", $google_user_id, PDO::PARAM_INT);
    $check_simple_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
    $check_simple_user_exists->execute();
    $result_id=$check_simple_user_exists->fetch(PDO::FETCH_ASSOC);
    $user_id=($result_id)?$result_id['id']:$user_id;
  }*/

    # lier google et le systeme interne
    /*
    $update1 = $bdd->prepare("UPDATE `users` SET `user_google_id`=:google_id WHERE id=:id");
    $update1->bindValue("id", $user_id, PDO::PARAM_INT);
    $update1->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
    $update1->execute();

    $update2 = $bdd->prepare("UPDATE  `users_google` SET `user_id`=:id,`is_connected`=1 WHERE id=:google_id");
    $update2->bindValue("id", $user_id, PDO::PARAM_INT);
    $update2->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
    $update2->execute();
*/
    
    
    require_once('../../functions.php');
    /**/
    $check_user_exists2 = $bdd->prepare("SELECT * FROM `users` WHERE email like :email");
    $check_user_exists2->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
  $check_user_exists2->execute();
  $cond2=$check_user_exists2->fetch(PDO::FETCH_ASSOC);
 
    
      $_SESSION['user']  =$cond2['username'];
      $_SESSION['user_id'] = $cond2['id'];
      $_SESSION['google_user_id'] = $google_user_id;

    
      if(isset($_COOKIE["page_when_logging_to_add_result"])) {
				header("Location:".$_COOKIE["page_when_logging_to_add_result"]);
				unset($_COOKIE['page_when_logging_to_add_result']); 
				setcookie("open_add_resulat_modal", "yes", time()+600, "/");
			}
      else if(isset($_COOKIE["page_when_logging_to_rev_fiche"])) {
				header("Location:".$_COOKIE["page_when_logging_to_rev_fiche"]);
				unset($_COOKIE['page_when_logging_to_rev_fiche']); 
				setcookie("open_rev_fiche_modal", "yes", time()+600, "/");
			}
      else if(isset($_COOKIE["currentPage"])) {
				header("Location:".$_COOKIE["currentPage"]);
				unset($_COOKIE['currentPage']); 
			}
			else{
        header('Location: membre-profil.php');
			}

 
  exit;

endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login with Google account</title>
  <style>
    .btn{
      display: flex;
      justify-content: center;
      padding: 50px;
    }
    a{
      all: unset;
      cursor: pointer;
      padding: 10px;
      display: flex;
      width: 250px;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      background-color: 
#f9f9f9;
      border: 1px solid 
rgba(0, 0, 0, .2);
      border-radius: 3px;
    }
    a:hover{
      background-color: 
#ffffff;
    }
    img{
      width: 50px;
      margin-right: 5px;
    
    }
  </style>
</head>
<body>
    <div class="btn">
    <a href="<?= $login_url ?>"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login with Google</a>
    </div>
</body>
</html>