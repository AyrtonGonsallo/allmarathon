<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';
$id = (int)$_GET['evenement_id'];

$erreur = "";
if( isset($_POST['sub'])) {
    if($_POST['titre']=="")
        $erreur .= "titre vide<br />";
    if($_POST['texte']=="")
        $erreur .= "texte vide<br />";
    if($erreur == "" ) {
        try {
            $req4 = $bdd->prepare("INSERT INTO evenementimportantdirect (date,admin,titre,texte,evenement_important_id,une) VALUES (:date,:admin,:titre,:texte,:evenement_important_id,:une)");

           $req4->bindValue('date',date("Y-m-d H:i:s"), PDO::PARAM_STR);
           $req4->bindValue('admin',$_SESSION['login'], PDO::PARAM_STR);
           $req4->bindValue('titre',$_POST['titre'], PDO::PARAM_STR);
           $req4->bindValue('texte',$_POST['texte'], PDO::PARAM_STR);
           $req4->bindValue('evenement_important_id',$id, PDO::PARAM_INT);
           $req4->bindValue('une',$_POST['une'], PDO::PARAM_STR);
           $statut=$req4->execute();
       }
       catch(Exception $e)
       {
        die('Erreur : ' . $e->getMessage());
        }
}

}

try{
    $req = $bdd->prepare("SELECT * FROM evenementimportants WHERE ID=:id");
    $req->bindValue('id',$id, PDO::PARAM_INT);
    $req->execute();
    $event=  $req->fetch(PDO::FETCH_ASSOC);


}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}


$destination_path = "../images/direct/".$id;

if(!is_dir($destination_path))
    mkdir($destination_path);

$destination_path = "../images/direct/".$id."/audio/";

if(!is_dir($destination_path))
    mkdir($destination_path);

$destination_path = "/images/direct/".$id."/audio/";


