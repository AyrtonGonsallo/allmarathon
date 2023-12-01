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

   if(!isset($_GET['videoID']))
    header('Location:video.php');

    require_once '../database/connexion.php';
    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Titre']=="")
            $erreur .= "Erreur titre.<br />";
        if($_POST['Champion_id']=="")
            $_POST['Champion_id'] = 0;
        if($erreur == ""){
            $top_ippon2=(isset($_POST['top_ippon'])) ? 1:0;
            try {
                             $req4 = $bdd->prepare("UPDATE videos SET Titre=:Titre ,Duree=:Duree ,Objet=:Objet,description=:desc ,Categorie=:Categorie ,Vignette=:Vignette , A_la_une=:A_la_une, Champion_id=:Champion_id ,Technique_id=:Technique_id ,Technique2_id=:Technique2_id ,Evenement_id=:Evenement_id, PoidID=:PoidID, Sexe=:Sexe, top_ippon=:top_ippon WHERE ID=:ID");

                             $req4->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
                             $req4->bindValue('Duree',$_POST['Duree'], PDO::PARAM_STR);
                             $req4->bindValue('Objet',$_POST['Objet'], PDO::PARAM_STR);
                             $req4->bindValue('desc',$_POST['description'], PDO::PARAM_STR);
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
                             $req4->bindValue('ID',$_GET['videoID'], PDO::PARAM_INT);
                             $req4->execute();

                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }

                header("Location: video.php");
        }
    }

    try{
              $req = $bdd->prepare("SELECT * FROM videos WHERE ID=:ID");
              $req->bindValue('ID',$_GET['videoID'], PDO::PARAM_INT);
              $req->execute();
              $video= $req->fetch(PDO::FETCH_ASSOC);

            $req1 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY C.Intitule,E.Nom,E.DateDebut");
            $req1->execute();
            $result3= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result3, $row);
            }

            

            $req3 = $bdd->prepare("SELECT ID,Nom FROM champions WHERE ID=:ID");
            $req3->bindValue('ID',$video['Champion_id'], PDO::PARAM_INT);
            $req3->execute();
            $champion= $req3->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

    $y = 0;
     

    // $query6  = sprintf('SELECT ID,Nom FROM champions WHERE ID=%s',$video['Champion_id']);
    // $result6 = mysql_query($query6);
    // $champion  = mysql_fetch_array($result6);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<script type="text/javascript" src="../fonction/tablesorter/jquery-1.3.2.min.js"></script>
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
       document.getElementById('vignette').value = (document.getElementById('objet').value != "")? "https://i1.ytimg.com/vi/XXIDVIDEOXX/default.jpg":"" ;
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
<legend>Modifier video</legend>
    <form action="videoDetail.php?videoID=<?php echo $_GET['videoID'];?>" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
<table>
<tr><td align="right"><label for="Titre">Titre : </label></td><td><input type="text" name="Titre" value="<?php echo str_replace('\\','',str_replace('"','\'',$video['Titre']));?>" /></td></tr>
        <tr><td align="right"><label for="Duree">Duree : </label></td><td><input type="text" name="Duree" value="<?php echo $video['Duree'];?>" /></td></tr>
        <tr><td align="right"><label for="Objet">Objet : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="objet" name="Objet"  ><?php echo str_replace('\\','',$video['Objet']);?></textarea></td></tr>
        <tr><td align="right"><label for="Objet">Description : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="description" name="description"  ><?php echo str_replace('\\','',$video['description']);?></textarea></td></tr>

        <tr><td  align="right"><label for="Vignette">Vignette : </label></td><td><input type="text" size="50" id="vignette" name="Vignette" value="<?php echo $video['Vignette'];?>" /></td></tr>
        <tr><td  align="right"><label for="Vignette">A la une : </label></td><td>
            <select name="A_la_une" >
                <option value="1" <?php if($video['A_la_une'] == 1) echo 'selected="selected"'; ?> >oui</option>
                <option value="0" <?php if($video['A_la_une'] == 0) echo 'selected="selected"'; ?> >non</option>
            </select>
            </td>
        </tr>
        <tr><td align="right"><label for="Categorie">Categorie : </label></td><td>
         <select name="Categorie" >
            <option value="combat" <?php if($video['Categorie']=="combat") echo 'selected="selected"'; ?> >combat</option>
            <option value="clip"  <?php if($video['Categorie']=='clip') echo 'selected="selected"'; ?>>clip</option>
            <option value="competition"  <?php if($video['Categorie']=='competition') 'echo selected="selected"'; ?>>competition</option>
            
            <option value="divers"  <?php if($video['Categorie']=='divers') echo 'selected="selected"'; ?>>divers</option>
            <option value="reaction"  <?php if($video['Categorie']=='reaction') echo 'selected="selected"'; ?>>reaction</option>
            <option value="trailer"  <?php if($video['Categorie']=='trailer') echo 'selected="selected"'; ?>>teaser</option>
            <option value="interview"  <?php if($video['Categorie']=='interview') echo 'selected="selected"'; ?>>interview</option>
       
         </select></td></tr>
        
        <tr><td><label for="champion1">Champion :</label></td>
                <td><div id="autoCompChamp1">
                    <input autocomplete="off" type="text" id="temp1" onkeyup="autoComp(1);" value="<?php if($champion){echo $champion['Nom'];}?>" />
                    <div id="autocomp1" style="display:none;" class="autocomp"></div>
                    <input style="display:none;" id="champion1" name="Champion_id" type="text" value="<?php echo $champion['ID'];?>" />
                </div></td></tr>

        <tr><td align="right"><label for="Evenement_id">Evenement : </label></td><td>
        <select name="Evenement_id" >
        <option value="0">aucun</option>
        <?php //while($event = mysql_fetch_array($result3)){
            foreach ($result3 as $event) {
            $str = ($event['ID']==$video['Evenement_id']) ? '<option value="'.$event['ID'].'" selected="selected" >'.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>':'<option value="'.$event['ID'].'">'.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';
                echo $str;
        } ?>
        </select></td></tr>
        <tr><td><label for="poid">Poid : </label></td><td><input type="text" name="poid" value="<?php echo $video['PoidID'] ?>" /></td></tr>
        <tr><td><label for="sexe">Sexe : </label></td><td>
                <select name="sexe">
                    <option value="M" <?php echo ($video['Sexe'] == 'M') ? 'selected' : '' ?>>M</option>
                    <option value="F" <?php echo ($video['Sexe'] == 'F') ? 'selected' : '' ?>>F</option>
                </select>
            </td></tr>
			<tr><td>Top ippon : </td><td><?php
			if($video['top_ippon']==1){
				echo '<input type="checkbox" name="top_ippon" checked="checked">';
			}
			else{
				echo '<input type="checkbox" name="top_ippon">';
			}
			?>
			</td></tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>
        </table>
    </form>
</fieldset>
