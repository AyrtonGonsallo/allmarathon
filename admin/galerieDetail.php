<?php

ini_set('display_errors', 0);

ini_set('display_startup_errors', 0);

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





if(!isset($_GET['galerieID']))

    header('location:galerie.php');



    $page = 0;

    if(isset($_GET['page']) && is_numeric($_GET ['page']))

        $page = $_GET['page'];



    $erreur = "";

    if( isset($_POST['sub'])){

        if($erreur == "" ){

            try {

         $req2 = $bdd->prepare("UPDATE galeries SET Titre_en=:Titre_en, Date=:Date, Photographe=:Photographe, Evenement_id=:Evenement_id WHERE ID=:gallerieID");



         $req2->bindValue('Titre_en',$_POST['Titre_en'], PDO::PARAM_INT);

         $req2->bindValue('Date',$_POST['Date'], PDO::PARAM_STR);

         $req2->bindValue('Photographe',$_POST['Photographe'], PDO::PARAM_STR);

         $req2->bindValue('Evenement_id',$_POST['Evenement_id'], PDO::PARAM_INT);

         $req2->bindValue('gallerieID',$_GET['galerieID'], PDO::PARAM_STR);

         $req2->execute();

         header('location:galerie.php');

     }

     catch(Exception $e)

     {

        die('Erreur : ' . $e->getMessage());

    }

        }

    }



    try{

            $req = $bdd->prepare("SELECT * FROM galeries WHERE ID=:ID");

            $req->bindValue('ID',$_GET['galerieID'], PDO::PARAM_INT);

            $req->execute();

            $gal= $req->fetch(PDO::FETCH_ASSOC);



            $req1 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY C.Intitule,E.Nom,E.DateDebut");

            $req1->execute();

            $result3= array();

            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  

                array_push($result3, $row);

            }



            $req2 = $bdd->prepare("SELECT * FROM images WHERE Galerie_id=:ID ORDER BY ID LIMIT :offset,20");

            $req2->bindValue('ID',$_GET['galerieID'], PDO::PARAM_INT);

            $req2->bindValue('offset',$page*20, PDO::PARAM_INT);

            $req2->execute();

            $result4= array();

            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  

                array_push($result4, $row);

            }



            $req_nb_image = $bdd->prepare("SELECT count(*) FROM images WHERE Galerie_id=:ID");

            $req_nb_image->bindValue('ID',$_GET['galerieID'], PDO::PARAM_INT);

            $req_nb_image->execute();

            $nbr_Image= $req_nb_image->fetch(PDO::FETCH_ASSOC);

            // $tabTemp = explode(".",(($nbr_Image)/20));

            $nbr_page = floor($nbr_Image['count(*)']/20);





        }

        catch(Exception $e)

        {

            die('Erreur : ' . $e->getMessage());

        }

?>



<!DOCTYPE html

    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->



<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
   <!-- <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>-->



    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="../fonction/nyroModal-1.5.0/styles/nyroModal.css" type="text/css" media="screen" />

    <script type="text/javascript" src="../fonction/nyroModal-1.5.0/js/jquery.nyroModal-1.5.0.min.js"></script>

    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

    <script type="text/javascript">


    function modal(name) {

        $.nyroModalManual({

            url: name

        });

        return false;

    }

    </script>



   

    <title>alljudo admin galerie</title>





    <script type="text/javascript" src="../script/ajax.js"></script>

	<!--<script type="text/javascript" src="../script/gal.js"></script>-->

    <script type="text/javascript">

    function autoComp(index) {

        reponse = ajaxCollector('resultatAutoCompletion.php?id=' + index + '&str=' + document.getElementById('temp' +

            index).value);

        if (reponse != 0) {

            document.getElementById("autocomp" + index).style.display = "";

            document.getElementById("autocomp" + index).innerHTML = reponse;

        } else {

            document.getElementById("autocomp" + index).style.display = "none";

        }



    }



    function addCompletion(str, index) {

        tab = str.split(':');

        idChamp = tab[0];

        name = tab[1];

        document.getElementById("autocomp" + index).style.display = "none";

        document.getElementById("champion" + index).value = idChamp;

        document.getElementById("temp" + index).value = name;

    }

    </script>



    <!-- InstanceEndEditable -->

</head>



