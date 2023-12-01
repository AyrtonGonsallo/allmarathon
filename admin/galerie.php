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

    if($_SESSION['admin'] == false && $_SESSION['photo'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    $page = 0;
    if(isset($_GET['page']) && is_numeric($_GET ['page']))
        $page = $_GET['page'];


    $erreur = "";
    if( isset($_POST['sub'])){
        if($_POST['Titre']=="")
            $erreur .= "Erreur titre.<br />";
        
        if($erreur == ""){
            try {
                             $req4 = $bdd->prepare("INSERT INTO galeries (Titre,Titre_en,Date,Photographe,Evenement_id,Admin,lien) VALUES (:Titre,:Titre_en,:Date,:Photographe,:Evenement_id,:Admin,:lien)");

                             $req4->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
                             $req4->bindValue('Titre_en',$_POST['Titre_en'], PDO::PARAM_STR);
                             $req4->bindValue('Date',$_POST['Date'], PDO::PARAM_STR);
                             $req4->bindValue('Photographe',$_POST['Photographe'], PDO::PARAM_STR);
                             $req4->bindValue('Evenement_id',$_POST['Evenement_id'], PDO::PARAM_INT);
                             $req4->bindValue('Admin',$_SESSION['login'], PDO::PARAM_STR);
                             $req4->bindValue('lien',$_POST['Lien'], PDO::PARAM_STR);
                             $req4->execute();
                             $destination_path = "../images/galeries/".$bdd->lastInsertId()."/";
                             mkdir($destination_path);
                             chmod($destination_path,0777);

                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
        }
    }
 try{
              $req = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC,E.Nom,E.DateDebut");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

            $req1 = $bdd->prepare("SELECT * FROM galeries ORDER BY ID DESC LIMIT :offset,25");
            $req1->bindValue('offset',$page*25, PDO::PARAM_INT);
            $req1->execute();
            $result3= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result3, $row);
            }

            $req2 = $bdd->prepare("SELECT COUNT(*) AS nbr_gal FROM galeries");
            $req2->execute();
            $data= $req2->fetch(PDO::FETCH_ASSOC);
            $nbrGal    = $data['nbr_gal'];

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin galerie</title>

    <script type="text/javascript">
    $(function() {
        $('#datepicker').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#datepicker').datepicker('option', {
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>

    <!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Ajouter galerie</legend>
        <form action="galerie.php" method="post">
            <p id="pErreur" align="center"><?php echo $erreur; ?></p>
            <table>
                <tr>
                    <td align="right"><label for="Titre">Titre : </label></td>
                    <td><input type="text" name="Titre" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Titre_en">Titre_en : </label></td>
                    <td><input type="text" name="Titre_en" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Date">Date : </label></td>
                    <td><input type="text" name="Date" id="datepicker" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Lien">Lien : </label></td>
                    <td><input type="text" name="Lien" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Photographe">Photographe : </label></td>
                    <td><input type="text" name="Photographe" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Evenement_id">Evenement : </label></td>
                    <td>
                        <select name="Evenement_id">
                            <option value="0">aucun</option>
                            <?php //while($event = mysql_fetch_array($result1)){
            foreach ($result1 as $event) {
            $str = '<option value="'.$event['ID'].'">'.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';
            echo $str;
        } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="sub" value="cr&eacute;er" /></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <fieldset style="float:left;">
        <legend>Liste des galeries</legend>
        <div align="center">
            <div style="text-align:center;">
                <?php
            for($i=0;$i<$nbrGal/25;$i++){
                echo ($i==$page)?'<a style="margin:2px;color:red;font-size:16px;" href="galerie.php?page='.$i.'">'.($i+1).'</a>':'<a style="margin:2px;" href="galerie.php?page='.$i.'">'.($i+1).'</a>';
            }
        ?>
            </div>
            <table class="tab1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Titre_en</th>
                        <th>Date</th>
                        <th>Lien</th>
                        <th>Photographe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        
                        //while($galerie = mysql_fetch_array($result3)){
                        foreach ($result3 as $galerie) {
                        echo "<tr align=\"center\" ><td>".$galerie['ID']."</td><td>".$galerie['Titre']."</td><td>".$galerie['Titre_en']."</td><td>".$galerie['Date']."</td><td>".$galerie['lien']."</td><td>".$galerie['Photographe']."</td>
                            <td>";

                        if($galerie['Admin'] == $_SESSION['login'] || $_SESSION['admin'] == true){
                        echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='galerieDetail.php?galerieID=".$galerie['ID']."'\" />
                            <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$galerie['Titre']." ?')) { location.href='supprimerGalerie.php?galerieID=".$galerie['ID']."&galerieTitre=".$galerie['Titre']."';} else { return 0;}\" />";
                        }
                        echo '</td></tr>';
        } ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</body>
<!-- InstanceEnd -->

</html>