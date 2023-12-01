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

                             $req4 = $bdd->prepare("INSERT INTO evenements (Nom,Sexe,DateDebut,DateFin,Presentation,Type,CategorieageID,CategorieID,Visible,".$reqDocBegin." PaysID) VALUES (:nom,:sexe,:dateDebut,:dateFin,:Presentation,:Type,:CategorieAgeID,:CategorieID,:Visible, ".$reqDocEnd." :PaysID)");



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

                                {header("Location: evenementResultatEquipe.php?evenementID=".$bdd->lastInsertId());}

                             else

                                    { header("Location: evenementResultatIndividuel.php?evenementID=".$bdd->lastInsertId());}

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

            <title>alljudo admin</title>



           

</head>



<body>

    <?php require_once "menuAdmin.php"; ?>

    

<h1>Détection des doublons</h1>

<br><br>

<fieldset style="float:left;">

    <legend>balayage par pas de 5, 10, 20, 30, 60, 100, 150, 200</legend>

    <div align="center">



        <div id="pager" class="pager">

           <form action="script_fusion.php" method="post" name="pas">

           

              Selectionnez le pas : 

              <select class="pagesize" name="length">

                <option value="5">5</option>

                 <option selected="selected"  value="10">10</option>

                 <option value="20">20</option>

                 <option value="30">30</option>

                 <option value="60">60</option>

                 <option value="100">100</option>

                 <option value="150">150</option>

                 <option value="150">200</option>

             </select><br><br>

             <input type="submit" value="valider"/>

             <br><br>

             Reprendre depuis une valeur ? <input style="border:solid" type="text" name="reprise" placeholder="debut" value="0" /><br>

         </form>

     </div>

     <br />

</div>

</fieldset>

<fieldset style="float:right;">

    <legend>balayage par intervalles</legend>

    <div align="center">



        <div id="pager" class="pager">

           <form action="script_fusion.php" method="post">

              

             valeur min : <input style="border:solid" type="text" name="min" placeholder="min" /><br>

             valeur max : <input style="border:solid" type="text" name="max" placeholder="max" /> 

              

             <input type="submit" value="valider"/>

         </form>

     </div>

     <br />

</div>

</fieldset>

</body>

<!-- InstanceEnd --></html>



