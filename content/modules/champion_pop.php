<?php

include("../classes/championPopularite.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

(!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

if(!empty($_SESSION['user'])) {
    $user=$_SESSION['user'];
    $erreur_auth='';
    }  else $user='';

    $champ_id = (isset($_GET['champ_id']))?(int)$_GET['champ_id']:0;
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $host     = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
    $ip       = $_SERVER["REMOTE_ADDR"];
    $date_fan     = date('Y-m-d H:i:s');

$champ_pop=new championPopularite();

($champ_pop->isUserFan($champ_id,$user_id)['donnees']) ? $test= "fan" : $test= "";


    if($test){
        
       $_SESSION['fan_error']  = "Un seul vote par utilisateur et par athlète.";
       header("Location: /athlète-".$champ_id.".html");

    }else{

        $champ_pop->devenirFan($champ_id,$user_id,$date_fan,$ip,$user_agent,$host);
        $_SESSION['fan_success']=1;
        header("Location: /athlète-".$champ_id.".html");
           }   

        // echo $erreur;
    //     if($erreur == ""){
    //         $queryInsert  = sprintf("INSERT INTO champion_popularite (champion_id,user_id,date,ip,user_agent,host) VALUES ('%s','%s','%s','%s','%s','%s')", $champ_id, $user_id, $date, $ip, $user_agt, $host);
    //         $resultInsert = mysql_query($queryInsert)or die(mysql_error());

    //         $query3    = sprintf("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('vote', '%s', '%s')"
    //             ,(int)$user_id
    //             ,(int)$champ_id);
				// // echo $query3;
    //         $result3   = mysql_query($query3) or die(mysql_error());

    //         header("Location: athlète-$champ_id.html");
            
            
    //         exit();
    //     }
            

    


?>
