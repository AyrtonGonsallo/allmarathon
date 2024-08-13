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

    if($_SESSION['admin'] == false && $_SESSION['news'] == false){
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
        if($_POST['Titre']=="")
            {$erreur .= "Erreur titre.<br />";}
        if($erreur == ""){
            $fileName = $_FILES['photo']['name'];
            try {
                
                    $req4 = $bdd->prepare("INSERT INTO news (date,source,auteur,titre,chapo,texte,photo,admin,liens_champions,aLaUne,aLaDeux,categorieID,url,legende,lien1,textlien1,evenementID,championID,videoID,bref) VALUES (:date,:source,:auteur,:titre,:chapo,:texte,:photo,:admin,:liens_champions,:aLaUne,:aLaDeux,:categorie,:url,:legende,:lien1,:textlien1,:evenementID,:championID,:videoID,:bref)");
                
                
                
                 $req4->bindValue('date',$_POST['Date'], PDO::PARAM_STR);
                 $req4->bindValue('source',$_POST['Source'], PDO::PARAM_STR);
                 $req4->bindValue('auteur',$_POST['auteur'], PDO::PARAM_STR);
                 $req4->bindValue('titre',$_POST['Titre'], PDO::PARAM_STR);
                 $req4->bindValue('aLaUne',$_POST['aLaUne'], PDO::PARAM_STR);
                 $req4->bindValue('aLaDeux',$_POST['aLaDeux'], PDO::PARAM_STR);
                 $req4->bindValue('bref',$_POST['bref'], PDO::PARAM_STR);
                 $req4->bindValue('liens_champions',$_POST['liens_champions'], PDO::PARAM_STR);
                 $req4->bindValue('chapo',$_POST['Chapo'], PDO::PARAM_STR);
                 $req4->bindValue('texte',$_POST['Texte'], PDO::PARAM_STR);
                 $req4->bindValue('photo',$fileName, PDO::PARAM_STR);
                 $req4->bindValue('url',$_POST['Url'], PDO::PARAM_STR);
                 $req4->bindValue('legende',$_POST['Legende'], PDO::PARAM_STR);
                 $req4->bindValue('lien1',$_POST['Lien1'], PDO::PARAM_STR);
                 $req4->bindValue('textlien1',$_POST['TextLien1'], PDO::PARAM_STR);
                 $req4->bindValue('evenementID',$_POST['evenementID'], PDO::PARAM_INT);
                 $req4->bindValue('categorie',$_POST['categorieID'], PDO::PARAM_INT);
                 $req4->bindValue('videoID',$_POST['videoID'], PDO::PARAM_INT);
                 $req4->bindValue('championID',$_POST['championID'], PDO::PARAM_INT);
                 $req4->bindValue('admin',$_SESSION['login'], PDO::PARAM_STR);
                 $statut=$req4->execute();

                 
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            $newsID = $bdd->lastInsertId();
            if($statut)  {  
                $array       = explode('-',$_POST['Date']);
                $destination_path = "../images/news/".$array[0]."/";
                 if(!is_dir($destination_path)){
                       mkdir($destination_path);
                       chmod($destination_path,0777);
                   }
                
                if(!empty($_FILES['photo']['name'])){
                    /*  cr�ation de l'mage au bon format */
                    $fileinfo = $_FILES['photo'];
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

                            $x = 414;
                            $size = getimagesize($fichierSource);
                            $y = ($x * $size[1]) / $size[0];
                            $img_new = imagecreatefromjpeg($fichierSource);

                            $img_mini = imagecreatetruecolor($x, $y);
                            imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                            imagejpeg($img_mini, $destination_path . "thumb_" . strtolower($fichierName));

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
      $req = $bdd->prepare("SELECT * FROM news  ORDER BY ID DESC");
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

    function get_categorie($bdd,$cid){
        if($cid==null or $cid==0){
            return array("Intitule"=>"");
        }
        $req33 = $bdd->prepare("SELECT * FROM newscategorie WHERE ID=:id");
        $req33->bindValue('id',$cid, PDO::PARAM_INT);
        $req33->execute();
        $cat= $req33->fetch(PDO::FETCH_ASSOC);
        return $cat;
    }

    // $query1    = sprintf('SELECT * FROM news ORDER BY ID DESC');
    // $result1   = mysql_query($query1);
      try{
          $req1 = $bdd->prepare("SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC");
          $req1->execute();
          $result2= array();
          while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result2, $row);
          }
      }
      catch(Exception $e)
      {
          die('Erreur : ' . $e->getMessage());
      }

    // $query2    = sprintf('SELECT E.ID,E.Nom,E.DateDebut,E.CategorieID,C.Intitule FROM evenements E INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID ORDER BY E.ID DESC');
    // $result2   = mysql_query($query2);
    
      try{
            $req3 = $bdd->prepare("SELECT * FROM newscategorie ORDER BY Intitule");
            $req3->execute();
            $result3= array();
            while ( $row  = $req3->fetch(PDO::FETCH_ASSOC)) {  
              array_push($result3, $row);
            }
        }
      catch(Exception $e)
      {
          die('Erreur : ' . $e->getMessage());
      }
    // $query3    = sprintf('SELECT * FROM newscategorie ORDER BY Intitule');
    // $result3   = mysql_query($query3);


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
        <legend>Ajouter news</legend>
        <form action="news.php" method="post" enctype="multipart/form-data">
            <p id="pErreur" align="center"><?php echo $erreur; ?></p>
            <table>
                <tr>
                    <td align="right"><label>A la une : </label></td>
                    <td><input type="radio" name="aLaUne" id="oui" value="1" /><label
                            for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="aLaUne" id="non" value="0"
                            checked="checked" /><label for="non">non</label></td>
                </tr>
                <tr>
                    <td align="right"><label>A la deux : </label></td>
                    <td><input type="radio" name="aLaDeux" id="oui" value="1" /><label
                            for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="aLaDeux" id="non" value="0"
                            checked="checked" /><label for="non">non</label></td>
                </tr>
                <tr>
                    <td align="right"><label>En bref : </label></td>
                    <td><input type="radio" name="bref" id="oui" value="1" /><label
                            for="oui">oui</label>&nbsp;&nbsp;&nbsp;<input type="radio" name="bref" id="non" value="0"
                            checked="checked" /><label for="non">non</label></td>
                </tr>
                <tr>
                    <td align="right"><label for="Date">Date : </label></td>
                    <td><input type="text" name="Date" id="timepicker" value="<?php echo date("Y-m-d G:i:s")?>" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Source">Source : </label></td>
                    <td><input type="text" name="Source" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="auteur">Nom de l'auteur : </label></td>
                    <td><input type="text" name="auteur" value="Laurent MATHIEU" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Titre">Titre : </label></td>
                    <td><input type="text" name="Titre" value="" /></td>
                </tr>
                
                <tr>
                    <td align="right"><label for="Chapo">Chapo : </label></td>
                    <td><textarea name="Chapo" cols="50" rows="7"></textarea></td>
                </tr>
                <tr>
                    <td align="right"><label for="Texte">Texte : </label></td>
                    <td><textarea name="Texte" cols="30" rows="9"></textarea></td>
                </tr>
                <tr>
                    <td align="right"><label for="Url">Url : </label></td>
                    <td><input type="text" name="Url" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Legende">L&eacute;gende : </label></td>
                    <td><input type="text" name="Legende" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="Lien1">Lien 1 : </label></td>
                    <td><input type="text" name="Lien1" value="" /></td>
                </tr>
                <tr>
                    <td align="right"><label for="TextLien1">Texte lien 1 : </label></td>
                    <td><input type="text" name="TextLien1" value="" /></td>
                </tr>
                <tr>
                    <td><label>Liens champions </label></td>
                    <td>
                        <div id="autoCompChamp1">
                            <input autocomplete="off" type="text" id="temp1" value="" />
                            <div id="autocomp1" style="display:none;" class="autocomp"></div>
                            <input style="display:none;" id="champion1" name="liens_champions" type="text" value="" />
                            <div id="result" style="display: inline;"></div </div>


                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="categorieID">catégorie : </label>
                    </td>
                    <td>
                        
                        
                        <select name="categorieID" >
                        <?php //while($pays = mysql_fetch_array($result4)){
                            foreach ($result3 as $cat) {
                               
                                    echo '<option value="'.$cat['ID'].'">'.$cat['Intitule'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr><td><label for="evenementID">évènement lié : </label></td><td><input id="evenementID" type="number" name="evenementID" value="" /></td></tr>
                <tr><td><label for="championID">coureur lié : </label></td><td><input id="championID" type="number" name="championID" value="" /></td></tr>
                <tr><td><label for="videoID">video lié : </label></td><td><input id="videoID" type="number" name="videoID" value="" /></td></tr>


                <tr align="center">
                    <td><label for="photo">Photo : </label></td>
                    <td><input type="file" name="photo" /></td>
                </tr>

                <tr align="center">
                    <td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <fieldset style="float:left;">
        <legend>Liste des news</legend>
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
                        <th>Catégorie</th>
                        <th>Titre</th>
                        <th>Une</th>
                        <th>Deux</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //while($news = mysql_fetch_array($result1)){<?>
                    <?php foreach ($result1 as $news) {
                        $cat=get_categorie($bdd,$news["categorieID"]);
			$incheck_deux='<input type="checkbox" name="check_deux" value="'.$news['ID'].'" checked="checked" onclick="chkit2('.$news['ID'].',this.checked);"/>';
			$uncheck_deux='<input type="checkbox" name="uncheck_deux" value="'.$news['ID'].'" onclick="chkit2('.$news['ID'].',this.checked);"/>';
            $deux = ($news['aLaDeux']) ? ''.$incheck_deux.'' : ''.$uncheck_deux.'';
			$incheck_une='<input type="checkbox" name="check_une" value="'.$news['ID'].'" checked="checked" onclick="chkit1('.$news['ID'].',this.checked);"/>';
			$uncheck_une='<input type="checkbox" name="uncheck_une" value="'.$news['ID'].'" onclick="chkit1('.$news['ID'].',this.checked);"/>';
            echo "<tr align=\"center\" ><td>".$news['ID']."</td><td>".$cat['Intitule']."</td><td>".$news['titre']."</td><td>",($news['aLaUne'])?"".$incheck_une."":"".$uncheck_une."","</td><td>" . $deux . "</td><td>".$news['date']."</td>
                <td>";
            if($news['admin'] == $_SESSION['login'] || $_SESSION['admin'] == true){
                echo "<img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='newsDetail.php?newsID=".$news['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".addslashes($news['titre'])." ?')) { location.href='supprimerNews.php?newsID=".$news['ID']."';} else { return 0;}\" />";
            }
            echo "</td></tr>";
        } ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</body>
<!-- InstanceEnd -->

</html>