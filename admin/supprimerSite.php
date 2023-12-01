<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

if($_GET['siteID']!=""){
    require_once '../database/connection.php';
    $query2 = sprintf("DELETE FROM liens WHERE ID=%s LIMIT 1",mysql_real_escape_string($_GET['siteID']));
    mysql_query($query2) or die(mysql_error());
    header('Location: annuaireSite.php');
}else{
    echo "erreur de variable";
}