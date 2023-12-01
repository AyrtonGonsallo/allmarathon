<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

    //verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
{
  header('Location: login.php');
  exit();
}

require_once '../database/connexion.php';


if(!isset($_GET['imageID']))
  header('location:galerie.php');

if( isset($_POST['subImage'])){
  if($_POST['Champion_id']=="")
    $_POST['Champion_id'] = 0;
  if($_POST['Champion2_id']=="")
    $_POST['Champion2_id'] = 0;
  if($erreur == "" ){
   try {
    $req2 = $bdd->prepare("UPDATE images SET Champion_id=:Champion_id, Champion2_id=:Champion2_id, Technique_id=:Technique_id WHERE ID=:ID");

    $req2->bindValue('Champion_id',$_POST['Champion_id'], PDO::PARAM_INT);
    $req2->bindValue('Champion2_id',$_POST['Champion2_id'], PDO::PARAM_INT);
    $req2->bindValue('Technique_id',$_POST['Technique_id'], PDO::PARAM_INT);
    $req2->bindValue('ID',$_GET['imageID'], PDO::PARAM_INT);
    $req2->execute();
    
    header('location:galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$_GET['page']);

    $req = $bdd->prepare("SELECT u.email,c.Nom FROM champions c, abonnement a, users u WHERE a.champion = c.ID and u.id=a.user and ( a.champion =:champ or a.champion=:champ2)");
    $req->bindValue('champ',$_POST['Champion_id'], PDO::PARAM_INT);
    $req->bindValue('champ2',$_POST['Champion2_id'], PDO::PARAM_INT);
    $req->execute();
    $q= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
      array_push($q, $row);
    }

  }
  catch(Exception $e)
  {
    die('Erreur : ' . $e->getMessage());
  }

  while($r=mysql_fetch_assoc($q)){
    $email = 'contact@alljudo.net';
    $subject = "Il y a du nouveau sur la fiche de ".$r['Nom'];
    $headers = "From: ".$email."\r\n";
    $headers .= "Reply-To: ". $r['email'] . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $message = '
    <html><body>Bonjour,
    Vous recevez ce message pour vous pr&eacute;venir qu\'un nouveau r&eacute;sultat, une nouvelle vid&eacute;o ou une nouvelle photo a &eacute;t&eacute; ajout&eacute;e sur la fiche de '.$r['Nom'].'
    Cordialement
    L\'&eacute;quipe de Allmarathon</body></html>';
     mail($r['email'], $subject,$message,$headers);
     // mail('sabilmariam91@gmail.com', $subject,$message,$headers);

  }

  header('location:galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$_GET['page']);
}
}

?>
