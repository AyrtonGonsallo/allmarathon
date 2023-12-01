<?php

include("../classes/user.php");
include("../modules/functions.php");

$user=new user();

$email = "";
$isCompleted = false;
if(isset($_GET['email']))
{
    if (isset($_POST["confirm"])) {
        if ($_POST["confirm"] == "on") {
                // $stmt = $db_connection->prepare("UPDATE users  SET newsletter=1 WHERE email=?");
                // $isCompleted = $stmt->execute([$email]);
            $user->unsubscribe(decrypt_newsletter($_GET['email']));
            $isCompleted=true;
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AllMarathon.net | Unsubscribe</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <a href="/">
            <img width="174" height="66"
            src="/images/logo-news.png"
            alt="allmarathon logo" class="float-center"
            style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block; float: none; text-align: center; margin: 3% auto;"
            align="none">
        </a>
        <hr>
        <?php if ($isCompleted) { ?>
        <p style="text-align: center">Vous venez de vous désinscrire de notre newsletter, on espère continuer à vous voir <a href="" target="_blank" style="color: #222;" >le site</a>, sur notre <a href="https://shop.alljudo.net/" target="_blank" style="color: #222;" >boutique</a> ou sur notre <a href="https://www.facebook.com/alljudo.net/" target="_blank" style="color: #222;">page Facebook</a></p>
        <?php } else { ?>
        <div class="row">
            <form class="form-horizontal" action="" method="POST">
                <div class="row">
                    <!-- <div class="col-sm-10"> -->
                    <div class="col-sm-10 col-sm-offset-2">
                            <label  for="confirm">
                               Êtes vous sûr de vouloir vous désabonner ? &nbsp;&nbsp;&nbsp;&nbsp;
                           </label>
                           <input type="checkbox" name="confirm" required>
                    </div>
                    <!-- <div class="col-sm-2">
                       <input type="checkbox" name="confirm" required>
                    </div> -->
                      
                  <!--  </div> -->
               </div>
               <br><br>
            <div class="row">
                <div class="col-sm-offset-5 col-sm-2">
                    <button type="submit" class="btn btn-default">Se désabonner</button>
                </div>
            </div>
        </form>
    </div>
    <?php } ?>
</div>

</body>
</html>
