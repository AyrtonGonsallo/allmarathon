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

require_once '../database/connexion.php';

$id = $_GET['directID'];

$erreur = "";
if (isset($_POST['sub'])) {
    if ($_POST['texte'] == "")
        $erreur .= "texte vide";
    if ($erreur == "") {

        try {
                 $req4 = $bdd->prepare("UPDATE evenementimportantdirect SET texte=:texte, titre=:titre, une=:une WHERE ID=:id LIMIT 1");

                 $req4->bindValue('texte',$_POST['texte'], PDO::PARAM_STR);
                 $req4->bindValue('titre',$_POST['titre'], PDO::PARAM_STR);
                 $req4->bindValue('une',$_POST['une'], PDO::PARAM_STR);
                 $req4->bindValue('id',$id, PDO::PARAM_INT);
                 $req4->execute();
                 header('Location: evenementImportantDirectDetail.php?evenement_id='.$_GET['evenementID']);
                 
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
    }
}

try{
    $req = $bdd->prepare("SELECT * FROM evenementimportantdirect WHERE ID=:id");
    $req->bindValue('id',$id, PDO::PARAM_INT);
    $req->execute();
    $direct=  $req->fetch(PDO::FETCH_ASSOC);


}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
        <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
        <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
        <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
        <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript" src="../Scripts/direct_tiny_mce_init.js"></script>
        <title>allmarathon admin</title>
               <script type="text/javascript">
        function addCompletion(str,index){
            tab     = str.split(':');
            idChamp  = tab[0];
            name     = tab[1];
            nameLink = tab[2];
            document.getElementById("autocomp"+index).style.display = "none";
            $('#temp1').val('');
            $('#result').html('<a href="/athlete-'+idChamp+'-'+nameLink+'.html">'+name+'</a> ');
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
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>

        <fieldset style="float:left;">
            <legend>MODIFIER</legend>
            <div>
                <b style="color:red;"><?php echo $erreur . '<br />'; ?></b>
                <form action="" method="post" >
                    titre : <input name="titre" value="<?php echo str_replace("\\", "", $direct['titre']) ?>" /><br />
                    A la Une :
                    <?php if ($direct['une'] == 0) {
                    ?>
                        oui<input type="radio" name="une" value="1"/> non<input type="radio" name="une" value="0" checked/>
                    <?php } elseif ($direct['une'] == 1) {
                    ?>
                        oui<input type="radio" name="une" value="1" checked/> non<input type="radio" name="une" value="0"/>
                    <?php } ?><br/><br/>
                    <textarea name="texte" cols="30" rows="5"><?php echo str_replace("\\", "", $direct['texte']) ?></textarea>
                    <br/>
                    <div id="autoCompChamp1">
                        liens champions : <input autocomplete="off" type="text" id="temp1" value="" />
                        <div id="autocomp1" style="display:none;" class="autocomp"></div>
                        <input style="display:none;" id="champion1" name="Champion_id" type="text" value="" />
                        <div id="result" style="display: inline;"></div>
                    </div>
                    <input style="float:right;" name="sub" type="submit" value="modifier" />
                </form>


            </div>
        </fieldset>
    </body>
</html>