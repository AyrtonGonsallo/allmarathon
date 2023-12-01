<?php



$line1 = '"NOM Prenom"';
$line1 .= ';"Sexe : M F"';
$line1 .= ';"categorie de poid"';
$line1 .= ';"Date \'YYYY-MM-DD HH:MM\'';


header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/csv");
header("Content-Disposition: filename=\"resultat.csv\";");


echo $line1;
?>
