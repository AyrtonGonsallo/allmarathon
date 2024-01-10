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
        $req = $bdd->prepare("SELECT * FROM news WHERE ID=:id");
        $req->bindValue('id',$_GET['newsID'], PDO::PARAM_INT);
        $req->execute();
        $news= $req->fetch(PDO::FETCH_ASSOC);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

if($_GET['newsID']!=""){
    $erreur = "";
    if( isset($_POST['sub'])){

        if($_POST['Titre']=="")
            $erreur .= "Erreur titre.<br />";
        if($erreur == ""){
            $fileName = ($_FILES['photo']['name']!="") ? $_FILES['photo']['name'] : $news['photo'] ;

             try {
                 $req4 = $bdd->prepare("UPDATE news SET date=:date,source=:source,auteur=:auteur,titre=:titre,chapo=:chapo,texte=:texte,photo =:photo ,categorieID=:categorieID,aLaUne =:aLaUne,aLaDeux=:aLaDeux,url=:url,legende=:legende,lien1=:lien1,textlien1=:textlien1,liens_champions=:liens_champions,evenementID=:evenementID ,championID=:championID,videoID=:videoID WHERE ID=:id");

                 $req4->bindValue('date',$_POST['Date'], PDO::PARAM_STR);
                 $req4->bindValue('source',$_POST['Source'], PDO::PARAM_STR);
                 $req4->bindValue('auteur',$_POST['auteur'], PDO::PARAM_STR);
                 $req4->bindValue('titre',$_POST['Titre'], PDO::PARAM_STR);
                 $req4->bindValue('url',$_POST['Url'], PDO::PARAM_STR);
                 $req4->bindValue('legende',$_POST['Legende'], PDO::PARAM_STR);
                 $req4->bindValue('lien1',$_POST['Lien1'], PDO::PARAM_STR);
                 $req4->bindValue('textlien1',$_POST['TextLien1'], PDO::PARAM_STR);
                 $req4->bindValue('chapo',$_POST['Chapo'], PDO::PARAM_STR);
                 $req4->bindValue('texte',$_POST['Texte'], PDO::PARAM_STR);
                 $req4->bindValue('photo',$fileName, PDO::PARAM_STR);
                 $req4->bindValue('liens_champions',$_POST['liens_champions'], PDO::PARAM_STR);
                 $req4->bindValue('categorieID',$_POST['bref'], PDO::PARAM_INT);
                 $req4->bindValue('evenementID',$_POST['evenementID'], PDO::PARAM_INT);
                 $req4->bindValue('championID',$_POST['championID'], PDO::PARAM_INT);
                 $req4->bindValue('videoID',$_POST['videoID'], PDO::PARAM_INT);
                 $req4->bindValue('aLaUne',$_POST['aLaUne'], PDO::PARAM_INT);
                 $req4->bindValue('aLaDeux',$_POST['aLaDeux'], PDO::PARAM_INT);
                 
                 $req4->bindValue('id',$_GET['newsID'], PDO::PARAM_INT);
                 $statut=$req4->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
           
            $newsID = $_GET['newsID'];
            if($statut)  {
                
                $array       = explode('-',$_POST['Date']);
                $destination_path = "../images/news/".$array[0]."/";
                if($_FILES['photo']['error']==0){
                // if(!empty($_FILES['photo']['name'])){
                    /*  cr�ation de l'mage au bon format */

                    $fileinfo = $_FILES['photo'];
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
                            $x = 414;
                            $size = getimagesize($fichierSource);
                            $y = ($x * $size[1]) / $size[0];
                            $img_new = imagecreatefromjpeg($fichierSource);

                            $img_mini = imagecreatetruecolor($x, $y);
                            imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                            imagejpeg($img_mini, $destination_path . "thumb_" . strtolower($fichierName));

                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "Fichier corectement envoy� !";
                                header('Location: newsDetail.php?newsID='.$news["ID"]); 
                            }else{
                                $result = "Erreur phase finale";
                            }
                        }


                    }
                    else{
                        header('Location: newsDetail.php?newsID='.$news["ID"]); 
                    }
                    
            }
            
        }
    }

    // $query1    = sprintf('SELECT * FROM news WHERE ID=%s',mysql_real_escape_string($_GET['newsID']));
    // $result1   = mysql_query($query1);
    // $news      = mysql_fetch_array($result1);
   
    $tab       = explode('-',$news['date']);
    $yearNews  = $tab[0];

    try{
        $req = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
        $req->execute();
        $result5= array();
        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result5, $row);
      }
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
    // $result5   = mysql_query($query5);

    try{
        $req = $bdd->prepare("SELECT * FROM newscategorie ORDER BY Intitule");
        $req->execute();
        $result3= array();
        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result3, $row);
        }
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // $query3    = sprintf('SELECT * FROM newscategorie ORDER BY Intitule');
    // $result3   = mysql_query($query3);

    try{
        $req = $bdd->prepare("SELECT * FROM news_galerie WHERE news_id=:news_id");
        $req->bindValue('news_id',$_GET['newsID'], PDO::PARAM_INT);
        $req->execute();
        $result4= array();
        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result4, $row);
        }
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // $query4    = sprintf('SELECT * FROM news_galerie WHERE news_id=%s',(int)$_GET['newsID']);
    // $result4   = mysql_query($query4);
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
<!-- <script type="text/javascript" src="../fonction/jquery.uploadify-v1.6.2/jquery.uploadify.js"></script>
<link href="../fonction/jquery.uploadify-v1.6.2/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

