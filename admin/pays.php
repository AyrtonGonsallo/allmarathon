<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

    if($_SESSION['admin'] == false && $_SESSION['pays'] == false){
        header('Location: login.php');
        exit();
    }

    // require_once '../database/connection.php';
    require_once '../database/connexion.php';

    require("../facebook/src/Facebook/autoload.php");

    $fb = new Facebook\Facebook([
        'app_id' => '1174102726417393',
        'app_secret' => '92fc8cd56d71a7156f1e6f02323e918b',
        'default_graph_version' => 'v2.10',
    ]);
      
    //   $linkData = [
    //     'link' => 'http://www.example.com',
    //     'message' => 'User provided message',
    //     ];
      
    //   try {
    //     // Returns a `Facebook\FacebookResponse` object
    //     $response = $fb->post('/me/feed' , $linkData, 'EAAE2Q533v80BAMGmu3tp6Th5KRrDfGpDUX9bhxaJ4ft1b3Gex1C9aGUefdVwOoUdY1k5DGGMZBOL5MoXKhNcMWPr7GvjoRd8CYVQDDiZAfsqoCAHovV2nyP0UEzZAqHCBX7xJqrhGJYQKQVEWlv611Lcd2N0wM3D437GORuMR1DcTbgXZBt1J6amSGV1lnfdgUhXVrICaEqnqi35clTAwpYt50cHQEbKW8biWuOevscztkeaNnmQ');
    //   } catch(Facebook\Exceptions\FacebookResponseException $e) {
    //     echo 'Graph returned an error: ' . $e->getMessage();
    //     exit;
    //   } catch(Facebook\Exceptions\FacebookSDKException $e) {
    //     echo 'Facebook SDK returned an error: ' . $e->getMessage();
    //     exit;
    //   }
      
    //   $graphNode = $response->getGraphNode();
      
    //   echo 'Posted with id: ' . $graphNode['id'];


    $erreur = "";
    $statut=false;
    if( isset($_POST['sub']) ){
        if($_POST['NomPays']=="")
            {$erreur .= "Erreur titre.<br />";}
        if($erreur == ""){
            $fileName = $_FILES['Flag']['name'];
            try {
                
                $req4 = $bdd->prepare("INSERT INTO `pays`( `Abreviation`, `NomPays`,continent, `texte`, `Flag`, `Abreviation_2`, `Abreviation_3`, `Abreviation_4`, `Abreviation_5`,prefixe) VALUES (:ab,:np,:cont,:txt,:flag,:ab2,:ab3,:ab4,:ab5,:pref)");
                $req4->bindValue('pref',$_POST['prefixe'], PDO::PARAM_STR);
                 $req4->bindValue('ab',$_POST['Abreviation'], PDO::PARAM_STR);
                 $req4->bindValue('cont',$_POST['continent'], PDO::PARAM_STR);
                 $req4->bindValue('np',$_POST['NomPays'], PDO::PARAM_STR);
                 $req4->bindValue('txt',$_POST['Texte'], PDO::PARAM_STR);
                 $req4->bindValue('flag',$fileName, PDO::PARAM_STR);
                 $req4->bindValue('ab5',$_POST['Abreviation_5'], PDO::PARAM_STR);
                 $req4->bindValue('ab2',$_POST['Abreviation_2'], PDO::PARAM_STR);
                 $req4->bindValue('ab3',$_POST['Abreviation_3'], PDO::PARAM_STR);
                 $req4->bindValue('ab4',$_POST['Abreviation_4'], PDO::PARAM_STR);
                 $statut=$req4->execute();

                 
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            $paysID = $bdd->lastInsertId();
            if($statut)  {  
                
                $destination_path = "../images/flags/";
                 if(!is_dir($destination_path)){
                       mkdir($destination_path);
                       chmod($destination_path,0777);
                   }
                
                if(!empty($_FILES['Flag']['name'])){
                    /*  cr�ation de l'mage au bon format */
                    $fileinfo = $_FILES['Flag'];
                    $fichierSource = $fileinfo['tmp_name'];
                    $fichierName   = $fileinfo['name'];

                    if ( $fileinfo['error']) {
                          switch ( $fileinfo['error']){
                                   case 1: // UPLOAD_ERR_INI_SIZE
                                    $result = "'Le fichier d�passe la limite autoris�e par le serveur (fichier php.ini) !'";
                                   break;
                                   case 2: // UPLOAD_ERR_FORM_SIZE
                                    $result =  "'Le fichier d�passe la limite autoris�e dans le formulaire HTML !'";
                                   break;
                                   case 3: // UPLOAD_ERR_PARTIAL
                                     $result = "'L'envoi du fichier a �t� interrompu pendant le transfert !'";
                                   break;
                                   case 4: // UPLOAD_ERR_NO_FILE
                                    $result = "'Le fichier que vous avez envoy� a une taille nulle !'";
                                   break;
                          }
                    }else{

                            

                            if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                                $result = "Fichier corectement envoy� !";
                            }else{
                                $result = "Erreur phase finale";
                            }
                        }


                    }
            }
            
        }
    }

    try{
      $req = $bdd->prepare("SELECT * FROM pays ORDER BY ID DESC");
      $req->execute();
      $result1= array();
      while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result1, $row);
      }
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    


