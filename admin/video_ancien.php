<?php

 

session_start();
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

    require_once '../database/connection.php';

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
			if(isset($_POST['top_ippon'])){
			$top_ippon2=1;
            $query2    = sprintf("INSERT INTO videos (Titre ,Date ,Duree ,Objet ,Categorie ,Vignette , A_la_une, Champion_id ,Technique_id ,Technique2_id ,Evenement_id, PoidID, Sexe, top_ippon ) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                ,mysql_real_escape_string($_POST['Titre'])
                ,date("Y-m-d G:i:s")
                ,mysql_real_escape_string($_POST['Duree'])
                ,mysql_real_escape_string($_POST['Objet'])
                ,mysql_real_escape_string($_POST['Categorie'])
                ,mysql_real_escape_string($_POST['Vignette'])
                ,mysql_real_escape_string($_POST['A_la_une'])
                ,mysql_real_escape_string($_POST['Champion_id'])
                ,mysql_real_escape_string($_POST['Technique_id'])
                ,mysql_real_escape_string($_POST['Technique2_id'])
                ,mysql_real_escape_string($_POST['Evenement_id'])
                ,mysql_real_escape_string($_POST['poid'])
                ,mysql_real_escape_string($_POST['sexe'])
                ,$top_ippon2);
            $result2   = mysql_query($query2) or die(mysql_error());
			}
			else{
				$query2    = sprintf("INSERT INTO videos (Titre ,Date ,Duree ,Objet ,Categorie ,Vignette , A_la_une, Champion_id ,Technique_id ,Technique2_id ,Evenement_id, PoidID, Sexe, top_ippon) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                ,mysql_real_escape_string($_POST['Titre'])
                ,date("Y-m-d G:i:s")
                ,mysql_real_escape_string($_POST['Duree'])
                ,mysql_real_escape_string($_POST['Objet'])
                ,mysql_real_escape_string($_POST['Categorie'])
                ,mysql_real_escape_string($_POST['Vignette'])
                ,mysql_real_escape_string($_POST['A_la_une'])
                ,mysql_real_escape_string($_POST['Champion_id'])
                ,mysql_real_escape_string($_POST['Technique_id'])
                ,mysql_real_escape_string($_POST['Technique2_id'])
                ,mysql_real_escape_string($_POST['Evenement_id'])
                ,mysql_real_escape_string($_POST['poid'])
                ,mysql_real_escape_string($_POST['sexe'])
                ,0);
            $result2   = mysql_query($query2) or die(mysql_error());
			}

           $q=mysql_query("SELECT u.user_email,c.Nom FROM champions c, abonnement a,phpbb_users u WHERE a.champion = c.ID and u.user_id=a.user and  a.champion=".$_POST['Champion_id']);

           while($r=mysql_fetch_assoc($q)){
                  $email = 'contact@alljudo.net';
                  $subject = "Il y a du nouveau sur la fiche de ".$r['Nom'];
                  $headers = "From: ".$email."\r\n";
                  $headers .= "Reply-To: ". $r['user_email'] . "\r\n";
                  $headers .= "MIME-Version: 1.0\r\n";
                  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                  $message = '
<html><body>Bonjour,
Vous recevez ce message pour vous pr&eacute;venir qu\'un nouveau r&eacute;sultat, une nouvelle vid&eacute;o ou une nouvelle photo a &eacute;t&eacute; ajout&eacute;e sur la fiche de '.$r['Nom'].'
Cordialement
L\'&eacute;quipe de Allmarathon</body></html>';
                  mail($r['user_email'], $subject,$message,$headers);
           }


        }
    }

    $query1    = sprintf('SELECT * FROM videos ORDER BY ID DESC');// LIMIT %s,25',$page*25);
    $result1   = mysql_query($query1);

    $query3    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
    $result3   = mysql_query($query3) or die(mysql_error());


    $query5    = sprintf('SELECT ID,Nom FROM technique ORDER BY Nom');
    $result5   = mysql_query($query5);
    $y = 0;
    while($t = mysql_fetch_array($result5)){
        $techniques[$y]['Nom'] = $t['Nom'];
        $techniques[$y]['ID']  = $t['ID'];
        $y++;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
        document.getElementById('vignette').value = (document.getElementById('objet').value != "")? "http://i1.ytimg.com/vi/XXIDVIDEOXX/default.jpg":"" ;
    }
