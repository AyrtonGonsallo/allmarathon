<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);



include("../classes/news.php");

include("../classes/evenement.php");

include("../classes/evCategorieEvenement.php");

include("../classes/evCategorieAge.php");

include("../classes/video.php");

require_once '../../database/connexion.php';



$news=new news();

$last_news=$news->getNewsForNewsletter()['donnees'];



$event=new evenement();

$results=$event->getResultsForNewsletter();



$ev_cat_event=new evCategorieEvenement();

$ev_cat_age=new evCategorieAge();



$vd=new video();

// $videos=$vd->getVideosForNewsletter();



try{

    

        $req1 = $bdd->prepare("SELECT * FROM banniereNewsletter where actif = 'actif' ");

        $req1->execute();

    

    }

    catch(Exception $e)

    {

        die('Erreur : ' . $e->getMessage());

    }



function getPhotoUrl($date, $photo)

{

    return 'http://dev.allmarathon.fr/images/news/' . substr($date, 0, 4) . '/' . $photo;

}



function getNewsLink($id, $title)

{

    return 'http://dev.allmarathon.fr/actualite-marathon-' . $id . '-' . slugify($title) . '.html';

}





function slugify($text)

{

    $text = preg_replace('/[^\pL\d]+/u', '-', $text);

    $text = trim($text, '-');

    $text = strtolower($text);

    return $text;

}



if (count($last_news) < 1) {

    die('pas de news');

}

?>

<html><!-- Inliner Build Version 4380b7741bb759d6cb997545f3add21ad48f010b -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width">

<title>AllMarathon Newsletter</title>

<link rel="icon" type="image/x-icon" href="../images/favicon.ico" /> 

