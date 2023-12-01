<?php
    session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
		header('Location: login.php');
                exit();
    }

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connection.php';

$fieldList = mysql_list_fields("c1_devallmarathon_db", "technique");
$blackListAffichage = array("Conseils");
$blackList = array("ID");

$numFields = mysql_num_fields($fieldList);
for ($i = 0 ; $i < $numFields ; $i++){
    $name = mysql_field_name($fieldList, $i);
    $structure[$name]['type']=mysql_field_type($fieldList, $i);
    $structure[$name]['len']=mysql_field_len($fieldList, $i);
    $structure[$name]['flag'] = mysql_field_flags($fieldList, $i);
}

    $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
        if($erreur == ""){
            $query2    = sprintf("INSERT INTO technique (Nom,Famille,Presentation,Conseils,Presentation_en,Conseils_en) VALUES ('%s','%s','%s','%s','%s','%s')"
                ,mysql_real_escape_string($_POST['Nom'])
                ,mysql_real_escape_string($_POST['Famille'])
                ,mysql_real_escape_string($_POST['Presentation'])
                ,mysql_real_escape_string($_POST['Conseils'])
                ,mysql_real_escape_string($_POST['Presentation_en'])
                ,mysql_real_escape_string($_POST['Conseils_en']));
            $result2   = mysql_query($query2) or die(mysql_error());
        }
    }

$queryTechnique  = sprintf("SELECT * FROM technique ORDER BY id DESC");
$resultTechnique = mysql_query($queryTechnique);


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


<!-- InstanceEndEditable -->
</head>

    <body>
<?php require_once "menuAdmin.php"; ?>
        <fieldset style="float:left;">
        <legend>Liste des technique</legend>
            <table class="tab1">
                    <thead>
                        <tr>
                            <?php
                            foreach($structure as $key => $detail){
                                if(!in_array($key, $blackListAffichage)){ //si le champ n'est pas dans la blackList
                                    echo "<th>$key</th>";
                                }
                            }?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php         while($j = mysql_fetch_array($resultTechnique)){
                echo '<tr>';
                            foreach($structure as $key => $detail){
                                if(!in_array($key, $blackListAffichage)){ //si le champ n'est pas dans la blackList
                                    echo "<td>$j[$key]</td>";
                                }
                            }
               echo '<td><a href="techniqueDetail.php?techniqueID='.$j['ID'].'" ><img src="../images/edit.png" title="detail" /></a>
                        <img style="cursor:pointer;" src="../images/supprimer.png" alt="supprimer" title="supprimer"  onclick="if(confirm(\'Voulez vous vraiment supprimer '.$j['Nom'].' ?\')) { location.href=\'supprimerTechnique.php?techniqueID='.$j['ID'].'\';} else { return 0;}" /></td>',
                '</tr>';
                } ?>
                    </tbody>
                </table>
        </fieldset>
        <fieldset>
                    <legend>Nouvelle technique</legend>
                    <?php if(isset($_GET['retour'])){ echo '<span style="color:green;">Modification r�ussie</span><br /><br />'; }?>
                     <form action="" method="post">
                    <table >
                        <?php
                                foreach($structure as $key => $detail){
                                if(!in_array($key, $blackList)){ //si le champ n'est pas dans la blackList
                                    $readOnly = (stripos($detail['flag'], "primary_key"))?true:false;
                                    $null = (stripos($detail['flag'], "not_null"))?false:true;
                                    if($detail['type']!="blob"){
                                        echo '<tr><td><label for="'.$key.'">'.$key.'</label></td><td><input name="'.$key.'"';
                                        echo ($readOnly)?'readonly="readonly"':"";
                                        echo 'size="'.$detail['len'].'" value="';
                                        //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];
                                        echo '"/></td></tr>';
                                    }else{
                                        echo '<tr><td><label for="'.$key.'">'.$key.'</label></td><td><textarea cols="50" rows="8" name="'.$key.'"';
                                        echo ($readOnly)?' readonly="readonly" >':" >";
                                        //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];
                                        echo '</textarea></td></tr>';
                                    }
                                }
                         }?>
                    </table>
                        <div ><input type="submit" name="sub" value="Cr�er"/></div>
                </form>
                </fieldset>
    </body>
</html>