<?php


$server_ip="localhost";
$server_database="sc1mala2782_preprod_allmarathon";
$server_user="sc1mala2782_preprod_allmarathon_user";
$server_password= "q22CI@%3XC4MC@qy";

$mysqli = new mysqli($server_ip, $server_user, $server_password, $server_database);
try
{
	$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host='.$server_ip.';dbname='.$server_database, $server_user, $server_password, $pdo_options) or die( mysql_error() );
	
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}



?>