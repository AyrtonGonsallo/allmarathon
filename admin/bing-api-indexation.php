
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
    <script type="text/javascript">
    $(document).ready(function()
        {
            $("table.tablesorter")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager")});
        }
    );

</script>

    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>
    

   
    

</head>

<body>
    <?php require_once "menuAdmin.php"; 
    setlocale(LC_TIME, "fr_FR","French");
    ?>
    <div class="row justify-content-center">
        <div>
            <fieldset>
                <legend>Indexer un ensemble d'urls</legend>
                <?php

                function slugify($text)
                {
                // Swap out Non "Letters" with a -
                $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
                
                   // Trim out extra -'s
                $text = trim($text, '-');
                   // Make text lowercase
                   $text = strtolower($text);
                   return $text;
                }

                 


                                    
                    function get_evenements_to_discover(){
                        // Récupération des données dans la base de données (MySQL)
                        require_once '../database/connexion.php';
                        $result1= array();
                        try{
                                $req = $bdd->prepare("SELECT * FROM evenements");
                                $req->execute();
                                
                                while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                                    array_push($result1, $row);
                                }
                                return $result1;
                            }
                            catch(Exception $e)
                            {
                                die('Erreur : ' . $e->getMessage());
                            }
                    }

                    function add_url_to_discover($result1){ 
                        // Boucle qui liste les URL
                        $results= array();
                        foreach ($result1 as $res) {
                            $nom_res_lien_archive=$cat_event.' - '.$res['Nom'].' - '.strftime("%A %d %B %Y",strtotime($res['DateDebut']));
                            $loc        = 'https://dev.allmarathon.fr/resultats-marathon-'.$res['ID'].'-'.slugify($nom_res_lien_archive).'.html';
                           
                            array_push($results, $loc );
                        }    
                        return  $results;
                       
                    }

                    //init
                    $results_f= add_url_to_discover(get_evenements_to_discover());


                    echo ' <div id="pager" class="pager">
                    <form>
                        <img src="../fonction/tablesorter/first.png" class="first"/>
                        <img src="../fonction/tablesorter/prev.png" class="prev"/>
                        <input type="text" class="pagedisplay"/>
                        <img src="../fonction/tablesorter/next.png" class="next"/>
                        <img src="../fonction/tablesorter/last.png" class="last"/>
                        <select class="pagesize">
                            <option selected="selected"  value="10">10</option>
        
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option  value="40">40</option>
                        </select>
                    </form>
                </div>
                <br /><table class="tablesorter tab1">
                    <thead>
                    <tr>
                        <td align="right">urls</td>
                        
                    </tr>
                    </thead><tbody>';
                    foreach ($results_f as $res) {
                      
                        echo '<tr><td align="right">'.$res.'</td></tr>';
                    }    
                    echo '</tbody></table>';

                    // Données à envoyer
                    $data = array(
                        "host" => "dev.allmarathon.fr",
                        "key" => "86c419008d974fb98ae6b74501802971",
                        "keyLocation" => "https://dev.allmarathon.fr/86c419008d974fb98ae6b74501802971.txt",
                        "urlList" => $results_f
                    );

                    // Convertir les données en format JSON
                    $data_json = json_encode($data);

                    // URL de l'API
                    $url = 'https://api.indexnow.org/IndexNow';

                    // Initialiser une session cURL
                    $ch = curl_init($url);

                    // Configurer les options de cURL
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: ' . strlen($data_json)
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Exécuter la requête cURL
                    $response = curl_exec($ch);

                    // Vérifier s'il y a des erreurs
                    if(curl_errno($ch)){
                        echo 'Erreur cURL : ' . curl_error($ch);
                    }

                    // Fermer la session cURL
                    curl_close($ch);

                    // Gérer la réponse
                    echo $response;

                    ?>
            </fieldset>
        </div>
        
        
    </div>
    

    
</body>
<!-- InstanceEnd -->

</html>



<style>
    .success{
        background-color: #90d390;
        color: white;
        font-size: 16px;
        font-weight: bolder;
        padding: 20px;
    }
    .failure{
        background-color: #dd0c0c;
        color: white;
        font-size: 16px;
        font-weight: bolder;
        padding: 20px;
    }
    .envoyer{
        padding: 10px 20px;
        background-color: #fbff0b;
        border: none;
        font-weight: bold;
        text-transform: uppercase;
    }
    .row{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap:50px;
    }
    input{
        width:90% !important;
    }
    table{
        width:95% !important;
    }

    @media only screen and (max-width:801px) {
        .row{
             grid-template-columns: 1fr !important;
        }
    }
    </style>
<!--
curl -X -i POST \
  https://api.indexnow.org/IndexNow \
  -H 'Content-Type: application/json; charset=utf-8' \
  -d '{
  "host": "dev.allmarathon.fr",
  "key": "86c419008d974fb98ae6b74501802971",
  "keyLocation": "https://dev.allmarathon.fr/86c419008d974fb98ae6b74501802971.txt",
  "urlList": [
    "https://dev.allmarathon.fr/calendrier-agenda-marathons.html",
    "https://dev.allmarathon.fr/resultats-marathon.html",
    "https://dev.allmarathon.fr/liste-des-athletes.html"
  ]
}'

-->