$(document).ready(function() {
    $('#fileInput').fileUpload({
        'uploader':'../fonction/jquery.uploadify-v1.6.2/uploader.swf',
        'script':'newsUploadFile.php',
        'cancelImg':'../fonction/jquery.uploadify-v1.6.2/cancel.png',
        'folder':'<?php echo ':'.(int)$_GET['newsID'];?>',
        'buttonText':'Ajouter photos',
        'simUploadLimit':1,
        'multi':true,
        'auto':false,
        'onComplete': function(event, queueID, fileObj, response, data) {
             $('#filesUploaded').append('<br>'+response+'<br>');},
        'onAllComplete': function(event,data){location.href = 'newsDetail.php?newsID=<?php echo (int)$_GET['newsID'];?>';},
        'onError'         : function(evt, queueid, fobj, eobj){
            console.log(evt);
            console.log(queueid);
            console.log(fobj);
            console.log(eobj);
        }
        });
});
</script> -->

<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<title>allmarathon admin</title>

<script type="text/javascript">
    // $(function() {
    //      $('#timepicker').datetime({
    //         userLang    : 'en',
    //         americanMode: false
    //     });
    // });

</script>

<script type="text/javascript">
    function addCompletion(str,index){
        tab     = str.split(':');
        idChamp  = tab[0];
        name     = tab[1];
        nameLink = tab[2];
        document.getElementById("autocomp"+index).style.display = "none";
        $('#temp1').val('');
        $('#result').html('<a href="/athlete-'+idChamp+'-'+nameLink+'.html">'+name+'</a> ');
    }

    $(document).ready(function(){
        $('#temp1').keyup(function() {
            if($(this).val().length > 3)
                $.get('resultatAutoCompletionLien.php',{id: 1, str: $(this).val()},function(data){
                    $('#autocomp1').show();
                    $('#autocomp1').html(data);
                });
        });

        $('#cut').click(function(){
            $('#temp1').select();

        });
    });
