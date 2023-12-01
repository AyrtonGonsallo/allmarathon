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
    if($_POST['nom']=="") { $erreur .= "Erreur nom.<br />";}  

    if($erreur==""){
       
             /**  Fin de boucle for($i=1;$i<4;$i++) **/
             /** Traitement d'erreur **/
                    if($erreur == "" ){
                        try {
                            
                            
                                $sql ="INSERT INTO `marathons`( `nom`, `site_web`, `Instagram`, `facebook`, `youtube`, `description`, `image`, `lieu`,`PaysID`,Visible) VALUES (:nom,:site_web,:insta,:fb,:yt,:descr,:img,:lieu,:PaysID,:vis)";
                            
                                $fileName = $_FILES['img']['name'];
                             $req4 = $bdd->prepare($sql);
                             // :nom,:site_web,:insta,:fb,:yt,:descr,:img,:lieu
                             $req4->bindValue('nom',$_POST['nom'], PDO::PARAM_STR);
                             $req4->bindValue('site_web',$_POST['site_web'], PDO::PARAM_STR);
                             $req4->bindValue('vis',1, PDO::PARAM_STR);
                             $req4->bindValue('insta',$_POST['insta'], PDO::PARAM_STR);
                             $req4->bindValue('fb',$_POST['fb'], PDO::PARAM_STR);
                             $req4->bindValue('yt',$_POST['yt'], PDO::PARAM_STR);
                             $req4->bindValue('descr',$_POST['descr'], PDO::PARAM_STR);
                             $req4->bindValue('img',$fileName, PDO::PARAM_STR);
                             $req4->bindValue('lieu',$_POST['lieu'], PDO::PARAM_STR);
                             $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
                             $statut=$req4->execute();

                            
                            header("Location: marathon.php");
                            
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
                        
                    }
                        
                    }
                 
                }
            


            try{
              $req = $bdd->prepare("SELECT * FROM marathons ");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

           

            $req2 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
            $req2->execute();
            $result4= array();
            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result4, $row);
            }

          

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
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
            <link rel="stylesheet" href="../css/marathon-hierarchique-style.css" type="text/css"> 
            <!-- InstanceBeginEditable name="doctitle" -->
            <title>allmarathon admin</title>

            <script type="text/javascript">
                /*$(document).ready(function()
                {
	
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
   );*/

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
<!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Ajouter marathon</legend>
        <form action="marathon.php" method="post" enctype="multipart/form-data">
        <p id="pErreur" ><?php echo $erreur; ?></p>
            <table>
            
                
                <tr><td><label for="Nom">Intitul&eacute; : </label></td><td><input id="nom" type="text" name="nom" value="" /></td></tr>
                <tr><td><label for="site_web">site web  : </label></td><td><input id="site_web" type="text" name="site_web" value="" /></td></tr>
                <tr><td><label for="insta">Instagram  : </label></td><td><input id="insta" type="text" name="insta" value="" /></td></tr>
                <tr><td><label for="fb">facebook  : </label></td><td><input id="fb" type="text" name="fb" value="" /></td></tr>
                <tr><td><label for="yt">Chaine Youtube  : </label></td><td><input id="yt" type="text" name="yt" value="" /></td></tr>
                <tr>
                    <td align="right"><label for="descr">Description : </label></td>
                    <td><textarea name="descr" cols="30" rows="19"></textarea></td>
                </tr>                <tr align="center">
                    <td><label for="img">Photo : </label></td>
                    <td><input type="file" name="img" /></td>
                </tr>                
                <tr><td><label for="lieu">Ville : </label></td><td><input id="lieu" type="text" name="lieu" value="" /></td></tr>
                <tr>
                    <td>
                        <label for="PaysID">Pays : </label></td><td>
                        <select name="PaysID" id="pays">
                        <?php //while($pays = mysql_fetch_array($result4)){
                            foreach ($result4 as $pays) {
                                echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr ><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
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
            foreach ($result1 as $marathon) {
                echo "<tr align=\"center\" ><td>".$marathon['id']."</td><td>".$marathon['nom']."</td><td>".$marathon['lieu']."</td><td>".$marathon['PaysID']."</td><td>";
                
                echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='marathonDetail.php?marathonID=".$marathon['id']."'\" />
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$marathon['nom']." ?')) { location.href='supprimermarathon.php?marathonID=".$marathon['id']."&marathonNom=".$marathon['nom']."';} else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>





    </div>
</fieldset>
</body>


<!-- InstanceEnd --></html>

