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

        
            <div>
            <div id="chat-container">
        <div id="chat"></div>
        <textarea name="user-input" cols="50" rows="10"  id="user-input" placeholder="Tapez votre message..."></textarea>
        <button id="envoyer" style="display:block">Envoyer</button>
    </div>

    <script>
        function appendMessage(role, content) {
            document.getElementById('chat').innerHTML += `<p><strong>${role}:</strong> ${content}</p>`;
        }

        
    </script>

    <script type="importmap">
      {
        "imports": {
          "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>
    <script type="module">
      import { GoogleGenerativeAI } from "@google/generative-ai";

        // Fetch your API_KEY
        const API_KEY = "AIzaSyBYpcj3uZm4-usKo3pApa6thn8lo-dQSi0";

        // Access your API key (see "Set up your API key" above)
        const genAI = new GoogleGenerativeAI(API_KEY);

        async function run() {
            var userInput = document.getElementById('user-input').value;
            appendMessage('Utilisateur', userInput);
            // For text-only input, use the gemini-pro model
            const model = genAI.getGenerativeModel({ model: "gemini-pro"});

            const prompt = userInput

            const result = await model.generateContent(prompt);
            const response = await result.response;
            const text = response.text();
            appendMessage('Gemini: ',  text);
            console.log(text);
        }
        document.getElementById('envoyer').addEventListener("click", run);
    </script>


            </div>
      
    </body>
</html>