<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Example</title>
</head>
<body>
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
</body>
</html>