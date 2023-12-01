<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$e="";
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
    
    if(isset($_POST['ok'])){
                
                if($_POST['Titre']==''){
                    $e.=" titre ";
                }
                if(($_POST['Descriptif'])==''){
                    $e.=" Descriptif ";
                }
                 /*if(!validate_alpha_spce($_POST['Nom'])){
                    $e.=" Nom ";
                }*/
                 if($_POST['Mail']==""){
                    $e.=" Email ";
                }

                if($e==""){
                  try {
            $req = $bdd->prepare("INSERT INTO annonce (Titre,Descriptif,Nom,Mail,Code_postal,Ville,Telephone,Pays,sous_categorie_ID,Date_publication,User_ID) VALUES (:Titre,:Descriptif,:Nom,:Mail,:Code_postal,:Ville,:Telephone,:Pays,:sc,now(),2)");

            $req->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
            $req->bindValue('Descriptif',$_POST['Descriptif'], PDO::PARAM_STR);
            $req->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
            $req->bindValue('Mail',$_POST['Mail'], PDO::PARAM_STR);
            $req->bindValue('Code_postal',$_POST['Code_postal'], PDO::PARAM_INT);

            $req->bindValue('Ville',$_POST['Ville'], PDO::PARAM_STR);
            $req->bindValue('Telephone',$_POST['Telephone'], PDO::PARAM_STR);
            $req->bindValue('Pays',$_POST['Pays'], PDO::PARAM_STR);
            $req->bindValue('sc',$_POST['sc'], PDO::PARAM_INT);
            $req->execute();

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
                }
}

    $page = 0;
    if(isset($_GET['page']) && is_numeric($_GET ['page']))
        $page = $_GET['page'];

    $erreur = "";
    if( isset($_POST['sub'])){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";            
            if($erreur==""){
                $destination_path = "../uploadDocument/";
                 @mkdir($destination_path);
                 @chmod($destination_path,0777);
                $reqDocBegin = "";
                $reqDocEnd   = "";
                for($i=1;$i<4;$i++) {
                    if(empty($_FILES['fichier'.$i]['name']))
                        continue;
                    $fileinfo = $_FILES['fichier'.$i];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];
                    if ( $fileinfo['error']) {
                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    echo "'Le fichier ".$fichierName." dépasse la limite autorisée par le serveur (fichier php.ini) !'<br />";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    echo  "'Le fichier ".$fichierName." dépasse la limite autorisée dans le formulaire HTML !'<br />";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     echo"'L'envoi du fichier ".$fichierName." a été interrompu pendant le transfert !'<br />";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    echo "'Le fichier ".$fichierName." que vous avez envoyé a une taille nulle !'<br />";
                                   break;
                          }
                    }else{
                        $tab = explode('.',$fichierName);
                        //$extension = $tab[count($tab)-1];

                        $reqDocBegin .= "Document".$i.",";
                        $reqDocEnd   .= "'".$fichierName."',";
                        if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                            $result = "Fichier ".$fichierName." corectement envoyé !";
                        }else{
                            echo "Erreur phase finale fichier ".$fichierName."<br />";
                        }
                        }

                    }
            //     if($erreur == "" ){
            //     $query2    = sprintf("INSERT INTO evenements (Nom,Sexe,DateDebut,DateFin,Presentation,Type,CategorieageID,CategorieID,Visible,%s PaysID) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s', ".$reqDocEnd." '%s')"
            //     ,mysql_real_escape_string($reqDocBegin)
            //     ,mysql_real_escape_string($_POST['Nom'])
            //     ,mysql_real_escape_string($_POST['Sexe'])
            //     ,mysql_real_escape_string($_POST['DateDebut'])
            //     ,mysql_real_escape_string($_POST['DateFin'])
            //     ,mysql_real_escape_string($_POST['Presentation'])
            //     ,mysql_real_escape_string($_POST['Type'])
            //     ,mysql_real_escape_string($_POST['CategorieAgeID'])
            //     ,mysql_real_escape_string($_POST['CategorieID'])
            //     ,mysql_real_escape_string($_POST['Visible'])
            //     ,mysql_real_escape_string($_POST['PaysID']));
            // //exit($query2);
            // $result2   = mysql_query($query2) or die(mysql_error());
            // if($_POST['Type']=="Equipe")
            //     header("Location: evenementResultatEquipe.php?evenementID=".mysql_insert_id());
            // else
            //     header("Location: evenementResultatIndividuel.php?evenementID=".mysql_insert_id());
            // }
        }
    }
    try{

        $req1 = $bdd->prepare("SELECT a.*,u.username from annonce a,users u where u.id =a.User_ID   ORDER BY Date_publication DESC");
        $req1->execute();
        $result1= array();
        while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result1, $row);
        }

        $req2 = $bdd->prepare("SELECT a.*,u.username from annonce a,users u where u.id =a.User_ID   ORDER BY Date_modification DESC");
        $req2->execute();
        $result2= array();
        while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result2, $row);
        }
        $req3=$bdd->prepare("select * from categorie");
        $req3->execute();
        $categories= array();
        while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
            array_push($categories, $row);
        }

    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
    // $query1    = sprintf('SELECT a.*,u.username from annonce a,users u where u.id =a.User_ID   ORDER BY Date_publication DESC');// LIMIT %s,25',$page*25);
    // $result1   = mysql_query($query1);
    
    // $query2    = sprintf('SELECT a.*,u.username from annonce a,users u where u.id =a.User_ID   ORDER BY Date_modification DESC');// LIMIT %s,25',$page*25);
    // $result2   = mysql_query($query2);
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("table.tablesorter")
            .tablesorter({
                widthFixed: false,
                widgets: ['zebra']
            })
            .tablesorterPager({
                container: $("#pager")
            });
    });
    </script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>

    <script type="text/javascript">
    $(document).ready(function() {
        // $(function() {
        $('#datepicker').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#datepicker').datepicker('option', {
            dateFormat: 'yy-mm-dd'
        });
        $('#datepicker2').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#datepicker2').datepicker('option', {
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>
    <script type="text/javascript">
    function getsouscategorie() {
        $.ajax({
            type: 'get',
            url: '/sous_categorie.php?c=' + $("#s").val(),
            success: function(data) {
                $('#sc').html(data);
                <?php if(isset($_POST['sc']) && $e!=""){ ?>
                $("#sc<?=$_POST['sc'] ?>").attr("selected", "selected");
                <?php } ?>
            }
        });
    }
    $(function() {
        getsouscategorie();
        $('#s').change(function() {
            getsouscategorie();
        });

    });
    </script>

    <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
    tinyMCE.init({
        // General options
        convert_urls: false,
        mode: "exact",
        elements: "Descriptif",
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
    <!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <?php 
if(isset($_POST['ok'])){
    if($e==""){
    echo "<div class='success'>Votre annonce a bien &eacute;t&eacute; publi&eacute;";
    }else{
      echo "<div class='error'> les champs {".$e."} ne sont pas valides </div>";
    }
}
?>
    <div>
        <form name="f" action="" method="post" id="f">
            <table class="texte1">
                <tr>
                    <td width="170" valign="top"><strong>Titre</strong></td>
                    <td><input name="Titre" type="text" id="titre" size="50" maxlength="180"
                            value="<?php if(isset($_POST['Titre']) && $e!=""){echo $_POST['Titre'];} ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170" valign="top"><strong>Descriptif</strong></td>
                    <td><textarea class='mceToolbar mceLeft mceFirst mceLast' name="Descriptif" cols="39" rows="10"
                            id="descriptif"><?php if(isset($_POST['Descriptif']) && $e!="" ){echo $_POST['Descriptif'];} ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>NOM Pr&eacute;nom</strong></td>
                    <td><input name="Nom" type="text" id="nom" size="50" maxlength="25"
                            value="<?php if(isset($_POST['Nom']) && $e!="" ){echo $_POST['Nom'];} ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Email</strong></td>
                    <td><input name="Mail" type="text" id="mail" size="50" maxlength="30"
                            value="<?php if(isset($_POST['Mail']) && $e!="" ){echo $_POST['Mail'];} ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>T&eacute;l&eacute;phone</strong></td>
                    <td><input name="Telephone" type="text" id="telephone" size="50" maxlength="15"
                            value="<?php if(isset($_POST['Telephone']) && $e!="" ){echo $_POST['Telephone'];} ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Code postal</strong></td>
                    <td><input name="Code_postal" type="text" id="code_postal" size="50" maxlength="8"
                            value="<?php if(isset($_POST['Code_postal']) && $e!="" ){echo $_POST['Code_postal'];} ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Ville</strong></td>
                    <td><input name="Ville" type="text" id="ville" size="50" maxlength="25"
                            value="<?php if(isset($_POST['Ville']) && $e!=""){echo $_POST['Ville'];} ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Pays</strong></td>
                    <td><input name="Pays" type="text" id="pays" size="50" maxlength="25"
                            value="<?php if(isset($_POST['Pays']) && $e!=""){echo $_POST['Pays'];} ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Cat&eacute;gorie</strong></td>
                    <td>

                        <select id="s" name="c">
                            <option value="0">choisir une categorie</option>
                            <?php 
                                    foreach ($categories as $r) {
                                        if(isset($_POST['c']) && $_POST["c"]==$r['ID'] && $e!=""){
                                       echo "<option  id='c".$r['ID']."' value='".$r['ID']."' selected='selected'>".$r['nom_categorie']."</option>"; }else{
 echo "<option  id='c".$r['ID']."'  value='".$r['ID']."'>".$r['nom_categorie']."</option>";                                     
        } 
                                    }
                                    ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"><strong>Sous-Cat&eacute;gorie</strong></td>
                    <td>

                        <select id='sc' name='sc'>
                            <option value="0">choisir une categorie</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="images/CSS/1px-gris.gif" width="510" height="1" /></td>
                </tr>
                <tr>
                    <td width="170"></td>
                    <td><input type="submit" value="ajouter" name="ok" id="ok" /></td>
                </tr>
            </table>
        </form>
    </div>
    <fieldset style="float:left;">
        <legend>Liste des annonces Par date de publication</legend>
        <div align="center">
            <div id="pager" class="pager">
                <form>
                    <img src="../fonction/tablesorter/first.png" class="first" />
                    <img src="../fonction/tablesorter/prev.png" class="prev" />
                    <input type="text" class="pagedisplay" />
                    <img src="../fonction/tablesorter/next.png" class="next" />
                    <img src="../fonction/tablesorter/last.png" class="last" />
                    <select class="pagesize">
                        <option selected="selected" value="10">10</option>

                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                </form>
            </div>
            <br />

            <table class="tablesorter">
                <thead>
                    <tr>
                        <th>ID </th>
                        <th>categorie</th>
                        <th>utilisteur</th>
                        <th>titre</th>
                        <th>description</th>
                        <th>date de publication</th>
                        <th>date de modification</th>
                        <th>nom</th>
                        <th>email</th>
                        <th>code postal</th>
                        <th>ville </th>
                        <th>pays</th>
                        <th>telephone</th>
                        <th>premiem</th>
                        <th>validit&eacute;</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        foreach ($result1 as $annonces) {
         if($annonces['Premium']==1){ $p="oui";}else{$p="non";};if($annonces['Valide']==1){ $v="oui";}else{$v="non";}
            echo "<tr align=\"center\" ><td>".$annonces['ID']."</td><td>".$annonces['sous_categorie_ID']."</td><td>".$annonces['username']."</td><td>".$annonces['Titre']."</td><td>".$annonces['Descriptif']."</td><td>".$annonces['Date_publication']."</td>
             <td>".$annonces['Date_modification']."</td><td>".$annonces['Nom']."</td><td>".$annonces['Mail']."</td><td>".$annonces['Code_postal']."</td><td>".$annonces['Ville']."</td><td>".$annonces['Pays']."</td>
            <td>".$annonces['Telephone']."</td><td>".$p."</td><td>".$v."</td>
                <td>
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='annonceDetail.php?annonceID=".$annonces['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer cette annonce : ".$annonces['Titre']." ?')) { location.href='supprimerannonce.php?annonceID="
                .$annonces['ID']."';} else { return 0;}\" /></td></tr>";
        } ?>

                </tbody>
            </table>


        </div>
    </fieldset>


    <fieldset style="float:left;">
        <legend>Liste des annonces Par date de modification</legend>
        <div align="center">
            <div id="pager" class="pager">
                <form>
                    <img src="../fonction/tablesorter/first.png" class="first" />
                    <img src="../fonction/tablesorter/prev.png" class="prev" />
                    <input type="text" class="pagedisplay" />
                    <img src="../fonction/tablesorter/next.png" class="next" />
                    <img src="../fonction/tablesorter/last.png" class="last" />
                    <select class="pagesize">
                        <option selected="selected" value="10">10</option>

                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                </form>
            </div>
            <br />

            <table class="tablesorter">
                <thead>
                    <tr>
                        <th>ID </th>
                        <th>categorie</th>
                        <th>utilisteur</th>
                        <th>titre</th>
                        <th>description</th>
                        <th>date de publication</th>
                        <th>date de modification</th>
                        <th>nom</th>
                        <th>email</th>
                        <th>code postal</th>
                        <th>ville </th>
                        <th>pays</th>
                        <th>telephone</th>
                        <th>premiem</th>
                        <th>validit&eacute;</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
          foreach ($result2 as $annonces) {
          if($annonces['Premium']==1){ $p="oui";}else{$p="non";};if($annonces['Valide']==1){ $v="oui";}else{$v="non";}
            echo "<tr align=\"center\" ><td>".$annonces['ID']."</td><td>".$annonces['sous_categorie_ID']."</td><td>".$annonces['username']."</td><td>".$annonces['Titre']."</td><td>".$annonces['Descriptif']."</td><td>".$annonces['Date_publication']."</td>
            <td>".$annonces['Date_modification']."</td><td>".$annonces['Nom']."</td><td>".$annonces['Mail']."</td><td>".$annonces['Code_postal']."</td><td>".$annonces['Ville']."</td><td>".$annonces['Pays']."</td>
            <td>".$annonces['Telephone']."</td><td>".$p."</td><td>".$v."</td>
                <td>
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='annonceDetail.php?annonceID=".$annonces['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer cette annonce : ".$annonces['Titre']." ?')) { location.href='supprimerannonce.php?annonceID="
                .$annonces['ID']."';} else { return 0;}\" /></td></tr>";
        } ?>

                </tbody>
            </table>


        </div>
    </fieldset>
</body>
<!-- InstanceEnd -->

</html>