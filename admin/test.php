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

function get_data_sitemap_coureur(){
    // Récupération des données dans la base de données (MySQL)
    require_once '../database/connexion.php';
    $result1= array();
    try{
            $req = $bdd->prepare("SELECT * FROM champions");
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
    
function display_sitemap_coureur($result1){ 
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        $loc        = 'https://dev.allrathon.fr/athlete-'.$res['ID'].'-'.slugify($res['Nom']).'.html';
        echo '
        <url>
            <loc>'.$loc.'</loc>
        </url>';
    }   
}


function get_data_sitemap_news(){
    // Récupération des données dans la base de données (MySQL)
    require_once '../database/connexion.php';
    $result1= array();
    try{
            $req = $bdd->prepare("SELECT * FROM news");
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
    
function display_sitemap_news($result1){ 
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        $url_text=slugify($res['titre']);
        $loc        = 'https://dev.allrathon.fr/actualite-marathon-'.$res['ID'].'-'.$url_text.'.html';
        echo '
        <url>
            <loc>'.$loc.'</loc>
        </url>';
    }   
}

function get_data_sitemap_videos(){
    // Récupération des données dans la base de données (MySQL)
    require_once '../database/connexion.php';
    $result1= array();
    try{
            $req = $bdd->prepare("SELECT * FROM videos");
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
    
function display_sitemap_videos($result1){ 
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        
        $loc        = 'https://dev.allrathon.fr/video-de-marathon-'.$res['ID'].'.html';
        echo '
        <url>
            <loc>'.$loc.'</loc>
        </url>';
    }   
}

function get_data_sitemap_evenements(){
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
    
function display_sitemap_evenements($result1){ 
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        
        $loc        = 'https://dev.allrathon.fr/resultats-marathon-'.$res['ID'].'-'.slugify($res['Nom']).'.html';
        echo '
        <url>
            <loc>'.$loc.'</loc>
        </url>';
    }   
}































// Affichage
     header('Content-Type: text/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    ?>
    <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php display_sitemap_evenements(get_data_sitemap_evenements()); ?>
    </urlset>
