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

  try{

        // $req1 = $bdd->prepare("SELECT a.*,u.username from annonce a,phpbb_users u where u.user_id =a.User_ID ORDER BY ID DESC");
        // $req1->execute();
        // $result1= array();
        // while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
        //     array_push($result1, $row);
        // }

        $req3 = $bdd->prepare("SELECT * FROM evcategorieevenement ORDER BY Intitule");
        $req3->execute();
        $result3= array();
        while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result3, $row);
        }
        $req4=$bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
        $req4->execute();
        $result4= array();
        while ( $row  = $req4->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result4, $row);
        }

        $req5=$bdd->prepare("SELECT * FROM evcategorieage ORDER BY ID");
        $req5->execute();
        $result5= array();
        while ( $row  = $req5->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result5, $row);
        }

        $req6=$bdd->prepare("SELECT * from annonce where ID=:ID");
        $req6->bindValue('ID',$_GET['annonceID'], PDO::PARAM_INT);
        $req6->execute();
        $r= $req6->fetch(PDO::FETCH_ASSOC);

        $req6=$bdd->prepare("SELECT * FROM categorie");
        $req6->execute();
        $categories= array();
        while ( $row  = $req6->fetch(PDO::FETCH_ASSOC)) {  
            array_push($categories, $row);
        }

        $req7=$bdd->prepare("SELECT * FROM sous_categorie where ID=:ID");
        $req7->bindValue('ID',$r['sous_categorie_ID'], PDO::PARAM_INT);
        $req7->execute();
        $sc=$req7->fetch(PDO::FETCH_ASSOC);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript">
                
                $(function(){
                   $('#s').change(function(){
                    $.ajax({
                    type:'get',
                    url:'/sous_categorie.php?c='+$("#s").val(),
                    success:function(data){
                      $('#sc').html(data);  
                    }
                    }); 
                   })
                });
              </script>
               <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
    // General options
    convert_urls : false,
    mode : "exact",
    elements : "Descriptif",
    theme : "advanced",
    plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    // Theme options
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
    theme_advanced_buttons3 : "undo,redo,|,visualaid,|,tablecontrols"
});
</script>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>


<fieldset style="float:left;">
<legend>Modification de l'annonce</legend>
<div align="center">
<?php
function getoption($id){
  $req_getoption=$bdd->prepare("SELECT * FROM sous_categorie where ID=:ID");
        $req_getoption->bindValue('ID',$r['sous_categorie_ID'], PDO::PARAM_INT);
        $req_getoption->execute();
        $r=$req_getoption->fetch(PDO::FETCH_ASSOC);
    return $r['valeur'];
  }
