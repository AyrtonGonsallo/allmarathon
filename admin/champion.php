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

    if($_SESSION['admin'] == false && $_SESSION['ev'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';
    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
        
        $date   = ($_POST['DateNaissance']=='')?'NULL':$_POST['DateNaissance'];
        $datechang   = ($_POST['DateChangementNat']=='')?'NULL':$_POST['DateChangementNat'];
        if($erreur == ""){
            try {
                 $req4 = $bdd->prepare("INSERT INTO champions (Nom,Sexe,PaysID,NvPaysID,DateChangementNat,DateNaissance,LieuNaissance,Equipementier,Lien_site_équipementier,Instagram,Facebook, Bio,Poids,Taille) VALUES (:Nom,:Sexe,:PaysID,:NvPaysID,:DateChangementNat,:DateNaissance,:LieuNaissance,:Equipementier,:lien_equip,:Instagram,:Facebook,:Bio,:p,:t)");

                 $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
                 $req4->bindValue('Sexe',$_POST['Sexe'], PDO::PARAM_STR);
                 $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
                 $req4->bindValue('NvPaysID',$_POST['NvPaysID'], PDO::PARAM_STR);
                 $req4->bindValue('DateChangementNat',$datechang, PDO::PARAM_STR);
                 $req4->bindValue('DateNaissance',$date, PDO::PARAM_STR);
                 $req4->bindValue('LieuNaissance',$_POST['LieuNaissance'], PDO::PARAM_STR);
                 $req4->bindValue('Equipementier',$_POST['Equipementier'], PDO::PARAM_STR);
                 $req4->bindValue('lien_equip',$_POST['lien_equip'], PDO::PARAM_STR);
                $req4->bindValue('Instagram',$_POST['Instagram'], PDO::PARAM_STR);
                $req4->bindValue('p',$_POST['poids'], PDO::PARAM_INT);
                $req4->bindValue('t',$_POST['taille'], PDO::PARAM_INT);
                $req4->bindValue('Facebook',$_POST['Facebook'], PDO::PARAM_STR);
                $req4->bindValue('Bio',$_POST['Bio'], PDO::PARAM_STR);
                 
                 $statut=$req4->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }


            // $query2    = sprintf("INSERT INTO champions (Nom,Sexe,PaysID,DateNaissance,LieuNaissance,Grade,Clubs,Taille,Poids,TokuiWaza,MainDirectrice,Activite,Forces,Idole,Idole2,Idole3,Idole4,Idole5,Idole6,Idole7,Lidole2,Lidole3,Lidole4,Lidole5,Lidole6,Lidole7,Anecdote,Phrase,VuPar,Site) VALUES ('%s','%s','%s',%s,'%s','%s','%s',%s,%s,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
            //     ,mysql_real_escape_string($_POST['Nom'])
            //     ,mysql_real_escape_string($_POST['Sexe'])
            //     ,mysql_real_escape_string($_POST['PaysID'])
            //     ,$date
            //     ,mysql_real_escape_string($_POST['LieuNaissance'])
            //     ,mysql_real_escape_string($_POST['Grade'])
            //     ,mysql_real_escape_string($_POST['Clubs'])
            //     ,$taille
            //     ,$poid
            //     ,mysql_real_escape_string($_POST['TokuiWaza'])
            //     ,mysql_real_escape_string($_POST['MainDirectrice'])
            //     ,mysql_real_escape_string($_POST['Activite'])
            //     ,mysql_real_escape_string($_POST['Forces'])
            //     ,mysql_real_escape_string($_POST['Idole'])
            //     ,mysql_real_escape_string($_POST['Idole2'])
            //     ,mysql_real_escape_string($_POST['Idole3'])
            //     ,mysql_real_escape_string($_POST['Idole4'])
            //     ,mysql_real_escape_string($_POST['Idole5'])
            //     ,mysql_real_escape_string($_POST['Idole6'])
            //     ,mysql_real_escape_string($_POST['Idole7'])
            //     ,mysql_real_escape_string($_POST['Lidole2'])
            //     ,mysql_real_escape_string($_POST['Lidole3'])
            //     ,mysql_real_escape_string($_POST['Lidole4'])
            //     ,mysql_real_escape_string($_POST['Lidole5'])
            //     ,mysql_real_escape_string($_POST['Lidole6'])
            //     ,mysql_real_escape_string($_POST['Lidole7'])
            //     ,mysql_real_escape_string($_POST['Anecdote'])
            //     ,mysql_real_escape_string($_POST['Phrase'])
            //     ,mysql_real_escape_string($_POST['VuPar'])
            //     ,mysql_real_escape_string($_POST['Site']));
            // $result2   = mysql_query($query2) or die(mysql_error());
        }
    }
    
    $order = 'a';
    if(isset($_GET['order']))
        $order = $_GET['order'];

    try{
              $req = $bdd->prepare("SELECT ID,Nom,Sexe FROM champions WHERE UPPER(Nom) LIKE :Nom ORDER BY  UPPER(Nom)");
              $req->bindValue('Nom',strtoupper($order)."%", PDO::PARAM_STR);
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
                }

                $req3 = $bdd->prepare("SELECT ID,Nom,Sexe FROM champions ORDER BY ID DESC LIMIT 500");
                $req3->execute();
                $result3= array();
                while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
                    array_push($result3, $row);
                 }

                 $req4 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
                $req4->execute();
                $result4= array();
                while ( $row  = $req4->fetch(PDO::FETCH_ASSOC)) {  
                    array_push($result4, $row);
                 }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

    // $query1    = sprintf("SELECT ID,Nom,Sexe FROM champions WHERE UPPER(Nom) LIKE '%s%%' ORDER BY  UPPER(Nom)",strtoupper($order));
    // $result1   = mysql_query($query1);

    // $query3    = sprintf("SELECT ID,Nom,Sexe FROM champions ORDER BY ID DESC LIMIT 500");
    // $result3   = mysql_query($query3);

    // $query4    = sprintf('SELECT * FROM pays ORDER BY NomPays');
    // $result4   = mysql_query($query4);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
