<?php

$server_ip="allmarcadmin.mysql.db";
$server_database="allmarcadmin";
$server_user="allmarcadmin";
$server_password="J5Sswy879aX5cB";
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