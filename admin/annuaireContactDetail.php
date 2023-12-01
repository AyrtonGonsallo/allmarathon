<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 

//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';
if(isset($_GET['siteID'])) {
    $id = (int)$_GET['siteID'];
    $erreur = "";
    if( isset($_POST['sub']) ) {
        if($_POST['club']=="")
            $erreur .= "Erreur club.<br />";
        if($erreur == "") {
            try {
                 $req3 = $bdd->prepare("UPDATE clubs SET Valide=:Valide,club=:club,pays=:pays, responsable=:responsable, telephone=:telephone, mel=:mel, site=:site, description=:description, ville=:ville, CP=:CP, departement=:departement, adresse=:adresse, gcoo1=:gcoo1, gcoo2=:gcoo2, gaddress=:gaddress   WHERE ID=:ID");
                 
                 $req3->bindValue('Valide',$_POST['Valide'], PDO::PARAM_INT);
                 $req3->bindValue('club',$_POST['club'], PDO::PARAM_STR);
                 $req3->bindValue('responsable',$_POST['responsable'], PDO::PARAM_STR);
                 $req3->bindValue('telephone',$_POST['telephone'], PDO::PARAM_STR);
                 $req3->bindValue('mel',$_POST['mel'], PDO::PARAM_STR);
                 $req3->bindValue('site',$_POST['site'], PDO::PARAM_STR);
                 $req3->bindValue('pays',$_POST['pays'], PDO::PARAM_STR);
                 $req3->bindValue('description',$_POST['description'], PDO::PARAM_STR);
                 $req3->bindValue('ville',$_POST['ville'], PDO::PARAM_STR);
                 $req3->bindValue('gaddress',$_POST['gaddress'], PDO::PARAM_STR);
                 $req3->bindValue('adresse',$_POST['adresse'], PDO::PARAM_STR);
                 $req3->bindValue('gcoo1',$_POST['gcoo1'], PDO::PARAM_STR);
                 $req3->bindValue('gcoo2',$_POST['gcoo2'], PDO::PARAM_STR);
                 $req3->bindValue('departement',substr(trim($_POST['CP']), 0, 2), PDO::PARAM_INT);
                 $req3->bindValue('CP',$_POST['CP'], PDO::PARAM_INT);
                 $req3->bindValue('ID',$id, PDO::PARAM_INT);

                 $statut=$req3->execute();
                
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
             header("Location: annuaireContact.php");

        }
    }

    try{
              $req = $bdd->prepare("SELECT * FROM clubs WHERE ID=:ID");
              $req->bindValue('ID',$id, PDO::PARAM_INT);
              $req->execute();
              $club= $req->fetch(PDO::FETCH_ASSOC);

            $req1 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
            $req1->execute();
            $paysNomTab= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($paysNomTab, $row);
            }

            $req2 = $bdd->prepare("SELECT * FROM departements ORDER BY NomDepartement");
            $req2->execute();
            $departements= array();
            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
                array_push($departements, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
        <script src="../script/jquery-1.3.2.min.js" type="text/javascript"></script>
        
        <title>allmarathon admin</title>


        <script type="text/javascript">
            function geocode(){
                if($("#ville").val() == "")
                    return true;
                $.ajax({
                    async: false,
                    cache: false,
                    type: "POST",
                    data: ({adresse : $("#adresse").val().replace(new RegExp(" ","g"),"+")+',+'+$("#ville").val().replace(new RegExp(" ","g"),"+")+',+'+$("#pays option:selected").text()}),
                    url: '../ajaxgeocoder.php',
                    success: function(data) {
                        //alert(data);
                        var doc = typeof JSON !='undefined' ?  JSON.parse(data) : eval('('+data+')');
                        if(doc.status == 'OK'){
                            $('#gcoo1').val(doc.results[0].geometry.location.lat);
                            $('#gcoo2').val(doc.results[0].geometry.location.lng);
                            $('#gaddress').val(doc.results[0].formatted_address);
                        }else{
                            alert(data);
                        }
                        return true;
                    },
                    error:function (xhr, textstatus, thrownError){
                        alert(textstatus);
                        return false;
                    }
                });

            }
        </script>

    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>
        <fieldset style="float:left;">
            <legend>Modifier contact</legend>
            <form action="annuaireContactDetail.php?siteID=<?php echo $id; ?>" method="post" onsubmit="return geocode()">
                <p id="pErreur" align="center"><?php echo $erreur; ?></p>
                <table>
                    <tr><td  align="right"><label for="Valide">Valide : </label></td><td><input type="radio" name="Valide" value="1" <?php if($club['Valide']) echo 'checked="checked"';?> /><span>oui</span><input type="radio" name="Valide" value="0" <?php if(!$club['Valide']) echo 'checked="checked"';?> /><span >non</span></td></tr>
                    <tr><td align="right"><label for="club">Club : </label></td><td><input type="text" name="club" value="<?php echo str_replace("\\","",$club['club']); ?>" /></td></tr>
                    <tr><td align="right"><label for="responsable">Responsable : </label></td><td><input type="text" name="responsable" value="<?php echo str_replace("\\","",$club['responsable']); ?>" /></td></tr>
                    <tr><td align="right"><label for="telephone">T&eacute;l&eacute;phone : </label></td><td><input type="text" name="telephone" value="<?php echo $club['telephone']; ?>" /></td></tr>
                    <tr><td align="right"><label for="mel">mail : </label></td><td><input type="text" name="mel" value="<?php echo $club['mel']; ?>" /> <?php if($club['mel'] != "") { ?><a href="mailto:<?php echo $club['mel']; ?>?subject=<?php echo 'R&eacute;f&eacute;rencement sur alljudo.net'; ?>&body=<?php echo str_replace('
',"%0A","Vous venez d&#39;&ecirc;tre r&eacute;f&eacute;renc&eacute; sur alljudo.net, et je serais &agrave; mon tour ravi de figurer sur votre site.
        
Je vous invite &eacute;galement &agrave; d&eacute;couvrir notre offre de partenariat gratuit : /sites-partenaires.php

Merci d&#39;avance, cordialement

Laurent MATHIEU
shin-ji communication
04.74.21.63.26
06.82.94.74.12
http://www.shin-ji.com
 "); ?>">envoyer un mail &agrave; <?php echo $club['mel']; ?></a><?php } ?></td></tr>
                    <tr><td align="right"><label for="site">Site : </label></td><td><input type="text" name="site" value="<?php echo $club['site']; ?>" /></td></tr>
                    <tr><td align="right">Description du site : </td><td><textarea name="description" rows="4" cols="40"><?php echo str_replace("\\","",$club['description']) ?></textarea></td></tr>
                    <tr><td align="right">Adresse : </td><td><input type="text" id="adresse" name="adresse" value="<?php echo str_replace("\\","",$club['adresse']) ?>" /></td></tr>
                    <tr><td align="right">Code Postal : </td><td><input type="text" name="CP" value="<?php echo $club['CP'] ?>" /></td></tr>
                    <tr><td align="right">Ville : </td><td><input type="text" name="ville" id="ville" value="<?php echo str_replace("\\","",$club['ville']) ?>" /></td></tr>
                    <tr><td align="right">Pays : </td><td>
                            <select id="pays" name="pays">
                                <?php foreach($paysNomTab as $pays):?>
                                <option <?php if($club['pays'] == $pays['Abreviation']) echo 'selected="selected"'; ?> value="<?php echo $pays['Abreviation'] ?>"><?php echo $pays['NomPays'] ?></option>
                                <?php endforeach; ?>
                            </select></td></tr>
                    <input type="hidden" id="gcoo1" name="gcoo1" value="" /><input type="hidden" id="gcoo2" name="gcoo2" value="" /><input type="hidden" id="gaddress" name="gaddress" value="" />
                    <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifer" /></td></tr>
                </table>
            </form>
        </fieldset>

    </body>
    <!-- InstanceEnd --></html>