<body>

    <?php require_once "menuAdmin.php"; ?>

    <fieldset style="float:left;">

        <legend>Ajouter galerie</legend>

        <form action="galerieDetail.php?galerieID=<?php echo $_GET['galerieID']; ?>" method="post">

            <p id="pErreur" align="center"><?php echo $erreur; ?></p>

            <table>



                <tr>

                    <td align="right"><label for="Titre">Titre : </label></td>

                    <td><input type="text" name="Titre"

                            value="<?php echo str_replace('\\', '',str_replace('"', '\'', $gal['Titre']));?>"

                            disabled="disabled" /></td>

                </tr>

                <tr>

                    <td align="right"><label for="Titre_en">Titre_en : </label></td>

                    <td><input type="text" name="Titre_en"

                            value="<?php echo str_replace('\\', '',str_replace('"', '\'', $gal['Titre_en']));?>" /></td>

                </tr>

                <tr>

                    <td align="right"><label for="Date">Date : </label></td>

                    <td><input type="text" name="Date" id="datepicker" value="<?php echo $gal['Date'];?>" /></td>

                </tr>

                <tr>

                    <td align="right"><label for="Photographe">Photographe : </label></td>

                    <td><input type="text" name="Photographe"

                            value="<?php echo str_replace('\\', '',str_replace('"', '\'', $gal['Photographe']));?>" />

                    </td>

                </tr>

                <tr>

                    <td align="right"><label for="Evenement_id">Evenement : </label></td>

                    <td>

                        <select name="Evenement_id">

                            <option value="0">aucun</option>

                            <?php //while($event = mysql_fetch_array($result3)){

            foreach ($result3 as $event) {

            $str = ($event['ID']==$gal['Evenement_id']) ? '<option value="'.$event['ID'].'" selected="selected" >'.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>':'<option value="'.$event['ID'].'">'.$event['Intitule'].' '.$event['Nom'].' '.substr($event['DateDebut'],0,4).'</option>';

                echo $str;

        } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td colspan="2" align="center"><input type="submit" name="sub" value="modifier" /></td>

                </tr>

            </table>

        </form>

    </fieldset>



    <fieldset class="upload_form">

        <legend>Uploader une Image</legend>

        <div class="container clearfix">

            <div class="row">

                <div class="col-md-8">



                    <form id="form" action="galerieUploadFile.php" method="post" enctype="multipart/form-data">



                        <input id="folder" type="hidden" value="<?php echo ':'.$gal['ID'];?>" name="folder" />

						<div class="upload__box">

						<div class="upload__btn-box">

							<label class="upload__btn">

							<p>Upload images</p>

							<input id="uploadImage" type="file" accept="image/*" name="image[]" multiple="" data-max_length="20" class="upload__inputfile">

							</label>

						</div>

						<div class="upload__img-wrap"></div>

						</div>

                        <!--div id="preview"><img src="filed.png" /></div><br-->

                        <input class="btn btn-success" type="submit" value="Upload">

                    </form>

                    <div id="err"></div>



                </div>

            </div>

    </fieldset>



    <fieldset class="uploaded_images">

        <legend>Images dans la galerie</legend>

        



            <?php echo '<div><b style="float:left">Pages suivantes:</b>';

    for($i=0;$i<$nbr_page+1;$i++){

                echo ($i==$page)?'<a style="margin:2px;color:red;float:left;width:fit-content" href="galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$i.'">'.($i+1).'</a>':'<a style="margin:2px;float:left;width:fit-content" href="galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$i.'">'.($i+1).'</a>';

            }echo '</div><div class="wrapper_images_uploaded">';

    $i=1;

    //while($image = mysql_fetch_array($result4)){

    foreach ($result4 as $image) {

        try{

  $req6 = $bdd->prepare("SELECT ID,Nom FROM champions WHERE ID=:ID");

  $req6->bindValue('ID',$image['Champion_id'], PDO::PARAM_INT);

  $req6->execute();

  $champ1=$req6->fetch(PDO::FETCH_ASSOC);



  $req7 = $bdd->prepare("SELECT ID,Nom FROM champions WHERE ID=:ID");

  $req7->bindValue('ID',$image['Champion2_id'], PDO::PARAM_INT);

  $req7->execute();

  $champ2=$req7->fetch(PDO::FETCH_ASSOC);

}



catch(Exception $e)

{

    die('Erreur : ' . $e->getMessage());

}



        // $query6  = sprintf('SELECT ID,Nom FROM champions WHERE ID=%s',$image['Champion_id']);

        // $result6 = mysql_query($query6);

        // $champ1  = mysql_fetch_array($result6);

        // $query7  = sprintf('SELECT ID,Nom FROM champions WHERE ID=%s',$image['Champion2_id']);

        // $result7 = mysql_query($query7);

        // $champ2  = mysql_fetch_array($result7);



        ?>

            <div class="image_galerie" style="margin-top:15px;">

                <img src="../images/galeries/<?php echo $gal['ID'];?>/<?php echo $image['Nom'];?>"

                    onclick="modal('../images/galeries/<?php echo $gal['ID'];?>/<?php echo $image['Nom'];?>');"

                    height="80" alt="errerur_chargement_image" />

                <form style="float:left;"

                    action="galerieImageUpdate.php?imageID=<?php echo $image['ID'];?>&galerieID=<?php echo $_GET['galerieID']; ?>&page=<?php echo $page;?>"

                    method="post">

                    <table>

                        <tr>

                            <td colspan="2"><?php echo $image['Nom'];?></td>

                        </tr>

                        <tr>

                            <td><label for="champion<?php echo $i; ?>1">champion1</label></td>

                            <td>

                                <div id="autoCompChamp<?php echo $i; ?>1">

                                    <input autocomplete="off" type="text" id="temp<?php echo $i; ?>1"

                                        onkeyup="autoComp(<?php echo $i; ?>1);" value="<?php echo $champ1['Nom']; ?>" />

                                    <div id="autocomp<?php echo $i; ?>1" style="display:none;" class="autocomp"></div>

                                    <input style="display:none;" id="champion<?php echo $i; ?>1" name="Champion_id"

                                        type="text" value="<?php echo $champ1['ID']; ?>" />

                                </div>

                            </td>

                        </tr>



                        <tr>

                            <td><label for="champion<?php echo $i; ?>2">champion2</label></td>

                            <td>

                                <div id="autoCompChamp<?php echo $i; ?>2">

                                    <input autocomplete="off" type="text" id="temp<?php echo $i; ?>2"

                                        onkeyup="autoComp(<?php echo $i; ?>2);" value="<?php echo $champ2['Nom']; ?>" />

                                    <div id="autocomp<?php echo $i; ?>2" style="display:none;" class="autocomp"></div>

                                    <input style="display:none;" id="champion<?php echo $i; ?>2" name="Champion2_id"

                                        type="text" value="<?php echo $champ2['ID']; ?>" />

                                </div>

                            </td>

                        </tr>





                        <tr>

                            <td><label for="Technique_id">technique</label></td>

                            <td><select name="Technique_id">

                                    <option value="0">aucune</option>

                                    <?php foreach($techniques AS $tech) echo ($tech['ID']==$image['Technique_id'])? '<option selected="selected" value="'.$tech['ID'].'">'.$tech['Nom'].'</option>' : '<option value="'.$tech['ID'].'">'.$tech['Nom'].'</option>';?>

                                </select></td>

                        </tr>



                        <tr>

                            <td><input

                                    onclick="if(confirm('Voulez vous vraiment supprimer l\'image ?')) document.location.href='galerieImageDelete.php?imageID=<?php echo $image['ID'];?>'; else return 0;"

                                    type="button" value="supprimer" /></td>

                            <td><input type="submit" value="modifier" name="subImage" /></td>

                        </tr>

                    </table>

                </form>

                <div style="clear:both;"></div>

            </div>



            <?php $i++;} 

        echo '</div><div><b style="float:left">Pages suivantes:</b>';

    for($i=0;$i<$nbr_page+1;$i++){

                echo ($i==$page)?'<a style="margin:2px;color:red;float:left;width:fit-content" href="galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$i.'">'.($i+1).'</a>':'<a style="margin:2px;float:left;width:fit-content" href="galerieDetail.php?galerieID='.$_GET['galerieID'].'&page='.$i.'">'.($i+1).'</a>';

            }echo '</div>';?>



        

    </fieldset>






    <script>
    $(document).ready(function(){
	    
        $('#datepicker').datepicker({
            dateFormat: "yy-mm-dd"
            });
    }
   );

</script>



<script type="text/javascript" src="../script/gal.js"></script>
</body>

<!-- InstanceEnd -->



</html>