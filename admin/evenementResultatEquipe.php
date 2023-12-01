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

if(!(isset($_GET['evenementID'])or isset($_GET['evenement_filsID']))){
    header("Location: evenement.php");
}
if(isset($_GET['evenementID'])){
    $eventID=$_GET['evenementID'];
    $table="evenements";
}else if(isset($_GET['evenement_filsID'])){
    $eventID=$_GET['evenement_filsID'];
    $table="evenements_fils";
}
$erreur = array();
try{
    $req = $bdd->prepare("SELECT * FROM ".$table." WHERE ID = :id");
    $req->bindValue('id',$eventID, PDO::PARAM_INT);
    $req->execute();
    $evenement=$req->fetch(PDO::FETCH_ASSOC) ;
   

  $req1 = $bdd->prepare("SELECT * FROM evequipe WHERE Sexe='M' AND evenementID =:id");
  $req1->bindValue('id',$eventID, PDO::PARAM_INT);
  $req1->execute();
  $result4= array();
  while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
      array_push($result4, $row);
  }
  $reqMixte = $bdd->prepare("SELECT * FROM evequipe WHERE Sexe='MF' AND evenementID =:id");
  $reqMixte->bindValue('id',$eventID, PDO::PARAM_INT);
  $reqMixte->execute();
  $resultMixtes= array();
  while ( $row  = $reqMixte->fetch(PDO::FETCH_ASSOC)) {  
      array_push($resultMixtes, $row);
  }
  $req2 = $bdd->prepare("SELECT * FROM evequipe WHERE Sexe='F' AND evenementID =:id");
  $req2->bindValue('id',$eventID, PDO::PARAM_INT);
  $req2->execute();
  $result5= array();
  while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
      array_push($result5, $row);
  }

}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
if(isset($_POST['sub'])){
    if($evenement['Type']=='Equipe'){
        //homme
        for($i=0;$i<8;$i++){
            if($_POST['NomEquipe'.$i]!=""){

            try {
                    $req4 = $bdd->prepare("INSERT INTO evequipe (Rang,NomEquipe,Equipe,Sexe,EvenementID) VALUES (:Rang,:NomEquipe,:Equipe,'M',:evenementID)");

                    $req4->bindValue('Rang',$_POST['RangEquipe'.$i], PDO::PARAM_INT);
                    $req4->bindValue('NomEquipe',$_POST['NomEquipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('Equipe',$_POST['Equipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('evenementID',$eventID, PDO::PARAM_INT);
                    $req4->execute();
                }
                catch(Exception $e)
                {
                    $erreur[$_POST['NomEquipe'.$i]] = "insertion de l'�quipe en BD �chou�";
                continue;
                }

            $equipeID = $bdd->lastInsertId();
            $equipe = explode(';',str_replace("  "," ",$_POST['Equipe'.$i]));
            if($equipe[0]=="")
                    continue;
            foreach($equipe as $m){
                $membre = explode(':',$m);
                $membre[0] = trim($membre[0]);
                $membre[1] = trim($membre[1]);
                if(is_numeric($membre[0])){
                    //champion existe
                    $championID = $membre[0];
                }else{
                    //insertion nouveau champion ?
                    if($membre[0]!=""){
                        try {
                            $req5 = $bdd->prepare("INSERT INTO champions (Nom,Sexe) VALUES (:Nom,'M')");

                            $req5->bindValue('Nom',$membre[0], PDO::PARAM_STR);
                            $statut=$req5->execute();
                            $championID = $bdd->lastInsertId();
                        $membre[1]  = $membre[0];
                        $erreur[$_POST['NomEquipe'.$i]][$membre[0]] = "A �t� ins�rer dans la BD --> ";
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                    }
                }
                if($membre[0]!=""){
                    try {
                            $req5 = $bdd->prepare("INSERT INTO evresultats (Rang,EvenementId,ChampionID,equipeID) VALUES (:Rang,:EvenementId,:ChampionID,:equipeID)");

                            $req5->bindValue('Rang',$_POST['RangEquipe'.$i], PDO::PARAM_INT);
                            $req5->bindValue('EvenementId',$eventID, PDO::PARAM_INT);
                            $req5->bindValue('ChampionID',$championID, PDO::PARAM_INT);
                            $req5->bindValue('equipeID',$equipeID, PDO::PARAM_INT);
                            $req5->execute();
                        //   $championID = $bdd->lastInsertId();
                        // $membre[1]  = $membre[0];
                        $erreur[$_POST['NomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat r&eacute;ussie";
                        }
                        catch(Exception $e)
                        {
                            $erreur[$_POST['NomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat &eacute;chou&eacute;";
                        }
                }
            }
            }   
        }
        
        //femme
        for($i=8;$i<16;$i++){
            if($_POST['fNomEquipe'.$i]!=""){
                        
                try {
                    $req4 = $bdd->prepare("INSERT INTO evequipe (Rang,NomEquipe,Equipe,Sexe,evenementID) VALUES (:Rang,:NomEquipe,:Equipe,'F',:evenementID)");

                    $req4->bindValue('Rang',$_POST['fRangEquipe'.$i], PDO::PARAM_INT);
                    $req4->bindValue('NomEquipe',$_POST['fNomEquipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('Equipe',$_POST['fEquipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('evenementID',$eventID, PDO::PARAM_INT);
                    $req4->execute();
                    $equipeID = $bdd->lastInsertId();
                }
                catch(Exception $e)
                {
                    $erreur[$_POST['fNomEquipe'.$i]] = "insertion de l'&eacute;quipe en BD &eacute;chou&eacute;";
                continue;
                }
            
            $equipe = explode(';',str_replace("  "," ",$_POST['fEquipe'.$i]));
            if($equipe[0]=="")
                    continue;
            foreach($equipe as $m){
                $membre = explode(':',$m);
                $membre[0] = trim($membre[0]);
                $membre[1] = trim($membre[1]);
                if(is_numeric($membre[0])){
                    //champion existe
                    $championID = $membre[0];
                }else{
                    //insertion nouveau champion ?
                    try {
                            $req5 = $bdd->prepare("INSERT INTO champions (Nom,Sexe) VALUES (:Nom,'F')");

                            $req5->bindValue('Nom',$membre[0], PDO::PARAM_STR);
                            $statut=$req5->execute();
                            $championID = $bdd->lastInsertId();
                        $membre[1]  = $membre[0];
                        $erreur[$_POST['fNomEquipe'.$i]][$membre[0]] = "A &eacute;t&eacute; ins&eacute;rer dans la BD --> ";
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }

                    // $query4    = sprintf("INSERT INTO champions (Nom,Sexe) VALUES ('%s','F')"
                    //      ,mysql_real_escape_string($membre[0]));
                    // $result4   = mysql_query($query4);
                    // $championID = mysql_insert_id();
                    // $membre[1]  = $membre[0];
                    // $erreur[$_POST['fNomEquipe'.$i]][$membre[0]] = "A �t� ins�rer dans la BD --> ";
                }

                try {
                            $req5 = $bdd->prepare("INSERT INTO evresultats (Rang,EvenementId,ChampionID,equipeID) VALUES (:Rang,:EvenementId,:ChampionID,:equipeID)");

                            $req5->bindValue('Rang',$_POST['fRangEquipe'.$i], PDO::PARAM_INT);
                            $req5->bindValue('EvenementId',$eventID, PDO::PARAM_INT);
                            $req5->bindValue('ChampionID',$championID, PDO::PARAM_INT);
                            $req5->bindValue('equipeID',$equipeID, PDO::PARAM_INT);
                            $req5->execute();
                        //   $championID = $bdd->lastInsertId();
                        // $membre[1]  = $membre[0];
                        $erreur[$_POST['fNomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat r&eacute;ussie";
                        }
                        catch(Exception $e)
                        {
                            $erreur[$_POST['fNomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat &eacute;chou&eacute;";
                        }

                //  $query3    = sprintf("INSERT INTO evresultats (Rang,EvenementId,ChampionID,equipeID) VALUES ('%s','%s','%s','%s')"
                // ,mysql_real_escape_string($_POST['fRangEquipe'.$i])
                // ,mysql_real_escape_string($eventID)
                // ,mysql_real_escape_string($championID)
                // ,mysql_real_escape_string($equipeID));
                // $result3   = mysql_query($query3);
                // if($result3){
                //     $erreur[$_POST['fNomEquipe'.$i]][$membre[1]] .= "insertion du r�sultat r�ussie";
                // }else{
                //     $erreur[$_POST['fNomEquipe'.$i]][$membre[1]] .= "insertion du r�sultat �chou�";
                // }
            }
            }   
        }
    }else{
        for($i=0;$i<8;$i++){
            if($_POST['NomEquipe'.$i]!=""){

            try {
                    $req4 = $bdd->prepare("INSERT INTO evequipe (Rang,NomEquipe,Equipe,Sexe,EvenementID) VALUES (:Rang,:NomEquipe,:Equipe,'MF',:evenementID)");

                    $req4->bindValue('Rang',$_POST['RangEquipe'.$i], PDO::PARAM_INT);
                    $req4->bindValue('NomEquipe',$_POST['NomEquipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('Equipe',$_POST['Equipe'.$i], PDO::PARAM_STR);
                    $req4->bindValue('evenementID',$eventID, PDO::PARAM_INT);
                    $req4->execute();
                }
                catch(Exception $e)
                {
                    $erreur[$_POST['NomEquipe'.$i]] = "insertion de l'�quipe en BD �chou�";
                continue;
                }

            $equipeID = $bdd->lastInsertId();
            $equipe = explode(';',str_replace("  "," ",$_POST['Equipe'.$i]));
            if($equipe[0]=="")
                    continue;
            foreach($equipe as $m){
                $membre = explode(':',$m);
                $membre[0] = trim($membre[0]);
                $membre[1] = trim($membre[1]);
                if(is_numeric($membre[0])){
                    //champion existe
                    $championID = $membre[0];
                }else{
                    //insertion nouveau champion ?
                    if($membre[0]!=""){
                        try {
                            $req5 = $bdd->prepare("INSERT INTO champions (Nom,Sexe) VALUES (:Nom,'M')");

                            $req5->bindValue('Nom',$membre[0], PDO::PARAM_STR);
                            $statut=$req5->execute();
                            $championID = $bdd->lastInsertId();
                        $membre[1]  = $membre[0];
                        $erreur[$_POST['NomEquipe'.$i]][$membre[0]] = "A �t� ins�rer dans la BD --> ";
                        }
                        catch(Exception $e)
                        {
                            die('Erreur : ' . $e->getMessage());
                        }
                    }
                }
                if($membre[0]!=""){
                    try {
                            $req5 = $bdd->prepare("INSERT INTO evresultats (Rang,EvenementId,ChampionID,equipeID) VALUES (:Rang,:EvenementId,:ChampionID,:equipeID)");

                            $req5->bindValue('Rang',$_POST['RangEquipe'.$i], PDO::PARAM_INT);
                            $req5->bindValue('EvenementId',$eventID, PDO::PARAM_INT);
                            $req5->bindValue('ChampionID',$championID, PDO::PARAM_INT);
                            $req5->bindValue('equipeID',$equipeID, PDO::PARAM_INT);
                            $req5->execute();
                        //   $championID = $bdd->lastInsertId();
                        // $membre[1]  = $membre[0];
                        $erreur[$_POST['NomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat r&eacute;ussie";
                        }
                        catch(Exception $e)
                        {
                            $erreur[$_POST['NomEquipe'.$i]][$membre[1]] = "insertion du r&eacute;sultat &eacute;chou&eacute;";
                        }
                }
            }
            }   
        }
    }
}

 

    // $query1    = sprintf('SELECT * FROM evenements WHERE ID = %s',mysql_real_escape_string($_GET['evenementID']));
    // $result1   = mysql_query($query1);
    // $evenement = mysql_fetch_array($result1);
    
    // $query4    = sprintf("SELECT * FROM evequipe WHERE Sexe='M' AND evenementID = %s",mysql_real_escape_string($_GET['evenementID']));
    // $result4   = mysql_query($query4)or die(mysql_error());
    
    // $query5    = sprintf("SELECT * FROM evequipe WHERE Sexe='F' AND evenementID = %s",mysql_real_escape_string($_GET['evenementID']));
    // $result5   = mysql_query($query5)or die(mysql_error());
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<meta charset="utf-8">
<script type="text/javascript" src="../script/ajax.js" ></script>

    <script type="text/javascript">
        function addName(index){
            first = document.getElementById('Equipe'+index).value == "";
            vide  = document.getElementById('autocomp'+index).value == "";
            if(!vide){
                if(first){
                   document.getElementById('Equipe'+index).value += document.getElementById('autocomp'+index).value;
                }else{
                   document.getElementById('Equipe'+index).value += ";"+document.getElementById('autocomp'+index).value;
                }
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
    </script>
</head>
<body>
<?php require_once "menuAdmin.php";?>
<?php if($erreur != ""){ ?>
<fieldset>
<legend>Rapport d'insertion</legend>
<pre>
<?php echo print_r($erreur);  ?>
</pre>
</fieldset>
<?php } ?>
<?php
    if(isset($_GET['evenementID'])){
        echo '<form action="evenementResultatEquipe.php?evenementID='.$_GET['evenementID'].'" method="post">';
    }else if(isset($_GET['evenement_filsID'])){
        echo '<form action="evenementResultatEquipe.php?evenement_filsID='.$_GET['evenement_filsID'].'" method="post">';

    }
    if($evenement['Type']=='Equipe'){
    ?>
<fieldset>
    <legend><?php echo $evenement['Nom'];?> : homme</legend>
    <?php 
    
    //affichages des r�ultats pour cette �v�nements
    if(sizeof($result4)!=0){
         echo "<table class=\"tab1\"><thead><tr><th>Rang</th><th>Nom &eacute;quipe</th><th>Composition</th><th>Action</th></tr></thead><tbody>";
        // while($equipe = mysql_fetch_array($result4)){
         foreach ($result4 as $equipe) {
            
            if(isset($_GET['evenementID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenementID=".$_GET['evenementID']."';} else { return 0;}\" /></td></tr>";
            }else if(isset($_GET['evenement_filsID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenement_filsID=".$_GET['evenement_filsID']."';} else { return 0;}\" /></td></tr>";

            }
        }
        echo "</tbody></table>";
    }
    ?>
    <br />
   
    <table>
    <thead>
    <tr>
        <th>Rang</th>
        <th>Nom &eacute;quipe</th>
        <th>Entrez le noms ici :</th>
        <th>Composition</th>
    </tr>
    </thead>
    <?php for($i = 0;$i<8;$i++){
        echo '<tr>';
        echo '<td align="center"><img src="../images/boutonMoins.gif" alt="moins" onclick="document.getElementById(\'RangEquipe'.$i.'\').value--;"/><input type="text"  id="RangEquipe'.$i.'" name="RangEquipe'.$i.'" value="'.($i+1).'" maxlength="1" size="1" /><img src="../images/boutonPlus.gif" alt="plus" onclick="document.getElementById(\'RangEquipe'.$i.'\').value++;" /></td>';
        echo '<td><input type="text" name="NomEquipe'.$i.'"  /></td>';
        echo '<td><input type="text" autocomplete="off" name="autocomp'.$i.'" id="autocomp'.$i.'" onkeyup="autoCompletion('.$i.');"/></td>';
        echo '<td><a style="cursor:pointer;" onclick="addName(\''.$i.'\')">add</a><input type="text" name="Equipe'.$i.'" id="Equipe'.$i.'"  size="100"/></td>';
        echo '</tr>';
        echo '<tr><td></td><td></td><td><div align="center" style="display:none;" class="comp" id="comp'.$i.'"></div></td><td></td></tr>';
    }
    ?>
    </table>
</fieldset>
<br /><br />
<fieldset>
    <legend><?php echo $evenement['Nom'];?> : femme</legend>
    <?php 
    
    //affichages des r�ultats pour cette �v�nements
    if(sizeof($result5)!=0){
         echo "<table class=\"tab1\"><thead><tr><th>Rang</th><th>Nom &eacute;quipe</th><th>Composition</th><th>Action</th></tr></thead><tbody>";
        // while($equipe = mysql_fetch_array($result5)){
         foreach ($result5 as $equipe) {
            if(isset($_GET['evenementID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenementID=".$_GET['evenementID']."';} else { return 0;}\" /></td></tr>";
        }else if(isset($_GET['evenement_filsID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenement_filsID=".$_GET['evenement_filsID']."';} else { return 0;}\" /></td></tr>";
            }
        }
        echo "</tbody></table>";
    }
    ?>
    <br />
    
    <table>
    <thead>
    <tr>
        <th>Rang</th>
        <th>Nom &eacute;quipe</th>
        <th>Entrez le noms ici :</th>
        <th>Composition</th>
    </tr>
    </thead>
    <?php for($i = 8;$i<16;$i++){
        echo '<tr>';
        echo '<td align="center"><img src="../images/boutonMoins.gif" alt="moins" onclick="document.getElementById(\'fRangEquipe'.$i.'\').value--;"/><input type="text"  id="fRangEquipe'.$i.'" name="fRangEquipe'.$i.'" value="'.($i-7).'" maxlength="1" size="1" /><img src="../images/boutonPlus.gif" alt="plus" onclick="document.getElementById(\'fRangEquipe'.$i.'\').value++;" /></td>';
        echo '<td><input type="text" name="fNomEquipe'.$i.'"  /></td>';
        echo '<td><input type="text" autocomplete="off" name="autocomp'.$i.'" id="autocomp'.$i.'" onkeyup="autoCompletion('.$i.');"/></td>';
        echo '<td><a style="cursor:pointer;" onclick="addName(\''.$i.'\')">add</a><input type="text" name="fEquipe'.$i.'" id="Equipe'.$i.'" size="100"/></td>';
        echo '</tr>';
        echo '<tr><td></td><td></td><td><div align="center" style="display:none;" class="comp" id="comp'.$i.'"></div></td><td></td></tr>';
    }
    ?>
    </table>
    
    
</fieldset>
<?php
    }
    else{
?>
<fieldset>
    <legend><?php echo $evenement['Nom'];?> : Equipes Mixtes</legend>
    <?php 
    
    //affichages des r�ultats pour cette �v�nements
    if(sizeof($resultMixtes)!=0){
         echo "<table class=\"tab1\"><thead><tr><th>Rang</th><th>Nom &eacute;quipe</th><th>Composition</th><th>Action</th></tr></thead><tbody>";
        // while($equipe = mysql_fetch_array($result4)){
         foreach ($resultMixtes as $equipe) {
            
            if(isset($_GET['evenementID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenementID=".$_GET['evenementID']."';} else { return 0;}\" /></td></tr>";
            }else if(isset($_GET['evenement_filsID'])){
                echo "<tr align=\"center\"><td>".$equipe['Rang']."</td><td>".$equipe['NomEquipe']."</td><td>".$equipe['Equipe']."</td><td><img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$equipe['NomEquipe']." ?')) { location.href='supprimerResultatEquipe.php?equipeID=".$equipe['ID']."&evenement_filsID=".$_GET['evenement_filsID']."';} else { return 0;}\" /></td></tr>";

            }
        }
        echo "</tbody></table>";
    }
    ?>
    <br />
   
    <table>
    <thead>
    <tr>
        <th>Rang</th>
        <th>Nom &eacute;quipe</th>
        <th>Entrez le noms ici :</th>
        <th>Composition</th>
    </tr>
    </thead>
    <?php for($i = 0;$i<8;$i++){
        echo '<tr>';
        echo '<td align="center"><img src="../images/boutonMoins.gif" alt="moins" onclick="document.getElementById(\'RangEquipe'.$i.'\').value--;"/><input type="text"  id="RangEquipe'.$i.'" name="RangEquipe'.$i.'" value="'.($i+1).'" maxlength="1" size="1" /><img src="../images/boutonPlus.gif" alt="plus" onclick="document.getElementById(\'RangEquipe'.$i.'\').value++;" /></td>';
        echo '<td><input type="text" name="NomEquipe'.$i.'"  /></td>';
        echo '<td><input type="text" autocomplete="off" name="autocomp'.$i.'" id="autocomp'.$i.'" onkeyup="autoCompletion('.$i.');"/></td>';
        echo '<td><a style="cursor:pointer;" onclick="addName(\''.$i.'\')">add</a><input type="text" name="Equipe'.$i.'" id="Equipe'.$i.'"  size="100"/></td>';
        echo '</tr>';
        echo '<tr><td></td><td></td><td><div align="center" style="display:none;" class="comp" id="comp'.$i.'"></div></td><td></td></tr>';
    }
    ?>
    </table>
</fieldset>
<?php
}
?>
<input type="submit" name="sub" value="Envoyer les r&eacute;sultats"/>
    </form>
</body>
</html>

