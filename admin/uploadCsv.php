<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: text/html; charset=utf-8');
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
        header('Location: login.php');
                exit();
    }

function convert( $str ) {
    return iconv( "Windows-1252", "UTF-8", $str );
}
if(isset($_GET['evenementID'])){
    $eventID=$_GET['evenementID'];
    $table="evenements";
}
if ((isset($_FILES['file']) || isset($_POST['url_drive'])) && isset($eventID)) {
    $separateur=";";
    if ($_FILES['file']['tmp_name']!=""){
        //echo "par fichier";
        //var_dump($_FILES['file']);
        $file = $_FILES['file']['tmp_name'];
    }else if (isset($_POST['url_drive'])){
        $separateur=",";
        //echo "par url";
        $url_drive = $_POST['url_drive'];
        //echo $url_drive;
        // Convert the Google Sheets URL to the CSV export URL
        $csv_url = str_replace("/edit?usp=sharing", "/export?format=csv", $url_drive);
        
        // Download the CSV file
        $file = 'temp.csv'; // Temporary file to store the downloaded CSV
        file_put_contents($file, file_get_contents($csv_url));
    }
   
    require_once '../database/connexion.php';
    ini_set('auto_detect_line_endings',1);

    $handle = fopen($file, 'r');
    //var_dump($file);
    //exit(-1);
    fgetcsv($handle, 1000);
    $min_id=0;
    $max_id=0;
    $insertChampion = 0;
    $insertResultat = 0;
    $i = 0;
    while (($data = fgetcsv($handle, 1000, $separateur)) !== FALSE) {

        $data = array_map( "convert", $data );
        $evenementID = $eventID;               
            $Nom = ""; 
            $Sexe = ""; 
            $Pays = ""; 
            $Rang = ""; 
            $Temps = ""; 
        if ($_FILES['file']['tmp_name']!=""){
            $evenementID = $data[0];               
            $Nom = $data[1];
            $Sexe = $data[2];
            $Pays = $data[3];
            $Rang = $data[4];
            $Temps = $data[5];
        }else if (isset($_POST['url_drive'])){
            $Nom = $data[0];
            $Sexe = $data[1];
            $Pays = $data[2];
            $Rang = $data[3];
            $Temps = $data[4]; 
        }
       
        
       /* if(!is_numeric($evenementID) && $evenementID!="" || is_numeric($Pays) || !is_numeric($Rang) || $Sexe!='M' && $Sexe!='F')
            $erreur[$Nom] = "";
        if(!is_numeric($evenementID) && $evenementID!="")
            $erreur[$Nom] .= 'Evenement Id doit être composer de chiffre. ';
        if(is_numeric($Pays))
            $erreur[$Nom] .= 'Pays doit être composer de lettre. ';
        if(!is_numeric($Rang))
            $erreur[$Nom] .= 'le Rang doit être composer de chiffre. ';
        if( $Sexe!='M' && $Sexe!='F')
            $erreur[$Nom] .= 'le sexe doit etre M ou F. ';
        if(isset($erreur[$Nom]))
            continue;
            */
        //recherche de la présence du champion dans la table champions
        try{
            
            $req1 = $bdd->prepare("SELECT ID FROM champions WHERE Nom LIKE :Nom");
            $req1->bindValue('Nom',$Nom, PDO::PARAM_STR);
            $req1->execute();
            }
            catch(Exception $e)
        {
            $erreur[$Nom] = 'erreur SQL recherche champions : '.$e->getMessage();
        }
        $numberChampions = $req1->rowCount();
         
        //recherche de la présence de l'événement

        if($evenementID != ""){

            try{

                $req4 = $bdd->prepare("SELECT ID FROM ".$table." WHERE ID= :id");
                $req4->bindValue('id',$evenementID, PDO::PARAM_STR);
                $req4->execute();
            }
            catch(Exception $e)
            {
                // die('Erreur : ' . $e->getMessage());
                $erreur[$Nom] = 'erreur pas d\'evenement corespondant a l\'ID '.$evenementID;
                continue;
            }

        }else{
           $evenementID = $eventID;
        }


        if(isset($erreur[$Nom]))
            continue;
             
        if($numberChampions==0){
            //insertion du nouveau champion

            try {
                 $req2 = $bdd->prepare("INSERT INTO champions (Nom,Sexe,PaysID) VALUES (:Nom,:Sexe,:Pays)");

                 $req2->bindValue('Nom',$Nom, PDO::PARAM_STR);
                 $req2->bindValue('Sexe',$Sexe, PDO::PARAM_STR);
                 $req2->bindValue('Pays',$Pays, PDO::PARAM_STR);
                 $req2->execute();
            }
            catch(Exception $e)
            {
                $erreur[$Nom] = 'erreur SQL insertion champions : '. $e->getMessage();
                continue;
            }
            $idChampion=$bdd->lastInsertId();
            if($i==0){
                $min_id=$idChampion;
            }
            $max_id= $idChampion;
            $insertChampion++;
        }else{
            $champion=$req1->fetch(PDO::FETCH_ASSOC);
            $idChampion = $champion['ID'];
        }

        //insertion du résultat

        try {
                 $req3 = $bdd->prepare("INSERT INTO evresultats (Rang,EvenementId,ChampionID,Temps) VALUES (:Rang,:evenementID,:idChampion,:Temps)");

                 $req3->bindValue('Rang',$Rang, PDO::PARAM_INT);
                 $req3->bindValue('evenementID',$evenementID, PDO::PARAM_INT);
                 $req3->bindValue('idChampion',$idChampion, PDO::PARAM_INT);
                 $req3->bindValue('Temps',$Temps, PDO::PARAM_STR);
                 $req3->execute();
            }
            catch(Exception $e)
            {
                $erreur[$Nom] = 'erreur SQL insertion résultat : '. $e->getMessage();
                continue;
            }

            try{
              $req5 = $bdd->prepare("SELECT u.email,c.Nom FROM champions c, abonnement a,users u WHERE a.champion = c.ID and u.id=a.user and  a.champion=:idChampion");
              $req5->bindValue('idChampion',$idChampion, PDO::PARAM_INT);

              $req5->execute();
              $result5= array();
              while ( $row  = $req5->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result5, $row);
              }
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }

            foreach ($result5 as $r) {
                  
                  $email = 'contact@alljudo.net';
                  $subject = "Il y a du nouveau sur la fiche de ".$r['Nom'];
                  $headers = "From: ".$email."\r\n";
                  $headers .= "Reply-To: ". $r['email'] . "\r\n";
                  $headers .= "MIME-Version: 1.0\r\n";
                  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                  $message = '
                    <html><body>Bonjour,
                    Vous recevez ce message pour vous pr&eacute;venir qu\'un nouveau r&eacute;sultat, une nouvelle vid&eacute;o ou une nouvelle photo a &eacute;t&eacute; ajout&eacute;e sur la fiche de '.$r['Nom'].'
                    Cordialement
                    L\'&eacute;quipe de Allmarathon</body></html>';
                  // mail('sabilmariam91@gmail.com', $subject,$message,$headers);
                   mail($r['email'], $subject,$message,$headers);
           }

        $insertResultat++;
        
        $i++;
    }
}
if(!(isset($_POST["lancer_script"]))){
    echo '<head><link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico"></head><body>';
    require_once "menuAdmin.php";
    echo " nbr nouveau champions : ".$insertChampion." nbr resultat inserés : ".$insertResultat;
    echo "<br><pre>";
    if(isset($erreur))
        print_r($erreur);
    echo "</pre></body>";?>
    <form Method="POST" ACTION="" >
        <input type="text" name="lancer_script" value="true" style="display:none">
        <input type="text" name="min_id" value="<? echo $min_id;?>" style="display:none">
        <input type="text" name="max_id" value="<? echo $max_id;?>" style="display:none">
        <input type="submit" class="season-selector-button" value="lancer le script">
    </form>
<?}?>
   <? if(isset($_POST["lancer_script"])){ 
     echo '<head><link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico"></head><body>';
     require_once "menuAdmin.php";
    $start_time = time();
    require '../content/classes/test.php';

    require '../content/classes/script_fusion_fiches.php'; 
    $result=recherche_et_fusion($_POST["min_id"],$_POST["max_id"]);?>
<? if($result){ ?>

    <fieldset style="float:left;">

        <legend>Suggestion de fusion (nouveau par ancien)</legend>



        <div id="compte" style="color:red;background-color:black;width: 300px;height:50px; font-size:20px;padding:10px;margin:10px;border-radius:20px;"><? echo count($result);?> de vos athlètes ajoutés pourraient déja exister</div>

            <div >

                <table class="tab1">

                <thead>

                    <tr><th colspan="2">A remplacer</th><th colspan="2">remplacant</th><th colspan="2">Action</th></tr>

                </thead>

                <tbody>

                    

                    <tr><td>ID</td><td>Nom</td><td>ID</td><td>Nom</td><td colspan="2"></td></tr>

                    <?php //while($champion = mysql_fetch_array($result3)){

                        foreach ($result as $format) {

                            foreach ($format->getRemplacants() as $remplacant) {

                    if($_SESSION['admin'] == true){

                        echo "<tr align=\"center\" ><td>".$format->getChampion_courant()['ID']."</td><td>".$format->getChampion_courant()['Nom']."</td><td>".$remplacant['ID']."</td><td>".$remplacant['Nom']."</td>

                            <td colspan='2'>

                            <a href=\"remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."'\" target=\"_blank\">

                            <img style=\"cursor:pointer;\" src=\"../images/replace.png\" alt=\"replace\" title=\"remplacer\" onclick=\"deleteRow2(this)\" />

                            </a>

                            <img style=\"cursor:pointer;\" src=\"../images/cancel.png\" alt=\"annuler\" title=\"annuler\"  onclick=\"if(confirm('Voulez vous vraiment annuler le remplacement de ".$format->getChampion_courant()['Nom']."par ".$remplacant['Nom']." ?')) { deleteRow(this)} else { return 0;}\" />

                            

                            <a href=\"remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."&modifier=true\" target=\"_blank\">

                            <img style=\"cursor:pointer;\" src=\"../images/rempMod.png\" alt=\"replaceandedit\" title=\"remplacer et modifier\" onclick=\"deleteRow2(this)\" />

                            </a>

                            

                            </td></tr>";

                    }elseif($_SESSION['ev'] == true){

                        echo "<tr align=\"center\" ><td>".$format->getChampion_courant()['ID']."</td><td>".$format->getChampion_courant()['Nom']."</td><td>".$remplacant['ID']."</td><td>".$remplacant['Nom']."</td> 

                            <td colspan='2'><img style=\"cursor:pointer;\" src=\"../images/replace.png\" alt=\"replace\" title=\"remplacer\" onclick=\"location.href='remplacerChampion2.php?remplacer=".$format->getChampion_courant()['ID']."&remplacant=".$remplacant['ID']."'\" />

                            </td></tr>";

                    }

                    }

                    } ?>

                    

                </tbody>

                </table>

            </div>

        </div>

    </fieldset>

    <div style="position: absolute; right: 0px; color:red;background-color:black;width: 700px;;height:60px; font-size:30px;padding:20px;margin:20px;border-radius:40px;">

        <?php 

            $end_time = time();

            $execution_time = ($end_time - $start_time);

            echo "<b style=\"color:white\">Temps d'exécution :</b> ".$execution_time."<b style=\"color:white\"> sec</b>";

        ?>

    </div>
<? }else{?>
Le script n'a pas détecté de doublons
<? }
}?>