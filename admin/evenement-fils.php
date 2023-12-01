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

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET ['page']))
    $page = $_GET['page'];

$erreur = "";
if( isset($_POST['sub'])){
    if($_POST['Nom']=="") { $erreur .= "Erreur nom.<br />";}  

    if($erreur==""){
        $destination_path = "../uploadDocument/";
        @mkdir($destination_path);
        @chmod($destination_path,0777);
        $reqDocBegin = "";
        $reqDocEnd   = "";
        for($i=1;$i<4;$i++) {
                if(empty($_FILES['fichier'.$i]['name'])) {continue;}
                
                $fileinfo = $_FILES['fichier'.$i];
                $fichierSource = $fileinfo['tmp_name'];
                $fichierName   = $fileinfo['name'];
                if ( $fileinfo['error']) {
                    switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                   echo "'Le fichier ".$fichierName." d&eacute;passe la limite autoris&eacute;e par le serveur (fichier php.ini) !'<br />";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                   echo  "'Le fichier ".$fichierName." d&eacute;passe la limite autoris&eacute;e dans le formulaire HTML !'<br />";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                   echo"'L'envoi du fichier ".$fichierName." a &eacute;t&eacute; interrompu pendant le transfert !'<br />";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                   echo "'Le fichier ".$fichierName." que vous avez envoy&eacute; a une taille nulle !'<br />";
                                   break;
                               }
                           }
                    else{
                            $tab = explode('.',$fichierName);
                        //$extension = $tab[count($tab)-1];

                            $reqDocBegin .= "Document".$i.",";
                            $reqDocEnd   .= "'".$fichierName."',";
                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "Fichier ".$fichierName." corectement envoy&eacute; !";
                            }else{
                                echo "Erreur phase finale fichier ".$fichierName."<br />";
                            }
                        }

                    }
             /**  Fin de boucle for($i=1;$i<4;$i++) **/
             /** Traitement d'erreur **/
                    if($erreur == "" ){
                        try {
                            $sql .="INSERT INTO evenements_fils (Nom,Sexe,DateDebut,DateFin,Presentation,Type,CategorieageID,CategorieID,Visible,".$reqDocBegin." PaysID) VALUES (:nom,:sexe,:dateDebut,:dateFin,:Presentation,:Type,:CategorieAgeID,:CategorieID,:Visible, ".$reqDocEnd." :PaysID)";
                            
                             $req4 = $bdd->prepare($sql);
                             $req4->bindValue('nom',$_POST['Nom'], PDO::PARAM_STR);
                             $req4->bindValue('sexe',$_POST['Sexe'], PDO::PARAM_STR);
                             $req4->bindValue('dateDebut',$_POST['DateDebut'], PDO::PARAM_STR);
                             $req4->bindValue('dateFin',$_POST['DateFin'], PDO::PARAM_STR);
                             $req4->bindValue('Presentation',$_POST['Presentation'], PDO::PARAM_STR);
                             $req4->bindValue('Type',$_POST['Type'], PDO::PARAM_STR);
                             $req4->bindValue('CategorieAgeID',$_POST['CategorieAgeID'], PDO::PARAM_INT);
                             $req4->bindValue('CategorieID',$_POST['CategorieID'], PDO::PARAM_STR);
                             $req4->bindValue('Visible',$_POST['Visible'], PDO::PARAM_STR);
                             $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
                             $statut=$req4->execute();

                             if($_POST['Type']=="Equipe")
                                {header("Location: evenementResultatEquipe.php?evenement_filsID=".$bdd->lastInsertId());}
                             else
                                    { header("Location: evenementResultatIndividuel.php?evenement_filsID=".$bdd->lastInsertId());}
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
            // //exit($query2);
            // // $result2   = mysql_query($query2) or die(mysql_error());
                        
                    }
                    /** fin de traitement d'erreur **/
                }
            }

    // $query1    = sprintf('SELECT E.*,A.Intitule,C.Intitule AS typeEvenement FROM evenements E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');// LIMIT %s,25',$page*25);
    // $result1   = mysql_query($query1);

            try{
              $req = $bdd->prepare("SELECT E.*,A.Intitule,C.Intitule AS typeEvenement FROM evenements_fils E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

            $req1 = $bdd->prepare("SELECT * FROM evcategorieevenement ORDER BY Intitule");
            $req1->execute();
            $result3= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result3, $row);
            }

            $req2 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
            $req2->execute();
            $result4= array();
            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result4, $row);
            }

            $req3 = $bdd->prepare("SELECT * FROM evcategorieage where Intitule like '%eteran%' ORDER BY ID");
            $req3->execute();
            $result5= array();
            while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result5, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }


    // $query3    = sprintf('SELECT * FROM evcategorieevenement ORDER BY Intitule');
    // $result3   = mysql_query($query3);

    // $query4    = sprintf('SELECT * FROM pays ORDER BY NomPays');
    // $result4   = mysql_query($query4);

    // $query5    = sprintf('SELECT * FROM evcategorieage ORDER BY ID');
    // $result5   = mysql_query($query5);
        ?>

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
        <head>
        <meta charset="utf-8">
            <meta http-equiv="Content-Type" content="text/html;" />
            <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
            <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
            <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
            <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
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
            <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
            <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
            <!-- InstanceBeginEditable name="doctitle" -->
            <title>allmarathon admin</title>

            <script type="text/javascript">
                $(document).ready(function()
                {
	// $(function() {
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
   }
   );

