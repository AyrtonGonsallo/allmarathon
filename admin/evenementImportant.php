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

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    $erreur = "";
    if( isset($_POST['sub'])){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
            if($erreur==""){
                $destination_path = "../images/logos/";
                $reqDocBegin = "";
                $reqDocEnd   = "";
                if(!empty($_FILES['Logo']['name'])){
                    $fileinfo = $_FILES['Logo'];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];
                    if ( $fileinfo['error']) {
                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    echo "'Le fichier ".$fichierName." d�passe la limite autoris�e par le serveur (fichier php.ini) !'<br />";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    echo  "'Le fichier ".$fichierName." d�passe la limite autoris�e dans le formulaire HTML !'<br />";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     echo"'L'envoi du fichier ".$fichierName." a �t� interrompu pendant le transfert !'<br />";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    echo "'Le fichier ".$fichierName." que vous avez envoy� a une taille nulle !'<br />";
                                   break;
                          }
                    }else{
                        if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                            $result = "Fichier ".$fichierName." corectement envoy� !";
                        }else{
                            echo "Erreur phase finale fichier ".$fichierName."<br />";
                        }
                        }

                    }
                if($erreur == "" ){
                if(!empty($_FILES['Logo']['name'])){
                     try {
                             $req4 = $bdd->prepare("INSERT INTO evenementimportants (Nom,Text1,Text2,Logo,Evenement_id,Lien) VALUES (:Nom,:Text1,:Text2,:Logo,:Evenement_id,:Lien)");

                             $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
                             $req4->bindValue('Text1',$_POST['Text1'], PDO::PARAM_STR);
                             $req4->bindValue('Text2',$_POST['Text2'], PDO::PARAM_STR);
                             $req4->bindValue('Logo',$_FILES['Logo']['name'], PDO::PARAM_STR);
                             $req4->bindValue('Evenement_id',$_POST['Evenement_id'], PDO::PARAM_STR);
                             $req4->bindValue('Lien',$_POST['Lien'], PDO::PARAM_STR);
                             $statut=$req4->execute();

                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                   
                }else{
                    try {
                             $req4 = $bdd->prepare("INSERT INTO evenementimportants (Nom,Text1,Text2,Logo,Evenement_id,Lien) VALUES (:Nom,:Text1,:Text2,'',:Evenement_id,:Lien)");

                             $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
                             $req4->bindValue('Text1',$_POST['Text1'], PDO::PARAM_STR);
                             $req4->bindValue('Text2',$_POST['Text2'], PDO::PARAM_STR);
                             $req4->bindValue('Evenement_id',$_POST['Evenement_id'], PDO::PARAM_STR);
                             $req4->bindValue('Lien',$_POST['Lien'], PDO::PARAM_STR);
                             $statut=$req4->execute();

                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                    // $query2    = sprintf("INSERT INTO evenementimportants (Nom,Text1,Text2,Logo,Evenement_id,Lien) VALUES (:,:,:,'',:,:)"
                    // ,mysql_real_escape_string($_POST['Nom'])
                    // ,mysql_real_escape_string($_POST['Text1'])
                    // ,mysql_real_escape_string($_POST['Text2'])
                    // ,mysql_real_escape_string($_POST['Evenement_id'])
                    // ,mysql_real_escape_string($_POST['Lien']));
                }

            // //exit($query2);
            // $result2   = mysql_query($query2) or die(mysql_error());
                }
            }
    }

    try{
              $req = $bdd->prepare("SELECT * FROM evenementimportants ORDER BY ID DESC");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
                }

                $req3 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
                $req3->execute();
                $result5= array();
                while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
                    array_push($result5, $row);
                 }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

    // $query1    = sprintf('SELECT * FROM evenementimportants ORDER BY ID DESC ');
    // $result1   = mysql_query($query1);

    // $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
    // $result5   = mysql_query($query5);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../Scripts/tiny_mce_init.js"></script>
<title>allmarathon admin</title>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter evenement</legend>
    <form action="evenementImportant.php" method="post" enctype="multipart/form-data">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <tr><td align="right"><label for="Nom">Nom : </label></td><td><input type="text" name="Nom"/></td></tr>
        <tr><td align="right"><label for="Text1">Information : partie gauche</label></td><td><textarea cols="50" rows="10" name="Text1" value=""></textarea></td></tr>
        <tr><td align="right"><label for="Text2">Information : partie droite</label></td><td><textarea cols="50" rows="10" name="Text2" value=""></textarea></td></tr>
        <tr><td align="right"><label for="Logo">Logo : </label></td><td><input type="file" name="Logo"/></td></tr>
        <tr><td align="right"><label for="Evenement_id">Evenement : </label></td><td>
        <select name="Evenement_id" >
           <option value="0">aucun</option>
        <?php //while($event = mysql_fetch_array($result5)){
            foreach ($result5 as $event) {
            $str = '<option value="'.$event['ID'].'">'.$event['ID'].' : '.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';
            echo $str;
        } ?>
        </select></td></tr>
        <tr><td align="right"><label for="Lien">Lien : </label></td><td><input type="text" name="Lien"/></td></tr>
          <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Liste des evenements importants</legend>
<div align="center">
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($result1 as $evenement) {
            echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['Nom']."</td>
                <td>
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='evenementImportantDetail.php?evenementID=".$evenement['ID']."'\" />
                </td></tr>";
        } ?>

    </tbody>
    </table>
</div>
</fieldset>
</body>
</html>

