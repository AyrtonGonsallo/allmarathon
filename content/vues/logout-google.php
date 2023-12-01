<?php
session_start();

if(!isset($_SESSION['token'])){
  header('Location: login-google.php');
  exit;
}
require('../database/connexion.php');
require('./config.php');
$client = new Google_Client();
$client->setAccessToken($_SESSION['token']);
# Revoking the google access token
$client->revokeToken();
$google_user_id=$_SESSION['google_user_id'];
	$user_id=$_SESSION['user_id'];
# Deleting the session that we stored
$_SESSION = array();

if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
  );
}

session_destroy();
$update2 = $bdd->prepare("UPDATE  `users_google` SET `user_id`=:id,`is_connected`=0 WHERE id=:google_id");
$update2->bindValue("id", $user_id, PDO::PARAM_INT);
$update2->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
$update2->execute();
header("Location: membre-profil.php");
exit;