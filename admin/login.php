<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// require_once '../database/connection.php';
require_once '../database/connexion.php';
$erreur = "";
if(isset($_POST['submit']) && $_POST['submit']=='connexion'){
  $req = $bdd->prepare("SELECT * FROM admin WHERE login=:login AND password=:password");
  $req->bindValue('login',$_POST['login'], PDO::PARAM_STR);
  $req->bindValue('password',sha1($_POST['password']), PDO::PARAM_STR);
  $req->execute();
  $user= $req->fetch(PDO::FETCH_ASSOC);


  // $query1    = sprintf("SELECT * FROM admin WHERE login='%s' AND password='%s'"
  //   ,mysql_real_escape_string($_POST['login'])
  //   ,mysql_real_escape_string(sha1($_POST['password'])));
  // $result1   = mysql_query($query1) or die(mysql_error());
  if($user){
    $_SESSION['login']=$user['login'];
    if($user['permission'] == "admin")
      $_SESSION['admin']=true;
    else
      $_SESSION['admin']=false;

    if(!$_SESSION['admin']){
      header('Location: evenementImportantDirect.php');
    }

    $erreur = "Authentification r�ussie";
  }
  else{
    $erreur = "Identifiants incorrect !";
  }
}

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

    <title>Admin : login</title>


    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>

    <p style="Color:red;"><?php echo $erreur; ?></p>
    <?php if(!isset($_SESSION['login'])) {?>
    <div class="wrapper fadeInDown">


        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="/images/allmarathon.png" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->

            <form action="login.php" method="post">
                <input type="text" id="login" class="fadeIn second" name="login" placeholder="Login">
                <input type="text" id="password" class="fadeIn third" name="password" placeholder="Mot de passe">
                <input type="submit" name="submit" class="fadeIn fourth" value="connexion">
            </form>

            <!-- Remind Passowrd -->
            <!-- <div id="formFooter">
                <a class="underlineHover" href="#">Forgot Password?</a>
            </div> -->

        </div>

    </div>
    <?php } ?>

    <!-- <h1>IDENTIFICATION</h1> -->
    <!-- <fieldset>
        <p style="Color:red;"><?php echo $erreur; ?></p>
        <?php if(!isset($_SESSION['login'])) {?>
        <form action="login.php" method="post">
            <table>
                <tr>
                    <td><label for="login">Login : </label></td>
                    <td><input type="text" name="login" id="login" /></td>
                </tr>
                <tr>
                    <td><label for="password">Mot de passe : </label></td>
                    <td><input type="password" name="password" id="password" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="connexion" /></td>
                </tr>
            </table>
        </form>
        <?php } ?>
    </fieldset> -->
    <br />

</body>

</html>