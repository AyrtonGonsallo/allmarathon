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

$erreur = "";

if(isset($_GET['marathonID']) or isset($_GET['marathon_filsID'])){
    if(isset($_GET['marathonID'])){
        $eventID=$_GET['marathonID'];
        $table="marathons";
    }


    if( isset($_POST['sub']) ){

        if($_POST['nom']=="")

            $erreur .= "Erreur nom.<br />";

            if($erreur==""){


                        if($erreur == ""){

                           

                             try {
                                               $fileName = ($_FILES['img']['name'])?$_FILES['img']['name']:$marathon['image'];
 
                 if($_FILES['img']['name']){
                    $fileName = $_FILES['img']['name'];

                    $req4 = $bdd->prepare("UPDATE `marathons` SET `nom`=:nom,prefixe=:prefixe,`site_web`=:site_web,`Inscription`=:inscr,`Instagram`=:insta,`facebook`=:fb,`youtube`=:yt,`description`=:descr,`image`=:img,`lieu`=:lieu,`PaysID`=:PaysID where id=:id");
                    $req4->bindValue('id',$_GET['marathonID'], PDO::PARAM_INT);
                    $req4->bindValue('nom',$_POST['nom'], PDO::PARAM_STR);
                    $req4->bindValue('prefixe',$_POST['prefixe'], PDO::PARAM_STR);
                    $req4->bindValue('site_web',$_POST['site_web'], PDO::PARAM_STR);
                    $req4->bindValue('inscr',$_POST['inscr'], PDO::PARAM_STR);
                    $req4->bindValue('insta',$_POST['insta'], PDO::PARAM_STR);
                    $req4->bindValue('fb',$_POST['fb'], PDO::PARAM_STR);
                    $req4->bindValue('yt',$_POST['yt'], PDO::PARAM_STR);
                    $req4->bindValue('descr',$_POST['descr'], PDO::PARAM_STR);
                    $req4->bindValue('img',$fileName, PDO::PARAM_STR);
                    $req4->bindValue('lieu',$_POST['lieu'], PDO::PARAM_STR);
                    $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
                    $statut=$req4->execute();
                 }else{
                    $req4 = $bdd->prepare("UPDATE `marathons` SET `nom`=:nom,prefixe=:prefixe,`site_web`=:site_web,`Inscription`=:inscr,`Instagram`=:insta,`facebook`=:fb,`youtube`=:yt,`description`=:descr,`lieu`=:lieu,`PaysID`=:PaysID,`Visible`=:vis where id=:id");
                    $req4->bindValue('id',$_GET['marathonID'], PDO::PARAM_INT);
                    $req4->bindValue('nom',$_POST['nom'], PDO::PARAM_STR);
                    $req4->bindValue('prefixe',$_POST['prefixe'], PDO::PARAM_STR);
                    $req4->bindValue('site_web',$_POST['site_web'], PDO::PARAM_STR);
                    $req4->bindValue('vis',$_POST['Visible'], PDO::PARAM_STR);
                    $req4->bindValue('inscr',$_POST['inscr'], PDO::PARAM_STR);
                    $req4->bindValue('insta',$_POST['insta'], PDO::PARAM_STR);
                    $req4->bindValue('fb',$_POST['fb'], PDO::PARAM_STR);
                    $req4->bindValue('yt',$_POST['yt'], PDO::PARAM_STR);
                    $req4->bindValue('descr',$_POST['descr'], PDO::PARAM_STR);
                    
                    $req4->bindValue('lieu',$_POST['lieu'], PDO::PARAM_STR);
                    $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
                    $statut=$req4->execute();
                 }
                 

            }

            catch(Exception $e)

            {

                die('Erreur : ' . $e->getMessage());

            }
            $marathonID = $bdd->lastInsertId();
            if($statut)  {  
                $array       = explode('-',$_POST['Date']);
                $destination_path = "../images/marathons/".$array[0]."/";
                 if(!is_dir($destination_path)){
                       mkdir($destination_path);
                       chmod($destination_path,0777);
                   }
                
                if(!empty($_FILES['img']['name'])){
                    /*  cr�ation de l'mage au bon format */
                    $fileinfo = $_FILES['img'];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];

                    if ( $fileinfo['error']) {
                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    $result = "'Le fichier d�passe la limite autoris�e par le serveur (fichier php.ini) !'";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    $result =  "'Le fichier d�passe la limite autoris�e dans le formulaire HTML !'";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     $result = "'L'envoi du fichier a �t� interrompu pendant le transfert !'";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    $result = "'Le fichier que vous avez envoy� a une taille nulle !'";
                                   break;
                          }
                    }else{

                            $x = 414;
                            $size = getimagesize($fichierSource);
                            $y = ($x * $size[1]) / $size[0];
                            $img_new = imagecreatefromjpeg($fichierSource);

                            $img_mini = imagecreatetruecolor($x, $y);
                            imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                            imagejpeg($img_mini, $destination_path . "thumb_" . strtolower($fichierName));

                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "Fichier corectement envoy� !";
                            }else{
                                $result = "Erreur phase finale";
                            }
                        }


                    }
            }
            header("Location: marathon.php");



    }

        }

    }



    try{
           

            $req = $bdd->prepare("SELECT * FROM ".$table." WHERE ID=:id");
            

            $req->bindValue('id',$eventID, PDO::PARAM_INT);

            $req->execute();

            $marathon= $req->fetch(PDO::FETCH_ASSOC);

            $req2 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");

            $req2->execute();

            $result4= array();

            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  

                array_push($result4, $row);

            } 
            // evenement déja reliés
            $req = $bdd->prepare("SELECT E.*,A.Intitule,C.Intitule AS typeEvenement FROM evenements E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID where E.marathon_id = :curr_mar_id ORDER BY E.ID DESC");
            $req->bindValue('curr_mar_id',$marathon['id'], PDO::PARAM_INT); 
            $req->execute();
              $liées= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($liées, $row);
            }
            // evenement a relier
            $req = $bdd->prepare("SELECT E.*,A.Intitule,C.Intitule AS typeEvenement FROM evenements E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID where UPPER(E.Nom) like :curr_mar_nom and E.marathon_id != :curr_mar_id and E.PaysID like :curr_mar_pays ORDER BY E.ID DESC");
            $req->bindValue('curr_mar_pays','%'.$marathon['PaysID'].'%', PDO::PARAM_STR); 
            $req->bindValue('curr_mar_nom',"%".strtoupper($marathon['nom'])."%", PDO::PARAM_STR); 
            $req->bindValue('curr_mar_id',$marathon['id'], PDO::PARAM_INT); 
            $req->execute();
              $a_liéer= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($a_liéer, $row);
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