</script>

<title>allmarathon admin</title>




</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter video</legend>
    <form action="video.php" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>

        <tr><td align="right"><label for="Titre">Titre : </label></td><td><input type="text" name="Titre" value="" /></td></tr>
        <tr><td  align="right"><label for="Duree">Duree : </label></td><td><input type="text" name="Duree" value="" /></td></tr>
        <tr><td align="right"><label for="Objet">Objet : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="objet" name="Objet" ></textarea></td></tr>
        <tr><td  align="right"><label for="Vignette">Vignette : </label></td><td><input size="50" type="text" id="vignette" name="Vignette" value="" /></td></tr>
        <tr><td  align="right"><label for="Vignette">A la une : </label></td><td>
            <select name="A_la_une" >
                <option value="1" selected="selected">oui</option>
                <option value="0">non</option>
            </select>
            </td>
        </tr>
        <tr><td align="right"><label for="Categorie">Categorie : </label></td><td>
        <select name="Categorie" >
            <option value="combat">combat</option>
            <option value="clip">clip</option>
            <option value="competition">competition</option>
            <option value="technique">technique</option>
            <option value="divers">divers</option>
            <option value="reaction">reaction</option>
              <option value="trailer">teaser</option>
                <option value="interview">interview</option>
        </select></td></tr>
        <tr><td><label for="champion1">Champion :</label></td>
                <td><div id="autoCompChamp1">
                    <input autocomplete="off" type="text" id="temp1" onkeyup="autoComp(1);" value="" />
                    <div id="autocomp1" style="display:none;" class="autocomp"></div>
                    <input style="display:none;" id="champion1" name="Champion_id" type="text" value="" />
                </div></td></tr>

        <tr><td><label for="Technique_id">technique : </label></td><td><select name="Technique_id"><option value="0" >aucune</option><?php foreach($techniques AS $tech) echo '<option value="'.$tech['ID'].'">'.$tech['Nom'].'</option>';?></select></td></tr>
        <tr><td><label for="Technique2_id">technique2 :</label></td><td><select name="Technique2_id"><option value="0" >aucune</option><?php foreach($techniques AS $tech) echo '<option value="'.$tech['ID'].'">'.$tech['Nom'].'</option>';?></select></td></tr>
        <tr><td align="right"><label for="Evenement_id">Evenement : </label></td><td>
        <select name="Evenement_id" >
        <option value="0">aucun</option>
        <?php while($event = mysql_fetch_array($result3)){
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

<fieldset style="float:left;">
<legend>Liste des video</legend>
<div >
    <div id="pager" class="pager">
	<form>
		<img src="../fonction/tablesorter/first.png" class="first"/>
		<img src="../fonction/tablesorter/prev.png" class="prev"/>
		<input type="text" class="pagedisplay"/>
		<img src="../fonction/tablesorter/next.png" class="next"/>
		<img src="../fonction/tablesorter/last.png" class="last"/>
		<select class="pagesize">
			<option selected="selected"  value="10">10</option>

			<option value="20">20</option>
			<option value="30">30</option>
			<option  value="40">40</option>
		</select>
	</form>
    </div>
    <br />
    <table class="tablesorter">
    <thead>
        <tr><th>ID</th><th>Titre</th><th>Categorie</th><th>Vignette</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php while($video = mysql_fetch_array($result1)){
            echo "<tr align=\"center\" ><td>".$video['ID']."</td><td>".$video['Titre']."</td><td>".$video['Categorie']."</td><td><img height=\"60\" width=\"90\" src=\"".$video['Vignette']."\" alt= \"".$video['Vignette']."\"/></td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='videoDetail.php?videoID=".$video['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$video['Titre']." ?')) { location.href='supprimerVideo.php?videoID=".$video['ID']."';} else { return 0;}\" /></td></tr>";
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>


