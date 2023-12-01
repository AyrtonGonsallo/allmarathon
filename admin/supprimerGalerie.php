<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

if($_GET['galerieID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM galeries WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['galerieID'], PDO::PARAM_INT);
                 $req->execute();
                 $path = "../images/galeries/".$_GET['galerieID']."/";
			     clearDir($path);
			     header('Location: galerie.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
}else{
    echo "erreur de variable";
}

function clearDir($dossier) {
	$ouverture=@opendir($dossier);
	if (!$ouverture) return;
	while($fichier=readdir($ouverture)) {
		if ($fichier == '.' || $fichier == '..') continue;
			if (is_dir($dossier."/".$fichier)) {
				$r=clearDir($dossier."/".$fichier);
				if (!$r) return false;
			}
			else {
				$r=@unlink($dossier."/".$fichier);
				if (!$r) return false;
			}
	}
closedir($ouverture);
$r=@rmdir($dossier);
if (!$r) return false;
	return true;
}


?>
