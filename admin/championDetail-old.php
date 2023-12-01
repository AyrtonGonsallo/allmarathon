<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
		{
		header('Location: login.php');
                exit();
    }

    require_once '../database/connection.php';


if(isset($_GET['championID'])) {
    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
        $poid   = ($_POST['Poids']=='')?'NULL':"'".mysql_real_escape_string($_POST['Poids'])."'";
        $taille = ($_POST['Taille']=='')?'NULL':"'".mysql_real_escape_string($_POST['Taille'])."'";
        $date   = ($_POST['DateNaissance']=='')?'NULL':"'".mysql_real_escape_string($_POST['DateNaissance'])."'";
        if($erreur == ""){
            $query2    = sprintf("UPDATE champions SET Nom='%s' ,Sexe='%s' ,PaysID='%s' ,DateNaissance=%s ,LieuNaissance='%s' ,Grade='%s',Clubs='%s',Taille=%s, Poids=%s, TokuiWaza='%s', MainDirectrice='%s', Activite='%s', Forces='%s', Idole='%s', Anecdote='%s', Phrase='%s', VuPar='%s', Site='%s' WHERE ID=%s"
                ,mysql_real_escape_string($_POST['Nom'])
                ,mysql_real_escape_string($_POST['Sexe'])
                ,mysql_real_escape_string($_POST['PaysID'])
                ,$date
                ,mysql_real_escape_string($_POST['LieuNaissance'])
                ,mysql_real_escape_string($_POST['Grade'])
                ,mysql_real_escape_string($_POST['Clubs'])
                ,$taille
                ,$poid
                ,mysql_real_escape_string($_POST['TokuiWaza'])
                ,mysql_real_escape_string($_POST['MainDirectrice'])
                ,mysql_real_escape_string($_POST['Activite'])
                ,mysql_real_escape_string($_POST['Forces'])
                ,mysql_real_escape_string($_POST['Idole'])
                ,mysql_real_escape_string($_POST['Anecdote'])
                ,mysql_real_escape_string($_POST['Phrase'])
                ,mysql_real_escape_string($_POST['VuPar'])
                ,mysql_real_escape_string($_POST['Site'])
                ,mysql_real_escape_string($_GET['championID']));
            $result2   = mysql_query($query2) or die(mysql_error());
            if($result2){
                $query    = sprintf('SELECT * FROM champions WHERE ID=%s',(mysql_real_escape_string($_GET['championID'])-1));
                $result   = mysql_query($query);
                if(mysql_num_rows($result)==1)
                    header("Location: championDetail.php?championID=".($_GET['championID']-1));
                else
                    header("Location: champion.php?order=Z");
            }
        }
    }

    $query1    = sprintf('SELECT * FROM champions WHERE ID=%s',mysql_real_escape_string($_GET['championID']));
    $result1   = mysql_query($query1);
    $champion  = mysql_fetch_array($result1);

    $query4    = sprintf('SELECT * FROM pays ORDER BY NomPays');
    $result4   = mysql_query($query4);
    
    $query3    = sprintf('SELECT * FROM evresultats WHERE ChampionID=%s',mysql_real_escape_string($_GET['championID']));
    $result3   = mysql_query($query3);

    $query4    = sprintf('SELECT * FROM pays ORDER BY NomPays');
    $result4   = mysql_query($query4);

    $tab       = explode(" ", $champion['Nom']);
    $nbr       = count($tab);
    
    
    $query6     = "SELECT * FROM champions WHERE ( ";
    $query6    .= "UPPER(Nom) LIKE UPPER('%".mysql_real_escape_string($tab[0])."%') ";
    for($i=1;$i<$nbr;$i++){
        if(strlen($tab[$i])>3)
            $query6 .= "OR UPPER(Nom) LIKE UPPER('%".mysql_real_escape_string($tab[$i])."%') ";
    }
    $query6    .= ") AND ID != ".mysql_real_escape_string($champion['ID'])." ORDER BY Nom";
    //echo "<br>".$nbr." ".$query6;
    $result6   = mysql_query($query6);


}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<!-- InstanceBeginEditable name="doctitle" -->

<title>allmarathon admin</title>

<script type="text/javascript">
    $(function() {
         $('#datepicker').datepicker({
            changeMonth: true,
			changeYear: true
		});
        $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
        $('#datepicker2').datepicker({
            changeMonth: true,
			changeYear: true
		});
        $('#datepicker2').datepicker('option', {dateFormat: 'yy-mm-dd'});
    });
    
    $(document).ready(function(){
        $('#rempMod').click(function() {
            if(validerRemplacement($('#ramplacant option:selected').html())){
                idRemp = $('#ramplacant option:selected').val();
                window.open("championDetail.php?championID="+idRemp);
                $('#rempForm').submit();
            }
        });
    });
    
    
    function validerRemplacement(nom){
        if(window.confirm(nom+' sera supprimer, voulez vous continuer ?'))
        {
            return true;
        }else{
            return false;
        }
    }
    

</script>