<style type="text/css">

    @font-face {

        font-family: 'Roboto';

        font-style: normal;

        font-weight: 400;

        src: local('Roboto'), local('Roboto-Regular'), url('https://fonts.gstatic.com/s/roboto/v15/5M21SdFLkD52QavfmHs6cA.ttf') format('truetype');

    }



    @font-face {

        font-family: 'Roboto';

        font-style: normal;

        font-weight: 700;

        src: local('Roboto Bold'), local('Roboto-Bold'), url('https://fonts.gstatic.com/s/roboto/v15/97uahxiqZRoncBaCEI3aW6CWcynf_cDxXwCLxiixG1c.ttf') format('truetype');

    }



    @font-face {

        font-family: 'Roboto';

        font-style: normal;

        font-weight: 900;

        src: local('Roboto Black'), local('Roboto-Black'), url('https://fonts.gstatic.com/s/roboto/v15/9_7S_tWeGDh5Pq3u05RVkqCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');

    }



    @media only screen and (max-width: 596px) {

        .small-float-center {

            margin: 0 auto !important;

            float: none !important;

            text-align: center !important;

        }



        .small-text-center {

            text-align: center !important;

        }



        .small-text-left {

            text-align: left !important;

        }



        .small-text-right {

            text-align: right !important;

        }



        .hide-for-large {

            display: block !important;

            width: auto !important;

            overflow: visible !important;

            max-height: none !important;

            font-size: inherit !important;

            line-height: inherit !important;

        }



        table.body table.container .hide-for-large {

            display: table !important;

            width: 100% !important;

        }



        table.body table.container .row.hide-for-large {

            display: table !important;

            width: 100% !important;

        }



        table.body table.container .callout-inner.hide-for-large {

            display: table-cell !important;

            width: 100% !important;

        }



        table.body table.container .show-for-large {

            display: none !important;

            width: 0;

            mso-hide: all;

            overflow: hidden;

        }



        table.body img {

            width: auto;

            height: auto;

        }



        table.body center {

            min-width: 0 !important;

        }



        table.body .container {

            width: 95% !important;

        }



        table.body .columns {

            height: auto !important;

            -moz-box-sizing: border-box;

            -webkit-box-sizing: border-box;

            box-sizing: border-box;

            padding-left: 16px !important;

            padding-right: 16px !important;

        }



        table.body .column {

            height: auto !important;

            -moz-box-sizing: border-box;

            -webkit-box-sizing: border-box;

            box-sizing: border-box;

            padding-left: 16px !important;

            padding-right: 16px !important;

        }



        table.body .columns .column {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.body .columns .columns {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.body .column .column {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.body .column .columns {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.body .collapse .columns {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.body .collapse .column {

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        td.small-1 {

            display: inline-block !important;

            width: 8.33333% !important;

        }



        th.small-1 {

            display: inline-block !important;

            width: 8.33333% !important;

        }



        td.small-2 {

            display: inline-block !important;

            width: 16.66667% !important;

        }



        th.small-2 {

            display: inline-block !important;

            width: 16.66667% !important;

        }



        td.small-3 {

            display: inline-block !important;

            width: 25% !important;

        }



        th.small-3 {

            display: inline-block !important;

            width: 25% !important;

        }



        td.small-4 {

            display: inline-block !important;

            width: 33.33333% !important;

        }



        th.small-4 {

            display: inline-block !important;

            width: 33.33333% !important;

        }



        td.small-5 {

            display: inline-block !important;

            width: 41.66667% !important;

        }



        th.small-5 {

            display: inline-block !important;

            width: 41.66667% !important;

        }



        td.small-6 {

            display: inline-block !important;

            width: 50% !important;

        }



        th.small-6 {

            display: inline-block !important;

            width: 50% !important;

        }



        td.small-7 {

            display: inline-block !important;

            width: 58.33333% !important;

        }



        th.small-7 {

            display: inline-block !important;

            width: 58.33333% !important;

        }



        td.small-8 {

            display: inline-block !important;

            width: 66.66667% !important;

        }



        th.small-8 {

            display: inline-block !important;

            width: 66.66667% !important;

        }



        td.small-9 {

            display: inline-block !important;

            width: 75% !important;

        }



        th.small-9 {

            display: inline-block !important;

            width: 75% !important;

        }



        td.small-10 {

            display: inline-block !important;

            width: 83.33333% !important;

        }



        th.small-10 {

            display: inline-block !important;

            width: 83.33333% !important;

        }



        td.small-11 {

            display: inline-block !important;

            width: 91.66667% !important;

        }



        th.small-11 {

            display: inline-block !important;

            width: 91.66667% !important;

        }



        td.small-12 {

            display: inline-block !important;

            width: 100% !important;

        }



        th.small-12 {

            display: inline-block !important;

            width: 100% !important;

        }



        .columns td.small-12 {

            display: block !important;

            width: 100% !important;

        }



        .column td.small-12 {

            display: block !important;

            width: 100% !important;

        }



        .columns th.small-12 {

            display: block !important;

            width: 100% !important;

        }



        .column th.small-12 {

            display: block !important;

            width: 100% !important;

        }



        table.body td.small-offset-1 {

            margin-left: 8.33333% !important;

        }



        table.body th.small-offset-1 {

            margin-left: 8.33333% !important;

        }



        table.body td.small-offset-2 {

            margin-left: 16.66667% !important;

        }



        table.body th.small-offset-2 {

            margin-left: 16.66667% !important;

        }



        table.body td.small-offset-3 {

            margin-left: 25% !important;

        }



        table.body th.small-offset-3 {

            margin-left: 25% !important;

        }



        table.body td.small-offset-4 {

            margin-left: 33.33333% !important;

        }



        table.body th.small-offset-4 {

            margin-left: 33.33333% !important;

        }



        table.body td.small-offset-5 {

            margin-left: 41.66667% !important;

        }



        table.body th.small-offset-5 {

            margin-left: 41.66667% !important;

        }



        table.body td.small-offset-6 {

            margin-left: 50% !important;

        }



        table.body th.small-offset-6 {

            margin-left: 50% !important;

        }



        table.body td.small-offset-7 {

            margin-left: 58.33333% !important;

        }



        table.body th.small-offset-7 {

            margin-left: 58.33333% !important;

        }



        table.body td.small-offset-8 {

            margin-left: 66.66667% !important;

        }



        table.body th.small-offset-8 {

            margin-left: 66.66667% !important;

        }



        table.body td.small-offset-9 {

            margin-left: 75% !important;

        }



        table.body th.small-offset-9 {

            margin-left: 75% !important;

        }



        table.body td.small-offset-10 {

            margin-left: 83.33333% !important;

        }



        table.body th.small-offset-10 {

            margin-left: 83.33333% !important;

        }



        table.body td.small-offset-11 {

            margin-left: 91.66667% !important;

        }



        table.body th.small-offset-11 {

            margin-left: 91.66667% !important;

        }



        table.body table.columns td.expander {

            display: none !important;

        }



        table.body table.columns th.expander {

            display: none !important;

        }



        table.body .right-text-pad {

            padding-left: 10px !important;

        }



        table.body .text-pad-right {

            padding-left: 10px !important;

        }



        table.body .left-text-pad {

            padding-right: 10px !important;

        }



        table.body .text-pad-left {

            padding-right: 10px !important;

        }



        table.menu {

            width: 100% !important;

        }



        table.menu td {

            width: auto !important;

            display: inline-block !important;

        }



        table.menu th {

            width: auto !important;

            display: inline-block !important;

        }



        table.menu.vertical td {

            display: block !important;

        }



        table.menu.vertical th {

            display: block !important;

        }



        table.menu.small-vertical td {

            display: block !important;

        }



        table.menu.small-vertical th {

            display: block !important;

        }



        table.menu[align="center"] {

            width: auto !important;

        }



        table.button.small-expand {

            width: 100% !important;

        }



        table.button.small-expanded {

            width: 100% !important;

        }



        table.button.small-expand table {

            width: 100%;

        }



        table.button.small-expanded table {

            width: 100%;

        }



        table.button.small-expand table a {

            text-align: center !important;

            width: 100% !important;

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.button.small-expanded table a {

            text-align: center !important;

            width: 100% !important;

            padding-left: 0 !important;

            padding-right: 0 !important;

        }



        table.button.small-expand center {

            min-width: 0;

        }



        table.button.small-expanded center {

            min-width: 0;

        }

    }

</style>

<!-- Wrapper for the body of the email -->

<table class="body"

       style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; height: 100%; width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; background: #f3f3f3; margin: 0; padding: 0;"

       bgcolor="#f3f3f3">

    <tbody>

    <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

        <!-- The class, align, and <center> tag center the container -->

        <td class="float-center" align="center" valign="top"

            style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; float: none; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0;">

            <center style="width: 100%; min-width: 580px;">

                <!-- The content of your email goes here. -->

                <table class="container"

                       style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; background: #fefefe; margin: 0 auto; padding: 0;"

                       bgcolor="#fefefe">

                    <tbody>

                    <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                        <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                            align="left" valign="top">

                            <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="small-12 large-12 first last columns"

                                        style="width: 564px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 16px;"

                                        align="left">

                                        <a href="http://dev.allmarathon.fr/newsletter-allmarathon.html">

                                            <img width="174" height="66"

                                                 src="http://dev.allmarathon.fr/images/logo-news.png"

                                                 class="float-center"

                                                 style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block; float: none; text-align: center; margin: 0 auto;"

                                                 align="none">

                                        </a>

                                    </th>

                                </tr>

                                </tbody>

                            </table>

                            <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="small-12 large-12 first last columns"

                                        style="width: 564px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 16px;"

                                        align="left">

                                        <a href="<?= getNewsLink($last_news[0]['ID'], $last_news[0]['titre']) ?>">

                                            <img width="556" height="360"

                                                 src="<?= getPhotoUrl($last_news[0]['date'], $last_news[0]['photo']) ?>"

                                                  class="float-center"

                                                 style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block; float: none; text-align: center; margin: 0 auto;"

                                                 align="none"/>

                                        </a>

                                    </th>

                                </tr>

                                </tbody>

                            </table>

                            <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="marathon-top-new-title small-12 large-12 first last columns text-center"

                                        style="width: 564px; text-align: center; color: #0a0a0a; font-family: 'Roboto', sans-serif !important; font-weight: 700 !important; line-height: 1.3; font-size: 1.5em; margin: 0 auto; padding: 0 16px 16px;"

                                        align="center">

                                        <!--                                        FLORENT URANI SE BLESSE A L‘ÉPAULE-->

                                        <?= mb_strtoupper($last_news[0]['titre'], 'utf-8') ?>

                                    </th>

                                </tr>

                                </tbody>

                            </table>

                            <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="marathon-top-new-detail small-12 large-12 first last columns text-center"

                                        style="width: 564px; text-align: center; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 16px;"

                                        align="center">

                                        <p style="color: #0a0a0a; font-family: 'Roboto', sans-serif; font-weight: 400 !important; text-align: center; line-height: 1.3; font-size: 0.9em; margin: 0 0 10px; padding: 0;"

                                           align="center">

                                            <!--                                            Coup dur pour Florent Urani qui s'est blessé ce matin à l'épaule et pour qui-->

                                            <!--                                            la-->

                                            <!--                                            participation aux prochaines échéances pourrait être compromise.-->

                                            <?= $last_news[0]['chapo'] ?>

                                        </p>

                                    </th>

                                </tr>

                                </tbody>

                            </table>

                            <!-- start test -->

                            <?php while($pub=$req1->fetch(PDO::FETCH_ASSOC)) { ?>

                             <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="small-12 large-12 first last columns"

                                        style="width: 564px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 1em 16px 3em;"

                                        align="left">

                                        <a href="<?php echo $pub['url']; ?>">

                                        <img width="556" height="143"

                                             src="http://dev.allmarathon.fr/images/pubs/<?php echo $pub['image']; ?>" 

                                             class="float-center"

                                             style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block; float: none; text-align: center; margin: 0 auto;"

                                             align="none">

                                        </a>

                                        <p style="color: #0a0a0a; font-family: 'Roboto', sans-serif; font-weight: 400 !important; text-align: center; line-height: 1.3; font-size: 0.9em; margin: 0.5em 0 10px; padding: 0;"

                                           align="center"><?php print_r ($pub['text']); ?></p>

                                    </th>

                                </tr>

                                </tbody>

                            </table> 

                            <?php } ?>

                            <!-- end test -->

                            <table class="row"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <?php for ($i=1; $i <2 ; $i+=2) {

                                

                                    ?>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <th class="small-12 large-6 columns first"

                                        style="width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 8px 16px 16px;"

                                        align="left">

                                        <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <a href="<?= getNewsLink($last_news[$i]['ID'], $last_news[$i]['titre']) ?>">



                                                        <img src="<?= getPhotoUrl($last_news[$i]['date'], $last_news[$i]['photo']) ?>"

                                                             

                                                             style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block;"/>

                                                    </a>

                                                    <p style="font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 0.9em; color: #0a0a0a; text-align: left; line-height: 1.3; margin: 0.5em 0 10px; padding: 0;"

                                                       align="left">

                                                       <?= mb_strtoupper($last_news[$i]['titre'], 'utf-8') ?>

                                                    </p>



                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </th>

                                    <th class="small-12 large-6 columns last"

                                        style="width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 16px 8px;"

                                        align="left">

                                        <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <a href="<?= getNewsLink($last_news[$i+1]['ID'], $last_news[$i+1]['titre']) ?>">



                                                        <img src="<?= getPhotoUrl($last_news[$i+1]['date'], $last_news[$i+1]['photo']) ?>"

                                                             

                                                             style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block;">

                                                    </a>

                                                    <p style="font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 0.9em; color: #0a0a0a; text-align: left; line-height: 1.3; margin: 0.5em 0 10px; padding: 0;"

                                                       align="left">

                                                       <?= mb_strtoupper($last_news[$i+1]['titre'], 'utf-8') ?>

                                                    </p>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </th>

                                </tr>

                                <?php } ?>

                                </tbody>

                            </table>



                            <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;margin-bottom: 16px;">

                                <tbody >

                                    <tr style="vertical-align: top; text-align: left; padding: 0;" >

                                        <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 8px 0px 16px;"><span

                                                                        style="color: #fefefe; font-family: 'Roboto', sans-serif !important; font-weight: 400 !important; text-align: center; line-height: 1.3; text-decoration: none; font-size: 1.1em !important; display: inline-block; width: 98.5%; background: #fbff0b; margin: 0; padding: 10px 0; border: 0 solid #ec5840;text-transform: uppercase;">derniers résultats</span> </td>

                                    </tr>

                                    <tr style="vertical-align: top; text-align: left; padding: 0;">

                                    <td>

                                        <ul style="list-style:none;">

                                            <?php

                        foreach ($results['donnees'] as $key => $resultat) {

                            $cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();

                            if($resultat['Type']=="Equipe"){

                                $type_event= " par équipes";

                            }

                            else{

                                $type_event=""; 

                            }

                            $cat_age=$ev_cat_age->getEventCatAgeByID($resultat['CategorieageID'])['donnees']->getIntitule();

                            $nom_res=$cat_event.' '.$type_event.' '.$cat_age.' ('.$resultat['Sexe'].') - '.$resultat['Nom'].' - '.substr($resultat['DateDebut'],0,4);

                            echo '<li style="float: left;width: 98.5%;padding: 5px 0; text-align:center"><a href="http://dev.allmarathon.fr/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res).'.html" style="font-family: \'Roboto\', sans-serif;font-weight: 400;font-size: 1em;color: #0a0a0a;text-align: left;line-height: 1.3;margin: 0.5em 0 10px;padding: 0; text-decoration: underline;">'.$nom_res.'</a>

                            </li>';

                        }

                        // die;

                                                ?>

                                        </ul>

                                    </td>

                                    </tr>

                                </tbody>

                            </table>

                            <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;margin-bottom: 16px;">

                                <tbody >

                                    <tr style="vertical-align: top; text-align: left; padding: 0;" >

                                        <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 8px 0px 16px;"><span

                                                                        style="color: #fefefe; font-family: 'Roboto', sans-serif !important; font-weight: 400 !important; text-align: center; line-height: 1.3; text-decoration: none; font-size: 1.1em !important; display: inline-block; width: 98.5%; background: #fbff0b; margin: 0; padding: 10px 0; border: 0 solid #ec5840;text-transform: uppercase">Dernières vidéos

                                                                    </span> </td>

                                    </tr>

                                    <tr style="vertical-align: top; text-align: left; padding: 0;">

                                    <td>

                                        <table>

                                            <tr>

                                                

                                        <?php

                            foreach ($videos['donnees'] as $key => $video) {

                                 $pad = ($key==0) ? "padding: 20px 8px 16px 14px;" : "padding: 20px 15px 8px 16px;";

                                $img_top ='';

                                $vignette = (strpos($video['Vignette'], 'dailymotion') !== false) ? $video['Vignette'] : str_replace("default","0",$video['Vignette']) ;

                                echo '

                                <td style="width:50%;'.$pad.'" align="left" valign="top">

                                                    <a href="http://dev.allmarathon.fr/video-de-marathon-'.$video['ID'].'.html">



                                                        <img src="'.$vignette.'" style="width: 274px;">

                                                    </a>

                                                    <p style="font-family: \'Roboto\', sans-serif; font-weight: 700; font-size: 0.9em; color: #0a0a0a; text-align: left; line-height: 1.3; margin: 0.5em 0 10px; padding: 0;" align="left">

                                                       '.$video['Titre'].'                                                    </p>



                                                </td>



                            

                        ';

                            }

                        ?>

                        </tr>

                                        </table>

                                    </td>

                                    </tr>

                                </tbody>

                            </table><p

                                    style="border-top-style: solid; border-top-color: #e5e5e5; border-top-width: 2px; font-size: 5px; line-height: 5px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; margin: 0 20px 15px; padding: 0;"

                                    align="left">

                                &nbsp;

                            </p>

                            <table class="row marathon-buttons"

                                   style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">

                                <tbody>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">



                                    <!-- Calendrier -->

                                    <td class="small-12 large-6 first columns"

                                        style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 8px 0px 16px;"

                                        align="left" valign="top">

                                        <table class="button large alert expanded"

                                               style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100% !important; margin: 0 0 16px; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                                        <tbody>

                                                        <tr style="vertical-align: top; text-align: left; padding: 0;"

                                                            align="left">

                                                            <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #fefefe; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; background: #ec5840; margin: 0; padding: 0; border: 0px solid #ec5840;"

                                                                align="left" bgcolor="#ec5840" valign="top"><a

                                                                        href="http://dev.allmarathon.fr/calendrier-marathon.html"

                                                                        style="color: #fefefe; font-family: 'Roboto', sans-serif !important; font-weight: 400 !important; text-align: center; line-height: 1.3; text-decoration: none; font-size: 1.1em !important; display: inline-block; border-radius: 3px; width: 100%; background: #fbff0b; margin: 0; padding: 10px 0; border: 0 solid #ec5840;text-transform: uppercase;">calendrier</a></td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td>



                                    <!-- Bons plans marathon -->

                                    <td class="small-12 large-6 last columns"

                                        style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 0px 8px;"

                                        align="left" valign="top">

                                        <table class="button large alert expanded"

                                               style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100% !important; margin: 0 0 16px; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                                        <tbody>

                                                        <tr style="vertical-align: top; text-align: left; padding: 0;"

                                                            align="left">

                                                            <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #fefefe; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; background: #ec5840; margin: 0; padding: 0; border: 0px solid #ec5840;"

                                                                align="left" bgcolor="#ec5840" valign="top"><a

                                                                        href="https://www.alljudo.shop/" target='blank'

                                                                        style="color: #fefefe; font-family: 'Roboto', sans-serif !important; font-weight: 400 !important; text-align: center; line-height: 1.3; text-decoration: none; font-size: 1.1em !important; display: inline-block; border-radius: 3px; width: 100%; background: #fbff0b; margin: 0; padding: 10px 0; border: 0 solid #ec5840;text-transform: uppercase;"><font color="#fee616">Bons plans marathon</font></a>

                                                            </td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td>



                                    <th class="expander"

                                        style="visibility: hidden; width: 0; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                        align="left"></th>

                                </tr>

                                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                    <!-- Dernières vidéos -->

                                    <td class="small-12 large-6 first columns"

                                        style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 8px 0px 16px;"

                                        align="left" valign="top">

                                        <table class="button large alert expanded"

                                               style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100% !important; margin: 0 0 16px; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                                        <tbody>

                                                        <tr style="vertical-align: top; text-align: left; padding: 0;"

                                                            align="left">

                                                            <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #fefefe; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; background: #ec5840; margin: 0; padding: 0; border: 0px solid #ec5840;"

                                                                align="left" bgcolor="#ec5840" valign="top"></td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td>

                                    <!-- Allmarathon Shop -->

                                    <!-- <td class="small-12 large-6 last columns"

                                        style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; width: 274px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 16px 0px 8px;"

                                        align="left" valign="top">

                                        <table class="button large alert expanded"

                                               style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100% !important; margin: 0 0 16px; padding: 0;">

                                            <tbody>

                                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">

                                                <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                                    align="left" valign="top">

                                                    <table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">

                                                        <tbody>

                                                        <tr style="vertical-align: top; text-align: left; padding: 0;"

                                                            align="left">

                                                            <td style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #fefefe; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; background: #ec5840; margin: 0; padding: 0; border: 0px solid #ec5840;"

                                                                align="left" bgcolor="#ec5840" valign="top">

                                                                <a href="https://www.facebook.com/groups/867377543414039/" target="_blank"

                                                                   style="color: #fefefe; font-family: 'Roboto', sans-serif !important; font-weight: 400 !important; text-align: center; line-height: 1.3; text-decoration: none; font-size: 1.1em !important; display: inline-block; border-radius: 3px; width: 100%; background: #fbff0b; margin: 0; padding: 10px 0; border: 0 solid #ec5840;">ANNONCES JUDO</a>

                                                            </td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td> -->



                                    <th class="expander"

                                        style="visibility: hidden; width: 0; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"

                                        align="left"></th>

                                </tr>

                                </tbody>

                            </table>

                            <p style="border-top-style: solid; border-top-color: #e5e5e5; border-top-width: 2px; font-size: 5px; line-height: 5px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; margin: 4px 20px 15px; padding: 0;"

                               align="left">

                                &nbsp;

                            </p>

                            <!--                            <hr/>-->

                            