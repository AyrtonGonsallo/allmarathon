<?php	
require('./database/connexion.php');

	session_start();
	$google_user_id=$_SESSION['google_user_id'];
	$user_id=$_SESSION['user_id'];
	unset($_SESSION['user']);
	unset($_SESSION['user_id']);	
	unset($_SESSION['google_user_id']);

	
	
	$update2 = $bdd->prepare("UPDATE  `users_google` SET `user_id`=:id,`is_connected`=0 WHERE id=:google_id");
	$update2->bindValue("id", $user_id, PDO::PARAM_INT);
	$update2->bindValue("google_id", $google_user_id, PDO::PARAM_INT);
	$update2->execute();
	session_destroy();
	header('Location: /');
	exit;

?>
