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
        <script src="../Scripts/CsvToTable.js"></script>
        <title>allmarathon admin</title>
        <style>
		table {
		  margin: 0 auto;
		  text-align: center;
		  border-collapse: collapse;
		  border: 1px solid #d4d4d4;
		  height: 500px; 
		}
		 
		tr:nth-child(even) {
		  background: #d4d4d4;
		}
		 
		th, td {
		  padding: 10px 30px;
		}
		 
		th {
		  border-bottom: 1px solid #d4d4d4;
		}
	</style>   
    </head>

    <body>
        <?php require_once "menuAdmin.php"; ?>

        
            <div>
            <div id="chat-container">
        <div id="chat"></div>
        <textarea name="user-input" cols="50" rows="10" id="user-input" placeholder="Règles à suivre...">
            1ere colonne : Prénom Nom (respecter la casse)
            2e colonne : Sexe (M pour les hommes et F pour les femmes)
            3e colonne : Pays (utiliser les 3 lettres du code ISO du pays. Exemple : FRA pour la France)
            4e colonne : Classement (1 pour le premier, 2 pour le deuxième, ect..)
            5e colonne : le temps réalisé ou chrono (sous la forme 02:10:20 pour un temps de deux heures, dix minutes et vingt secondes)
        </textarea>
        <textarea name="user-input" cols="80" rows="10" id="user-data" placeholder="Vos données(élement html)..."></textarea>
        <button onclick="sendMessage()" style="display:block">Envoyer</button>
    </div>

    <script>
        function appendMessage(role, content) {
            document.getElementById('chat').innerHTML += `<p><strong>${role}:</strong> ${content}</p>`;
        }

        function sendMessage() {
            var userInput = document.getElementById('user-input').value;
            var userData = document.getElementById('user-data').value
            appendMessage('Utilisateur', "Extrait les résultats suivants. "+"Suis ces règles et affiche moi un csv : "+userInput);

            // Appel AJAX vers le script PHP
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'https://api.openai.com/v1/chat/completions');
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Authorization", "Bearer sk-proj-Hkc7oeW930GRgHVqUMoFT3BlbkFJOCSshUxDT7rHXLhFp7A6");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    var chatGPTResponse = response.choices[0].message.content;
                   
                    var csvtotable = new CsvToTable({
		                csvData: chatGPTResponse
                    });
                    csvtotable.run();

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
                'content': "Extrait les résultats suivants: "+userData+". Suis ces règles et affiche moi un csv : "+userInput
                }
            ]
            });
            xhr.send(data);
        }
    </script>


            </div>
        
    </body>
</html>