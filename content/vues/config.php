
<?php
require('../../google-api/vendor/autoload.php');

# Add your client ID and Secret
$client_id = "914127014227-rugj4pp18ddofk3dqtf8234imqkgp57p.apps.googleusercontent.com";
$client_secret = "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";

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);

# redirection location is the path to login.php
$redirect_uri = 'https://allmarathon.fr/content/vues/login-google.php';
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");