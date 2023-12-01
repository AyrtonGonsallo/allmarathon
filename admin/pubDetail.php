<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

    session_start();

    //verif de validiter session

    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))

    {

		header('Location: login.php');

                exit();

    }



    require_once '../database/connexion.php';

$pubID  = (isset($_GET['pubID']))?$_GET['pubID']:exit("error ID");

$adType = (isset($_GET['type']))?addslashes($_GET['type']):exit("error type");

if(!in_array($adType , array("banniere160x600","banniere300x250","banniere336x280","banniere728x90","banniere768x90","banniere300x60","banniereBackground","bannieremobile","banniereNewsletter")))

exit("type incompatible");



    $erreur = "";

    if( isset($_POST['sub']) ){

        if($_POST['nom']=="")

            $erreur .= "Erreur nom.<br />";

        if($erreur == ""){

            if (in_array($_GET['type'], array("banniereNewsletter","bannieremobile"))) {

                try {

                    if ($_GET['type'] == "banniereNewsletter") {

                        $req4 = $bdd->prepare("UPDATE $adType SET nom=:nom,text=:text,actif=:actif,image=:image,url=:url WHERE ID=:ID LIMIT 1");

                        $req4->bindValue('text',$_POST['text'], PDO::PARAM_STR);

                    } 

                    

                    else {

                        $req4 = $bdd->prepare("UPDATE $adType SET nom=:nom,code=:text,actif=:actif,image=:image,url=:url WHERE ID=:ID LIMIT 1");

                        $req4->bindValue('text',$_POST['text'], PDO::PARAM_STR);

                    }

                    

                    

       

                    $req4->bindValue('nom',$_POST['nom'], PDO::PARAM_STR);

                    

                    $req4->bindValue('actif',$_POST['actif'], PDO::PARAM_STR);

                    $req4->bindValue('image', $_POST['image'], PDO::PARAM_STR);

                    $req4->bindValue('url', $_POST['url'], PDO::PARAM_STR);

                    $req4->bindValue('ID',$pubID, PDO::PARAM_INT);

                    $statut=$req4->execute();

                    $retour = true;

                    header('Location: pub.php?type='.$adType);

       

                }

                catch(Exception $e)

                {

                   die('Erreur : ' . $e->getMessage());

               }

            } else {

                try {

                    $req4 = $bdd->prepare("UPDATE $adType SET nom=:nom,code=:code,image=:image,url=:url,actif=:actif,restriction=:restriction WHERE ID=:ID LIMIT 1");

       

                    $req4->bindValue('nom',$_POST['nom'], PDO::PARAM_STR);

                    $req4->bindValue('code',$_POST['code'], PDO::PARAM_STR);

                    $req4->bindValue('image', $_POST['image'], PDO::PARAM_STR);

                    $req4->bindValue('url', $_POST['url'], PDO::PARAM_STR);              
                    $req4->bindValue('actif',$_POST['actif'], PDO::PARAM_STR);

                    $req4->bindValue('restriction',@implode('|',$_POST['restriction']), PDO::PARAM_STR);

                    $req4->bindValue('ID',$pubID, PDO::PARAM_INT);

                    $statut=$req4->execute();

                    $retour = true;

                    header('Location: pub.php?type='.$adType);

       

                }

                catch(Exception $e)

                {

                   die('Erreur : ' . $e->getMessage());

               }

            }

            

        }

    }





 try{



    $req1 = $bdd->prepare("SELECT * FROM $adType WHERE ID=:ID");

    $req1->bindValue('ID',$pubID, PDO::PARAM_INT);

    $req1->execute();

    $pub=$req1->fetch(PDO::FETCH_ASSOC);



}

catch(Exception $e)

{

    die('Erreur : ' . $e->getMessage());

}



// $queryPub  = sprintf("SELECT * FROM $adType WHERE ID='%s'",mysql_real_escape_string($pubID));

// $resultPub = mysql_query($queryPub);

// $pub       = mysql_fetch_array($resultPub);

if (!in_array($_GET['type'], array("banniereNewsletter","bannieremobile"))) 

$temp = explode("|", $pub['restriction']);

else

$temp = array();

$restriction = array();

foreach($temp as $val)

     $restriction[$val] = true;

