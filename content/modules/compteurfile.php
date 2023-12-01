<?php 
$id_doc=$_GET['id_doc'];
require_once '../classes/evenement.php';

$event=new evenement();

$pdf= $event->getEvenementByID($id_doc)['donnees']->getDocument3();
$path= 'PDF_frame';
$p= '/'.$path."-".$pdf; 
$event->incrementerCompteur($id_doc);
	
header( "Location: $p" ); // redirection vers le téléchargement 

?>