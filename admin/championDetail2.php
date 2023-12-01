<?php

//ceci est utilisé au niveau de script_fusion.php (remplacer + modifier)

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

        $poid   = ($_POST['Poids']=='')?'NULL': $_POST['Poids'];

        $taille = ($_POST['Taille']=='')?'NULL': $_POST['Taille'];

        $date   = ($_POST['DateNaissance']=='')?'NULL': $_POST['DateNaissance'];

        $datechang   = ($_POST['DateChangementNat']=='')?'NULL': $_POST['DateChangementNat'];

        if($erreur == ""){



         try {

             $req4 = $bdd->prepare("UPDATE champions SET Nom=:Nom ,Sexe=:Sexe ,PaysID=:PaysID,NvPaysID=:NvPaysID,DateChangementNat=:DateChangementNat ,DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Grade=:Grade,Clubs=:Clubs,Taille=:Taille, Poids=:Poids, TokuiWaza=:TokuiWaza, MainDirectrice=:MainDirectrice, Activite=:Activite, Forces=:Forces, Idole=:Idole, Idole2=:Idole2, Idole3=:Idole3, Idole4=:Idole4, Idole5=:Idole5, Idole6=:Idole6, Idole7=:Idole7, Lidole2=:Lidole2, Lidole3=:Lidole3, Lidole4=:Lidole4, Lidole5=:Lidole5, Lidole6=:Lidole6, Lidole7=:Lidole7, Anecdote=:Anecdote, Phrase=:Phrase, VuPar=:VuPar, Site=:Site WHERE ID=:id");



             $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);

             $req4->bindValue('Sexe',$_POST['Sexe'], PDO::PARAM_STR);

             $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);

             $req4->bindValue('NvPaysID',$_POST['NvPaysID'], PDO::PARAM_STR);

             $req4->bindValue('DateChangementNat',$datechang, PDO::PARAM_STR);

             $req4->bindValue('DateNaissance',$date, PDO::PARAM_STR);

             $req4->bindValue('LieuNaissance',$_POST['LieuNaissance'], PDO::PARAM_STR);

             $req4->bindValue('Grade',$_POST['Grade'], PDO::PARAM_STR);

             $req4->bindValue('Clubs',$_POST['Clubs'], PDO::PARAM_STR);

             $req4->bindValue('Taille',$taille, PDO::PARAM_INT);

             $req4->bindValue('Poids',$poid, PDO::PARAM_INT);

             $req4->bindValue('TokuiWaza',$_POST['TokuiWaza'], PDO::PARAM_STR);

             $req4->bindValue('MainDirectrice',$_POST['MainDirectrice'], PDO::PARAM_STR);

             $req4->bindValue('Activite',$_POST['Activite'], PDO::PARAM_STR);

             $req4->bindValue('Forces',$_POST['Forces'], PDO::PARAM_STR);

             $req4->bindValue('Idole',$_POST['Idole'], PDO::PARAM_STR);

             $req4->bindValue('Idole2',$_POST['Idole2'], PDO::PARAM_STR);

             $req4->bindValue('Idole3',$_POST['Idole3'], PDO::PARAM_STR);

             $req4->bindValue('Idole4',$_POST['Idole4'], PDO::PARAM_STR);

             $req4->bindValue('Idole5',$_POST['Idole5'], PDO::PARAM_STR);

             $req4->bindValue('Idole6',$_POST['Idole6'], PDO::PARAM_STR);

             $req4->bindValue('Idole7',$_POST['Idole7'], PDO::PARAM_STR);

             $req4->bindValue('Lidole2',$_POST['Lidole2'], PDO::PARAM_STR);

             $req4->bindValue('Lidole3',$_POST['Lidole3'], PDO::PARAM_STR);

             $req4->bindValue('Lidole4',$_POST['Lidole4'], PDO::PARAM_STR);

             $req4->bindValue('Lidole5',$_POST['Lidole5'], PDO::PARAM_STR);

             $req4->bindValue('Lidole6',$_POST['Lidole6'], PDO::PARAM_STR);

             $req4->bindValue('Lidole7',$_POST['Lidole7'], PDO::PARAM_STR);

             $req4->bindValue('Anecdote',$_POST['Anecdote'], PDO::PARAM_STR);

             $req4->bindValue('Phrase',$_POST['Phrase'], PDO::PARAM_STR);

             $req4->bindValue('VuPar',$_POST['VuPar'], PDO::PARAM_STR);

             $req4->bindValue('Site',$_POST['Site'], PDO::PARAM_STR);

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



    <title>alljudo admin</title>



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

                                if($champion['PaysID']==$pays['Abreviation'])

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

                    <tr><td  align="right"><label for="Activite">Activit&eacute; : </label></td><td><input type="text" name="Activite" id="Activite" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Activite']));?>" /></td></tr>

                    <tr><td  align="right"><label for="Forces">Force : </label></td><td><textarea name="Forces" id="Forces" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Forces']));?></textarea></td></tr>

                    <tr><td  align="right"><label for="Idole">Idole : </label></td><td><input type="text" name="Idole" id="Idole" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Idole']));?>" /></td></tr>



                    <tr><td  align="right"><label for="Idole2"><?php echo ("chanteur ou groupes pr&eacute;f&eacute;r&eacute; :"); ?>  </label></td><td><input type="text" name="Idole2" id="Idole2" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole2'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole2"><?php echo ("lien :"); ?>  </label></td><td><input type="text" name="Lidole2" id="Lidole2" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole2'])); ?>" /></td></tr>        

                    <tr><td  align="right"><label for="Idole3"><?php echo ("chanson pr&eacute;f&eacute;r&eacute;e : "); ?></label></td><td><input type="text" name="Idole3" id="Idole3" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole3'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole3"><?php echo ("lien : "); ?></label></td><td><input type="text" name="Lidole3" id="Lidole3" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole3'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Idole4"><?php echo ("humoriste pr&eacute;f&eacute;r&eacute; :"); ?> </label></td><td><input type="text" name="Idole4" id="Idole4" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole4'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole4"><?php echo ("lien :"); ?> </label></td><td><input type="text" name="Lidole4" id="Lidole4" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole4'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Idole5"><?php echo ("sketch pr&eacute;f&eacute;r&eacute; :"); ?> </label></td><td><input type="text" name="Idole5" id="Idole5" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole5'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole5"><?php echo ("lien :"); ?> </label></td><td><input type="text" name="Lidole5" id="Lidole5" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole5'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Idole6"><?php echo ("Film pr&eacute;f&eacute;r&eacute; :"); ?> </label></td><td><input type="text" name="Idole6" id="Idole6" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole6'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole6"><?php echo ("lien :"); ?> </label></td><td><input type="text" name="Lidole6" id="Lidole6" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole6'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Idole7"><?php echo ("Plat pr&eacute;f&eacute;r&eacute; :"); ?> </label></td><td><input type="text" name="Idole7" id="Idole7" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Idole7'])); ?>" /></td></tr>

                    <tr><td  align="right"><label for="Lidole7"><?php echo ("lien :"); ?> </label></td><td><input type="text" name="Lidole7" id="Lidole7" value="<?php echo str_replace('\\', '', str_replace('"', '\'', $champion['Lidole7'])); ?>" /></td></tr>





                    <tr><td  align="right"><label for="Anecdote">Anecdote : </label></td><td><textarea name="Anecdote" id="Anecdote" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Anecdote']));?></textarea></td></tr>

                    <tr><td  align="right"><label for="Phrase">Phrase : </label></td><td><textarea name="Phrase" id="Phrase" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Phrase']));?></textarea></td></tr>

                    <tr><td  align="right"><label for="VuPar">VuPar : </label></td><td><textarea name="VuPar" id="VuPar" cols="50" rows="4" ><?php echo str_replace('\\', '',str_replace('"', '\'', $champion['VuPar']));?></textarea></td></tr>

                    <tr><td  align="right"><label for="Site">Site : </label></td><td><input type="text" name="Site" id="Site" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $champion['Site']));?>" /></td></tr>





                    <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>

                </table>

            </form>

        </fieldset>



        

    <?php if(sizeof($result3)!=0){?>

    <fieldset style="float:left;">

        <legend>Resultat champion</legend>

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