try{
    $req = $bdd->prepare("SELECT * FROM evenementimportantdirect WHERE evenement_important_id=:id ORDER BY ID DESC");
    $req->bindValue('id',$id, PDO::PARAM_INT);
    $req->execute();
    $result2= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result2, $row);
    }
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
                // General options
                convert_urls : false,
                mode : "textareas",
                theme : "advanced",
                plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                // Theme options
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
                theme_advanced_buttons3 : "undo,redo,|,visualaid,|,tablecontrols",
                external_image_list_url : "directGetFile.php?direct_id=<?php echo $id;?>"
            });
        </script>
        <script type="text/javascript" src="../fonction/jquery.uploadify-v1.6.2/jquery.uploadify.js"></script>
        <link href="../fonction/jquery.uploadify-v1.6.2/uploadify.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            function getCode(nom_fichier){
                var object = '<object type="application/x-shockwave-flash" data="/fonction/dewplayer/dewplayer.swf" width="200" height="20" id="dewplayer" name="dewplayer"><param name="wmode" value="transparent" /><param name="movie" value="fonction/dewplayer/dewplayer.swf" /><param name="flashvars" value="mp3=<?php echo $destination_path; ?>'+nom_fichier+'" /></object>';
                $('#audio_code').val(object);
                $('#audio_code_text').show();
                $('#audio_code').show();
            }


            $(document).ready(function() {
                $('#fileInput').fileUpload({
                    'uploader':'../fonction/jquery.uploadify-v1.6.2/uploader.swf',
                    'script':'directUploadFile.php',
                    'cancelImg':'../fonction/jquery.uploadify-v1.6.2/cancel.png',
                    'folder':'<?php echo ':'.$id;?>',
                    'buttonText':'Ajouter photos',
                    'simUploadLimit':1,
                    'multi':true,
                    'auto':false,
                    'onComplete': function(event, queueID, fileObj, response, data) {
                        $('#filesUploaded').append('<br>'+response+'<br>');},
                        'onAllComplete': function(event,data){
                        //window.location.reload();
                    }

                });
                $('#fileInput2').fileUpload({
                    'uploader':'../fonction/jquery.uploadify-v1.6.2/uploader.swf',
                    'script':'directUploadAudio.php',
                    'cancelImg':'../fonction/jquery.uploadify-v1.6.2/cancel.png',
                    'folder':'<?php echo ':'.$id;?>',
                    'buttonText':'Ajouter audio',
                    'simUploadLimit':1,
                    'multi':true,
                    'auto':false,
                    'onComplete': function(event, queueID, fileObj, response, data) {
                        $('#filesUploaded2').append('<br>'+response+'<br>');},
                        'onAllComplete': function(event,data){
                        //window.location.reload();
                    }

                });
                $('#audio_code').click( function(){
                    $(this).select();
                });
            });
        </script>
        <script type="text/javascript">
            function addCompletion(str,index){
                tab     = str.split(':');
                idChamp  = tab[0];
                name     = tab[1];
                nameLink = tab[2];
                document.getElementById("autocomp"+index).style.display = "none";
                $('#temp1').val('');
                $('#result').html('<a href="/athlète-'+idChamp+'-'+nameLink+'.html">'+name+'</a> ');
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
        <title>allmarathon admin</title>
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>

        <fieldset style="float:left;width: 650px;">
            <legend>Direct <?php echo $event['Nom']?></legend>
            <div>
                <b style="color:red;"><?php echo $erreur.'<br />'; ?></b>
                <form action="" method="post" >
                    titre : <input name="titre" value="<?php if(isset($_POST['titre'])) echo $_POST['titre']; ?>" /><br />
                    A la Une : 
                    <?php if(isset($_POST['une'])){
                        if($_POST['une'] == 0){
                            ?>
                            oui<input type="radio" name="une" value="1"/> non<input type="radio" name="une" value="0" checked/>
                            <?php }if ($_POST['une'] == 1){ ?>
                            oui<input type="radio" name="une" value="1" checked/> non<input type="radio" name="une" value="0"/>
                            <?php }
                        }else{
                            ?>
                            oui<input type="radio" name="une" value="1"/> non<input type="radio" name="une" value="0" checked/>
                            <?php
                        }
                        ?>
                        <br/><br/>
                        <textarea name="texte" cols="30" rows="5"><?php if(isset($_POST['texte'])) echo $_POST['texte']; ?></textarea><br/>
                        <div id="autoCompChamp1">
                            liens champions : <input autocomplete="off" type="text" id="temp1" value="" />
                            <div id="autocomp1" style="display:none;" class="autocomp"></div>
                            <input style="display:none;" id="champion1" name="Champion_id" type="text" value="" />
                            <div id="result" style="display: inline;"></div
                            </div>
                            <input style="float:right;" name="sub" type="submit" value="inserer" />
                        </form>

                        <br />
                        <h3>Direct :</h3>
                <?php //while($direct = mysql_fetch_array($result2)):
                foreach ($result2 as $direct) {
                    ?>

                    <div style="margin: 2px;padding: 2px;border:dotted 1px gray;">
                        <a href="modifierDirect.php?directID=<?php echo $direct['ID'] ?>&evenementID=<?php echo $direct['evenement_important_id']?>">modifer</a> <a href="supprimerDirect.php?directID=<?php echo $direct['ID'] ?>&evenementID=<?php echo $direct['evenement_important_id']?>" onclick="if(window.confirm('Supprimer le post ?')){return true; }else {return false;}">supprimer</a><br />
                        <b><?php echo $direct['admin'] ?></b> <i><?php echo $direct['date'] ?></i> <?php echo str_replace("\\", "", $direct['titre']) ?><br />
                        <?php echo str_replace("\\", "", $direct['texte']) ?>
                    </div>
                    <?php }?>
                </div>
            </fieldset>
            <div style="float:left;">
                <fieldset>
                    <legend>Envoi d'image</legend>
                    <div class="demo">
                        <input type="file" id="fileInput" name="fileInput" />
                        <br />
                        <a href="javascript:$('#fileInput').fileUploadStart();">Envoyer Photos</a> | <a href="javascript:$('#fileInput').fileUploadClearQueue();">Effacer Liste</a>
                    </div>
                    <div id="filesUploaded"></div>
                </fieldset>
                <fieldset>
                    <legend>Envoi de mp3</legend>
                    <div class="demo">
                        <input type="file" id="fileInput2" name="fileInput2" />
                        <br />
                        <a href="javascript:$('#fileInput2').fileUploadStart();">Envoyer Fichier</a> | <a href="javascript:$('#fileInput2').fileUploadClearQueue();">Effacer Liste</a>
                    </div>
                    <div id="filesUploaded2"></div>

                    <div>
                        <table class="tab1">
                            <thead>
                                <tr>
                                    <th>Fichier audio disponible</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $destination_path = "../images/direct/".$id."/audio";

                                $MyDirectory = opendir($destination_path) or die('dossier introuvable');
                                while($Entry = @readdir($MyDirectory)) {
                                    // !is_dir($Directory.'/'.$Entry) &&
                                    if($Entry != '.' && $Entry != '..') {
                                        echo '<tr>
                                        <td><a onclick="getCode(\''.$Entry.'\')" style="cursor:pointer;" >'.$Entry.'</a></td>
                                    </tr>';
                                }
                            }
                            closedir($MyDirectory);
                            ?>
                        </tbody>
                    </table>
                    <span id="audio_code_text" style="display: none;">&agrave; copier dans le html du direct </span><br />
                    <input id="audio_code" style="display: none;" size="30" />
                </div>
            </fieldset>
        </div>
    </body>
    </html>