//echo "<pre>";

//echo print_r($structure);

//echo "</pre>";



?>





<!DOCTYPE html

    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->



<head><meta charset="big5">

    

    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

    <script type="text/javascript">

    function checkAll() {

        var tab = new Array("accueil", "actualite", "resultats", "calendrier", "videos", "photos", "marathonkas",

            "technique", "savoirs", "annuaires", "detente");

        element = document.getElementById("tous");

        id = tab.pop()

        while (id != null) {

            if (box = document.getElementById(id)) {

                if (element.checked === true)

                    box.checked = true;

                else

                    box.checked = false;

            }

            id = tab.pop();

        }

    }

    </script>

    <title>allmarathon admin</title>



</head>



<body>

    <?php require_once "menuAdmin.php"; ?>

    <?php if(!in_array($_GET['type'], array("banniereNewsletter","bannieremobile"))) {?>

    <fieldset>

        <legend>Modifier la pub: <?php echo $pub['nom'];

        echo " (ID: " . $pubID . ")"; ?> </legend>

        <?php if(isset($retour)){ echo '<span style="color:green;">Modification r�ussie</span><br /><br />'; }?>

        <form action="pubDetail.php?pubID=<?php echo $pubID; ?>&type=<?php echo $adType; ?>" method="post">

            <table>

                <?php

echo ' <tr><td><label for="nom">Nom : </label></td><td><input type="text" name="nom" value="';

            echo $pub['nom'];

            echo '" /></td></tr>';

echo' <tr>

                <td></td>

                <td></td>

            </tr>';

            echo ' <tr><td><label for="code">Code : </label></td><td><textarea name="code" id="code" cols="50" rows="8" >';

            echo $pub['code'];

            echo '</textarea></td></tr>';

            echo ' <tr><td><label for="image">Image Url : </label></td><td><input type="text" name="image" value="';

            echo $pub['image'];

            echo '" /></td></tr>';

echo ' <tr><td><label for="url">Url : </label></td><td><input type="text" name="url" value="';

            echo $pub['url'];

            echo '" /></td></tr>';

                                /*foreach($structure as $key => $detail){

                                if(!in_array($key, $blackList)){ //si le champ n'est pas dans la blackList

                                    $readOnly = (stripos($detail['flag'], "primary_key"))?true:false;

                                    $null = (stripos($detail['flag'], "not_null"))?false:true;

                                    if($detail['type']!="blob"){

                                        echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><input name="'.$key.'"';

                                        echo ($readOnly)?'readonly="readonly"':"";

                                        echo 'size="'.$detail['len'].'" value="';

                                        echo ($null && $pub[$key]=="")?"null":str_replace("\\","",$pub[$key]);

                                        echo '"/></td></tr>';

                                    }else{

                                        echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><textarea cols="50" rows="8" name="'.$key.'"';

                                        echo ($readOnly)?' readonly="readonly" >':" >";

                                        echo ($null && $pub[$key]=="")?"null":str_replace("\\","",$pub[$key]);

                                        echo '</textarea></td></tr>';

                                    }

                                }

                         }*/?>



                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                    <td>actif : </td>

                    <td><label for="actif1">oui</label><input checked="checked" type="radio" name="actif" id="actif1"

                            value="actif" <?php if($pub['actif']=="actif") echo 'checked="checked"';?> /> <label

                            for="actif2">non</label><input type="radio" name="actif" id="actif2" value="inactif"

                            <?php if($pub['actif']=="inactif") echo 'checked="checked"';?> /></td>

                </tr>

                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                    <table>

                        <tr>

                            <td>Restrictions :</td>

                            <td>

                                <table>

                                    <tr>

                                        <td><label for="tous"><i>tous</i></label></td>

                                        <td><input id="tous" type="checkbox" onclick="checkAll();" /></td>

                                    </tr>

                                    <tr>

                                        <td><label for="accueil">Accueil</label></td>

                                        <td><input id="accueil" type="checkbox" name="restriction[]" value="accueil"

                                                <?php echo (isset($restriction['accueil'])) ? ' checked="checked"' : "" ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="actualite">Actualit&eacute;</label></td>

                                        <td><input id="actualite" type="checkbox" name="restriction[]" value="actualite"

                                                <?php echo (isset($restriction['actualite'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="resultats">R&eacute;sultats</label></td>

                                        <td><input id="resultats" type="checkbox" name="restriction[]" value="resultats"

                                                <?php echo (isset($restriction['resultats'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="calendrier">Calendrier</label></td>

                                        <td><input id="calendrier" type="checkbox" name="restriction[]"

                                                value="calendrier"

                                                <?php echo (isset($restriction['calendrier'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="videos">Vid&eacute;os</label></td>

                                        <td><input id="videos" type="checkbox" name="restriction[]" value="videos"

                                                <?php echo (isset($restriction['videos'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="photos">Photos</label></td>

                                        <td><input id="photos" type="checkbox" name="restriction[]" value="photos"

                                                <?php echo (isset($restriction['photos'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="marathonkas">Marathonkas</label></td>

                                        <td><input id="marathonkas" type="checkbox" name="restriction[]" value="marathonkas"

                                                <?php echo (isset($restriction['marathonkas'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="technique">Technique</label></td>

                                        <td><input id="technique" type="checkbox" name="restriction[]" value="technique"

                                                <?php echo (isset($restriction['technique'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="savoirs">Savoirs</label></td>

                                        <td><input id="savoirs" type="checkbox" name="restriction[]" value="savoirs"

                                                <?php echo (isset($restriction['savoirs'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="annuaires">Annuaires</label></td>

                                        <td><input id="annuaires" type="checkbox" name="restriction[]" value="annuaires"

                                                <?php echo (isset($restriction['annuaires'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="detente">D&eacute;tente</label></td>

                                        <td><input id="detente" type="checkbox" name="restriction[]" value="detente"

                                                <?php echo (isset($restriction['detente'])) ? ' checked="checked"' : ""; ?> />

                                        </td>

                                    </tr>

                                </table>

                            </td>



                        </tr>

                    </table>

                </tr>

            </table>

            <div><input type="submit" name="sub" value="Modifier" /></div>

        </form>

    </fieldset>

    <?php }else {?>

    <fieldset>

        <legend>Modifier la pub: <?php echo $pub['nom'];

        echo " (ID: " . $pubID . ")"; ?> </legend>

        <?php if(isset($retour)){ echo '<span style="color:green;">Modification r�ussie</span><br /><br />'; }?>

        <form action="pubDetail.php?pubID=<?php echo $pubID; ?>&type=<?php echo $adType; ?>" method="post">

            <table>

                <?php

echo ' <tr><td><label for="nom">Nom : </label></td><td><input type="text" name="nom" value="';

            echo $pub['nom'];

            echo '" /></td></tr>';

echo ' <tr><td><label for="image">Image Url : </label></td><td><input type="text" name="image" value="';

            echo $pub['image'];

            echo '" /></td></tr>';

echo ' <tr><td><label for="url">Url : </label></td><td><input type="text" name="url" value="';

            echo $pub['url'];

            echo '" /></td></tr>';

            echo ' <tr><td><label for="text">Text : </label></td><td><textarea name="text" id="text" cols="50" rows="8" >';

                echo $pub['code'];

                echo '</textarea></td></tr>';

echo' <tr>

                <td></td>

                <td></td>

            </tr>';

            if($_GET['type'] == "banniereNewsletter"){

            echo ' <tr><td><label for="text">Text : </label></td><td><textarea name="text" id="text" cols="50" rows="8" >';

            echo $pub['text'];

            echo '</textarea></td></tr>';}

                                ?>



                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                    <td>actif : </td>

                    <td><label for="actif1">oui</label><input checked="checked" type="radio" name="actif" id="actif1"

                            value="actif" <?php if($pub['actif']=="actif") echo 'checked="checked"';?> /> <label

                            for="actif2">non</label><input type="radio" name="actif" id="actif2" value="inactif"

                            <?php if($pub['actif']=="inactif") echo 'checked="checked"';?> /></td>

                </tr>

                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                    <table>

                        <tr>

                            <td>



                            </td>



                        </tr>

                    </table>

                </tr>

            </table>

            <div><input type="submit" name="sub" value="Modifier" /></div>

        </form>

    </fieldset>

    <?php }?>

</body>



</html>