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

require_once '../database/connexion.php';


if(isset($_GET['championID'])) {
    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
            $poid = empty($_POST['Poids']) ? null : $_POST['Poids'];
            $taille = empty($_POST['Taille']) ? null : $_POST['Taille'];
            $date = empty($_POST['DateNaissance']) ? null : $_POST['DateNaissance'];
            $datechang = empty($_POST['DateChangementNat']) ? null : $_POST['DateChangementNat'];
            
        if($erreur == ""){

         try {
             $req4 = $bdd->prepare("UPDATE champions SET Nom=:Nom ,Poids=:poids ,Taille=:taille ,Sexe=:Sexe ,PaysID=:PaysID,NvPaysID=:NvPaysID,DateChangementNat=:DateChangementNat ,DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Lien_site_équipementier=:lien_equip,Instagram=:Instagram,Facebook=:Facebook, Bio=:Bio,Visible=:visible WHERE ID=:id");

             $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
             $req4->bindValue('Sexe',$_POST['Sexe'], PDO::PARAM_STR);
             $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
             $req4->bindValue('NvPaysID',$_POST['NvPaysID'], PDO::PARAM_STR);
             $req4->bindValue('DateChangementNat',$datechang, PDO::PARAM_STR);
             $req4->bindValue('DateNaissance',$date, PDO::PARAM_STR);
             $req4->bindValue('LieuNaissance',$_POST['LieuNaissance'], PDO::PARAM_STR);
             $req4->bindValue('lien_equip',$_POST['lien_equip'], PDO::PARAM_STR);
             $req4->bindValue('Instagram',$_POST['Instagram'], PDO::PARAM_STR);
             $req4->bindValue('poids',$_POST['poids'], PDO::PARAM_STR);
             $req4->bindValue('taille',$_POST['taille'], PDO::PARAM_STR);
             $req4->bindValue('Facebook',$_POST['Facebook'], PDO::PARAM_STR);
             $req4->bindValue('Bio',$_POST['Bio'], PDO::PARAM_STR);
             $req4->bindValue('visible',$_POST['Visible'], PDO::PARAM_INT);
             $req4->bindValue('id',$_GET['championID'], PDO::PARAM_INT);
             $statut=$req4->execute();

         }
         catch(Exception $e)
         {
            die('Erreur : ' . $e->getMessage());
        }
        if($statut){
            try {
                $req5=$bdd->prepare("SELECT * FROM champions WHERE ID=id");
                $req5->bindValue('id',$_GET['championID']-1, PDO::PARAM_INT); 
                $req5->execute();
                header("Location: championDetail.php?championID=".($_GET['championID']-1));
            }
            catch(Exception $e){
                header("Location: champion.php?order=Z");
            }
        }
    }
}

