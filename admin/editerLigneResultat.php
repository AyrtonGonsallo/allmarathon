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

require_once '../database/connexion.php';

if($_GET['evResultID']!=""){
         try{
              $req = $bdd->prepare("SELECT E.ID,E.Rang,E.Club,E.PoidID,C.Nom,E.ChampionID,E.EvenementID FROM evresultats E INNER JOIN champions C ON E.ChampionID=C.ID WHERE E.ID=:id");
              $req->bindValue('id',$_GET['evResultID'], PDO::PARAM_INT);
              $req->execute();
              $ev_result=$req->fetch(PDO::FETCH_ASSOC) ;

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    }

if(isset($_POST['sub'])){
    
            try {
                 $req4 = $bdd->prepare("UPDATE evresultats SET Rang=:Rang,ChampionID=:ChampionID,Club=:Club,PoidID=:PoidID WHERE ID=:id");

                 $req4->bindValue('Rang',$_POST['Rang'], PDO::PARAM_STR);
                 $req4->bindValue('ChampionID',$_POST['ChampionID'], PDO::PARAM_STR);
                 $req4->bindValue('Club',$_POST['Club'], PDO::PARAM_STR);
                 $req4->bindValue('PoidID',$_POST['PoidID'], PDO::PARAM_STR);
                 $req4->bindValue('id',$_GET['evResultID'], PDO::PARAM_INT);
                 $req4->execute();
                 header('Location: evenementResultatIndividuel.php?evenementID='.$ev_result['EvenementID']);
                 
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
        <head>
        <meta charset="utf-8">
            <meta http-equiv="Content-Type" content="text/html;" />
            <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
            <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
            <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
            <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
            
            <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
            <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
            <!-- InstanceBeginEditable name="doctitle" -->
            <title>allmarathon admin</title>
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Editer ligne de résultat</legend>
        <form action="editerLigneResultat.php?evResultID=<?php echo $_GET['evResultID'];?>" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td align="right"><label for="Type">Champion : </label></td>
                    <td>
                        <div id="autoCompChamp1">
                            <input autocomplete="off" type="text" id="temp1" onkeyup="autoComp(1);" value="<?php echo $ev_result['Nom'];?>" />
                            <div id="autocomp1" style="display:none;" class="autocomp"></div>
                            <input style="display:none;" id="champion1" name="ChampionID" type="text" value="<?php echo $ev_result['ChampionID'];?>" />

                        </div>
                </td>
                </tr>
                <tr>
                    <td align="right"><label for="Type">Rang : </label></td>
                    <td><input type="text" name="Rang" id="rang" value="<?php echo $ev_result['Rang'];?>" /></td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="Nom">Club : </label></td><td><input type="text" name="Club" id="club" value="<?php echo $ev_result['Club'];?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="Nom">Poids : </label></td><td><input type="text" name="PoidID" id="poids" value="<?php echo $ev_result['PoidID'];?>" />
                    </td>
                </tr>
                <tr align="center"><td colspan="2"><input type="submit" name="sub" value="Modifier" /></td></tr>
</body>
<script type="text/javascript">
    function autoComp(index){
        $.get('resultatAutoCompletion.php',{id: index, str: $('#temp'+index).val()},function(data){
            if(data != 0){
                $('#autocomp'+index).css('display', '');
                $('#autocomp'+index).html(data);
            }else{
                $('#autocomp'+index).css('display', 'none');
            }
        });
    }
    function addCompletion(str,index){
        tab     = str.split(':');
        idChamp = tab[0];
        name    = tab[1];
        document.getElementById("autocomp"+index).style.display = "none";
        document.getElementById("champion"+index).value = idChamp;
        document.getElementById("temp"+index).value = name;
    }
</script>
</html>

