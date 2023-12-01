<?php 
session_start();
require_once 'database/connection.php';
require_once('functions.php');

$users = 'SELECT * FROM   users';
$user = mysql_query($users) or die(mysql_error());
 
$username=$_POST['username'];
$pass=$_POST['password'];



while($x = mysql_fetch_array($user )){ 
$hash=$x['password'];
if ($x['username']==$username &&  (eco_check_hash($pass, $hash) || encrypt($pass)==$x['password'] ) ) {
	
	$_SESSION['user']    =$x['username'];
    $_SESSION['user_id'] = $x['id'];
	

}
	

}


?>