?>

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
    <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
    tinyMCE.init({
        // General options
        convert_urls: false,
        mode: "exact",
        elements: "Texte",
        theme: "advanced",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        // Theme options
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
        theme_advanced_buttons3: "undo,redo,|,visualaid,|,tablecontrols"
    });
    </script>

    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>
    <script type="text/javascript">
    $(function() {
        $('#timepicker').datetime({
            userLang: 'en',
            americanMode: false
        });
    });
    </script>

    <script type="text/javascript">
    function addCompletion(str, index) {
        tab = str.split(':');
        idChamp = tab[0];
        name = tab[1];
        nameLink = tab[2];
        document.getElementById("autocomp" + index).style.display = "none";
        $('#temp1').val('');
        $('#result').html('<a href="/athlete-' + idChamp + '-' + nameLink + '.html">' + name +
            '</a> ');
    }

    $(document).ready(function() {
        $('#temp1').keyup(function() {
            if ($(this).val().length > 3)
                $.get('resultatAutoCompletionLien.php', {
                    id: 1,
                    str: $(this).val()
                }, function(data) {
                    $('#autocomp1').show();
                    $('#autocomp1').html(data);
                });
        });

        $('#cut').click(function() {
            $('#temp1').select();

        });
    });
    </script>

    <!-- InstanceEndEditable -->

    <!-- script update auto -->
    <script type="text/javascript">
    function chkit1(uid, chk) {
        chk = (chk == true ? "1" : "0");
        var url = "check_une.php?id=" + uid + "&chkYesNo=" + chk;
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        // Use get instead of post.
        req.open("GET", url, true);
        req.send(null);
    }
    </script>
    <script type="text/javascript">
    function chkit2(uid, chk) {
        
        chk = (chk == true ? "1" : "0");
        var url = "check_deux.php?id=" + uid + "&chkYesNo=" + chk;
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        // Use get instead of post.
        req.open("GET", url, true);
        req.send(null);
    }
    </script>

</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="float:left;">
        <legend>Ajouter un pays</legend>
        <form action="pays.php" method="post" enctype="multipart/form-data">
            <p id="pErreur" align="center"><?php echo $erreur; ?></p>
            <table>
                <tr>
                    <td align="right"><label for="NomPays">NomPays : </label></td>
                    <td><input type="text" name="NomPays" value="" /></td>
                </tr>
                <tr><td><label for="prefixe">Préfixe : </label></td><td><input id="prefixe" type="text" name="prefixe" value="En" /></td></tr>
                <tr>
                    <td> 
                        <label for="continent">Continent : </label></td><td>
                        <select name="continent" style="width: 86%;height: 50px;background: #f6f6f6;text-align:center">
                            <option value="Afrique">Afrique</option>
                            <option value="Amérique du Nord">Amérique du Nord</option>
                            <option value="Amérique du Sud">Amérique du Sud</option>
                            <option value="Asie">Asie</option>
                            <option value="Europe">Europe</option>
                            <option value="Océanie">Océanie</option>
                        </select>
                    </td>
                </tr>                
                <tr>
                    <td align="right"><label for="Abreviation">Abreviation : </label></td>
                    <td><input type="text" name="Abreviation" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_2">Abreviation_2 : </label></td>
                    <td><input type="text" name="Abreviation_2" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_3">Abreviation_3 : </label></td>
                    <td><input type="text" name="Abreviation_3" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_4">Abreviation_4 : </label></td>
                    <td><input type="text" name="Abreviation_4" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Abreviation_5">Abreviation_5 : </label></td>
                    <td><input type="text" name="Abreviation_5" value="" /></td>
                </tr>
    
                <tr>
                    <td align="right"><label for="Texte">Texte : </label></td>
                    <td><textarea name="Texte" cols="30" rows="9"></textarea></td>
                </tr>
                <tr align="center">
                    <td><label for="Flag">Drapeau : </label></td>
                    <td><input type="file" name="Flag" /></td>
                </tr>

                <tr align="center">
                    <td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <fieldset style="float:left;">
        <legend>Liste des pays</legend>
        <div>
        <div id="pager" class="pager">
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
        <br />
            <table class="tab1 tablesorter">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Abreviation	</th>
                        <th>Abreviation_2</th>
                        <th>Abreviation_3</th>
                        <th>Abreviation_4</th>
                        <th>Abreviation_5</th>
                        <th>Préfixe	</th>
                        <th>Continent</th>
                        <th>NomPays	</th>
                        <th>texte</th>
                        <th>Flag</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //while($pays = mysql_fetch_array($result1)){<?>
                    <?php foreach ($result1 as $pays) {
                        $pays_flag_url='https://allmarathon.fr/images/flags/'.$pays['Flag'];
                        echo "<tr align=\"center\" >
                            <td>".$pays['ID']."</td>
                            <td>".$pays['Abreviation']."</td>
                            <td>".$pays['Abreviation_2']."</td>
                            <td>".$pays['Abreviation_3']."</td>
                            <td>".$pays['Abreviation_4']."</td>
                            <td>".$pays['Abreviation_5']."</td>
                            <td>".$pays['prefixe']."</td>
                            <td>".$pays['continent']."</td>
                            <td>".$pays['NomPays']."</td>
                            <td>".$pays['texte']."</td>
                            <td><img src=".$pays_flag_url." alt=''></td>
                            <td>";
                                echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='paysDetail.php?paysID=".$pays['ID']."'\" />
                                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".addslashes($pays['NomPays'])." ?')) { location.href='supprimerPays.php?paysID=".$pays['ID']."';} else { return 0;}\" />";
                            echo "</td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</body>
<!-- InstanceEnd -->

</html>