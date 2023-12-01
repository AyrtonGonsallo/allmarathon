<?php $start_time = time();

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



   



?>

<?php

    require '../content/classes/test.php';

        require '../content/classes/script_fusion_fiches.php'; 

        

        if((isset($_POST['length']))&&(! isset($_POST['reprise'])))

        {

            $_SESSION['length'] = $_POST['length'];

            $debut=0;

            $fin=$debut+$_SESSION['length'];

            

            $result=recherche_et_fusion($debut,$fin);

            

        }

        else if((isset($_POST['length']))&&(isset($_POST['reprise'])))

        {

            $_SESSION['length'] = $_POST['length'];

            $debut=$_POST['reprise'];

            $fin=$debut+$_SESSION['length'];

            

            $result=recherche_et_fusion($debut,$fin);

            

        }

        else if((isset($_POST['min'])) && (isset($_POST['max'])))

        {

            $result=recherche_et_fusion($_POST['min'],$_POST['max']);

            

        }

        else{

            $debut=$_GET['debut'];

            $fin=$_GET['fin'];

            

            $result=recherche_et_fusion($debut,$fin);

        }           

                    

        

?>

<?php





$renvoyés=renvoyés::getNbr();



?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 

<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>

<script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>

<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>

<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />

<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<script>

var renvoyés = <?php echo json_encode($renvoyés); ?>;

window.onload = function() {

   document.getElementById("compte").innerHTML="nombre total de lignes : "+renvoyés+"\n"; 

} 

function deleteRow(btn) {

    //suprimer la ligne

  var row = btn.parentNode.parentNode;

  row.parentNode.removeChild(row);

  //mettre a jour le nombre

  renvoyés--;

  document.getElementById("compte").innerHTML ="nombre total de lignes : "+renvoyés+"\n"; 

}

function deleteRow2(btn) {

    //suprimer la ligne

  var row = btn.parentNode.parentNode.parentNode;

  row.parentNode.removeChild(row);

  //mettre a jour le nombre

  renvoyés--;

  document.getElementById("compte").innerHTML ="nombre total de lignes : "+renvoyés+"\n"; 

}

function deleteRow3(btn) {

    //suprimer la ligne

  var row = btn.parentNode.parentNode.parentNode.parentNode;

  row.parentNode.removeChild(row);

  //mettre a jour le nombre

  renvoyés--;

  document.getElementById("compte").innerHTML ="nombre total de lignes : "+renvoyés+"\n"; 

}











function maj() {

  //mettre a jour le nombre

  renvoyés--;

  document.getElementById("compte").innerHTML ="nombre total de lignes : "+renvoyés+"\n"; 

}

</script>

<!-- InstanceBeginEditable name="doctitle" -->

<title>alljudo admin</title>







<!-- InstanceEndEditable -->

</head>



<body>



<?php require_once "menuAdmin.php"; 

if(!(isset($_POST['min'])) && !(isset($_POST['max']))){

    echo "<b style=\"font-size:30px;font-weight:bold;\">de ".$debut." a ".$fin."</b>";

}

else if((isset($_POST['min'])) && (isset($_POST['max'])))

        {

            echo "<b style=\"font-size:30px;font-weight:bold;\">de ".$_POST['min']." a ".$_POST['max']."</b>";

        }

?>

<br><br>

<div>

    <fieldset style="float:left;">

        <legend>Suggestion de fusion (nouveau par ancien)</legend>



        <div id="compte" style="color:red;background-color:black;width: 300px;height:50px; font-size:20px;padding:10px;margin:10px;border-radius:20px;"></div>

            <div >

                <table class="tab1">

                <thead>

                    <tr><th colspan="2">A remplacer</th><th colspan="2">remplacant</th><th colspan="2">Action</th></tr>

                </thead>

                <tbody>

                    

                    <tr><td>ID</td><td>Nom</td><td>ID</td><td>Nom</td><td colspan="2"></td></tr>

                    <?php //while($champion = mysql_fetch_array($result3)){

                        foreach ($result as $format) {

                            foreach ($format->getRemplacants() as $remplacant) {

                    if($_SESSION['admin'] == true){

                        echo "<tr align=\"center\" ><td>".$format->getChampion_courant()['ID']."</td><td>".$format->getChampion_courant()['Nom']."</td><td>".$remplacant['ID']."</td><td>".$remplacant['Nom']."</td>

                            <td colspan='2'>

                            <a href=\"remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."'\" target=\"_blank\">

                            <img style=\"cursor:pointer;\" src=\"../images/replace.png\" alt=\"replace\" title=\"remplacer\" onclick=\"deleteRow2(this)\" />

                            </a>

                            <img style=\"cursor:pointer;\" src=\"../images/cancel.png\" alt=\"annuler\" title=\"annuler\"  onclick=\"if(confirm('Voulez vous vraiment annuler le remplacement de ".$format->getChampion_courant()['Nom']."par ".$remplacant['Nom']." ?')) { deleteRow(this)} else { return 0;}\" />

                            

                            <a href=\"remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."&modifier=true\" target=\"_blank\">

                            <img style=\"cursor:pointer;\" src=\"../images/rempMod.png\" alt=\"replaceandedit\" title=\"remplacer et modifier\" onclick=\"deleteRow2(this)\" />

                            </a>

                            

                            </td></tr>";

                    }elseif($_SESSION['ev'] == true){

                        echo "<tr align=\"center\" ><td>".$format->getChampion_courant()['ID']."</td><td>".$format->getChampion_courant()['Nom']."</td><td>".$remplacant['ID']."</td><td>".$remplacant['Nom']."</td> 

                            <td colspan='2'><img style=\"cursor:pointer;\" src=\"../images/replace.png\" alt=\"replace\" title=\"remplacer\" onclick=\"location.href='remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."'\" />

                            </td></tr>";

                    }

                    }

                    } ?>

                    

                </tbody>

                </table>

            </div>

        </div>

    </fieldset>

    <div style="position: absolute; right: 0px; color:red;background-color:black;width: 700px;;height:60px; font-size:30px;padding:20px;margin:20px;border-radius:40px;">

        <?php 

            $end_time = time();

            $execution_time = ($end_time - $start_time);

            echo "<b style=\"color:white\">Temps d'exécution :</b> ".$execution_time."<b style=\"color:white\"> sec</b>";

        ?>

    </div>

</div>



<?php 

    if((!isset($_POST['min'])) && (!isset($_POST['max']))){

        $deb2=$fin;

        $fin2=$deb2+$_SESSION['length'];

        echo "<button style=\"position: absolute; top:700px;font-weight:bold; left:600px;background-color:red;font-size:20px;cursor:pointer\" onclick=\"location.href='script_fusion.php?debut=".$deb2."&fin=".$fin2."'\">Traiter les ".$_SESSION['length']." suivants</button>" ;

}?>  





<!-- message de succes -->

<?php

        if(isset($_GET['message'])){

            echo "remplacement reussi";

        }

        ?>

</body>

</html>





