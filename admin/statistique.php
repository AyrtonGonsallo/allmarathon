<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

if($_SESSION['admin'] == false){
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';

$fin = strtotime('2009-08-01');
$t = time();
while($t > $fin){
    $monthKey = timestampToMonth($t);
    $month[$monthKey]['inscription'] = 0;
    $month[$monthKey]['commentaire'] = 0;
    // $month[$monthKey]['message forum'] = 0;
    $month[$monthKey]['votes'] = 0;
    $month[$monthKey]['pari'] = 0;
    $month[$monthKey]['admin athlète'] = 0;
    $month[$monthKey]['admin club'] = 0;
    $month[$monthKey]['ab'] = 0;
    $t = strtotime('-1 month', $t);
}

$total['inscription'] = 0;
$total['commentaire'] = 0;
// $total['message forum'] = 0;
$total['votes'] = 0;
$total['pari'] = 0;
$total['admin athlète'] = 0;
$total['admin club'] = 0;
$total['ab'] = 0;


try{
    //nombre d'inscription
  $req1 = $bdd->prepare("SELECT user_regdate FROM users WHERE id > 56");
  $req1->execute();
  $newRegResult= array();
  while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
    array_push($newRegResult, $row);
    }
    foreach ($newRegResult as $reg) {
      $month[timestampToMonth($reg['user_regdate'])]['inscription']++;
    }

            //nombre de commentaire
    $req2 = $bdd->prepare("SELECT date FROM commentaires");
    $req2->execute();
    $commentResult= array();
    while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
       $month[dateToMonth($c['date'])]['commentaire']++;
    }


    $req3 = $bdd->prepare("SELECT date FROM champion_popularite WHERE date !='0000-00-00'");
    $req3->execute();
    $commentResult= array();
    while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
       $month[dateToMonth($c['date'])]['votes']++;
    }

    // Nombre de pari
     $req3 = $bdd->prepare("SELECT date FROM pari_user WHERE date !='0000-00-00'");
    $req3->execute();
    $commentResult= array();
    while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
       $month[dateToMonth($c['date'])]['pari']++;
    }

    // admin athlète
     $req3 = $bdd->prepare("SELECT date_creation FROM champion_admin_externe WHERE date_creation !='0000-00-00'");
    $req3->execute();
    $commentResult= array();
    while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
       $month[dateToMonth($c['date_creation'])]['admin athlète']++;
    }

    // admin club
     $req3 = $bdd->prepare("SELECT date_creation FROM club_admin_externe WHERE date_creation !='0000-00-00' ");
    $req3->execute();
    $commentResult= array();
    while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
       $month[dateToMonth($c['date_creation'])]['admin club']++;
    }

    // abonnement
     $req3 = $bdd->prepare("SELECT date_creation FROM abonnement WHERE date_creation !='0000-00-00'");
    $req3->execute();
    $commentResult= array();
    while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
        array_push($commentResult, $row);
    }
    foreach ($commentResult as $c) {
        $month[dateToMonth($c['date_creation'])]['ab']++;
    }
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

//nombre d'inscription
// $newRegQuery  = sprintf('SELECT user_regdate FROM users WHERE id > 56');
// $newRegResult = mysql_query($newRegQuery) or die(mysql_error());
// while($reg = mysql_fetch_array($newRegResult)){
//         $month[timestampToMonth($reg['user_regdate'])]['inscription']++;
// }

//nombre de commentaire
// $commentQuery  = sprintf('SELECT date FROM commentaires');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//         $month[dateToMonth($c['date'])]['commentaire']++;
// }

// //nombre de post forum
// $newRegQuery  = sprintf('SELECT post_time FROM phpbb_posts');
// $newRegResult = mysql_query($newRegQuery) or die(mysql_error());
// while($reg = mysql_fetch_array($newRegResult)){
//         $month[timestampToMonth($reg['post_time'])]['message forum']++;
// }

//nombre de commentaire
// $commentQuery  = sprintf('SELECT date FROM champion_popularite WHERE date !=\'0000-00-00\' ');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//     $month[dateToMonth($c['date'])]['votes']++;
// }

//nombre de pari
// $commentQuery  = sprintf('SELECT date FROM pari_user WHERE date !=\'0000-00-00\' ');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//     $month[dateToMonth($c['date'])]['pari']++;
// }

//admin athlète
// $commentQuery  = sprintf('SELECT date_creation FROM champion_admin_externe WHERE date_creation !=\'0000-00-00\' ');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//     $month[dateToMonth($c['date_creation'])]['admin athlète']++;
// }

//admin club
// $commentQuery  = sprintf('SELECT date_creation FROM club_admin_externe WHERE date_creation !=\'0000-00-00\' ');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//     $month[dateToMonth($c['date_creation'])]['admin club']++;
// }

// $commentQuery  = sprintf('SELECT date_creation FROM abonnement WHERE date_creation !=\'0000-00-00\' ');
// $commentResult = mysql_query($commentQuery) or die(mysql_error());
// while($c = mysql_fetch_array($commentResult)){
//     $month[dateToMonth($c['date_creation'])]['ab']++;
// }

function timestampToMonth($time){
    $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
    return $month[date('n',$time)-1].date('-Y',$time);
}

function dateToMonth($date){
    $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
    $time = strtotime($date);
    return $month[date('n',$time)-1].date('-Y',$time);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("table.tablesorter").tablesorter({widthFixed: false, widgets: ['zebra']});
        }
        );

    </script>
    <title>allmarathon admin</title>

</head>

<body>
    <?php require_once "menuAdmin.php"; ?>


    <fieldset>
        <legend>Statistique par mois</legend>
        <pre>
            <?php //echo print_r($month); ?>
        </pre>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th>moi</th><th>Inscription</th><th>Commentaire</th><th>Votes</th><th>Pari</th><th>Admin athlète</th><th>Admin club</th><th>abonnements</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($month as $nom => $m): ?>
                    <tr>
                        <td><?php echo $nom ?></td>
                        <?php foreach($m as $cat => $nbr):
                        $total[$cat] += $nbr;
                        ?>
                        <td><?php echo $nbr ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach;?>
            <tr>
                <td>Total</td>
                <?php foreach($total as $cat => $nbr):  ?>
                    <td><?php echo $nbr ?></td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</fieldset>

</body>
</html>