<head><meta charset="windows-1252">

<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>

<script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>

<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
            <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $("table.tablesorter1")
                    .tablesorter({widthFixed: false, widgets: ['zebra']})
                    .tablesorterPager({container: $("#pager")});
                    $("table.tablesorter2")
                    .tablesorter({widthFixed: false, widgets: ['zebra']})
                    .tablesorterPager({container: $("#pager2")});
                }
                );

            </script>
<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/marathon-hierarchique-style.css" type="text/css"> 
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<!-- InstanceBeginEditable name="doctitle" -->

<title>allmarathon admin</title>



<script type="text/javascript">
/*
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
    */

</script>
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        convert_urls: false,
        mode: "exact",
        elements: "descr",
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

</head>
<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">


    <?php
    if(isset($_GET['marathonID'])){
        echo '<legend>Modifier marathon</legend><form action="marathonDetail.php?marathonID='.$_GET['marathonID'].'" method="post" enctype="multipart/form-data">';
    }
    ?>

    <p id="pErreur" ><?php echo $erreur; ?></p>

    <table>
            
            
            <tr><td><label for="Nom">Intitul&eacute; : </label></td><td><input id="nom" type="text" name="nom" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $marathon['nom']));?>" /></td></tr>
            <tr><td><label for="prefixe">Préfixe : </label></td><td><input id="prefixe" type="text" name="prefixe" value="<?php echo $marathon['prefixe'];?>" /></td></tr>

            <tr><td><label for="site_web">site web  : </label></td><td><input id="site_web" type="text" name="site_web" value="<?php echo $marathon['site_web'];?>" /></td></tr>
            <tr><td><label for="inscr">Inscription  : </label></td><td><input id="inscr" type="text" name="inscr" value="<?php echo $marathon['Inscription'];?>" /></td></tr>
            <tr><td><label for="insta">Instagram  : </label></td><td><input id="insta" type="text" name="insta" value="<?php echo $marathon['Instagram'];?>" /></td></tr>
            <tr><td><label for="fb">facebook  : </label></td><td><input id="fb" type="text" name="fb" value="<?php echo $marathon['facebook'];?>" /></td></tr>
            <tr><td><label for="yt">Chaine Youtube  : </label></td><td><input id="yt" type="text" name="yt" value="<?php echo $marathon['youtube'];?>" /></td></tr>
            <tr>
                <td align="right"><label for="descr">Description : </label></td>
                <td><textarea name="descr" cols="30" rows="19">
                <?php echo $marathon['description'];?>
                </textarea></td>
            </tr>       
            <tr align="center"><td><label for="img">Image : </label></td><td><input type="file" name="img" /></td></tr>         
            <tr><td colspan="2">
                <?php
                if($marathon['image']!="" && $marathon['image']!=NULL){?>
                    <img alt="image_marathons" src="<?php echo "../images/marathons/".$marathon['image'] ?>" width="200px">
                <?php }else{?>
                    <img alt="image-defaut_marathons" src="../images/news/defaut.jpg" width="200px">
                <?php } ?>
            </td></tr>              
            <tr><td><label for="lieu">Ville : </label></td><td><input id="lieu" type="text" name="lieu" value="<?php echo $marathon['lieu'];?>" /></td></tr>
            <tr><td><label for="PaysID">Pays : </label></td><td>

