<?php
session_start();
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
{
    header('Location: login.php');
    exit();
}
$techniqueID = (isset ($_GET['techniqueID']))?addslashes($_GET['techniqueID']):exit("error");

require_once '../database/connection.php';

$fieldList = mysql_list_fields("c1_allmarathon", "technique");
$blackListAffichage = array("Conseils");
$blackList = array();

$numFields = mysql_num_fields($fieldList);
for ($i = 0 ; $i < $numFields ; $i++){
    $name = mysql_field_name($fieldList, $i);
    $structure[$name]['type']=mysql_field_type($fieldList, $i);
    $structure[$name]['len']=mysql_field_len($fieldList, $i);
    $structure[$name]['flag'] = mysql_field_flags($fieldList, $i);
}

$erreur = "";
if( isset($_POST['sub']) ){
    if($_POST['nom']=="")
    {$erreur .= "Erreur nom.<br />";}
    if($erreur == ""){
        $query2    = sprintf("UPDATE technique SET Nom='%s',Famille='%s',Presentation='%s',Conseils='%s',Presentation_en='%s',Conseils_en='%s' WHERE ID='%s'"
            ,mysql_real_escape_string($_POST['nom'])
            ,mysql_real_escape_string($_POST['famille'])
            ,mysql_real_escape_string($_POST['presentation'])
            ,mysql_real_escape_string($_POST['conseils'])
            ,mysql_real_escape_string($_POST['presentation_en'])
            ,mysql_real_escape_string($_POST['conseils_en'])
            ,mysql_real_escape_string($techniqueID));
        $result2   = mysql_query($query2) or die(mysql_error());
        $retour = true;
    }
    else {
        echo $erreur;
    }
}

$queryTechnique  = sprintf("SELECT * FROM technique WHERE ID='%s'",mysql_real_escape_string($techniqueID));
$resultTechnique = mysql_query($queryTechnique);
$technique       = mysql_fetch_array($resultTechnique);


