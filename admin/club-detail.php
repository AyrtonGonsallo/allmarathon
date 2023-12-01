<?php
require_once('Templates/_sessionCheck.php');
require_once 'database/connection.php';


$club_id = (isset($_GET['clubID']))?(int)$_GET['clubID']:exit('error');

//
$queryAdmin  = sprintf('SELECT u.username,u.user_id FROM phpbb_users u INNER JOIN club_admin_externe p ON u.user_id = p.user_id WHERE p.actif = 1 AND p.club_id = %s',$club_id);
$resultAdmin = mysql_query($queryAdmin) or die(mysql_error());

//$queryFan  = sprintf('SELECT u.username,u.user_id FROM phpbb_users u INNER JOIN club_popularite c ON u.user_id = c.user_id WHERE c.champion_id=\'%s\'',$champion_id);
//$resultFan = mysql_query($queryFan);

if($connected) {
    $queryDemandeEnCour  = sprintf('SELECT * FROM club_admin_externe WHERE user_id=%s AND club_id = %s AND actif=0',$_SESSION['user_id'],$club_id);
    $resultDemandeEnCour = mysql_query($queryDemandeEnCour);
    $demandeEnCour       = (mysql_num_rows($resultDemandeEnCour) == 0)?false:true;

    $queryUserAdmin  = sprintf('SELECT * FROM club_admin_externe WHERE user_id=%s AND club_id = %s AND actif=1',$_SESSION['user_id'],$club_id);
    $resultUserAdmin = mysql_query($queryUserAdmin);
    $userAdmin       = (mysql_num_rows($resultUserAdmin) == 0)?false:true;
}


$query1    = sprintf('SELECT * FROM clubs WHERE ID = %s',$club_id);
$result1   = mysql_query($query1) or die(mysql_error());
$club      = mysql_fetch_array($result1);


$queryPays  = sprintf("SELECT P.Abreviation,P.NomPays,P.Flag FROM pays P INNER JOIN clubs C ON C.pays=P.Abreviation ORDER BY NomPays");
$resultPays = mysql_query($queryPays) or die(mysql_error());
while($p = mysql_fetch_array($resultPays)) {
    $paysTab[$p['Abreviation']]=$p['Flag'];
    $paysNomTab[$p['Abreviation']]=$p['NomPays'];
}


