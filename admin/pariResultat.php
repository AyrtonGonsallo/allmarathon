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

if( isset($_POST['sub_validation_finale'])) {

     try{

        $req6 = $bdd->prepare("SELECT * FROM pari_composition WHERE pari_id=:pari_id ORDER BY id DESC");
        $req6->bindValue('pari_id',$pariID, PDO::PARAM_INT);
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

    // while($comp = mysql_fetch_array($result6)) {
    foreach ($result6 as $comp) {
        $parts_temp = explode(':',$comp['podium_final']);
        $parts = array();
        foreach($parts_temp as $part)
            $parts[] = (int)$part;
        if(sizeof($parts) != 4)
            $erreur .= $comp['id']." : Le podium doit etre compos� de athlètekas <br />";
        if($comp['premier_final'] == "")
            $erreur .= $comp['id']." : Le premier ne peut pas etre vide <br />";
        $resultat[$comp['id']]['podium']  = $parts;
        $resultat[$comp['id']]['premier'] = $comp['premier_final'];
    }
    if($erreur == "") {
        $userRes = array();
        foreach($resultat as $id => $res) {
            try{

                $req7 = $bdd->prepare("SELECT * FROM pari_user WHERE pari_comp_id=:pari_comp_id ORDER BY user_id");
                $req7->bindValue('pari_comp_id',$id, PDO::PARAM_INT);
                $req7->execute();
                $result7= array();
                while ( $row  = $req7->fetch(PDO::FETCH_ASSOC)) {  
                    array_push($result7, $row);
                }

            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            // $query7    = sprintf('SELECT * FROM pari_user WHERE pari_comp_id=%s ORDER BY user_id',$id);
            // $result7   = mysql_query($query7)or die(mysql_error());

            while($pari = mysql_fetch_array($result7)) {
                //echo $pari['pari_comp_id'].' '.$pari[podium].' -> '.implode(':',$res[podium]).' - ';
                $userPodium = explode(':',$pari[podium]);
                $tabBonneRep = array_intersect($userPodium,$res[podium]);
                $nbrBonneRep = count($tabBonneRep);
                //echo $nbrBonneRep.' - '.implode(':',$tabBonneRep).' - ';
                if($nbrBonneRep == 4)
                    $user[$pari['user_id']] += 160;
                else
                    $user[$pari['user_id']] += (15*$nbrBonneRep);
                if($pari['premier'] == $res['premier'])
                    $user[$pari['user_id']] += 40;
                //echo $user[$pari[user_id]].' <br />';
            }
        }


            foreach($user as $user_id => $points) {
                //insertion dans pari_resultat
                try {
            $req8 = $bdd->prepare("INSERT INTO pari_resultat (points,pari_id,user_id) VALUES (:points,:pariID,:user_id)");

            $req8->bindValue('points',$points, PDO::PARAM_INT);
            $req8->bindValue('pariID',$pariID, PDO::PARAM_INT);
            $req8->bindValue('user_id',$user_id, PDO::PARAM_INT);
            $req8->execute();

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
                // $query8 = sprintf("INSERT INTO pari_resultat (points,pari_id,user_id) VALUES ('%s','%s','%s')"
                //         ,$points
                //         ,$pariID
                //         ,$user_id);
                // $result8 = mysql_query($query8) or die(mysql_error());

            }

              try {
            $req2 = $bdd->prepare("UPDATE pari SET corrige='1'WHERE id=:pariID");

            $req2->bindValue('pariID',$pariID, PDO::PARAM_INT);
            $req2->execute();

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
            // $query2    = sprintf("UPDATE pari SET corrige='1'WHERE id=%s",$pariID);
            // //exit($query2);
            // $result2   = mysql_query($query2) or die(mysql_error());
        }
    
}


if( isset($_POST['sub_result'])) {
    if(sizeof($_POST['podium']) != 4)
        $erreur = $_POST['pari_comp_id']." : Le podium doit etre compos� de athlètekas <br />";
    if(empty($_POST['premier']))
        $erreur .= $_POST['pari_comp_id']." : Le premier ne peut pas etre vide <br />";
    $podium = implode(":", $_POST['podium']);
    if($erreur == "") {

        try {
            $req2 = $bdd->prepare("UPDATE pari_composition SET podium_final=:podium_final, premier_final=:premier_final WHERE id=:pari_comp");

            $req2->bindValue('podium_final',$podium, PDO::PARAM_INT);
            $req2->bindValue('premier_final',$_POST['premier'], PDO::PARAM_INT);
            $req2->bindValue('pari_comp',$_POST['pari_comp_id'], PDO::PARAM_INT);
            $req2->execute();

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        // $query2    = sprintf("UPDATE pari_composition SET podium_final='%s', premier_final='%s' WHERE id='%s'"
        //         ,mysql_real_escape_string($podium)
        //         ,mysql_real_escape_string($_POST['premier'])
        //         ,mysql_real_escape_string($_POST['pari_comp_id']));
        // //exit($query2);
        // $result2   = mysql_query($query2) or die(mysql_error());
    }
}

 try{

        $req1 = $bdd->prepare("SELECT p.*, e.Nom FROM pari p INNER JOIN evenements e ON p.evenement_id = e.id WHERE p.id=:p_id");
        $req1->bindValue('p_id',$pariID, PDO::PARAM_INT);
        $req1->execute();
        $pari=$req1->fetch(PDO::FETCH_ASSOC);

        $req5 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
        $req5->execute();
        $result5= array();
        while ( $row  = $req5->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result5, $row);
        }

        $req6 = $bdd->prepare("SELECT * FROM pari_composition WHERE pari_id=:pari_id ORDER BY id DESC");
        $req6->bindValue('pari_id',$pariID, PDO::PARAM_INT);
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

if($pari['corrige'] && $erreur == "")
    exit('Ce pari a �tait corig� ! <a href="pari.php">retour</a>');

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

            $(function() {
                $("#accordion").accordion();
            });
        </script>
    </head>

    <body>
<?php require_once "menuAdmin.php"; ?>


        <fieldset>
            <legend>R�sultat pari</legend>

            <b style="color:red;"><?php echo $erreur; ?></b>

            <div id="accordion">

<?php
                $i = 1;
                // while($compo = mysql_fetch_array($result6)):
                foreach ($result6 as $compo) {
                    $compos       = explode(";", $compo['participant']);
                    $forfaits     = explode(";", $compo['forfait']);
                    $participants = array();
                    $forfait      = array();

                    foreach($compos as $athlète) {
                        
                        $tab  = explode(":", $athlète);
                        if(trim($tab[0])!=""){
                            $id   = $tab[0];
                            $name = $tab[1];
                            $participants[$id] = $name;
                        }
                    }
                    foreach($forfaits as $athlète) {
                        $tab  = explode(":", $athlète);
                        if(trim($tab[0])!=""){
                        $id   = $tab[0];
                        $name = $tab[1];
                        if($id != '')
                            $participants[$id] = $name.' (forfait)';
                    }

                    }

                    $podium = explode(":", $compo['podium_final']);
                    ?>
                <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo$compo['id'] ?> : <?php echo $compo['poid'].' '.$compo['sexe'].' '.$compo['date'] ?></h3>
                <div>

                    <form action="" method="post">

                        <table>
                            <thead>
                                <tr>
                                    <th>Composition</th>
                                </tr>
                            </thead>
                            <tr>
                                <td rowspan="2" valign="top" style="font-size:0.8em;">
                                    <table>
                                        <tr>
                                            <th>Participant</th><th>Podium</th><th>Premier</th>
                                        </tr>
    <?php foreach($participants as $id => $nom): ?>

                                        <tr>
                                            <td>
                                                <a target="blank" href="../athlete-<?php echo $id ?>.html"><?php echo $nom ?></a>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="podium[]" value="<?php echo $id ?>" <?php if(in_array($id,$podium)) echo 'checked="checked"'; ?> />
                                            </td>
                                            <td>
                                                <input type="radio" name="premier" value="<?php echo $id ?>" <?php if($compo['premier_final'] == $id) echo 'checked="checked"'; ?> />
                                            </td>
                                        </tr>    
    <?php endforeach; ?>
                                    </table>
                                </td>
                                <td valign="top"><input type="hidden" name="pari_comp_id" value="<?php echo $compo['id']?>" /><input type="submit" name="sub_result" value="sauvegarder" /></td>
                            </tr>

                            <tr><td valign="top"><div align="center" style="display:none;" class="comp" id="comp<?php echo $i ?>"></div></td></tr>
                        </table>
                    </form>

                </div>
    <?php
                    $i++;
                }?>
            </div>
        </fieldset>
        <fieldset>
            <legend>Validation</legend>
            <form action="" method="post">
                Execute le script de validation des r�sultats pour tous les utilisateurs : ATTENTION PAS DE RETOUR POSSIBLE
                <input type="submit" name="sub_validation_finale" value="valider" onclick="if(window.confirm('Executer le script de correction ?')){return true;} else{return false;}"/>
            </form>   
        </fieldset>
    </body>
</html>


