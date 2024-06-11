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
    if(isset($_GET["ID"])){
        $id=$_GET["ID"];
    }else{
        $id=$_POST["ID"];
    }
    require_once '../database/connexion.php';

    $page = 0;
    if(isset($_GET['page']) && is_numeric($_GET ['page']))
        $page = $_GET['page'];


    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Titre']=="")
            $erreur .= "Erreur titre.<br />";
        if($_POST['Champion_id']=="")
            $_POST['Champion_id'] = 0;
        if($erreur == ""){
            $top_ippon2=(isset($_POST['top_ippon'])) ? 1:0;
			// if(isset($_POST['top_ippon'])){
			// $top_ippon2=1;
            try {
                             $req4 = $bdd->prepare("INSERT INTO videos (Titre ,Date ,Duree ,Objet,description ,Categorie ,Vignette ,A_la_une, Champion_id ,Technique_id ,Technique2_id ,Evenement_id, PoidID, Sexe, top_ippon ) VALUES (:Titre,:Date,:Duree,:Objet,:descr,:Categorie,:Vignette,:A_la_une,:Champion_id,:Technique_id,:Technique2_id,:Evenement_id,:PoidID,:Sexe,:top_ippon)");

                             $req4->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
                             $req4->bindValue('Date',date("Y-m-d G:i:s"), PDO::PARAM_STR);
                             $req4->bindValue('Duree',$_POST['Duree'], PDO::PARAM_STR);
                             $req4->bindValue('Objet',$_POST['Objet'], PDO::PARAM_STR);
                             $req4->bindValue('descr',$_POST['description'], PDO::PARAM_STR);
                             $req4->bindValue('Categorie',$_POST['Categorie'], PDO::PARAM_STR);
                             $req4->bindValue('Vignette',$_POST['Vignette'], PDO::PARAM_STR);
                             $req4->bindValue('A_la_une',$_POST['A_la_une'], PDO::PARAM_STR);

                             $req4->bindValue('Champion_id',$_POST['Champion_id'], PDO::PARAM_INT);
                             $req4->bindValue('Technique_id',0, PDO::PARAM_INT);
                             $req4->bindValue('Technique2_id',0, PDO::PARAM_INT);
                             $req4->bindValue('Evenement_id',$_POST['Evenement_id'], PDO::PARAM_INT);
                             $req4->bindValue('PoidID',$_POST['poid'], PDO::PARAM_STR);
                             $req4->bindValue('Sexe',$_POST['sexe'], PDO::PARAM_STR);
                             $req4->bindValue('top_ippon',$top_ippon2, PDO::PARAM_STR);
                             $req4->execute();

                             $req = $bdd->prepare("delete FROM champion_admin_externe_journal where ID=:id");
                             $req->bindValue('id',$_POST['jid'], PDO::PARAM_INT);
                             $req->execute();

                             header('Location: video.php');

                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }

           
        }
    }

    $y = 0;

    $req5 = $bdd->prepare("select j.champion_id,j.video,c.Nom as champion_name FROM champion_admin_externe_journal j, champions c where c.ID=j.champion_id and j.ID=:id");
    $req5->bindValue('id',$id, PDO::PARAM_INT);
    $req5->execute();
    $video_admin = $req5->fetch(PDO::FETCH_ASSOC);
    
    function generateYouTubeEmbed($url) {
        // Extraire l'ID de la vidéo YouTube à partir de l'URL
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $videoId = $query['v'];
    
        // Générer l'URL de l'iframe
        $iframeUrl = "https://www.youtube.com/embed/$videoId?si=rSA59f79NpmEXvX-";
    
        // Retourner l'iframe
        return "<iframe width=\"100%\" height=\"445\" src=\"$iframeUrl\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>";
    }
    
    function generateYouTubeThumbnail($url) {
        // Extraire l'ID de la vidéo YouTube à partir de l'URL
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $videoId = $query['v'];
    
        // Générer l'URL de l'image miniature
        $thumbnailUrl = "https://i1.ytimg.com/vi/$videoId/default.jpg";
    
        // Retourner l'URL de l'image miniature
        return $thumbnailUrl;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fonction/tablesorter/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
    $(document).ready(function()
        {
            $("table.tablesorter")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager")});
        }
    );

</script>
<script type="text/javascript" src="../script/ajax.js" ></script>
<script type="text/javascript">
    function autoComp(index){
        $.get('resultatAutoCompletion.php',{id: index, str: $('#temp'+index).val()},function(data){
            if(data != 0){
                $('#autocomp'+index).css('display', '');
                $('#autocomp'+index).html(data);
            }else{
                $('#autocomp'+index).css('display', 'none');
            }
        });
    }
    function addCompletion(str,index){
        tab     = str.split(':');
        idChamp = tab[0];
        name    = tab[1];
        document.getElementById("autocomp"+index).style.display = "none";
        document.getElementById("champion"+index).value = idChamp;
        document.getElementById("temp"+index).value = name;
    }
    function fillPathPicture(){
       if(document.getElementById('vignette').value == "")
        document.getElementById('vignette').value = (document.getElementById('objet').value != "")? "https://www.allmarathon.fr/images/logo-news.png":"" ;
    }

</script>
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
    tinyMCE.init({
        // General options
        convert_urls: false,
        mode: "exact",
        elements: "description",
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
<title>allmarathon admin</title>




</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter video</legend>
    <form action="valider_video_externe.php" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <input  id="jid" name="jid" type="hidden" value="<? echo $id;?>" />

        <tr><td align="right"><label for="Titre">Titre : </label></td><td><input type="text" name="Titre" value="" /></td></tr>
        <tr><td  align="right"><label for="Duree">Duree : </label></td><td><input type="text" name="Duree" value="" /></td></tr>
        <tr><td align="right"><label for="Objet">Objet : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="objet" name="Objet" >
        <? echo generateYouTubeEmbed($video_admin['video']);?>
        </textarea></td></tr>
        <tr><td align="right"><label for="description">Description : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="description" name="description" ></textarea></td></tr>

        <tr><td  align="right"><label for="Vignette">Vignette : </label></td><td><input size="50" type="text" id="vignette" name="Vignette" value="<? echo generateYouTubeThumbnail($video_admin['video']);?>" /></td></tr>
        <tr><td  align="right"><label for="Vignette">A la une : </label></td><td>
            <select name="A_la_une" >
                <option value="1" selected="selected">oui</option>
                <option value="0">non</option>
            </select>
            </td>
        </tr>
        <tr><td align="right"><label for="Categorie">Categorie : </label></td><td>
        <select name="Categorie" >
            <option value="course">course</option>
            <option value="conseils">conseils</option>
            <option value="trailer">teaser</option>
        </select></td></tr>
        <tr><td><label for="champion1">Champion :</label></td>
                <td><div id="autoCompChamp1">
                    <input autocomplete="off" type="text" id="temp1" onkeyup="autoComp(1);" value="<? echo $video_admin['champion_name'];?>" />
                    <div id="autocomp1" style="display:none;" class="autocomp"></div>
                    <input style="display:none;" id="champion1" name="Champion_id" type="text" value="<? echo $video_admin['champion_id'];?>" />
                </div></td></tr>

        <tr><td align="right"><label for="Evenement_id">Evenement : </label></td><td>
        <select name="Evenement_id" >
        <option value="0">aucun</option>
        <?php //while($event = mysql_fetch_array($result3)){
            foreach ($result3 as $event) {
            $str = '<option value="'.$event['ID'].'">'.$event['ID'].' : '.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';
            echo $str;
        } ?>
        </select></td></tr>
        <tr><td><label for="poid">Poid : </label></td><td><input type="text" name="poid" /></td></tr>
        <tr><td><label for="sexe">Sexe : </label></td><td>
                <select name="sexe">
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
            </td></tr>
			<tr><td>Top ippon : </td><td><input type="checkbox" name="top_ippon"/></td></tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
        </table>
    </form>
</fieldset>

</body>
<!-- InstanceEnd --></html>