//pub 728x90
$query_rs728 = "SELECT code FROM banniere728x90 WHERE actif='actif' AND restriction LIKE '%annuaires%' ORDER BY RAND() LIMIT 1";
$rs728       = mysql_query($query_rs728) or die(mysql_error());
$row_rs728   = mysql_fetch_assoc($rs728);
//partenaire
$queryPartner  = sprintf('SELECT * FROM partenaires ORDER BY RAND() DESC LIMIT 1');
$resultPartner = mysql_query($queryPartner) or die(mysql_error());
$partenaire    = mysql_fetch_array($resultPartner);
// pub 336*280
$query_rsBanniere336 = "SELECT code FROM banniere336x280 WHERE actif='actif' AND restriction LIKE '%annuaires%' ORDER BY RAND() LIMIT 1";
$rsBanniere336       = mysql_query($query_rsBanniere336) or die(mysql_error());
$row_rsBanniere336   = mysql_fetch_assoc($rsBanniere336);
// pub 300*250
$query_rsBanniere300 = "SELECT code FROM banniere300x250 WHERE actif='actif' AND restriction LIKE '%annuaires%' ORDER BY RAND() LIMIT 1";
$rsBanniere300       = mysql_query($query_rsBanniere300) or die(mysql_error());
$row_rsBanniere300   = mysql_fetch_assoc($rsBanniere300);
// pub 160*600
$query_rsBanniere160 = "SELECT code FROM banniere160x600 WHERE actif='actif' AND restriction LIKE '%annuaires%' ORDER BY RAND() LIMIT 1";
$rsBanniere160       = mysql_query($query_rsBanniere160) or die(mysql_error());
$row_rsBanniere160   = mysql_fetch_assoc($rsBanniere160);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php require_once("Templates/_headerScript.php") ?>
        <title>Annuaire des clubs de marathon</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="Description" content="Allmarathon.net, tout le marathon en France et dans le monde. Annuaire des clubs de marathon, ju-jitsu, taiso, self-defense, baby-marathon." lang="fr" xml:lang="fr" />
        <meta name="Keywords" content="club de marathon, trouver un club, chercher clubs, pratiquer le marathon, ju-jitsu, taiso, self-defense, baby-marathon" lang="fr" xml:lang="fr"/>

        <link href="styles/annuaire2009.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            function sortPays(){
                selected = document.getElementById('reroutage').selectedIndex;
                sort = document.getElementById('reroutage')[selected].value;
                window.location.href = 'contact-clubs-de-marathon-'+sort+'.html';
            }
            function sortDepartement(){
                selected = document.getElementById('reroutage2').selectedIndex;
                sort = document.getElementById('reroutage2')[selected].value;
                window.location.href = 'contact-clubs-de-marathon-<?php echo $pays; ?>-'+sort+'.html';
            }
        </script>

    </head>
    <body onload="MM_preloadImages('images/CSS/boutique1.png','images/CSS/flux1.png');l">
        <div id="surcontainer">
            <div id="container">
                <?php require_once("Templates/_header.php") ?>
                <div id="content">
                    <div id="colun">
                        <h1 style="margin-bottom: 4px;font-size: 1.5em;color: black;padding: 3px;font-weight: bold;height: auto;"><img src="images/flags/<?php echo $paysTab[$club['pays']] ?>" alt="" style="float: right;" width="31" height="25" /><?php echo $club['club'] ?></h1>
                        <p id="vote" style="margin-bottom: 20px;margin-top: 0px;font-size: 0.8em;">
                            <?php if($demandeEnCour): ?>
                            Votre demande est en attente de validation.
                            <?php elseif($userAdmin ):?>
                            Vous pouvez modifier la fiche en cliquant ici : <a href="club-admin.php?clubID=<?php echo $club_id ?>" >administration</a>
                            <?php else:?>
                            <a href="formulaire-administration-club.php?clubID=<?php echo $club_id ?>"> Devenir administrateur de la fiche du  <?php echo $club['club']; ?> <img src="images/CSS/administrateur.png" alt="" style="border: 0;margin-bottom: -4px;" /></a>
                            <?php endif;?>
                            <?php if(mysql_num_rows($resultAdmin) != 0 && !$userAdmin): ?>
                            Cette fiche est administrer par
                                <?php while( $admin = mysql_fetch_array($resultAdmin)): ?>
                            <a href="forum/ucp.php?i=pm&mode=compose&u=<?php echo $admin[user_id] ?>"><?php echo $admin[username] ?></a>
                                <?php endwhile;?>
                            <?php endif;?>
                        </p>


                        <div id="CV">
                            <table>
                                <tr>
                                    <?php if($club['gaddress'] != ""): ?>
                                    <td id="gmap" width="320">
                                        <div id="google_map" style="width: 320px;height: 240px;">
                                            <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAABViXIHiGZVTpberw8QWIXBTbXMVTG-lx21G4BZgI-Y3P0Y3vmRRi1XMgPDMsQqAVWCa84JecpI3lTA"></script>
                                            <script type="text/javascript">
                                                google.load("maps", "2.x",{"other_params":"sensor=false"});
                                                var baseIcon;
                                                var map;
                                                // Call this function when the page has been loaded
                                                function initialize() {
                                                    map = new google.maps.Map2(document.getElementById("google_map"));
                                                    map.setCenter(new google.maps.LatLng(<?php echo $club[gcoo1] ?>, <?php echo $club[gcoo2] ?>), 13);
                                                    
                                                    map.addControl(new GLargeMapControl3D());

                                                    baseIcon = new GIcon(G_DEFAULT_ICON);
                                                    baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
                                                    baseIcon.iconSize = new GSize(20, 34);
                                                    baseIcon.shadowSize = new GSize(37, 34);
                                                    baseIcon.iconAnchor = new GPoint(9, 34);
                                                    baseIcon.infoWindowAnchor = new GPoint(9, 2);
                                                    var point = new GLatLng('<?php echo $club[gcoo1] ?>','<?php echo $club[gcoo2] ?>');


                                                    if(point !== false){
                                                        map.addOverlay(createMarker(point, '<?php echo $club[club] ?>', 'A', '<img src="http://www.apercite.fr/api/apercite/240x180/oui/oui/<?php echo $club[site]; ?>" alt="" style="float:left;margin-right:5px;margin-top:7px;" width="50" /><div style="float:left;width:167px;margin-top:7px;"><b><?php echo htmlentities(str_replace('\'','?',$club[club])) ?></b><br /><i><?php echo htmlentities(str_replace('\'','?',$club[gaddress])) ?></i><br /><?php echo str_replace('\'','?',$club[telephone]) ?></div>'));
                                                        //

                                                    }
                                                }
                                                function createMarker(point, titre, letter, html) {
                                                    var letteredIcon = new GIcon(baseIcon);

                                                    letteredIcon.image = "http://www.google.com/mapfiles/marker" + letter + ".png";
                                                    markerOptions = { icon:letteredIcon };
                                                    //alert("22");
                                                    var marker = new GMarker(point, markerOptions);
                                                    marker.value = titre;
                                                    //alert("23");
                                                    GEvent.addListener(marker, "click", function() {
                                                        var myHtml =  html;
                                                        map.openInfoWindowHtml(point, myHtml);
                                                    });
                                                    return marker;
                                                }
                                                google.setOnLoadCallback(initialize);

                                            </script>
                                        </div>

                                    </td>
                                    <?php endif; ?>
                                    <td width="300" valign="top">
                                        <?php if($club[responsable] != ""): ?><strong>Responsable : </strong><?php echo str_replace("\\","",$club[responsable]) ?><br /><?php endif; ?>
                                        <?php if($club[telephone] != ""): ?><strong>T�l�phone : </strong><?php echo $club[telephone] ?><br /><?php endif; ?>
                                        <?php if($club[mel] != ""): ?><strong>Email : </strong><a href="mailto:<?php echo $club[mel] ?>"><?php echo $club[mel] ?></a><br /><?php endif; ?>
                                        <?php if($club[adresse] != ""): ?><strong>Adresse : </strong><?php echo $club[adresse] ?><br /><?php endif; ?>
                                        <?php if($club[CP] != ""): ?><strong>Ville : </strong><?php echo $club[CP] ?><?php endif; ?>
                                        <?php if($club[ville] != ""): ?> - <?php echo $club[ville] ?> - <?php echo $paysNomTab[$club[pays]] ?><br /><?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            <h2>Le site du <?php echo $club[club] ?></h2>
                            <table width="100%">
                                <tr>
                                    <td>
                                        <a href="<?php echo $club[site]; ?>"><img src="http://www.apercite.fr/api/apercite/320x240/oui/oui/<?php echo $club[site]; ?>" width="320" height="240" vspace="8" border="0" alt="apercu" /></a>
                                    </td>
                                    <td valign="top">
                                        <?php if($club[site] != ""): ?><a href="<?php echo $club[site] ?>"><?php echo str_replace('http://', '', $club[site]) ?></a><br /><?php endif; ?>
                                        <?php if($club[description] != ""): ?><strong>A savoir : </strong><?php echo $club[description] ?><br /><?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="clear:both;text-align:center;padding-top:25px;"><?php echo $row_rsBanniere336['code']; ?></div>
                    </div>
                    <div id="coldeux">
                        <?php echo $row_rsBanniere300['code']; ?>
                        <h3>R�f�rencez votre club</h3>
                        <div id="coldeuxbloc">
                            <span class="texte3">Vous �tes dirigeant d'un club de marathon et vous souhaitez &ecirc;tre r&eacute;f&eacute;renc&eacute; gratuitement dans notre annuaire cela ne prend que quelques minutes...<br />
                                <br />
                                <a href="formulaire-contact.php">&gt; Remplir le formulaire</a></span><br />
                        </div>
                        <h3>Sites partenaires</h3>
                        <div id="coldeuxbloc" align="center" ><a href="<?php echo $partenaire['URL']; ?>"><img src="http://www.apercite.fr/api/apercite/240x180/oui/oui/<?php echo $partenaire['URL']; ?>" width="240" height="180" vspace="8" border="0" /></a><a href="<?php echo $partenaire['URL']; ?>"></a><a href="<?php echo $partenaire['URL']; ?>"></a><a href="<?php echo $partenaire['URL']; ?>"></a>
                            <div id="Pgratuit" >
                                <a href="sites-partenaires.php">Faites comme <?php echo $partenaire['Nom1']; ?>, augmentez gratuitement votre visibilit�,
                                    en devenant partenaire</a></div>
                        </div>

                        <div id="coldeuxblocpub">
                            <?php echo $row_rsBanniere160['code']; ?></div>
                    </div>
                </div>
                <?php require_once("Templates/_footer.php") ?>
            </div>
        </div>

    </body>
    <?php require_once("Templates/_footerScript.php") ?>
</html>
