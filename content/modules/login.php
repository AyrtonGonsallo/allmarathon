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

$user=$us->getUserByUsername($login,$login)['donnees'];



if(isset($login) && isset($password))
{
		if(sizeof($user)!=0 && decrypt($password,$user->getPassword())){
			$_SESSION['user']    =$user->getUsername();
		    $_SESSION['user_id'] = $user->getId();
		    // header("Location: http://localhost/allmarathon_nv/www/");
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
			else{
				header("Location:".$_POST['previous_url']);
			}
		    // header('Location:'.$_SERVER['HTTP_REFERER']);
			exit;
		}
		else{
			//$_SESSION['auth_error'] = "Login ou mot de passe incorrect ! <br>".$login." ".$password;
			$_SESSION['auth_error'] = "Login ou mot de passe incorrect ! <br>";
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