//echo "<pre>";
//echo print_r($structure);
//echo "</pre>";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>


    <link href="../styles/annuaire2009.css" rel="stylesheet" type="text/css" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function addCompletion(str,index){
            tab     = str.split(':');
            idChamp  = tab[0];
            name     = tab[1];
            nameLink = tab[2];
            document.getElementById("autocomp"+index).style.display = "none";
            $('#temp1').val(''+name+'');
            $('#result').html('<input type="hidden" name="j_idchamp" value="'+idChamp+'"/><input type="hidden" name="j_name" value="'+name+'"/>');
        }

        $(document).ready(function(){
            $('#temp1').keyup(function() {
                if($(this).val().length > 3)
                    $.get('resultatAutoCompletionLien.php',{id: 1, str: $(this).val()},function(data){
                        $('#autocomp1').show();
                        $('#autocomp1').html(data);
                    });
            });

            $('#cut').click(function(){
                $('#temp1').select();

            });
        });
    </script>

    <style>
        .autocomp
        {
            position:absolute;
            width:142px;
            background-color:white;
            border:solid 1px black;
            padding:2px;
            text-align:center;
            z-index:100;
        }
        .autocomp a
        {
            color:blue;
            display:block;
            cursor:pointer;
        }
        .autocomp a:hover
        {
            color:white;
            background-color:#6666AA;
        }
        .autocomp a:focus
        {
            color:white;
            background-color:#6666AA;
        }

    </style>
    <!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset>
    <legend>Modifier technique</legend>
    <?php
    if(isset($_POST['sup_spec'])){
        for($i=0,$n=count($_POST["j_sup"]);$i<$n;$i++){
            $supprimer_spec="DELETE FROM technique_specialiste WHERE id_champion='".intval($_POST["j_sup"][$i])."' ";
            $res_supprimer_spec = mysql_query($supprimer_spec);
        }
        echo '<meta http-equiv="Refresh" content="0;URL=techniqueDetail.php?techniqueID='.$techniqueID.'">';
    }
    elseif(isset($_POST['ajout_special'])){
        $r_techspec="INSERT INTO technique_specialiste VALUES('','".$techniqueID."','".$_POST['j_idchamp']."','".$_POST['j_name']."')";
        $res_techspec=mysql_query($r_techspec);
        echo '<meta http-equiv="Refresh" content="0;URL=techniqueDetail.php?techniqueID='.$techniqueID.'">';
    }

    elseif(isset($_POST['special'])){
        echo "<h1>Ajouter un spécialiste</h1>";
        echo '<form method="post" action=""><table>
							<tr><td><label>Nom du champion : </label></td><td>
								<div id="autoCompChamp1">
									<input autocomplete="off" type="text" id="temp1" value="" />
									<div id="autocomp1" style="display:none;" class="autocomp"></div>
									<input style="display:none;" id="champion1" name="Champion_id" type="text" value="" />
									<div id="result" style="display: inline;"></div>
								</div>
								

							</td></tr>
							<tr><td>&nbsp;</td><td><input type="submit" name="ajout_special" value="ajouter"/></td></tr>
							</form></table>';
    }

    elseif(isset($retour)){ echo '<span style="color:green;">Modification réussie</span><br /><br />'; }else{?>
    <form action="" method="post">
        <table >
            <?php
            echo ' <tr><td><label for="nom">Nom : </label></td><td><input type="text" id="nom" name="nom" value=';
            echo $technique[1];

            echo ' /></td></tr>';
            echo ' <tr><td><label for="famille">Famille : </label></td><td><input type="text" id="famille" name="famille" value=';
            echo $technique[2];
            echo ' /></td></tr>';

            echo ' <tr><td><label for="presentation">Pr&eacute;sentation : </label></td><td><textarea name="presentation" id="presentation" cols="50" rows="8" >';
            echo $technique[3];
            echo '</textarea></td></tr>';
            echo ' <tr><td><label for="conseil">Conseil : </label></td><td><textarea name="conseil" id="conseil" cols="50" rows="8" >';
            echo $technique[5];
            echo '</textarea></td></tr>';

            /*  foreach($structure as $key => $detail){
                  if(!in_array($key, $blackList)){ //si le champ n'est pas dans la blackList
                      $readOnly = (stripos($detail['flag'], "primary_key"))?true:false;
                      $null = (stripos($detail['flag'], "not_null"))?false:true;
                      if($detail['type']!="blob"){
                          echo '<tr><td><label for="'.$key.'">'.$key.'</label></td><td><input name="'.$key.'"';
                          echo ($readOnly)?'readonly="readonly"':"";
                          echo 'size="'.$detail['len'].'" value="';
                          echo ($null && $technique[$key]=="")?"null":$technique[$key];
                          echo '"/></td></tr>';
                      }else{
                          echo '<tr><td><label for="'.$key.'">'.$key.'</label></td><td><textarea cols="50" rows="8" name="'.$key.'"';
                          echo ($readOnly)?' readonly="readonly" >':" >";
                          echo ($null && $technique[$key]=="")?"null":$technique[$key];
                          echo '</textarea></td></tr>';
                      }
                  }
              }*/?>
        </table>
        <?php
        $r_spec="select * from technique_specialiste where id_technique like '".$techniqueID."' order by nom_champion";
        $res_spec=mysql_query($r_spec);
        if(mysql_num_rows($res_spec)!=0){
            echo '<h2>Les Sp&eacute;cialistes:</h2>';
            echo '<table>';
            while($row_spec=mysql_fetch_array($res_spec)){
                echo '<tr><td>'.$row_spec['nom_champion'].'</td><td><input type="checkbox" name="j_sup[]" value="'.$row_spec['id_champion'].'"/></td></tr>';
            }
            echo '</table><br/>';
        }
        ?>
        <div ><input type="submit" name="special" value="Ajouter des sp&eacute;cialistes"/><?php if(mysql_num_rows($res_spec)!=0){echo '<input type="submit" name="sup_spec" value="Supprimer les sp&eacute;cialistes"/>';} ?><input type="submit" name="sub" value="Modifier"/></div>
    </form><div><?php } ?>
</fieldset>
</body>
</html>