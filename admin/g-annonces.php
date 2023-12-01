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

    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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


<!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>


    <fieldset style="float:left;">
        <legend>Gestion des annonces</legend>
        <div align="center">
            <div id="menu">
                <div class="menuItem"> <a href="annonces.php" >Annonces</a></div><br /><br />
                <div class="menuItem"><a href="categories.php" >Categories</a> </div>   <br /><br />
                <div class="menuItem"><a href="souscategorie.php" >sous Categories</a> </div><br /><br />
                <div class="menuItem"><a href="annonces-parametres.php" >Parametres</a> </div>
            </div>
        </div>
    </body>
    <!-- InstanceEnd --></html>

