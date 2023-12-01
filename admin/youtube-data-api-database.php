<?php 

   require_once '../database/connexion_youtube.php';

   if(isset($_POST["data"])){

      $chaine=json_decode($_POST['data']);

      $desc=htmlspecialchars($chaine->description,ENT_QUOTES);

      $image=explode("=",$chaine->image)[0];

      $sql="INSERT INTO `youtube_data_api_chaines2`( `idchaine`, `titre`, `description`, `image`,`url`) VALUES ('$chaine->idchaine','$chaine->titre','$desc','$image','$chaine->url')";

      try {

         $req = $bdd->prepare($sql);

         $req->execute();

         echo json_encode("insertion reussie");

     }

     catch(Exception $e)

     {

      $return_arr[] = array("erreur" => $e->getMessage(),

                    "requette" => $sql);

      echo json_encode($return_arr);

  }

  }else{

      echo json_encode("pas d'ajout");

  }


  // supression d'une chaine
  if(isset($_POST["channel_to_delete"])){

    $idchaine=$_POST['channel_to_delete'];

    

    $sql="DELETE FROM `youtube_data_api_chaines2` WHERE idchaine like '%$idchaine%'";

    try {

       $req = $bdd->prepare($sql);

       $req->execute();

       echo json_encode("supression reussie");

   }

   catch(Exception $e)

   {

    $return_arr[] = array("erreur" => $e->getMessage(),

                  "requette" => $sql);

    echo json_encode($return_arr);

}

}else{

    echo json_encode("pas de supression");

}




  // supression d'une video
  if(isset($_POST["video_to_delete"])){

    $idvideo=$_POST['video_to_delete'];

    

    $sql="DELETE FROM `youtube_data_api_videos2` WHERE video_id like '%$idvideo%'";

    try {

       $req = $bdd->prepare($sql);

       $req->execute();

       echo json_encode("supression reussie");

   }

   catch(Exception $e)

   {

    $return_arr[] = array("erreur" => $e->getMessage(),

                  "requette" => $sql);

    echo json_encode($return_arr);

}

}else{

    echo json_encode("pas de supression de video");

}

// supression d'une recente video
if(isset($_POST["last_video_to_delete"])){

    $idvideo=$_POST['last_video_to_delete'];

    

    $sql="DELETE FROM `youtube_data_api_last_videos2` WHERE video_id like '%$idvideo%'";

    try {

       $req = $bdd->prepare($sql);

       $req->execute();

       echo json_encode("supression de derniere video reussie");

   }

   catch(Exception $e)

   {

    $return_arr[] = array("erreur" => $e->getMessage(),

                  "requette" => $sql);

    echo json_encode($return_arr);

}

}else{

    echo json_encode("pas de supression de derniere video");

}
?> 