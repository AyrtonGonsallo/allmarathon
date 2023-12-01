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

if($_SESSION['admin'] == false){
    header('Location: login.php');
    exit();
}

include("../content/classes/user.php");

$user=new user();
$nb_abonnes=sizeof($user->getAbonnesNewsLetter()['donnees']);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        <legend>Statistiques sur la newsletter</legend>
        <pre>
            <?php //echo print_r($month); ?>
        </pre>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th>Nombre d'abonnés</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td><?php echo $nb_abonnes; ?></td>
                </tr>
            
        </tbody>
    </table>
</fieldset>

</body>
</html>