<!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Modifier champion</legend>
<form action="championDetail.php?championID=<?php echo $_GET['championID'];?>" method="post" >
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>

        <tr><td align="right"><label for="Nom">Nom : </label></td><td><input type="text" name="Nom" value="<?php echo str_replace('\\', '',$champion['Nom']);?>" /></td></tr>
        <tr><td  align="right"><label for="Sexe">Sexe : </label></td><td><input type="radio" name="Sexe" value="M" <?php if($champion['Sexe']=="M") echo 'checked="checked"';?> /><span>homme</span><input type="radio" name="Sexe" value="F" <?php if($champion['Sexe']=="F") echo 'checked="checked"';?> /><span >femme</span></td></tr>
        
        <tr><td align="right"><label for="PaysID">Pays : </label></td><td>
        <select name="PaysID" >
        <?php while($pays = mysql_fetch_array($result4)){
            
            if($champion['PaysID']==$pays['Abreviation'])
                echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';
            else
                echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
        } ?>
        </select>
        </td></tr>
        <tr><td  align="right"><label for="DateNaissance">Date Naissance : </label></td><td><input type="text" name="DateNaissance" id="datepicker" value="<?php echo $champion['DateNaissance'];?>" /></td></tr>
        <tr><td  align="right"><label for="LieuNaissance">Lieu de Naissance : </label></td><td><input type="text" name="LieuNaissance" id="LieuNaissance" value="<?php echo $champion['LieuNaissance'];?>" /></td></tr>
        <tr><td  align="right"><label for="Grade">Grade : </label></td><td><input type="text" name="Grade" id="Grade" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Grade']));?>" /></td></tr>
        <tr><td  align="right"><label for="Clubs">Clubs : </label></td><td><input type="text" name="Clubs" id="Clubs" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Clubs']));?>" /></td></tr>
        <tr><td  align="right"><label for="Taille">Taille : </label></td><td><input type="text" name="Taille" id="Taille" value="<?php echo $champion['Taille'];?>" /></td></tr>
        <tr><td  align="right"><label for="Poids">Poids : </label></td><td><input type="text" name="Poids" id="Poids" value="<?php echo $champion['Poids'];?>" /></td></tr>
        <tr><td  align="right"><label for="TokuiWaza">TokuiWaza : </label></td><td><textarea name="TokuiWaza" id="TokuiWaza" cols="50" rows="4"><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['TokuiWaza']));?></textarea></td></tr>
        <tr><td  align="right"><label for="MainDirectrice">MainDirectrice : </label></td><td>
        <select name="MainDirectrice" >
            <option value="">aucune</option>
            <option value="droite" <?php if($champion['MainDirectrice']=='droite') echo 'selected="selected"'; ?>>droite</option>
            <option value="gauche" <?php if($champion['MainDirectrice']=='gauche') echo 'selected="selected"'; ?>>gauche</option>
            <option value="ambidextre" <?php if($champion['MainDirectrice']=='ambidextre') echo 'selected="selected"'; ?>>ambidextre</option>
        </select>

        </td></tr>
        <tr><td  align="right"><label for="Activite">Activit� : </label></td><td><input type="text" name="Activite" id="Activite" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Activite']));?>" /></td></tr>
        <tr><td  align="right"><label for="Forces">Force : </label></td><td><textarea name="Forces" id="Forces" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Forces']));?></textarea></td></tr>
        <tr><td  align="right"><label for="Idole">Idole : </label></td><td><input type="text" name="Idole" id="Idole" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Idole']));?>" /></td></tr>
        <tr><td  align="right"><label for="Anecdote">Anecdote : </label></td><td><textarea name="Anecdote" id="Anecdote" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Anecdote']));?></textarea></td></tr>
        <tr><td  align="right"><label for="Phrase">Phrase : </label></td><td><textarea name="Phrase" id="Phrase" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Phrase']));?></textarea></td></tr>
        <tr><td  align="right"><label for="VuPar">VuPar : </label></td><td><textarea name="VuPar" id="VuPar" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['VuPar']));?></textarea></td></tr>
        <tr><td  align="right"><label for="Site">Site : </label></td><td><input type="text" name="Site" id="Site" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Site']));?>" /></td></tr>


        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>
       </table>
    </form>
</fieldset>
<?php if(mysql_num_rows($result6)!=0){?>
<fieldset style="float:left;">
<legend>Corecteur</legend>
<p>Un ou plusieur champion existant porte un nom proche de celui ci peut etre s'agit t'il de la m�me personne.</p>
<form action="remplacerChampion.php?remplacer=<?php echo $champion['ID']; ?>" method="post" id="rempForm" onsubmit="return validerRemplacement('<?php echo $champion['Nom']; ?>');">
 <label>selectionner le champions par lequel celui ci sera remplacer : </label>
 <select id="ramplacant" name="remplacant">
<?php while($champion2 = mysql_fetch_array($result6)){
                echo '<option value="'.$champion2['ID'].'">'.$champion2['Nom'].'</option>';
        } ?>
</select>
 <input type="submit" value="remplacer" />
 <input type="button" value="remplacer+modifier" id="rempMod"/>
</form>

</fieldset>
<?php } ?>
<?php if(mysql_num_rows($result3)!=0){?>
<fieldset style="float:left;">
<legend>Resultat champion</legend>
<div>
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Evenement</th><th>Rang</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php while($resultat = mysql_fetch_array($result3)){
            $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID WHERE E.ID=%s',mysql_real_escape_string($resultat['EvenementID']));
            $result5   = mysql_query($query5);
            $evenement  = mysql_fetch_array($result5);
            echo "<tr align=\"center\" ><td>".$resultat['ID']."</td><td>".$evenement['Intitule']." ".$evenement['Nom']." ".substr($evenement['DateDebut'],0,4)."</td><td>".$resultat['Rang']."</td>
                <td></td></tr>";
        } ?>
    </tbody>
    </table>
</div>

</fieldset>
<?php } ?>
</body>
<!-- InstanceEnd --></html>



