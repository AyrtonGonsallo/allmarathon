<?php
require_once('Templates/_sessionCheck.php');
    session_start();
    header('Content-Type: text/html; charset=ISO-8859-1');
    $connected = (isset($_SESSION['user']) && $_SESSION['user']!="") ? true:false;
     require_once 'database/connection.php';

    $page = (isset($_GET['page']))?(int)$_GET['page']:0;
    $nbr_athlètes = $page*30;

    $queryRes   = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.username FROM pari_resultat p INNER JOIN phpbb_users u ON p.user_id=u.user_id ORDER BY p.points DESC LIMIT $nbr_athlètes,30");
    $resultRes  = mysql_query($queryRes) or die(mysql_error());
    
    while($res = mysql_fetch_array($resultRes)){
        //array_push($liste_pari, $res[pari_id]);
        $resultTab[$res[user_id]][$res[pari_id]] = $res[points]; 
        $resultTab[$res[user_id]][username]      = $res[username]; 
        $resultTab[$res[user_id]][total]        += $res[points];
    }

    $resultNbr  = mysql_query("Select FOUND_ROWS() AS nbr");
    $fetch      = mysql_fetch_array($resultNbr);
    $nbr        = $fetch['nbr'];
    $tab        = explode('.',$nbr/30);
    $nbr_page   = $tab[0];

    //pub 728x90
    $query_rs728 = "SELECT code FROM banniere728x90 WHERE actif='actif' AND restriction LIKE '%athlètes%' ORDER BY RAND() LIMIT 1";
    $rs728       = mysql_query($query_rs728) or die(mysql_error());
    $row_rs728   = mysql_fetch_assoc($rs728);

function dateMois($date) {
    $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
    $timestamp = mktime(substr($date, 11, 2),substr($date, 14, 2),0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
    return $month[date("n",$timestamp)-1].' '.date('Y');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"><!-- InstanceBegin template="/Templates/global.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<?php require_once("Templates/_headerScript.php") ?>

<title>Allmarathon.net : classement des athlètes</title>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="styles/championsliste2009.css" rel="stylesheet" type="text/css" />


</head>
<body onload="MM_preloadImages('images/CSS/boutique1.png','images/CSS/flux1.png');">
<div id="surcontainer">
<div id="container">
 <?php require_once("Templates/_header.php") ?>
<div id="content">
<div id="colun">


<h1>Les paris AllMarathon.net</h1>
<ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0"><a href="pari-accueil.html" style="color:white;">Accueil</a></li>
      <li class="TabbedPanelsTab" tabindex="1"><a href="pari-en-cours.html" style="color:white;">En cours</a></li>
      <li class="TabbedPanelsTab TabbedPanelsTabSelected" tabindex="2"><a href="pari-classement.html" style="color:white;">Classement</a></li>
</ul>
<div class="TabbedPanelsContentGroup">
    <table>
        <tr>
            <th>Membre</th>
        <?php
        $i=0;
            if(isset($liste_pari))
            foreach($liste_pari as $pid): ?>
            <th>Pari <?php echo $i ?></th>
        <?php $i++;
            endforeach;?>
            <th>Total</th>
        </tr>
        <?php
        if(isset($resultTab))
        foreach($resultTab as $user_id => $tab): ?>
            <tr>
                <td>
                    <?php echo $tab[username] ?>
                </td>
                <?php foreach($liste_pari as $pid): ?>
                <td>
                    <?php echo $tab[$pid] ?>
                </td>
                <?php endforeach;?>
                <td>
                    <?php echo $tab[total] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br />
<div id="navPage">
      <?php if($page != "0")
                echo '<a href="classement-athlètes-'.($page-1).'.html" >pr�c�dent</a>';
            echo " ".($page+1)." / ".($nbr_page+1)." ";
            if($page != $nbr_page)
                echo '<a href="classement-athlètes-'.($page+1).'.html" >suivant</a>';
      ?>
  </div><br />
</div>
</div>
<div id="coldeux" style="margin-top: 0px;">
    <h3 style="margin-top: 0px;">comment parier ?</h3>

<div id="coldeuxbloc">
    <br />
    <div id="Pgratuit"></div>
</div>
    <br />
 <script type="text/javascript"><!--
google_ad_client = "pub-7261110840191217";
/* 300x250, date de cr�ation 25/06/09 */
google_ad_slot = "5513132053";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<br />
<br />
<br />
</div>
<?php require_once("Templates/_footer.php") ?>
            </div>
        </div>

    </body>
<?php require_once("Templates/_footerScript.php") ?>
</html>