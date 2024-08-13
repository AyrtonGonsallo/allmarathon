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

if(isset($_GET['evenementID']) or isset($_GET['evenement_filsID'])){
    if(isset($_GET['evenementID'])){
        $eventID=$_GET['evenementID'];
        $table="evenements";
    }else if(isset($_GET['evenement_filsID'])){
        $eventID=$_GET['evenement_filsID'];
        $table="evenements_fils";
    }


    if( isset($_POST['sub']) ){

        if($_POST['Nom']=="")

            $erreur .= "Erreur nom.<br />";

        

            if($erreur==""){

                $destination_path = "../uploadDocument/";

                if(!is_dir($destination_path)){

                    @mkdir($destination_path);

                    @chmod($destination_path,0777);

                }

                

                $reqDoc = "";

                for($i=1;$i<4;$i+=2) {                   

                    if(empty($_FILES['fichier'.$i]['name']))

                        continue;

                    $fileinfo = $_FILES['fichier'.$i];

                    $fichierSource = $fileinfo['tmp_name'];

                    $fichierName   = $fileinfo['name'];

                    //$reqDoc = "";

                    

                    if ( $fileinfo['error']) {

                          switch ( $fileinfo['error']){

                                   case 1: // UPLOAD_ERR_INI_SIZE

                                    $erreur = "'Le fichier d�passe la limite autoris�e par le serveur (fichier php.ini) !'";

                                   break;

                                   case 2: // UPLOAD_ERR_FORM_SIZE

                                    $erreur =  "'Le fichier d�passe ".$fichierName." la limite autoris�e dans le formulaire HTML !'";

                                   break;

                                   case 3: // UPLOAD_ERR_PARTIAL

                                     $erreur = "'L'envoi du fichier ".$fichierName." a �t� interrompu pendant le transfert !'";

                                   break;

                                   case 4: // UPLOAD_ERR_NO_FILE

                                    $erreur = "'Le fichier ".$fichierName." que vous avez envoy� a une taille nulle !'";

                                   break;

                          }

                    }else{

                        //$tab = explode('.',$fichierName);

                        //$extension = $tab[count($tab)-1];

                        $reqDoc .= "Document".$i."='".$fichierName."',";

                        

                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {

                                $result = "Fichier corectement envoy� !";

                            }else{

                                $erreur = "Erreur phase finale";

                            }

                        }

                }

                        if($erreur == ""){

                           

                             try {
                                if(empty($_FILES['affiche']['name']) and !empty($_FILES['parcours_image']['name'])){
                                    $req4 = $bdd->prepare("UPDATE ".$table." SET Nom=:Nom,prefixe=:prefixe,Sexe=:Sexe,DateDebut=:DateDebut,parcours_iframe=:pifr,parcours_image=:pimg,Presentation=:Presentation,lien_resultats_complet=:lrc,CategorieageID=:CategorieageID,CategorieID=:CategorieID,Visible=:Visible,a_l_affiche=:a_l_affiche,Valider=:Valider,Contact=:Contact,Mail=:Mail,Web=:Web,insta=:insta,facebook=:facebook,youtube=:youtube,marathon_id=:mar_id,video_teaser=:vit,Telephone=:Telephone,".$reqDoc." PaysID=:PaysID WHERE ID=:event_id");

                                }else if(!empty($_FILES['affiche']['name']) and empty($_FILES['parcours_image']['name'])){
                                    $req4 = $bdd->prepare("UPDATE ".$table." SET Nom=:Nom,prefixe=:prefixe,Sexe=:Sexe,DateDebut=:DateDebut,parcours_iframe=:pifr,Presentation=:Presentation,lien_resultats_complet=:lrc,CategorieageID=:CategorieageID,CategorieID=:CategorieID,Visible=:Visible,a_l_affiche=:a_l_affiche,Valider=:Valider,Contact=:Contact,Mail=:Mail,Web=:Web,insta=:insta,facebook=:facebook,youtube=:youtube,marathon_id=:mar_id,affiche=:aff,video_teaser=:vit,Telephone=:Telephone,".$reqDoc." PaysID=:PaysID WHERE ID=:event_id");

                                }
                                else if(empty($_FILES['parcours_image']['name']) and empty($_FILES['affiche']['name'])){
                                    $req4 = $bdd->prepare("UPDATE ".$table." SET Nom=:Nom,prefixe=:prefixe,Sexe=:Sexe,DateDebut=:DateDebut,parcours_iframe=:pifr,Presentation=:Presentation,lien_resultats_complet=:lrc,CategorieageID=:CategorieageID,CategorieID=:CategorieID,Visible=:Visible,a_l_affiche=:a_l_affiche,Valider=:Valider,Contact=:Contact,Mail=:Mail,Web=:Web,insta=:insta,facebook=:facebook,youtube=:youtube,marathon_id=:mar_id,video_teaser=:vit,Telephone=:Telephone,".$reqDoc." PaysID=:PaysID WHERE ID=:event_id");

                                }
                                $fileName = $_FILES['affiche']['name'];
                                $parcours_image = $_FILES['parcours_image']['name'];
                                $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
                                $req4->bindValue('prefixe',$_POST['prefixe'], PDO::PARAM_STR);
                                $req4->bindValue('Sexe',$_POST['Sexe'], PDO::PARAM_STR);
                                $req4->bindValue('mar_id',$_POST['marathon'], PDO::PARAM_INT);
                                $req4->bindValue('DateDebut',$_POST['DateDebut'], PDO::PARAM_STR);
                                $req4->bindValue('lrc',$_POST['lrc'], PDO::PARAM_STR);
                                $req4->bindValue('pifr',$_POST['parcours_iframe'], PDO::PARAM_STR);
                                if(!empty($_FILES['affiche']['name'])){
                                    $req4->bindValue('aff',$fileName, PDO::PARAM_STR);

                                }else if(!empty($_FILES['parcours_image']['name'])){
                                    $req4->bindValue('pimg',$parcours_image, PDO::PARAM_STR);

                                }
                                
                                $req4->bindValue('vit',$_POST['video_teaser'], PDO::PARAM_STR);
                                

                                $req4->bindValue('Presentation',$_POST['Presentation'], PDO::PARAM_STR);

                                $req4->bindValue('CategorieageID',$_POST['CategorieAgeID'], PDO::PARAM_STR);

                                $req4->bindValue('CategorieID',$_POST['CategorieID'], PDO::PARAM_STR);

                                $req4->bindValue('Visible',$_POST['Visible'], PDO::PARAM_INT);
                                $req4->bindValue('a_l_affiche',$_POST['a_l_affiche'], PDO::PARAM_INT);
                                $req4->bindValue('Valider',$_POST['Valider'], PDO::PARAM_INT);


                                $req4->bindValue('Contact',$_POST['Contact'], PDO::PARAM_STR);

                                $req4->bindValue('Mail',$_POST['Mail'], PDO::PARAM_STR);
                                $req4->bindValue('insta',$_POST['insta'], PDO::PARAM_STR);
                                $req4->bindValue('facebook',$_POST['facebook'], PDO::PARAM_STR);
                                $req4->bindValue('youtube',$_POST['youtube'], PDO::PARAM_STR);
                                $req4->bindValue('Web',$_POST['Web'], PDO::PARAM_STR);

                                $req4->bindValue('Telephone',$_POST['Telephone'], PDO::PARAM_STR);

                                $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);

                                $req4->bindValue('event_id',$_GET['evenementID'], PDO::PARAM_INT);

                                

                                
                                $statut=$req4->execute();

                                if($statut)  {  
                                                
                                    $destination_path = "../images/events/";
                                    if(!is_dir($destination_path)){
                                        mkdir($destination_path);
                                        chmod($destination_path,0777);
                                    }
                                    
                                        if(!empty($_FILES['affiche']['name'])){
                                            /*  cr�ation de l'mage au bon format */
                                            $fileinfo = $_FILES['affiche'];
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
                                        if(!empty($_FILES['parcours_image']['name'])){
                                        /*  cr�ation de l'mage au bon format */
                                        $fileinfo = $_FILES['parcours_image'];
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

            }

            catch(Exception $e)

            {

                die('Erreur : ' . $e->getMessage());

            }

            header("Location: evenement.php");



    }

                    

            



        }

    }



    try{

            $req = $bdd->prepare("SELECT * FROM ".$table." WHERE ID=:id");
            

            $req->bindValue('id',$eventID, PDO::PARAM_INT);

            $req->execute();

            $event= $req->fetch(PDO::FETCH_ASSOC);



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



            $req3 = $bdd->prepare("SELECT * FROM evcategorieage ORDER BY ID");

            $req3->execute();

            $result5= array();

            while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  

                array_push($result5, $row);

            }

            $req5 = $bdd->prepare("SELECT * FROM marathons ");
            $req5->execute();
            $result6= array();
            while ( $row  = $req5->fetch(PDO::FETCH_ASSOC)) {  
              array_push($result6, $row);
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

<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/evenement-hierarchique-style.css" type="text/css"> 
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
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


    <?php
    if(isset($_GET['evenementID'])){
        echo '<legend>Ajouter evenement</legend><form action="evenementDetail.php?evenementID='.$_GET['evenementID'].'" method="post" enctype="multipart/form-data">';
    }else if(isset($_GET['evenement_filsID'])){
        echo '<legend>Ajouter sous evenement</legend><form action="evenementDetail.php?evenement_filsID='.$_GET['evenement_filsID'].'" method="post" enctype="multipart/form-data">';

    }
    ?>

    <p id="pErreur" ><?php echo $erreur; ?></p>

    <table>

        <tr><td><label for="Visible">Visible : </label></td><td><input  type="radio" name="Visible" id="visible_oui" value="1" <?php if($event['Visible']=='1') echo 'checked="checked"';?>/><label for="visible_oui">Oui</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="Visible" id="visible_non" value="0" <?php if($event['Visible']=='0') echo 'checked="checked"';?> /><label for="visible_non">non</label></td></tr>
        <tr><td><label for="a_l_affiche">À l'affiche : </label></td><td><input  type="radio" name="a_l_affiche" id="a_l_affiche_oui" value="1" <?php if($event['a_l_affiche']=='1') echo 'checked="checked"';?>/><label for="a_l_affiche_oui">Oui</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="a_l_affiche" id="a_l_affiche_non" value="0" <?php if($event['a_l_affiche']=='0') echo 'checked="checked"';?> /><label for="a_l_affiche_non">non</label></td></tr>

        <tr><td><label for="Valider">Valider : </label></td><td><input  type="radio" name="Valider" id="valider_oui" value="1" <?php if($event['Valider']=='1') echo 'checked="checked"';?>/><label for="valider_oui">Oui</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="Valider" id="valider_non" value="0" <?php if($event['Valider']=='0') echo 'checked="checked"';?> /><label for="valider_non">non</label></td></tr>
        

        <tr><td><label for="Nom">Intitul&eacute; (Ville + nom): </label></td><td><input type="text" id="nom" name="Nom"  value="<?php echo str_replace('\\', '',str_replace('"', '\'', $event['Nom']));?>"  /></td></tr>
        <tr><td><label for="prefixe">Préfixe : </label></td><td><input id="prefixe" type="text" name="prefixe" value="<?php echo $event['prefixe'];?>" /></td></tr>

        <tr><td ><label for="DateDebut">Date d&eacute;but : </label></td><td><input type="text" name="DateDebut" id="datepicker" value="<?php echo $event['DateDebut'];?>" /></td></tr>
        <tr><td><label for="marathon">Marathon de l’évènement : </label></td><td><input id="marathon" type="number" name="marathon" value="<?php echo $event['marathon_id'];?>" /></td></tr>
        <tr><td><label for="parcours_iframe">Parcours iframe : </label></td><td><textarea cols="50" rows="10" name="parcours_iframe" ><?php echo $event['parcours_iframe'];?></textarea></td></tr>
                <tr><td><label for="parcours_image">Parcours image : </label></td><td><input type="file" name="parcours_image"/></td></tr>
                <tr><td colspan="2">
                    <?php
                    
                    $destination_path = "../images/events/";
                    if($event['parcours_image']!="" && $event['parcours_image']!=NULL){?>
                        <img alt="image_news" src="<?php echo $destination_path."".$event['parcours_image'] ?>" width="200px">
                    <?php }else{?>
                        <img alt="image-defaut_news" src="../images/news/defaut.jpg" width="200px">
                    <?php } ?>
                </td></tr>

        <tr><td ><label for="Sexe">Sexe : </label></td>

            <td><input type="radio" name="Sexe" value="M" <?php if($event['Sexe']=="M") echo 'checked="checked"';?> /><span>homme</span><input type="radio" name="Sexe" value="F" <?php if($event['Sexe']=="F") echo 'checked="checked"';?>  /><span >femme</span><input type="radio" name="Sexe" value="MF" <?php if($event['Sexe']=="MF") echo 'checked="checked"';?> /><span >mixte</span></td></tr>

        <tr><td><label for="Contact">Contact : </label></td><td><input type="text" name="Contact" value="<?php echo $event['Contact'];?>" /></td></tr>

        <tr><td><label for="Mail">Mail : </label></td><td><input type="text" name="Mail" value="<?php echo $event['Mail'];?>" /> <?php if($event['Mail'] != "") { ?><a href="mailto:<?php echo $event['Mail']; ?>?subject=<?php echo 'R�f�rencement sur alljudo.net'; ?>&body=<?php echo  str_replace('

',"%0A",'Vous venez d��tre r�f�renc� sur alljudo.net, et je serais � mon tour ravi de figurer sur votre site.

Je vous invite �galement � d�couvrir notre offre de partenariat gratuit : /sites-partenaires.php



Merci d�avance, cordialement



Laurent MATHIEU

shin-ji communication

04.74.21.63.26

06.82.94.74.12

http://www.shin-ji.com

 '); ?>">envoyer un mail &agrave; <?php echo $event['Mail']; ?></a><?php } ?></td></tr>

        <tr><td><label for="Telephone">Telephone : </label></td><td><input type="text" name="Telephone" value="<?php echo $event['Telephone'];?>" /></td></tr>

        <tr><td><label for="Web">site web de l’évènement : </label></td><td><input type="text" name="Web" value="<?php echo $event['Web'];?>" /></td></tr>
        <tr><td><label for="insta">Instagram de l’évènement : </label></td><td><input type="text" name="insta" value="<?php echo $event['insta'];?>" /></td></tr>
        <tr><td><label for="facebook">Facebook de l’évènement : </label></td><td><input type="text" name="facebook" value="<?php echo $event['facebook'];?>" /></td></tr>
        <tr><td><label for="youtube">Chaine Youtube de l’évènement : </label></td><td><input type="text" name="youtube" value="<?php echo $event['youtube'];?>" /></td></tr>
        <tr><td><label for="lrs">Lien résultats complets : </label></td><td><input type="text" name="lrc" value="<?php echo $event['lien_resultats_complet'];?>" /></td></tr>
        <tr><td><label for="affiche">Affiche : </label></td><td><input type="file" name="affiche" /></td></tr>
        <tr><td colspan="2">
                <?php
                if($event['affiche']!="" && $event['affiche']!=NULL){?>
                    <img alt="image_events" src="<?php echo "../images/events/".$event['affiche'] ?>" width="200px">
                <?php }else{?>
                    <img alt="image-defaut_events" src="../images/news/defaut.jpg" width="200px">
                <?php } ?>
            </td></tr> 
        <tr><td ><label for="Presentation">Pr&eacute;sentation : </label></td><td><textarea cols="50" rows="4" name="Presentation" ><?php echo str_replace('\\', '',str_replace('"', '\'', $event['Presentation']));?></textarea></td></tr>
        <tr><td><label for="video_teaser">Video teaser (iframe) : </label></td><td><textarea onblur="fillPathPicture();" cols="50" rows="10" id="video_teaser" name="video_teaser"  ><?php echo str_replace('\\','',$event['video_teaser']);?></textarea></td></tr>

        <tr><td><label for="CategorieID">Cat&eacute;gorie d'age : </label></td><td>

        <select name="CategorieAgeID" >

        <?php //while($cat = mysql_fetch_array($result5)){

            foreach ($result5 as $cat) {

            if($event['CategorieageID']==$cat['ID'])

                echo '<option value="'.$cat['ID'].'" selected="selected">'.$cat['Intitule'].'</option>';

            else

                echo '<option value="'.$cat['ID'].'">'.$cat['Intitule'].'</option>';

        } ?>

        </select>

        </td></tr>

        <tr><td><label for="CategorieID">Cat&eacute;gorie : </label></td><td>

        <select name="CategorieID" id="intitule" >

        <?php //while($cat = mysql_fetch_array($result3)){

            foreach ($result3 as $cat) {

            if($event['CategorieID']==$cat['ID'])

                echo '<option value="'.$cat['ID'].'" selected="selected" >'.$cat['Intitule'].'</option>';

            else

                echo '<option value="'.$cat['ID'].'">'.$cat['Intitule'].'</option>';

        } ?>

        </select>

        </td></tr>

        <tr><td><label for="PaysID">Pays : </label></td><td>

        <select id="pays" name="PaysID" >

        <?php //while($pays = mysql_fetch_array($result4)){

            foreach ($result4 as $pays) {

            if($event['PaysID']==$pays['Abreviation'])

                echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';

            else

                echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';

        } ?>

        </select>

        </td></tr>


        <tr><td><label for="fichier1">Upload fichier1 : </label></td><td><input type="file" name="fichier1"/></td><td><?php if($event['Document1']!=null){ echo "<a href='https://dev.allrathon.fr/uploadDocument/".$event['Document1']."' target='_blank'>Voir le document ".$event['Document1']."</a>";}?></td></tr>


        <tr><td><label for="fichier3">Upload fichier3 : </label></td><td><input type="file" name="fichier3"/></td><td><?php if($event['Document3']!=null){ echo "<a href='https://dev.allrathon.fr/uploadDocument/".$event['Document3']."' target='_blank'>Voir le document ".$event['Document3']."</a>";}?></td></tr>
        

        <tr ><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>

       </table>

    </form>

</fieldset>
<fieldset style="float:left;">
    <legend>Liste des marathons</legend>
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
            <tr><th>ID</th><th>nom</th><th>lieu</th><th>PaysID</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php //while($marathon = mysql_fetch_array($result1)){
            foreach ($result6 as $marathon) {
                echo "<tr align=\"center\" ><td>".$marathon['id']."</td><td>".$marathon['nom']."</td><td>".$marathon['lieu']."</td><td>".$marathon['PaysID']."</td><td>";
                
                echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='marathonDetail.php?marathonID=".$marathon['id']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$marathon['nom']." ?')) { location.href='supprimermarathon.php?marathonID=".$marathon['id']."&marathonNom=".$marathon['nom']."';} else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>





    </div>
</fieldset>
</body>

  <script src="../js/evenement-detail-hierarchique-script.js?ver=<?php echo rand(111,999)?>"></script>
<!-- InstanceEnd --></html>

