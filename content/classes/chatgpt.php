<?php
$api_key = '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'; // Remplacez par votre clé API
//secrete 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

function chatGPT($messages) {
    global $api_key;

    $url = 'https://api.openai.com/v1/chat/completions';
    
    $data = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
    );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return json_decode($result, true);
}
?>