<select id="pays" name="PaysID" >

<?php //while($pays = mysql_fetch_array($result4)){

    foreach ($result4 as $pays) {

    if($marathon['PaysID']==$pays['Abreviation'])

        echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';

    else

        echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';

} ?>

</select>

</td></tr>
            <tr ><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>
    </table>

    </form>

</fieldset>

<fieldset style="float:left;">
<legend>Les évènements suivants sont rattachés
</legend>
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

     <table class="tablesorter tablesorter1">
        <thead>
            <tr><th>ID</th><th>DateDebut</th><th>Type</th><th>Nom</th><th>Sexe</th><th>cat d'age</th><th>pays</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php //while($evenement = mysql_fetch_array($liées)){
            foreach ($liées as $evenement) {
                echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['DateDebut']."</td><td>".$evenement['typeEvenement']."</td><td>".$evenement['Nom']."</td><td>".$evenement['Sexe']."</td><td>".$evenement['Intitule']."</td>
                <td>".$evenement['PaysID']."</td><td>";
                
                    
                
                    echo "<img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$evenement['Nom']." ?')) {location.href='evenement_marathon.php?remove=1&evenementID=".$evenement['ID']."&marathonID=".$_GET['marathonID']."'; } else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>





    </div>
</fieldset>
<fieldset style="float:left;">
<legend>Les évènements suivants peuvent être rattachés
</legend>
    <div >

        <div id="pager2" class="pager">
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

     <table class="tablesorter tablesorter2">
        <thead>
            <tr><th>ID</th><th>DateDebut</th><th>Type</th><th>Nom</th><th>Sexe</th><th>cat d'age</th><th>pays</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php //while($evenement = mysql_fetch_array($liées)){
            foreach ($a_liéer as $evenement) {
                echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['DateDebut']."</td><td>".$evenement['typeEvenement']."</td><td>".$evenement['Nom']."</td><td>".$evenement['Sexe']."</td><td>".$evenement['Intitule']."</td>
                <td>".$evenement['PaysID']."</td><td>";
                
                    
                
                    echo "<img style=\"cursor:pointer;\" width=16px height=16px src=\"../images/link.png\" alt=\"edit\" title=\"lier\" onclick=\"location.href='evenement_marathon.php?add=1&evenementID=".$evenement['ID']."&marathonID=".$_GET['marathonID']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$evenement['Nom']." ?')) { } else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>





    </div>
</fieldset>
</body>


<!-- InstanceEnd --></html>

