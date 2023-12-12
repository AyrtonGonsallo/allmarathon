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

    // require_once '../database/connection.php';
    require_once '../database/connexion.php';

     try{
        $req = $bdd->prepare("SELECT * FROM pays WHERE ID=:id");
        $req->bindValue('id',$_GET['paysID'], PDO::PARAM_INT);
        $req->execute();
        $pays= $req->fetch(PDO::FETCH_ASSOC);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

if($_GET['paysID']!=""){
    $erreur = "";
    if( isset($_POST['sub'])){

        if($_POST['NomPays']=="")
            $erreur .= "Erreur titre.<br />";
        if($erreur == ""){
            $fileName = ($_FILES['Flag']['name']!="") ? $_FILES['Flag']['name'] : $pays['Flag'] ;

             try {
                 $req4 = $bdd->prepare("UPDATE `pays` SET `Abreviation`=:ab,prefixe=:pref,`NomPays`=:np,`texte`=:txt,`Flag`=:flag,`Abreviation_2`=:ab2,`Abreviation_3`=:ab3,`Abreviation_4`=:ab4,`Abreviation_5`=:ab5   WHERE ID=:id");
                 $req4->bindValue('pref',$_POST['prefixe'], PDO::PARAM_STR);
                 $req4->bindValue('ab',$_POST['Abreviation'], PDO::PARAM_STR);
                 $req4->bindValue('np',$_POST['NomPays'], PDO::PARAM_STR);
                 $req4->bindValue('txt',$_POST['Texte'], PDO::PARAM_STR);
                 $req4->bindValue('flag',$fileName, PDO::PARAM_STR);
                 $req4->bindValue('ab5',$_POST['Abreviation_5'], PDO::PARAM_STR);
                 $req4->bindValue('ab2',$_POST['Abreviation_2'], PDO::PARAM_STR);
                 $req4->bindValue('ab3',$_POST['Abreviation_3'], PDO::PARAM_STR);
                 $req4->bindValue('ab4',$_POST['Abreviation_4'], PDO::PARAM_STR);
                 
                 $req4->bindValue('id',$_GET['paysID'], PDO::PARAM_INT);
                 $statut=$req4->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
           
            $paysID = $_GET['paysID'];
            if($statut)  {
                
                
                $destination_path = "../images/flags/";
                if($_FILES['Flag']['error']==0){
                // if(!empty($_FILES['Flag']['name'])){
                    /*  cr�ation de l'mage au bon format */

                    $fileinfo = $_FILES['Flag'];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];
                    if ( $fileinfo['error']) {

                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    $result = "'Le fichier d�passe la limite autoris�e par le serveur (fichier php.ini) !'";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    $result =  "'Le fichier d�passe la limite autoris�e dans le formulaire HTML !'";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     $result = "'L'envoi du fichier a �t� interrompu pendant le transfert !'";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    $result = "'Le fichier que vous avez envoy� a une taille nulle !'";
                                   break;
                          }
                    }else{
                            

                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "Fichier corectement envoy� !";
                                header('Location: paysDetail.php?paysID='.$pays["ID"]); 
                            }else{
                                $result = "Erreur phase finale";
                            }
                        }


                    }
                    else{
                        header('Location: paysDetail.php?paysID='.$pays["ID"]); 
                    }
                    
            }
            
        }
    }

    
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; " /><!--  charset=iso-8859-1 -->
<!-- <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script> -->
<script type = "text/javascript" src = "https://code.jquery.com/jquery-1.12.0.min.js"></script>
<!-- <script src="../fonction/ui/js/datepicker_time.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/timepicker.css" rel="stylesheet" type="text/css" /> -->
<link href="http://code.jquery.com/ui/1.10.2/jquery-ui.js" rel="stylesheet" type="text/css" />
<!-- <script src="../fonction/ui/js/timepicker.js" type="text/javascript"></script> -->
<link rel="stylesheet" href="../fonction/nyroModal-1.5.0/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../fonction/nyroModal-1.5.0/js/jquery.nyroModal-1.5.0.min.js"></script>
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>

<link href="../css/jquery.filer.css" type="text/css" rel="stylesheet" />
<link href="../css/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />


<script src="../fonction/jquery-filer/jquery.filer.min.js"></script>
<script src="../fonction/jquery-filer/custom_filer.js"></script>

<!-- <script type="text/javascript" src="../Scripts/tiny_mce_init.js"></script> -->

<script type="text/javascript">
tinyMCE.init({
    // General options
    convert_urls : false,
    mode : "exact",
    elements : "Texte",
    theme : "advanced",
    plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    // Theme options
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
    theme_advanced_buttons3 : "undo,redo,|,visualaid,|,tablecontrols"
});
     function modal(name)
    {
        $.nyroModalManual({
                 url : name
           });
        return false;
    };
</script>


<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<title>allmarathon admin</title>


</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Modifer pays</legend>
<a href="pays.php">Retour   &agrave; la page de gestion des pays</a>
    <form action="paysDetail.php?paysID=<?php echo $_GET['paysID'];?>" method="post" id="form_edit_pays" data-id="<?php echo $_GET['paysID'];?>" enctype='multipart/form-data'>
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
                <tr>
                    <td align="right"><label for="NomPays">NomPays : </label></td>
                    <td><input type="text" name="NomPays" value="<?php echo $pays['NomPays'];?>" /></td>
                </tr>
                <tr><td><label for="prefixe">Préfixe : </label></td><td><input id="prefixe" type="text" name="prefixe" value="<?php echo $pays['prefixe'];?>" /></td></tr>

                <tr>
                    <td align="right"><label for="Abreviation">Abreviation : </label></td>
                    <td><input type="text" name="Abreviation" value="<?php echo $pays['Abreviation'];?>" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_2">Abreviation_2 : </label></td>
                    <td><input type="text" name="Abreviation_2" value="<?php echo $pays['Abreviation_2'];?>" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_3">Abreviation_3 : </label></td>
                    <td><input type="text" name="Abreviation_3" value="<?php echo $pays['Abreviation_3'];?>" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_4">Abreviation_4 : </label></td>
                    <td><input type="text" name="Abreviation_4" value="<?php echo $pays['Abreviation_4'];?>" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_5">Abreviation_5 : </label></td>
                    <td><input type="text" name="Abreviation_5" value="<?php echo $pays['Abreviation_5'];?>" /></td>
                </tr>
    
                <tr>
                    <td align="right"><label for="Texte">Texte : </label></td>
                    <td><textarea name="Texte" cols="30" rows="9"><?php echo str_replace('\\', '',str_replace('"', '\'', $pays['texte']));?></textarea></td>
                </tr>
                <tr align="center">
                    <td><label for="Flag">Drapeau : </label></td>
                    <td><input type="file" name="Flag" /></td>
                </tr>
                <tr><td colspan="2">
                    <?php
                    if($pays['Flag']!="" && $pays['Flag']!=NULL){?>
                        <img alt="image_news" src="<?php echo "../images/flags/".$pays['Flag'] ?>" width="200px">
                    <?php }else{?>
                        <img alt="image-defaut_news" src="../images/news/defaut.jpg" width="200px">
                    <?php } ?>
                </td></tr>
                <tr align="center">
                    <td colspan="2"><input type="submit" name="sub" value="modifier" /></td>
                </tr>
            </table>
    </form>
</fieldset>

</fieldset>
</body>
</html>


