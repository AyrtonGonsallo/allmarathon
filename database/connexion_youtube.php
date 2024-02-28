<?php


$server_ip="localhost";
$server_database="mala4322_almarathon";
$server_user="mala4322_allmarathon";
$server_password="allmarathon";
/*
$server_ip="lhcp2091.webapps.net";
$server_database="v83j6wh7_alljudo";
$server_user="v83j6wh7_alljudo";
$server_password="Om~WAZgp]Gb4"; 
*/
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