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
        $req = $bdd->prepare("SELECT * FROM newscategorie WHERE ID=:id");
        $req->bindValue('id',$_GET['newscatID'], PDO::PARAM_INT);
        $req->execute();
        $cat= $req->fetch(PDO::FETCH_ASSOC);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

if($_GET['newscatID']!=""){
    $erreur = "";
    if( isset($_POST['sub'])){


             try {
                 $req4 = $bdd->prepare("UPDATE newscategorie SET Intitule=:inti,Description=:descr WHERE ID=:id");
                 $req4->bindValue('inti',$_POST['Intitule'], PDO::PARAM_STR);
                 $req4->bindValue('descr',$_POST['Description'], PDO::PARAM_STR);
                 $req4->bindValue('id',$_GET['newscatID'], PDO::PARAM_INT);
                 $statut=$req4->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
           
            $newscatID = $_GET['newscatID'];
            if($statut)  {
                header('Location: news_categorie.php'); 
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
<link href="https://code.jquery.com/ui/1.10.2/jquery-ui.js" rel="stylesheet" type="text/css" />
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
<legend>Modifer news categorie</legend>

    <form action="newscatDetail.php?newscatID=<?php echo $_GET['newscatID'];?>" method="post" id="form_edit_news" data-id="<?php echo $_GET['newscatID'];?>" enctype='multipart/form-data'>
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <tr>
            <td align="right">
                <label for="Intitule">Intitule : </label>
            </td>
            <td>
                <input type="text" name="Intitule" value="<?php echo $cat["Intitule"]; ?>" />
            </td>
        </tr>

       <tr>
            <td align="right"><label for="Description">Description : </label></td>
            <td><textarea name="Description" cols="30" rows="9"><?php echo $cat["Description"]; ?></textarea></td>
        </tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifer" /></td></tr>
       </table>
    </form>
</fieldset>

</body>
</html>


