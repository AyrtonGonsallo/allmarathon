<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {

    session_start();

}

//verif de validiter session

if (!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {

    header('Location: login.php');

    exit();

}



if ($_SESSION['admin'] == false) {

    header('Location: login.php');

    exit();

}



require_once '../database/connexion.php';



$adType = (isset($_GET['type'])) ? addslashes($_GET['type']) : "banniere160x600";

$ads = array("banniere160x600", "banniere300x250", "banniere336x280", "banniere728x90", "banniere768x90", "banniere300x60", "banniereBackground", "bannieremobile", "banniereNewsletter");

if (!in_array($adType,$ads ))

    exit("type incompatible");



$erreur = "";

if (isset($_POST['sub'])) {



     



    if ($_POST['nom'] == "")

        exit("Erreur nom.<br />");

    if ($erreur == "") {

      

        // if (!in_array($_GET['type'], array("banniereNewsletter","bannieremobile"))) {

          

            

        // } else {



            try {

                $destination_path = "../images/pubs/";

                if(!empty($_FILES['fileToUpload']['name'])){

                    $fileinfo = $_FILES['fileToUpload'];

                    $fichierSource = $fileinfo['tmp_name'];

                    $fichierName   = $fileinfo['name'];

                    if ( $fileinfo['error']) {

                          switch ( $fileinfo['error']){

                                   case 1: // UPLOAD_ERR_INI_SIZE

                                    echo "'Le fichier ".$fichierName." d�passe la limite autoris�e par le serveur (fichier php.ini) !'<br />";

                                   break;

                                   case 2: // UPLOAD_ERR_FORM_SIZE

                                    echo  "'Le fichier ".$fichierName." d�passe la limite autoris�e dans le formulaire HTML !'<br />";

                                   break;

                                   case 3: // UPLOAD_ERR_PARTIAL

                                     echo"'L'envoi du fichier ".$fichierName." a �t� interrompu pendant le transfert !'<br />";

                                   break;

                                   case 4: // UPLOAD_ERR_NO_FILE

                                    echo "'Le fichier ".$fichierName." que vous avez envoy� a une taille nulle !'<br />";

                                   break;

                          }

                    }else{

                        if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {

                            $result = "Fichier ".$fichierName." corectement envoy� !";

                        }else{

                            echo "Erreur phase finale fichier ".$fichierName."<br />";

                        }

                        }



                    }



                if ($_GET['type'] == "banniereNewsletter") {

                    $req = $bdd->prepare("INSERT INTO banniereNewsletter (nom,text,actif,image,url/*,restriction*/) VALUES (:nom,:text,:actif,:image,:url/*,:restriction*/)");

                    $req->bindValue('text', $_POST['text'], PDO::PARAM_STR);

                } 

                else if (!in_array($_GET['type'], array("banniereNewsletter","bannieremobile"))) {

                    $req = $bdd->prepare("INSERT INTO $adType (nom,code,image,url,actif,restriction) VALUES (:nom,:code,:image,:url,:actif,:restriction)");

                    $req->bindValue('code', $_POST['code'], PDO::PARAM_STR);

                    $req->bindValue('restriction', @implode('|', $_POST['restriction']), PDO::PARAM_STR);

                   



                }

                else {

                    $req = $bdd->prepare("INSERT INTO bannieremobile (nom,actif,image,url/*,restriction*/) VALUES (:nom,:actif,:image,:url/*,:restriction*/)");

                }



                

                  

                    

                

                



                $req->bindValue('nom', $_POST['nom'], PDO::PARAM_STR);

                $req->bindValue('actif', $_POST['actif'], PDO::PARAM_STR);

                $req->bindValue('image',$_FILES["fileToUpload"]["name"], PDO::PARAM_STR);

                $req->bindValue('url', $_POST['url'], PDO::PARAM_STR);

                //$req->bindValue('restriction', @implode('|', $_POST['restriction']), PDO::PARAM_STR);

                $req->execute();

                $msg = 'ajout avec succes';

            } catch (Exception $e) {

                die('Erreur : ' . $e->getMessage());

            }

        // }

    }

}


$erreur = "";

if (isset($_POST['restsub']) && (!in_array($_GET['type'], array("banniereNewsletter","bannieremobile")))) {

    $i = 0;

    while (isset($_POST['pubid' . $i])) {



        try {

            $req = $bdd->prepare("UPDATE $adType SET restriction=:restriction WHERE ID=:ID LIMIT 1");



            $req->bindValue('ID', $_POST['pubid' . $i], PDO::PARAM_INT);

            $req->bindValue('restriction', @implode('|', $_POST['restriction' . $i]), PDO::PARAM_STR);

            $req->execute();

            $i++;

        } catch (Exception $e) {

            die('Erreur : ' . $e->getMessage());

        }

    }

}



try {



    $req1 = $bdd->prepare("SELECT * FROM $adType ORDER BY id DESC");

    $req1->execute();

    $resultTechnique = array();

    while ($row = $req1->fetch(PDO::FETCH_ASSOC)) {

        array_push($resultTechnique, $row);

    }

} catch (Exception $e) {

    die('Erreur : ' . $e->getMessage());

}



if (!isset($_GET["type"])) {

    $_GET["type"] = "vide";

}

?>

<!DOCTYPE html

    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->



<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

    <!-- InstanceBeginEditable name="doctitle" -->

    <title>allmarathon admin</title>

</head>



<body>

    <?php require_once "menuAdmin.php"; ?>

    <fieldset style="float:left;">

        <br />

        <a href="pub.php?type=banniereBackground">Bannière Background</a>

        <a href="pub.php?type=banniere160x600">Bannière 300x600-1</a>

        <a href="pub.php?type=banniere300x250">Bannière 300x600-2</a>

        <a href="pub.php?type=banniere728x90">Bannière 970x250-1</a>

        <a href="pub.php?type=banniere768x90">Bannière 970x250-2</a>

        <a href="pub.php?type=bannieremobile">Bannière Mobile</a>

        <a href="pub.php?type=banniereNewsletter">Bannière Newsletter</a>

        <br /><br /><br />

        <legend>Liste des publicit&eacute; <?php echo $adType; ?></legend>

        <form action="pub.php?type=<?php echo $adType; ?>" method="post">

            <?php if(!in_array($_GET['type'], array("banniereNewsletter","bannieremobile")) || $_GET["type"] == "vide" ) { ?>

            <table class="tab1">

                <thead>

                    <tr>

                        <?php /* if(is_array($structure))

  foreach($structure as $key => $detail){

  if(!in_array($key, $blackListAffichage)){ //si le chamathlètet pathlètes la blackList
athlèteathlète
  echo "<th>$key</th>";

  }

  } */ ?>

                        <th>ID</th>

                        <th>Nom</th>

                        <th>Actif/Inactif</th>

                        <th>Action</th>

                        <th colspan="11">Restriction</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td colspan="4"></td>

                        <td>Accueil</td>

                        <td>Actualit&eacute;</td>

                        <td>R&eacute;sultats</td>

                        <td>Calendrier</td>

                        <td>Vid&eacute;os</td>

                        <td>Photos</td>

                        <td>Marathonkas</td>

                        <td>Technique</td>

                        <td>savoirs</td>

                        <td>Annuaires</td>

                        <td>D&eacute;tente</td>

                    </tr>

                    <?php

$i = 0;

foreach ($resultTechnique as $j) {



    echo '<tr>';

    echo "<td>" . $j['ID'] . "</td>";

    echo "<td>" . $j['nom'] . "</td>";

    echo "<td>" . $j['actif'] . "</td>";



    $temp = explode("|", $j['restriction']);

    $restriction = array();

    foreach ($temp as $val)

        $restriction[$val] = true;

    echo '<input style="display:none;" type="text" name="pubid' . $i . '" id="" value="' . $j['ID'] . '" />';

    echo '<td><a href="pubDetail.php?pubID=' . $j['ID'] . '&type=' . $adType . '" ><img style="cursor:pointer;border:0;" src="../images/edit.png" title="detail" /></a>

                             <img style="cursor:pointer;" src="../images/supprimer.png" alt="supprimer" title="supprimer"  onclick="if(confirm(\'Voulez vous vraiment supprimer ' . $j['nom'] . ' ?\')) { location.href=\'supprimerPub.php?pubID=' . $j['ID'] . '&type=' . $adType . '\';} else { return 0;}" /></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="accueil"', (isset($restriction['accueil'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="actualite"', (isset($restriction['actualite'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="resultats"', (isset($restriction['resultats'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="calendrier"', (isset($restriction['calendrier'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="videos"', (isset($restriction['videos'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="photos"', (isset($restriction['photos'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="marathonkas"', (isset($restriction['marathonkas'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="technique"', (isset($restriction['technique'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="savoirs"', (isset($restriction['savoirs'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="annuaires"', (isset($restriction['annuaires'])) ? ' checked="checked"' : "", '/></td>'

    , '<td align="center"><input type="checkbox" name="restriction' . $i . '[]" value="detente"', (isset($restriction['detente'])) ? ' checked="checked"' : "", '/></td>'

    , '</tr>';

    $i++;

}

?>

                    <tr>

                        <td align="right" colspan="15"><input type="submit" value="modifier" name="restsub" /></td>

                    </tr>

                </tbody>

            </table>

            <?php }else { ?>

            <table class="tab1">

                <thead>

                    <tr>

                        <?php /* if(is_array($structure))

  foreach($structure as $key => $detail){

  if(!in_array($key, $blackListAffichage)){ //si le champ n'est pas dans la blackList

  echo "<th>$key</th>";

  }

  } */ ?>

                        <th>ID</th>

                        <th>Nom</th>

                        <th>Actif/Inactif</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

$i = 0;

foreach ($resultTechnique as $j) {



    echo '<tr>';

    echo "<td>" . $j['ID'] . "</td>";

    echo "<td>" . $j['nom'] . "</td>";

    echo "<td>" . $j['actif'] . "</td>";

    echo '<input style="display:none;" type="text" name="pubid' . $i . '" id="" value="' . $j['ID'] . '" />';

    echo '<td><a href="pubDetail.php?pubID=' . $j['ID'] . '&type=' . $adType . '" ><img style="cursor:pointer;border:0;" src="../images/edit.png" title="detail" /></a>

                             <img style="cursor:pointer;" src="../images/supprimer.png" alt="supprimer" title="supprimer"  onclick="if(confirm(\'Voulez vous vraiment supprimer ' . $j['nom'] . ' ?\')) { location.href=\'supprimerPub.php?pubID=' . $j['ID'] . '&type=' . $adType . '\';} else { return 0;}" /></td>';

    $i++;

}

?>

                    <tr>

                        <td align="right" colspan="15"><input type="submit" value="modifier" name="restsub" /></td>

                    </tr>

                </tbody>

            </table>

            <?php } ?>

        </form>

    </fieldset>

    <?php if (isset($_GET['type']) && (!in_array($_GET['type'], array("banniereNewsletter","bannieremobile")))) { ?>

    <fieldset>

        <legend>Nouvelle pub</legend>

        <?php if (isset($_GET['retour'])) {

                                echo '<span style="color:green;">Modification r�ussie</span><br /><br />';

                            } ?>

        <form action="pub.php?type=<?php echo $adType; ?>" method="post" enctype="multipart/form-data">

            <table>

                <?php

    echo ' <tr><td><label for="nom">Nom : </label></td><td><input type="text" name="nom" /></td></tr>';

    echo ' <tr><td><label for="url">Url : </label></td><td><input type="text" name="url" /></td></tr>';

    echo ' <tr><td><label for="image">Image : </label></td><td><input type="file" name="fileToUpload" id="fileToUpload"/></td></tr>';

    echo ' <tr><td><label for="code">Code : </label></td><td><textarea name="code" id="code" cols="50" rows="8"></textarea></td></tr>';



    /* foreach($structure as $key => $detail){

      if(!in_array($key, $blackList)){ //si le champ n'est pas dans la blackList

      $readOnly = (stripos($detail['flag'], "primary_key"))?true:false;

      $null = (stripos($detail['flag'], "not_null"))?false:true;

      if($detail['type']!="blob"){

      echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><input name="'.$key.'"';

      echo ($readOnly)?'readonly="readonly"':"";

      echo 'size="'.$detail['len'].'" value="';

      //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];

      echo '"/></td></tr>';

      }else{

      echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><textarea cols="50" rows="8" name="'.$key.'"';

      echo ($readOnly)?' readonly="readonly" >':" >";

      //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];

      echo '</textarea></td></tr>';

      }

      }

      } */

    ?>

                <tr>

                    <td>actif : </td>

                    <td><label for="actif1">oui</label><input checked="checked" type="radio" name="actif" id="actif1"

                            value="actif" /> <label for="actif2">non</label><input type="radio" name="actif" id="actif2"

                            value="inactif" /></td>

                </tr>

                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                    <table>

                        <tr>

                            <td> Restrictions:</td>

                            <td>

                                <table>

                                    <tr>

                                        <td><label for="tous"><i>tous</i></label></td>

                                        <td><input id="tous" type="checkbox" onclick="checkAll();" /></td>

                                    </tr>

                                    <tr>

                                        <td><label for="accueil">Accueil</label></td>

                                        <td><input id="accueil" type="checkbox" name="restriction[]" value="accueil" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="actualite">Actualit&eacute;</label></td>

                                        <td><input id="actualite" type="checkbox" name="restriction[]"

                                                value="actualite" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="resultats">R&eacute;sultats</label></td>

                                        <td><input id="resultats" type="checkbox" name="restriction[]"

                                                value="resultats" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="calendrier">Calendrier</label></td>

                                        <td><input id="calendrier" type="checkbox" name="restriction[]"

                                                value="calendrier" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="videos">Vid&eacute;os</label></td>

                                        <td><input id="videos" type="checkbox" name="restriction[]" value="videos" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="photos">Photos</label></td>

                                        <td><input id="photos" type="checkbox" name="restriction[]" value="photos" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="marathonkas">Marathonkas</label></td>

                                        <td><input id="marathonkas" type="checkbox" name="restriction[]" value="marathonkas" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="technique">Technique</label></td>

                                        <td><input id="technique" type="checkbox" name="restriction[]"

                                                value="technique" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="savoirs">Savoirs</label></td>

                                        <td><input id="savoirs" type="checkbox" name="restriction[]" value="savoirs" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="annuaires">Annuaires</label></td>

                                        <td><input id="annuaires" type="checkbox" name="restriction[]"

                                                value="annuaires" />

                                        </td>

                                    </tr>

                                    <tr>

                                        <td><label for="detente">D&eacute;tente</label></td>

                                        <td><input id="detente" type="checkbox" name="restriction[]" value="detente" />

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>

                    </table>

                </tr>

            </table>

            <div><input type="submit" name="sub" value="Cr&eacute;er" /></div>

        </form>

    </fieldset>

    <?php } else { ?>

    <fieldset>

        <legend>Nouvelle pub</legend>

        <?php if (isset($_GET['retour'])) {

        echo '<span style="color:green;">Modification r�ussie</span><br /><br />';

    } ?>

        <form action="pub.php?type=<?php echo $adType; ?>" method="post" enctype="multipart/form-data">

            <table>

                <?php

    echo ' <tr><td><label for="nom">Nom : </label></td><td><input type="text" name="nom" /></td></tr>';

    echo ' <tr><td><label for="image">Image : </label></td><td><input type="file" name="fileToUpload" id="fileToUpload"/></td></tr>';

    echo ' <tr><td><label for="url">Url : </label></td><td><input type="text" name="url" /></td></tr>';

    if($_GET['type'] == "banniereNewsletter")

    echo ' <tr><td><label for="text">Text : </label></td><td><textarea name="text" id="text" cols="50" rows="8"></textarea></td></tr>';



    /* foreach($structure as $key => $detail){

      if(!in_array($key, $blackList)){ //si le champ n'est pas dans la blackList

      $readOnly = (stripos($detail['flag'], "primary_key"))?true:false;

      $null = (stripos($detail['flag'], "not_null"))?false:true;

      if($detail['type']!="blob"){

      echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><input name="'.$key.'"';

      echo ($readOnly)?'readonly="readonly"':"";

      echo 'size="'.$detail['len'].'" value="';

      //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];

      echo '"/></td></tr>';

      }else{

      echo '<tr><td><label for="'.$key.'">'.$key.' : </label></td><td><textarea cols="50" rows="8" name="'.$key.'"';

      echo ($readOnly)?' readonly="readonly" >':" >";

      //echo ($null && $joueur[$key]=="")?"null":$joueur[$key];

      echo '</textarea></td></tr>';

      }

      }

      } */

    ?>

                <tr>

                    <td>actif : </td>

                    <td><label for="actif1">oui</label><input checked="checked" type="radio" name="actif" id="actif1"

                            value="actif" /> <label for="actif2">non</label><input type="radio" name="actif" id="actif2"

                            value="inactif" /></td>

                </tr>

                <tr>

                    <td></td>

                    <td></td>

                </tr>

                <tr>

                </tr>

            </table>

            <div><input type="submit" name="sub" value="Cr&eacute;er" /></div>

        </form>

    </fieldset>

    <?php } ?>

</body>



</html>