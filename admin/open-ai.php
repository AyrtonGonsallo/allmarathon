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



$erreur = "";

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
              
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>

        <fieldset style="float:left;">
            <legend>MODIFIER</legend>
            <div>
            <div id="chat-container">
        <div id="chat"></div>
        <input type="text" id="user-input" placeholder="Tapez votre message...">
        <button onclick="sendMessage()">Envoyer</button>
    </div>

    <script>
        function appendMessage(role, content) {
            document.getElementById('chat').innerHTML += `<p><strong>${role}:</strong> ${content}</p>`;
        }

        function sendMessage() {
            var userInput = document.getElementById('user-input').value;
            appendMessage('Utilisateur', userInput);

            // Appel AJAX vers le script PHP
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'https://api.openai.com/v1/chat/completions');
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Authorization", "Bearer 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");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    var chatGPTResponse = response.choices[0].message.content;
                    appendMessage('ChatGPT', chatGPTResponse);
                }
            };
            const data = JSON.stringify({
            'model': 'gpt-3.5-turbo',
            'messages': [
                {
                'role': 'system',
                'content': 'You are a helpful assistant.'
                },
                {
                'role': 'user',
                'content': userInput
                }
            ]
            });
            xhr.send(data);
        }
    </script>


            </div>
        </fieldset>
    </body>
</html>