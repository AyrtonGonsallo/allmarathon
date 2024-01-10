
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->

<head>
    <meta charset="utf-8">
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html;" /> <!-- charset=iso-8859-1 -->
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/datepicker_time.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/timepicker.css" rel="stylesheet" type="text/css" />
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <script src="../fonction/ui/js/timepicker.js" type="text/javascript"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>



    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>
    

   
    

</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Test</legend>
        <?php 

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
            "url": "https://careers.google.com/jobs/google/technical-writer",
            "type": "URL_UPDATED"
            }';

            $response = $httpClient->post($endpoint, [ 'body' => $content ]);
            $status_code = $response->getStatusCode();
            print_r($response);
            
            echo "\n";
            echo "$status_code";
            echo $response->getBody()->getContents();
            
        ?>
    </fieldset>

   
</body>
<!-- InstanceEnd -->

</html>