if(isset($_POST['ok']) ) {

                $premium=(isset($_POST['premium']))? 1 : 0;
                $valide=(isset($_POST['valide']))? 1 : 0;


                 try {
                 $req_update = $bdd->prepare("UPDATE annonce SET Date_modification=now(),descriptif=:Descriptif,Titre=:Titre,Nom=:Nom,Mail=:Mail ,Telephone=:Telephone,Code_postal=:Code_postal,Ville=:Ville,Pays=:Pays,Premium=:premium,Valide=:valide,sous_categorie_ID=:sous_categorie_ID where ID=:ID");

                 $req_update->bindValue('Descriptif',$_POST['Descriptif'], PDO::PARAM_STR);
                 $req_update->bindValue('Titre',$_POST['Titre'], PDO::PARAM_STR);
                 $req_update->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
                 $req_update->bindValue('Mail',$_POST['Mail'], PDO::PARAM_STR);
                 $req_update->bindValue('Telephone',$_POST['Telephone'], PDO::PARAM_STR);
                 $req_update->bindValue('Code_postal',$_POST['Code_postal'], PDO::PARAM_STR);
                 $req_update->bindValue('Ville',$_POST['Ville'], PDO::PARAM_STR);
                 $req_update->bindValue('Pays',$_POST['Pays'], PDO::PARAM_STR);
                 
                 $req_update->bindValue('premium',$premium, PDO::PARAM_INT);
                 $req_update->bindValue('valide',$valide, PDO::PARAM_INT);
                 $req_update->bindValue('sous_categorie_ID', $_POST['sc'], PDO::PARAM_INT);
                 $req_update->bindValue('ID',$_GET['annonceID'], PDO::PARAM_INT);



                 $statut=$req_update->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
                echo "annonce modifi&eacute; avec succ&eacute;s";
                echo '<meta http-equiv="refresh" content="0,annonces.php">';
                //header("location:annonces.php");
                
}
                      
                        ?>
                          <form name="f" action="" method="post">
                          <table>
                          <tr><td>Titre</td><td><input type="text" name="Titre" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'',$r['Titre'])); ?>"/></td></tr>
                          <tr><td>Descriptif</td><td><textarea  style='width: 400px;height: 200px' name="Descriptif" id="" ><?php echo str_replace('\\', '',str_replace('"', '\'', $r['Descriptif'])); ?></textarea></td></tr>
                          <tr><td>Nom</td><td><input type="text" name="Nom" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $r['Nom'])); ?>"/></td></tr>
                          <tr><td>Email</td><td><input type="text" name="Mail" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'',$r['Mail'] )); ?>"/></td></tr>
                          <tr><td>telephone</td><td><input type="text" name="Telephone" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $r['Telephone'])); ?>"/></td></tr>
                          <tr><td>Code postal</td><td><input type="text" name="Code_postal" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'', $r['Code_postal']));  ?>"/></td></tr>
                          <tr><td>Ville</td><td><input type="text" name="Ville" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'',$r['Ville'] )); ?>"/></td></tr>
                          <tr><td>Pays</td><td><input type="text" name="Pays" id="" value="<?php echo str_replace('\\', '',str_replace('"', '\'',$r['Pays'] )); ?>"/></td></tr>
                           <tr>
                            <tr><td>Cat&eacute;gorie</td><td>

                            <select id="s" name="c" >
                            <option value="0" >choisir une categorie</option>
                            <?php 
                            $cat=0;
                            foreach ($categories as $r2) {
                                if($r2["ID"]==$sc['categorie_ID']){
                                    $cat=$r2["ID"];
                                   echo "<option  id='c".$r2['ID']."' value='".$r2['ID']."' selected='selected'>".$r2['nom_categorie']."</option>"; }
                                   else{
                                   echo "<option  id='c".$r2['ID']."' value='".$r2['ID']."'>".$r2['nom_categorie']."</option>";                                     
                            } 
                            }
                            ?>
                                        
                           </select>

                          </td>
                        <tr>
                            <td>Sous-Cat&eacute;gorie</td>
                            <td>
                                <select id='sc' name='sc' >
                                    <option value="0">choisir une categorie</option>
                                    <?php 
                                    try{
                                      $req_sc=$bdd->prepare("SELECT * FROM sous_categorie WHERE categorie_id=:ID");
                                      $req_sc->bindValue('ID',$cat, PDO::PARAM_INT);
                                      $req_sc->execute();
                                      $sc= array();
                                        while ( $row  = $req_sc->fetch(PDO::FETCH_ASSOC)) {  
                                            array_push($sc, $row);
                                        }
                                    }
                                    catch(Exception $e)
                                    {
                                        die('Erreur : ' . $e->getMessage());
                                    }
                                    
                                     foreach ($sc as $r2) {
                                       if($r2['ID']==$r['sous_categorie_ID']){
                                    echo '<option value="'.$r2['ID'].'" selected="selected">'.$r2['nom_sous_categorie'].'</option>';
                                        }else{
                                    echo '<option value="'.$r2['ID'].'">'.$r2['nom_sous_categorie'].'</option>';
                                        }
                                    }
                                    ?>
                                    
                                </select>
                            </td>
                      </tr>                          
                      <tr><td>Premium </td><td><?php if($r['Premium']==0){ ?><input type='checkbox' name='premium' /> <?php }else{?> <input type='checkbox' name='premium' checked='checked' /><?php } ?></td></tr>
                          <tr><td>valide </td><td><?php if($r['Valide']==0){ ?><input type='checkbox' name='valide' /> <?php }else{?> <input type='checkbox' name='valide' checked='checked' /><?php } ?></td></tr>
                          <tr><td></td><td><input type="submit" name="ok" id="" value="modifier"/></td></tr>
                          </table>
                          </form>
                        <?php
                      
                    // }
?>
    
</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>

