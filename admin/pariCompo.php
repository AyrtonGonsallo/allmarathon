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

$pariID = (isset($_GET['pariID']))?(int)$_GET['pariID']:exit('error');

require_once '../database/connexion.php';

$erreur = "";


if( isset($_POST['sub_comp'])) {
    if($_POST['poid'] == "")
        $erreur = "Erreur poid";
    if($erreur == "" ) {
         try {
        $req = $bdd->prepare("INSERT INTO pari_composition (sexe,poid,date,pari_id) VALUES (:sexe,:poid,:date,:pari_id)");

        $req->bindValue('sexe',$_POST['sexe'], PDO::PARAM_STR);
        $req->bindValue('poid',$_POST['poid'], PDO::PARAM_STR);
        $req->bindValue('date',$_POST['date'].' '.$_POST['heure'], PDO::PARAM_STR);
        $req->bindValue('pari_id',$pariID, PDO::PARAM_INT);
        $req->execute();

    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
        // $query2    = sprintf("INSERT INTO pari_composition (sexe,poid,date,pari_id) VALUES ('%s','%s','%s','%s')"
        //         ,mysql_real_escape_string($_POST['sexe'])
        //         ,mysql_real_escape_string($_POST['poid'])
        //         ,mysql_real_escape_string($_POST['date'].' '.$_POST['heure'])
        //         ,$pariID);
        // //exit($query2);
        // $result2   = mysql_query($query2) or die(mysql_error());
    }
}

if( isset($_POST['sub_comp_part'])) {
    try {
        $req = $bdd->prepare("UPDATE pari_composition SET participant=:participant, forfait=:forfait WHERE id=:id");

        $req->bindValue('participant',$_POST['Equipe'], PDO::PARAM_STR);
        $req->bindValue('forfait',$_POST['forfait'], PDO::PARAM_STR);
        $req->bindValue('id',$_POST['pari_comp_id'], PDO::PARAM_INT);
        $req->execute();

    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}

try{
  $req1 = $bdd->prepare("SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id WHERE p.id=:ID");
  $req1->bindValue('ID',$pariID, PDO::PARAM_INT);
  $req1->execute();
  $pari=$req1->fetch(PDO::FETCH_ASSOC);

$req2 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
$req2->execute();
$result5= array();
while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result5, $row);
}

$req6 = $bdd->prepare("SELECT * FROM pari_composition WHERE pari_id=:ID ORDER BY id DESC");
$req6->bindValue('ID',$pariID, PDO::PARAM_INT);
$req6->execute();
$result6= array();
while ( $row  = $req6->fetch(PDO::FETCH_ASSOC)) {  
    array_push($result6, $row);
}
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

// $query1    = sprintf('SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id WHERE p.id=%s ',$pariID);
// $result1   = mysql_query($query1);
// $pari      = mysql_fetch_array($result1);

// $query5    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
// $result5   = mysql_query($query5);

// $query6    = sprintf('SELECT * FROM pari_composition WHERE pari_id=%s ORDER BY id DESC',$pariID);
// $result6   = mysql_query($query6);

if($pari['corrige'])
    exit('Ce pari a d�j� �tait corig� ! <a href="pari.php">retour</a>');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="../fonction/ui/js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
        <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
        <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
        <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
        <script type="text/javascript" src="../script/ajax.js" ></script>
        <title>allmarathon admin</title>

        <script type="text/javascript">
            function addName(index){
                first = document.getElementById('Equipe'+index).value == "";
                vide  = document.getElementById('autocomp'+index).value == "";
                if(!vide){
                    document.getElementById('Equipe'+index).value += document.getElementById('autocomp'+index).value+";\n";
                    document.getElementById('autocomp'+index).value = "";
                    document.getElementById('comp'+index).style.display = "none";
                }

            }

            function autoCompletion(index){
                if(document.getElementById('autocomp'+index).value.length > 3){
                    document.getElementById('comp'+index).style.display = "";
                    document.getElementById('comp'+index).innerHTML = ajaxCollector('resultatAutoCompletion.php?id='+index+'&str='+document.getElementById('autocomp'+index).value);
                }else{
                    document.getElementById('comp'+index).style.display = "none";
                }
            }

            function addCompletion(str,index){
                document.getElementById('autocomp'+index).value = str;
                document.getElementById('comp'+index).style.display = "none";
            }


            $(function() {
                $("#accordion").accordion();
            });




        </script>
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>


        <fieldset>
            <legend>Composition pari</legend>
            <fieldset>
                <legend>Upload CSV</legend>
                <br />

                <a href="modelCsvPari.php">telecharger le model</a>

                <p>Envoie fichier r�sultat</p>

                <form action="uploadCsvPari.php?pariID=<?php echo $pariID;?>" method="post" enctype="multipart/form-data" name="form1">
                    <p><input type="file" name="file" /></p>
                    <input type="submit" name="Submit" value="Envoyer" />
                </form>
                <br />
            </fieldset>
            <fieldset>
                <legend>Nouvel categorie</legend>
                <b style="color:red;"><?php echo $erreur; ?></b>
                <form method="post" action="">
                    Sexe : <select name="sexe"><option value="M">Homme</option><option value="F">Femme</option></select>
                    Categorie : <input name="poid" size="4" />
                    Jour :
                    <select name="date">
                        <?php
                        $timestamp_fin = mktime(0,0,0,substr($pari['date_fin'], 5, 2),substr($pari['date_fin'], 8, 2),substr($pari['date_fin'], 0, 4));
                        $timestamp_debut = mktime(0,0,0,substr($pari['date_debut'], 5, 2),substr($pari['date_debut'], 8, 2),substr($pari['date_debut'], 0, 4));
                        $i = 1;
                        do {
                            echo '<option value="'.date('Y-m-d',$timestamp_debut).'">'.date('Y-m-d',$timestamp_debut).'</option>';
                            $timestamp_debut = mktime(0,0,0,substr($pari['date_debut'], 5, 2),substr($pari['date_debut'], 8, 2) + $i,substr($pari['date_debut'], 0, 4));
                            $i++;
                        }while($timestamp_debut <= $timestamp_fin)
                        ?>
                    </select>
                    Heure:
                    <select name="heure">
                        <option value="01:00">01:00</option>
                        <option value="02:00">02:00</option>
                        <option value="03:00">03:00</option>
                        <option value="04:00">04:00</option>
                        <option value="05:00">05:00</option>
                        <option value="06:00">06:00</option>
                        <option value="07:00">07:00</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                    </select>
                    <input type="submit" name="sub_comp" value="nouvelle cat" />
                </form>
            </fieldset>
            <div id="accordion">

                <?php
                $i = 1;
                
             //    foreach ($result6 as $compo) {
                 
             //     print_r(chop($compo['forfait'],";"));
             // }
             // die;
               

                foreach ($result6 as $compo) {

                    $compos   = explode(";", chop($compo['participant'],";\n"));
                    $forfaits = explode(";", chop($compo['forfait'],";\n"));
                    ?>
                <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Composition : <?php echo $compo['poid'].' '.$compo['sexe'].' '.$compo['date'] ?></h3>
                <div>
                    <div style="float:right;">Supprimer <img style="cursor:pointer;" src="../images/supprimer.png" alt="supprimer" title="supprimer"  onclick="if(confirm('Voulez vous vraiment supprimer ?')) { location.href='supprimerCompo.php?pariID=<?php echo $compo['pari_id'] ?>&compoID=<?php echo $compo['id'] ?>';} else { return 0;}" /></div>
                    <form action="" method="post">

                        <table>
                            <thead>
                                <tr>
                                    <th>Entrez le noms ici :</th>
                                    <th>Composition</th>
                                    <th>Forfait</th>
                                </tr>
                            </thead>
                            <tr>
                                <td valign="top" ><input type="hidden" name="pari_comp_id" value="<?php echo $compo['id']?>" /><input type="text" autocomplete="off" name="autocomp" id="autocomp<?php echo $i ?>" onkeyup="autoCompletion(<?php echo $i ?>);"/> <a style="cursor:pointer;" onclick="addName(<?php echo $i ?>)">add</a> </td>
                                <td rowspan="2" valign="top"><textarea name="Equipe" id="Equipe<?php echo $i ?>" cols="30" rows="10" ><?php echo $compo['participant']?></textarea></td>
                                <td rowspan="2" valign="top"><textarea name="forfait" cols="30" rows="10" ><?php echo $compo['forfait']?></textarea></td>
                                <td valign="top"><input type="submit" name="sub_comp_part" value="sauvegarder" /></td>
                                <td rowspan="2" valign="top" style="font-size:0.8em;"> recap participants :<br />
                                        <?php
                                       
                                        foreach($compos as $athlète) {
                                                $tab  = explode(":", $athlète);
                                                if(trim($tab[0])!="")
                                                {
                                                    $id   = $tab[0];
                                                    $name = $tab[1];
                                                    echo '<a target="blank" href="../athlète-'.$id.'.html">'.$name.'</a><br />';
                                                }
                                                    
                                                }
                                               
                                        
                                        ?>

                                </td>
                                <td rowspan="2" valign="top" style="font-size:0.8em;"> recap forfait :<br />
                                        <?php
                                        // if(sizeof($forfaits)!=1){
                                        // print_r($forfaits);
                                        // die;
                                        foreach($forfaits as $athlète) {
                                            
                                                $tab  = explode(":", $athlète);
                                                // print_r($tab);
                                                if(trim($tab[0])!="")
                                                {

                                                    $id   = $tab[0];
                                                    $name = $tab[1];
                                                    echo '<a target="blank" href="../athlète-'.$id.'.html">'.$name.'</a><br />';
                                                }
                                            }
                                            
                                        // }
                                        ?>

                                </td>
                            </tr>

                            <tr><td valign="top"><div align="center" style="display:none;" class="comp" id="comp<?php echo $i ?>"></div></td></tr>
                        </table></form>

                </div>
                    <?php
                    $i++;
                }?>
            </div>
        </fieldset>

    </body>
</html>


