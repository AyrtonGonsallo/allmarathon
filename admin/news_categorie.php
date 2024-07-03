<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

    if($_SESSION['admin'] == false && $_SESSION['news'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';



    if( isset($_POST['sub']) ){
        
        
            try {
                $req4 = $bdd->prepare("INSERT INTO newscategorie(Intitule,Description) VALUES (:inti,:descr);");
                $req4->bindValue('inti',$_POST['Intitule'], PDO::PARAM_STR);
                $req4->bindValue('descr',$_POST['Description'], PDO::PARAM_STR);
                $statut=$req4->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            $newsID = $bdd->lastInsertId();
        
    }

   
    try{
        $req3 = $bdd->prepare("SELECT * FROM newscategorie ORDER BY Intitule");
        $req3->execute();
        $result3= array();
        while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
          array_push($result3, $row);
        }
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/datepicker_time.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/timepicker.css" rel="stylesheet" type="text/css" />
<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<script src="../fonction/ui/js/timepicker.js" type="text/javascript"></script>

<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        convert_urls: false,
        mode: "exact",
        elements: "Description",
        theme: "advanced",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        // Theme options
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
        theme_advanced_buttons3: "undo,redo,|,visualaid,|,tablecontrols"
    });
</script>

<!-- InstanceBeginEditable name="doctitle" -->
<title>allmarathon admin</title>


</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter categorie de news</legend>
    <form action="news_categorie.php" method="post" enctype="multipart/form-data">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <tr><td align="right"><label for="Intitule">Intitule : </label></td><td><input type="text" name="Intitule" value="" /></td></tr>
        <tr>
            <td align="right"><label for="Description">Description : </label></td>
            <td><textarea name="Description" cols="30" rows="9"></textarea></td>
        </tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="créer" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Liste des categories news</legend>
<div >
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Intitule</th><th>Description</th></tr>
    </thead>
    <tbody>
        <?php foreach ($result3 as $cat) {
			echo "<tr align=\"center\" ><td>".$cat['ID']."</td><td>".$cat['Intitule']."</td><td>".$cat['Description']."</td>
                <td>";
            if($news['admin'] == $_SESSION['login'] || $_SESSION['admin'] == true){
                echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='newscatDetail.php?newscatID=".$cat['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".addslashes($news['titre'])." ?')) { location.href='supprimerNewscat.php?newscatID=".$cat['ID']."';} else { return 0;}\" />";
            }
            echo "</td>
            </tr>";
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>



