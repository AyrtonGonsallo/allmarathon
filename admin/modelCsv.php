<?php


$line1 = '"ID evenement : laisser vide pour ajouter les r�sultats a l\'�venement courant ou mettre l\'ID des evenement voulus"';
$line1 .= ';"Pr�nom Nom"';
$line1 .= ';"Sexe : M F"';
$line1 .= ';"abreviation pays"';
$line1 .= ';"rang"';
$line1 .= ';"categorie de poid"';
$line1 .= ';"club (facultatif)"';


header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/csv");
header("Content-Disposition: filename=\"resultat.csv\";");


echo $line1;
?>
