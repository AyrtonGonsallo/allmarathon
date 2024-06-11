<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

$champion_id=$_GET['championID'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si le fichier a été uploadé sans erreur
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $filename = $_FILES['file']['name'];
        $filetype = $_FILES['file']['type'];
        $filesize = $_FILES['file']['size'];

        // Vérifier l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), $allowed)) {
            die("Erreur : Format de fichier non autorisé.");
        }

        // Vérifier la taille du fichier - 5Mo maximum
        if ($filesize > 5 * 1024 * 1024) {
            die("Erreur : La taille du fichier est supérieure à la limite autorisée.");
        }

        // Vérifier le type MIME du fichier
        $allowed_mime_types = array(
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif'
        );
        if (!in_array($filetype, $allowed_mime_types)) {
            die("Erreur : Type de fichier non autorisé.");
        }

        // Renommer le fichier pour éviter les conflits
        function slugify($string) {
            $string = strtolower($string);
            $string = preg_replace('/[^a-z0-9-]/', '_', $string);
            $string = preg_replace('/-+/', "_", $string);
            return $string;
        }
        $new_filename = slugify(pathinfo($filename, PATHINFO_FILENAME)) . '.' . $ext;

        // Définir le chemin de destination
        $destination = '../../images/galeries/0/' . $new_filename;

        // Déplacer le fichier uploadé vers le répertoire de destination
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
           // echo "Le fichier a été uploadé avec succès.";
           try {
            require_once '../database/connexion.php';
             $req4 = $bdd->prepare("INSERT INTO `champion_admin_externe_journal`( `type`, `date`, `user_id`, `champion_id`, `image`) VALUES (:t,:d,:u,:c,:i)");

             $req4->bindValue('t','upload-image', PDO::PARAM_STR);
             $req4->bindValue('d',date("Y-m-d H:i:s"), PDO::PARAM_STR);
             $req4->bindValue('u',$user_id, PDO::PARAM_INT);
             $req4->bindValue('c',$champion_id, PDO::PARAM_INT);
             $req4->bindValue('i',$new_filename, PDO::PARAM_STR);
            
             $statut=$req4->execute();

            

            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            header('Location: /athlete-'.$_GET['championID'].'.html'); 
        } else {
            echo "Erreur : Un problème est survenu lors de l'upload du fichier.";
        }
    } else {
        echo "Erreur : " . $_FILES['file']['error'];
    }
} else {
    echo "Erreur : Le formulaire de téléchargement doit être soumis en utilisant la méthode POST.";
}
?>
