<?php
require('./config.php');
# the createAuthUrl() method generates the login URL.
$login_url = $client->createAuthUrl();
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
  $check_google_user_exists = $bdd->prepare("SELECT id FROM `users_google` WHERE `email` like :email");
  $check_google_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
  $check_google_user_exists->execute();
  $cond=$check_google_user_exists->fetch(PDO::FETCH_ASSOC);

  
  //var_dump($cond) ;exit(-1);
  if(!$cond){
    # Inserting the new user into the database
    $query_template = "INSERT INTO `users_google`  (`oauth_uid`, `first_name`, `last_name`,`email`,`profile_pic`,`gender`,`local`) VALUES (:param1,:param2,:param3,:param4,:param5,:param6,:param7)";
    $insert_stmt = $bdd->prepare($query_template);
    $insert_stmt->bindValue("param1", $google_id, PDO::PARAM_STR);
    $insert_stmt->bindValue("param2",$f_name,  PDO::PARAM_STR);
    $insert_stmt->bindValue("param3",  $l_name, PDO::PARAM_STR);
    $insert_stmt->bindValue("param4",  $email,  PDO::PARAM_STR);
    $insert_stmt->bindValue("param6",  $gender,  PDO::PARAM_STR);
    $insert_stmt->bindValue("param7", $local, PDO::PARAM_STR);
    $insert_stmt->bindValue("param5",  $picture, PDO::PARAM_STR);
    $insert_stmt->execute();
	  $google_user_id=$bdd->lastInsertId();
    if(!$google_user_id){
      echo "Failed to insert user.";
    }
    $check_simple_user_exists = $bdd->prepare("SELECT id FROM `users` WHERE user_google_id =:user_google_id or email like :email");
    $check_simple_user_exists->bindValue("user_google_id", $google_user_id, PDO::PARAM_INT);
    $check_simple_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
    $check_simple_user_exists->execute();
    $result_id=$check_simple_user_exists->fetch(PDO::FETCH_ASSOC);
    $user_id=($result_id)?$result_id['id']:$user_id;
  }else{
    $google_user_id=$cond['id'];
    $check_simple_user_exists = $bdd->prepare("SELECT id FROM `users` WHERE user_google_id =:user_google_id or email like :email");
    $check_simple_user_exists->bindValue("user_google_id", $google_user_id, PDO::PARAM_INT);
    $check_simple_user_exists->bindValue("email", '%'.$email.'%', PDO::PARAM_STR);
    $check_simple_user_exists->execute();
    $result_id=$check_simple_user_exists->fetch(PDO::FETCH_ASSOC);
    $user_id=($result_id)?$result_id['id']:$user_id;
  }

    # lier google et le systeme interne
    $update1 = $bdd->prepare("UPDATE `users` SET `user_google_id`=:google_id WHERE id=:id");
    $update1->bindValue("id", $user_id, PDO::PARAM_INT);
    $update1->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
    $update1->execute();

    $update2 = $bdd->prepare("UPDATE  `users_google` SET `user_id`=:id,`is_connected`=1 WHERE id=:google_id");
    $update2->bindValue("id", $user_id, PDO::PARAM_INT);
    $update2->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
    $update2->execute();

    
    
    require_once('../../functions.php');
    
    $check_user_exists2 = $bdd->prepare("SELECT * FROM `users` WHERE `user_google_id` = :ugid");
  $check_user_exists2->bindValue("ugid",$google_user_id, PDO::PARAM_INT);
  $check_user_exists2->execute();
  $cond2=$check_user_exists2->fetch(PDO::FETCH_ASSOC);
 
    if ($cond2 ) {
      $_SESSION['user']  =$cond2['username'];
      $_SESSION['user_id'] = $cond2['id'];
      $_SESSION['google_user_id'] = $google_user_id;

    }


  header('Location: membre-profil.php');
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