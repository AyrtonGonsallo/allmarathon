<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../classes/user.php");
include("functions.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$us=new user();
$login=$_POST['name_user'];
$password=$_POST['password'];

$user=$us->getUserByUsername($login)['donnees'];



if(isset($login) && isset($password))
{
		if(sizeof($user)!=0 && decrypt($password,$user->getPassword())){
			$_SESSION['user']    =$user->getUsername();
		    $_SESSION['user_id'] = $user->getId();
		    // header("Location: http://localhost/allmarathon_nv/www/");
		    header("Location:".$_POST['previous_url']);
		    // header('Location:'.$_SERVER['HTTP_REFERER']);
			exit;
		}
		else{
			$_SESSION['auth_error'] = "Login ou mot de passe incorrect ! ";
			header("Location:".$_POST['previous_url']);
			// header("Location: http://localhost/allmarathon_nv/www/");
			// header('Location:'.$_SERVER['HTTP_REFERER']);
			exit;
		}
}
else
{
			$_SESSION['auth_error'] = "Tous les champs sont obligatoires.";
			// header("Location: http://localhost/allmarathon_nv/www/");
			header("Location:".$_POST['previous_url']);
			// header('Location:'.$_SERVER['HTTP_REFERER']);
			exit;
}

?>