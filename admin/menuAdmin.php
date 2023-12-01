<?php

if (session_status() == PHP_SESSION_NONE) {

    session_start();

}

if(isset($_SESSION['admin']) && isset($_SESSION['login'])):

?>



<div id="menu">

    <ul>

        <?php if($_SESSION['admin'] || $_SESSION['news']):?><li class="menuItem"><a href="news.php">News</a></li>

        <?php endif ?>
        
        <?php if($_SESSION['admin'] || $_SESSION['mar']):?><li class="menuItem"><a href="marathon.php">Marathon</a>
        </li><?php endif ?>
        
        <?php if($_SESSION['admin'] || $_SESSION['ev']):?><li class="menuItem"><a href="evenement.php">Évenement</a>

        </li><?php endif ?>
       

        <?php if($_SESSION['admin'] || $_SESSION['ev']):?><li class="menuItem"><a href="champion.php">Champion</a></li>

        <?php endif ?>

        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="championAdminExterne.php">Champion admin</a></li>

        <?php endif ?>

        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="resultAdminExterne.php">Résultats admin</a></li>

        <?php endif ?>

       

        <?php if($_SESSION['admin'] || $_SESSION['photo']):?><li class="menuItem"><a href="galerie.php">Galerie</a></li>

        <?php endif ?>

        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="video.php">Vidéos</a></li><?php endif ?>

        <?php if($_SESSION['admin']):?>

            <li class="menuItem">

                <a href="#">Youtube Data API v3</a>

                <ul class="sub-menu">

                    <li class="sub"><a href="youtube-data-api-chaines.php">Chaines</a></li>

                    <li class="sub"><a href="youtube-data-api-videos.php">Videos</a></li>

                </ul>

            </li>

        <?php endif ?>

        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="validation.php">Validation</a></li><?php endif ?>

        
        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="pub.php">Pub</a></li><?php endif ?>

        
       <?php if($_SESSION['admin']):?>

        <li class="menuItem">

            <a href="newsletter.php">Newsletter</a>

            <ul class="sub-menu">

                <li class="sub"><a href="generate_users.php">G&eacute;n&eacute;rer CSV<br>(Newsletter)</a></li>

                <li class="sub"><a href="../content/modules/send-newsletter.php">Envoyer la<br>(Newsletter)</a></li>

                <li class="sub"><a href="/newsletter-allmarathon.html"

                        target="_blank">Newsletter<br>en ligne</a></li>

                <li class="sub"><a href="newsletter_stat.php">Statistiques</a></li>

            </ul>



        </li><?php endif ?>
        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="records.php">Records</a></li><?php endif ?>
        <?php if($_SESSION['admin']):?><li class="menuItem"><a href="index_script.php">Script de détection de doublons</a><?php endif ?>

        

        </ul>

    <div id="logout"> Bienvenue <?php echo $_SESSION['login'] ?> ! <a href="logout.php">Se d&eacute;connecter</a></div>

</div>



<div style="clear: both;"></div>

<br />

<?php endif; ?>



<style type="text/css">

#menu ul {

    position: relative;

    margin: 0;

    padding: 0;

    list-style: none;

    z-index: 3;

}



#menu li {

    display: inline-block;

}



#menu ul.sub-menu li {

    background-color: #C0C0C0;

    border-width: 0 1px 1px 1px;

    border-style: solid;

    border-color: #666666;

    height: fit-content;

}



#menu ul.sub-menu li a {

    color: #fff;

    text-align: center;

    margin: 5px 10px;

    padding: 5px 10px;

    width: 100px;

}



#menu ul.sub-menu li a:hover {

    color: snow;

    background-color: #666666;

}



#menu li:hover ul.sub-menu {

    display: block;

    z-index: 90;

}



.menuItem a {

    position: relative;

}



#menu ul.sub-menu {

    display: none;

    position: absolute;

    top: 25px;

}

</style>