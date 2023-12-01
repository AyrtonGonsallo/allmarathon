<?php
	session_start();

	unset($_SESSION['login']);
	unset($_SESSION['adin']);
	session_destroy();
        header("Location: login.php");

?>
