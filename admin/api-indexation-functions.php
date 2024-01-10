<?php 
    
    
    function update_url($url){

        //require_once 'content/modules/vendor/autoload.php';
        require('../google-api/vendor/autoload.php');

        $client = new Google_Client();

        // service_account_file.json is the private key that you created for your service account.
        $client->setAuthConfig('../content/modules/lib/google-api-php-client/allmarathon-6a6eaacd3a7a.json');
        $client->addScope('https://www.googleapis.com/auth/indexing');;

        // Get a Guzzle HTTP Client
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
        // Define contents here. The structure of the content is described in the next step.
        $content = '{
            "url": "'.$url.'",
            "type": "URL_UPDATED"
            }';
        
        $response = $httpClient->post($endpoint, [ 'body' => $content ]);
        $status_code = $response->getStatusCode();
        $response_json = array();
        //print_r($response);
        //echo "\n";
        //echo "$status_code";
        $response_json ["code"] =  $status_code;
        $response_json ["repsonse"] = $response->getBody()->getContents();;
        echo json_encode($response_json );
    }

    function delete_url($url){

        //require_once 'content/modules/vendor/autoload.php';
        require('../google-api/vendor/autoload.php');

        $client = new Google_Client();

        // service_account_file.json is the private key that you created for your service account.
        $client->setAuthConfig('../content/modules/lib/google-api-php-client/allmarathon-6a6eaacd3a7a.json');
        $client->addScope('https://www.googleapis.com/auth/indexing');;

        // Get a Guzzle HTTP Client
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
        // Define contents here. The structure of the content is described in the next step.
        $content = '{
            "url": "'.$url.'",
            "type": "URL_DELETED"
            }';
        
        $response = $httpClient->post($endpoint, [ 'body' => $content ]);
        $status_code = $response->getStatusCode();
        $response_json = array();
        //print_r($response);
        //echo "\n";
        //echo "$status_code";
        $response_json ["code"] =  $status_code;
        $response_json ["repsonse"] = $response->getBody()->getContents();;
        echo json_encode($response_json );
    }

    if(isset($_POST['function'])){
		$function=$_POST['function'];
		if($function=="update_url"){
			$url=$_POST['url'];
			update_url($url);
			//echo '<div>'.$order.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
        if($function=="delete_url"){
			$url=$_POST['url'];
			delete_url($url);
			//echo '<div>'.$order.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
    }
?>