</script>

<!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter champion</legend>
    <form action="champion.php" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>

        <tr><td align="right"><label for="Nom">Nom : </label></td><td><input type="text" name="Nom" value="" /></td></tr>
        <tr><td  align="right"><label for="Sexe">Sexe : </label></td><td><input type="radio" name="Sexe" value="M" checked="checked" /><span>homme</span><input type="radio" name="Sexe" value="F"  /><span >femme</span></td></tr>
        
        <tr><td align="right"><label for="PaysID">Pays : </label></td><td>
        <select name="PaysID" >
        <?php //while($pays = mysql_fetch_array($result4)){
            foreach ($result4 as $pays) {
            echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
        } ?>
        </select>
        </td></tr>

        <tr><td align="right"><label for="PaysID">Nouvelle Nationalité : </label></td><td>
        <select name="NvPaysID" >
        <?php //while($pays = mysql_fetch_array($result4)){
            foreach ($result4 as $pays) {
            echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
        } ?>
        </select>
        </td></tr>
        <tr><td  align="right"><label for="DateChangementNat">Date Changement Nationalité : </label></td><td><input type="text" name="DateChangementNat" id="datepicker" value="9999-12-31" /></td></tr>
        
        <tr><td  align="right"><label for="DateNaissance">Date Naissance : </label></td><td><input type="text" name="DateNaissance" id="datepicker2" value="" /></td></tr>
        <tr><td  align="right"><label for="LieuNaissance">Lieu de Naissance : </label></td><td><input type="text" name="LieuNaissance" id="LieuNaissance" value="" /></td></tr>
        <tr><td  align="right"><label for="Equipementier">Equipementier : </label></td><td><input type="text" name="Equipementier" id="Equipementier" value="" /></td></tr>
        <tr><td  align="right"><label for="lien_equip">Lien site équipementier : </label></td><td><input type="text" name="lien_equip" id="lien_equip" value="" /></td></tr>
        <tr><td  align="right"><label for="Instagram">Instagram : </label></td><td><input type="text" name="Instagram" id="Instagram" value="" /></td></tr>
        <tr><td  align="right"><label for="poids">poids : </label></td><td><input type="text" name="poids" id="poids" value="" /></td></tr>
        <tr><td  align="right"><label for="taille">taille : </label></td><td><input type="text" name="taille" id="taille" value="" /></td></tr>

        <tr><td  align="right"><label for="Facebook">Facebook : </label></td><td><input type="text" name="Facebook" id="Facebook" value="" /></td></tr>
        <tr><td  align="right"><label for="Bio">Bio : </label></td><td><textarea name="Bio" id="Bio" cols="50" rows="20"></textarea></td></tr>
        <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Liste des champions</legend>
<div >
    <div id="order">
        <?php for($i='A';$i!='AA';$i++)
            echo ($i==strtoupper($order))?'<a style="margin:2px;font-size:16px;color:red;" href="champion.php?order='.$i.'" >'.$i.'</a>':'<a style="margin:2px;" href="champion.php?order='.$i.'" >'.$i.'</a>';

        ?>
    </div>
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Sexe</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($champion = mysql_fetch_array($result1)){
            foreach ($result1 as $champion) {
             if($_SESSION['admin'] == true){
                echo "<tr align=\"center\" ><td>".$champion['ID']."</td><td>".$champion['Nom']."</td><td>".$champion['Sexe']."</td>
                    <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='championDetail.php?championID=".$champion['ID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$champion['Nom']." ?')) { location.href='supprimerChampion.php?championID=".$champion['ID']."';} else { return 0;}\" /></td></tr>";
            }elseif($_SESSION['ev'] == true){
                echo "<tr align=\"center\" ><td>".$champion['ID']."</td><td>".$champion['Nom']."</td><td>".$champion['Sexe']."</td>
                    <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='championDetail.php?championID=".$champion['ID']."'\" />
                    </td></tr>";
            }
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
<fieldset style="float:left;">
<legend>Liste des champions par ID</legend>
<div >
    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Sexe</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($champion = mysql_fetch_array($result3)){
            foreach ($result3 as $champion) {
           if($_SESSION['admin'] == true){
            echo "<tr align=\"center\" ><td>".$champion['ID']."</td><td>".$champion['Nom']."</td><td>".$champion['Sexe']."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='championDetail.php?championID=".$champion['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$champion['Nom']." ?')) { location.href='supprimerChampion.php?championID=".$champion['ID']."';} else { return 0;}\" /></td></tr>";
           }elseif($_SESSION['ev'] == true){
               echo "<tr align=\"center\" ><td>".$champion['ID']."</td><td>".$champion['Nom']."</td><td>".$champion['Sexe']."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='championDetail.php?championID=".$champion['ID']."'\" />
                </td></tr>";
           }
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>