try{
    $req = $bdd->prepare("SELECT * FROM champions WHERE ID=:id");
    $req->bindValue('id',$_GET['championID'], PDO::PARAM_STR);
    $req->execute();
    $champion=  $req->fetch(PDO::FETCH_ASSOC);

    if($champion){
         $req3 = $bdd->prepare("SELECT * FROM evresultats WHERE ChampionID=:id");
    $req3->bindValue('id',$_GET['championID'], PDO::PARAM_STR);
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

     $tab       = explode(" ", $champion['Nom']);
    $nbr       = count($tab);

    if(strlen($tab[0])>3){

        $query6     ="SELECT * FROM champions WHERE (ID !=".$champion['ID'].") AND (( UPPER(Nom) LIKE UPPER('%".$tab[0]."%'))";

        for($i=1;$i<$nbr;$i++){
            if(strlen($tab[$i])>3)
                $query6 .= "OR (UPPER(Nom) LIKE UPPER('%".$tab[$i]."%'))";
        }
        $query6    .= ") ORDER BY Nom";
    }else{
         $query6     ="SELECT * FROM champions WHERE (ID !=".$champion['ID'].")";
         $result_vide="AND (1=2)";
        for($i=1;$i<$nbr;$i++){
            if(strlen($tab[$i])>3)
                {
                    $query6 .= "AND (UPPER(Nom) LIKE UPPER('%".$tab[$i]."%'))";
                    $result_vide="";
                }
        }
        $query6    .= $result_vide." ORDER BY Nom";
        
    }

    
    $req6 = $bdd->prepare($query6);
    $req6->execute();
    $result6= array();
    while ( $row  = $req6->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result6, $row);
    }


    }else{
        header("Location: champion.php");
    }

}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

}

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
         $('#DateChangementNat').datepicker({
            changeMonth: true,
            changeYear: true
        });
         $('#DateChangementNat').datepicker('option', {dateFormat: 'yy-mm-dd'});
     });

        $(document).ready(function(){
            $('#rempMod').click(function() {
                if(validerRemplacement('<?php echo $champion['Nom']; ?>')){
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
                        <?php //while($pays = mysql_fetch_array($result4)){
                            foreach ($result4 as $pays) {
                                if($champion['PaysID']==$pays['Abreviation'] || $champion['PaysID']==$pays['Abreviation_2'] || $champion['PaysID']==$pays['Abreviation_3'] ||$champion['PaysID']==$pays['Abreviation_4'] ||$champion['PaysID']==$pays['Abreviation_5'])
                                    echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';
                                else
                                    echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
                            } ?>
                        </select>
                    </td></tr>
                    <tr><td  align="right"><label for="DateNaissance">Date Naissance : </label></td><td><input type="text" name="DateNaissance" id="datepicker" value="<?php echo $champion['DateNaissance'];?>" /></td></tr>
                    <tr><td  align="right"><label for="LieuNaissance">Lieu de Naissance : </label></td><td><input type="text" name="LieuNaissance" id="LieuNaissance" value="<?php echo $champion['LieuNaissance'];?>" /></td></tr>
                    <tr><td  align="right"><label for="DateChangementNat">Date Changement Nationalité : </label></td><td><input type="text" name="DateChangementNat" id="DateChangementNat" value="<?php echo $champion['DateChangementNat'];?>" /></td></tr>
                    <tr><td align="right"><label for="NvPaysID">Nouvelle nationalité : </label></td><td>
                    <select name="NvPaysID" >
                        <?php //while($pays = mysql_fetch_array($result4)){
                            foreach ($result4 as $pays) {
                                if($champion['NvPaysID']==$pays['Abreviation'])
                                    echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';
                                else
                                    echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
                            } ?>
                        </select>
                    </td></tr>
                    <tr><td  align="right"><label for="poids">poids : </label></td><td><input type="text" name="poids" id="poids" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Poids']));?>" /></td></tr>

                    <tr><td  align="right"><label for="taille">taille : </label></td><td><input type="text" name="taille" id="taille" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Taille']));?>" /></td></tr>

                    <tr><td  align="right"><label for="Equipementier">Equipementier :  </label></td><td><input type="text" name="Equipementier" id="Equipementier" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Equipementier']));?>" /></td></tr>
                    <tr><td  align="right"><label for="Facebook">Facebook : </label></td><td><input type="text" name="Facebook" id="Facebook" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Facebook']));?>" /></td></tr>
                    <tr><td  align="right"><label for="Instagram">Instagram : </label></td><td><input type="text" name="Instagram" id="Instagram" value="<?php echo $champion['Instagram'];?>" /></td></tr>
                    <tr><td  align="right"><label for="lien_equip">Lien site équipementier :  </label></td><td><input type="text" name="lien_equip" id="lien_equip" value="<?php echo $champion['Lien_site_équipementier'];?>" /></td></tr>
                    <tr><td  align="right"><label for="Bio">Bio : </label></td><td><textarea name="Bio" id="Bio" cols="50" rows="20"><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Bio']));?></textarea></td></tr>
                    <tr><td align="right"><label>Visible : </label></td><td><input type="radio" name="Visible" id="oui" value="1" <?php if($champion['Visible']) echo 'checked="checked"';?>/><label for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="Visible" id="non" value="0" <?php if(!$champion['Visible']) echo 'checked="checked"';?> /><label for="non">non</label></td></tr>


                    <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>
                </table>
            </form>
        </fieldset>
        <?php
        require '../content/classes/script_fusion_fiches.php';
        require '../content/classes/test.php';
        $result7=similaires3($champion['ID'],$champion['Nom'])?>

        <?php if(sizeof($result7)!=0){?>
            <div style="position:absolute;right:0px;width:400px;font-size:20px;">
        <fieldset >
            <legend>Correcteur</legend>
            <p style="padding:10px">Un ou plusieurs champions existants portent un nom proche de celui ci peut etre s'agit t'il de la m&ecirc;me personne.</p>
            <form action="remplacerChampion.php?remplacer=<?php echo $champion['ID']; ?>" method="post" id="rempForm" onsubmit="return validerRemplacement('<?php echo $champion['Nom']; ?>');">
             <label style="margin:20px">selectionnez le champion par lequel celui ci sera remplacé : </label><br><br>
             <select id="ramplacant" name="remplacant" style="display:block">
            <?php //while($champion2 = mysql_fetch_array($result6)){
               foreach ($result7 as $champion2) {
                    echo '<option value="'.$champion2['ID'].'">'.$champion2['Nom'].'</option>';
                } ?>
            </select><br><br>
            <input type="submit" value="remplacer" style="padding:10px" />
            <input type="button" value="remplacer+modifier" id="rempMod" style="padding:10px"/>
        </form>

    </fieldset>
            </div>
    <?php } ?>
    <?php if(sizeof($result7)==0){?>
        <div style="padding:20px;border:solid;width:400px;position:absolute;right:0px;font-size:20px;">
            <h1>pas de résultats</h1>
            <h4>Pour avoir un résultat soit:</h4>
                <ul style="color:blue">
                    <li>tous les noms et prénoms du champion à remplacer <i style="color:red">sont contenus</i> dans des noms différents d'un remplacant potentiel, de plus le nom du candidat et celui du remplacant doivent <i style="color:red">être similaires à 50%</i></li>
                    <li>le nom du candidat et celui du remplacant sont <i style="color:red">similaires à 70%</i>,  en outre tous les noms des remplacants potentiels doivent soit: <p style="width:370px;position:absolute;right:-30px;"><li><i style="color:green">commencer par les 3 mêmes lettres</i></li> <li> se <i style="color:purple">terminer par les 3 mêmes lettres</i></li>  <li><i style="color:red">commencer et se terminer par les 2 mêmes lettres</i></li></p> que ceux du remplacé</li>
                </ul>
            
        </div>
    <?php } ?>
    <?php if(sizeof($result3)!=0){?>
    <fieldset style="float:left;">
        <legend>Résultat champion</legend>
        <div>
            <table class="tab1">
                <thead>
                    <tr><th>ID</th><th>Evenement</th><th>Rang</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php //while($resultat = mysql_fetch_array($result3)){

                    foreach ($result3 as $resultat) {
                        try{
                            $req_champ = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID WHERE E.ID=:id");
                            $req_champ->bindValue('id',$resultat['EvenementID'], PDO::PARAM_INT);
                            $req_champ->execute();
                            $evenement= $req_champ->fetch(PDO::FETCH_ASSOC);
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                        // $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID WHERE E.ID=%s',mysql_real_escape_string($resultat['EvenementID']));
                        // $result5   = mysql_query($query5);
                        // $evenement  = mysql_fetch_array($result5);
                        


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