</script>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Modifer news</legend>
<a href="news.php">Retour   &agrave; la page de gestion des news</a>
    <form action="newsDetail.php?newsID=<?php echo $_GET['newsID'];?>" method="post" id="form_edit_news" data-id="<?php echo $_GET['newsID'];?>" enctype='multipart/form-data'>
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <tr><td align="right"><label>A la une : </label></td><td><input type="radio" name="aLaUne" id="oui" value="1" <?php if($news['aLaUne']) echo 'checked="checked"';?>/><label for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="aLaUne" id="non" value="0" <?php if(!$news['aLaUne']) echo 'checked="checked"';?> /><label for="non">non</label></td></tr>
        <tr><td align="right"><label>A la deux : </label></td><td><input type="radio" name="aLaDeux" id="oui" value="1" <?php if($news['aLaDeux']) echo 'checked="checked"';?>/><label for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="aLaDeux" id="non" value="0" <?php if(!$news['aLaDeux']) echo 'checked="checked"';?> /><label for="non">non</label></td></tr>
        <tr><td align="right"><label>En bref : </label></td><td><input type="radio" name="bref" id="oui" value="11" <?php if($news['categorieID']==11) echo 'checked="checked"';?>/><label for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="bref" id="non" value="0" <?php if($news['categorieID']!=11) echo 'checked="checked"';?> /><label for="non">non</label></td></tr>

        <tr><td  align="right"><label for="Date">Date : </label></td><td><input type="text" name="Date" id="timepicker" value="<?php echo $news['date'];?>" /></td></tr>
        <tr><td align="right"><label for="Source">Source : </label></td><td><input type="text" name="Source" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $news['source']));?>" /></td></tr>
        <tr><td align="right"><label for="auteur">Nom de l'auteur : </label></td><td><input type="text" name="auteur" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $news['auteur']));?>" /></td></tr>

        <tr><td align="right"><label for="Titre">Titre : </label></td><td><input type="text" name="Titre" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $news['titre']));?>" /></td></tr>
        
        <tr><td align="right"><label for="Chapo">Chapo : </label></td><td><textarea name="Chapo" cols="50" rows="7"><?php echo str_replace('\\', '',str_replace('"', '\'', $news['chapo']));?></textarea></td></tr>
        <tr><td  align="right"><label for="texte">Texte : </label></td><td><textarea name="Texte" id="Texte" cols="30" rows="9"><?php echo str_replace('\\', '',str_replace('"', '\'', $news['texte']));?></textarea></td></tr>
        <tr><td align="right"><label for="Url">Url : </label></td><td><input type="text" name="Url" value="<?php echo $news['url'];?>" /></td></tr>
        <tr><td align="right"><label for="Legende">L&eacute;gende : </label></td><td><input type="text" name="Legende" value="<?php echo $news['legende'];?>" /></td></tr>
        <tr><td align="right"><label for="Lien1">Lien 1 : </label></td><td><input type="text" name="Lien1" value="<?php echo $news['lien1'];?>" /></td></tr>
        <tr><td align="right"><label for="TextLien1">Texte lien 1 : </label></td><td><input type="text" name="TextLien1" value="<?php echo $news['textlien1'];?>" /></td></tr>
        <tr><td><label>Liens champions </label></td><td>
                <div id="autoCompChamp1">
                    <input autocomplete="off" type="text" name="liens_champions" id="temp1" value="" />
                    <div id="autocomp1" style="display:none;" class="autocomp"></div>
                    <input style="display:none;" id="champion1" name="champion1" type="text" value="" />
                    <div id="result" style="display: inline;"></div>
                </div>
        </td></tr>
        <tr><td><label for="evenementID">évènement lié : </label></td><td><input id="evenementID" type="number" name="evenementID" value="<?php echo $news['evenementID'];?>" /></td></tr>
        <tr><td><label for="championID">coureur lié : </label></td><td><input id="championID" type="number" name="championID" value="<?php echo $news['championID'];?>" /></td></tr>
        <tr><td><label for="videoID">video lié : </label></td><td><input id="videoID" type="number" name="videoID" value="<?php echo $news['videoID'];?>" /></td></tr>

        
        

        <tr align="center"><td><label for="photo">Photo : </label></td><td><input type="file" name="photo" /></td></tr>
        <tr><td colspan="2">
        <?php
        if($news['photo']!="" && $news['photo']!=NULL){?>
            <img alt="image_news" src="<?php echo "../images/news/".$yearNews."/".$news['photo'] ?>" width="200px">
        <?php }else{?>
            <img alt="image-defaut_news" src="../images/news/defaut.jpg" width="200px">
        <?php } ?>
        </td></tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifer" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Images dans la news</legend>
    <?php

    // while($image = mysql_fetch_array($result4)){
    foreach ($result4 as $image) {   ?>
        <div class="image_galerie" style="margin-top:15px;">
            <img src="../images/news/<?php echo $image['path'];?>" onclick="modal('../images/news/<?php echo $image['path'];?>');" height="80" alt="errerur_chargement_image"/>
            <table>
                <tr><td colspan="2"><?php echo $image['path'];?></td></tr>

                <tr><td><input onclick="if(confirm('Voulez vous vraiment supprimer l\'image ?')) document.location.href='newsImageDelete.php?imageID=<?php echo $image['ID'];?>&newsID=<?php echo $_GET['newsID'];?>'; else return 0;" type="button" value="supprimer" /></td></tr>
            </table>
            </form>
            <div style="clear:both;"></div>
        </div>
        <hr width="100%" />
    <?php } ?>

</fieldset>

<fieldset style="float:left;">
<form action="" method="post" enctype="multipart/form-data">
                              <input type="file" name="files[]" id="filer_input2" multiple="multiple">
                        </form>
</fieldset>
</body>
</html>