</script>

<!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Ajouter sous-événement</legend>
        <form action="evenement-fils.php" method="post" enctype="multipart/form-data">
            <p id="pErreur" ><?php echo $erreur; ?></p>
            <table>
                <tr><td><label for="Type">Type : </label></td><td><input  type="radio" name="Type" id="individuel" value="Individuel" checked="checked"/><label for="individuel">individuel</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="Type" id="equipe" value="Equipe" /><label for="equipe">&eacute;quipe</label></td></tr>
                <tr><td><label for="Type">Visible : </label></td><td><input  type="radio" name="Visible" id="visible_oui" value="1" checked="checked"/><label for="visible_oui">Oui</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="Visible" id="visible_non" value="0" /><label for="visible_non">non</label></td></tr>
                <tr><td><label for="Nom">Intitul&eacute; (Ville + nom) : </label></td><td><input id="nom" type="text" name="Nom" value="" /></td></tr>
                <tr><td><label for="DateDebut">Date d&eacute;but : </label></td><td><input type="text" name="DateDebut" id="datepicker" value="" /></td></tr>
                <tr><td><label for="DateFin">Date fin : </label></td><td><input type="text" name="DateFin" id="datepicker2" value="" /></td></tr>
                <tr id="sexeRow"><td><label for="Sexe">Sexe : </label></td><td><input type="radio" name="Sexe" value="M" /><span>homme</span><input type="radio" name="Sexe" value="F"  /><span >femme</span><input type="radio" id="mixte" name="Sexe" value="MF" checked="checked"  /><span >mixte</span></td></tr>
                <tr><td><label for="Presentation">Pr&eacute;sentation : </label></td><td><textarea cols="50" rows="10" name="Presentation" value=""></textarea></td></tr>
                <tr><td><label for="CategorieID">Cat&eacute;gorie d'age : </label></td><td>
                    <select name="CategorieAgeID" >
        <?php //while($cat = mysql_fetch_array($result5)){
            foreach ($result5 as $cat) {
                echo '<option value="'.$cat['ID'].'">'.$cat['Intitule'].'</option>';
            } ?>
        </select>
    </td></tr>        
    <tr><td><label for="CategorieID">Cat&eacute;gorie : </label></td><td>
        <select name="CategorieID" id="intitule">
        <?php //while($cat = mysql_fetch_array($result3)){
            foreach ($result3 as $cat) {
                echo '<option value="'.$cat['ID'].'">'.$cat['Intitule'].'</option>';
            } ?>
        </select>
    </td></tr>
    <tr><td><label for="PaysID">Pays : </label></td><td>
        <select name="PaysID" id="pays">
        <?php //while($pays = mysql_fetch_array($result4)){
            foreach ($result4 as $pays) {
                echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
            } ?>
        </select>
    </td>
</tr>

<tr><td><label for="fichier1">Upload fichier1 : </label></td><td><input type="file" name="fichier1"/></td></tr>
<tr><td><label for="fichier2">Upload fichier2 : </label></td><td><input type="file" name="fichier2"/></td></tr>
<tr><td><label for="fichier3">Upload fichier3 : </label></td><td><input type="file" name="fichier3"/></td></tr>
<tr ><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
</table>
</form>
</fieldset>


<fieldset style="float:left;">
    <legend>Liste des sous-événements</legend>
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

                 <option value="50">50</option>
                 <option value="100">100</option>
             </select>
         </form>
     </div>
     <br />

     <table class="tablesorter">
        <thead>
            <tr><th>ID</th><th>DateDebut</th><th>Type</th><th>Nom</th><th>Sexe</th><th>cat d'age</th><th>type</th><th>Pack</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($result1 as $evenement) {
                echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['DateDebut']."</td><td>".$evenement['typeEvenement']."</td><td>".$evenement['Nom']."</td><td>".$evenement['Sexe']."</td><td>".$evenement['Intitule']."</td><td>".$evenement['Type']."</td><td>".$evenement['Pack']."</td>
                <td><img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/dl.png\" alt=\"resultat\" title=\"ajouter r&eacute;sultat\" onclick=\"location.href='evenementResultat".$evenement['Type'].".php?evenement_filsID=".$evenement['ID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='evenementDetail.php?evenement_filsID=".$evenement['ID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$evenement['Nom']." ?')) { location.href='supprimerEvenement.php?evenement_filsID=".$evenement['ID']."&evenementNom=".$evenement['Nom']."';} else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>





    </div>
</fieldset>

</body>

<!-- InstanceEnd --></html>

