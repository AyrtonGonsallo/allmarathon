<?php
setlocale(LC_TIME, "fr_FR","French");

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


function get_data_rss(){
    // Récupération des données dans la base de données (MySQL)
    require_once '../database/connexion.php';
    $result1= array();
    try{
            $req = $bdd->prepare("SELECT * FROM news order by date desc");
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
    
function display_rss($result1){ 
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        $url_text=slugify($res['titre']);
        $loc        = 'https://www.allmarathon.fr/actualite-marathon-'.$res['ID'].'-'.$url_text.'.html';
        $date = $res['date'];
        $titre = $res['titre'];
        $tab = explode('-',$date);
        $yearNews  = $tab[0];
        $img_src='/images/news/'.$yearNews.'/'.$res['photo'];
        $photo = 'https://allmarathon.fr'.$img_src;
        $source = $res['source'];
        $description = $res['chapo'];
        $alt = $res['legende'];
        $categorie = $res['Type'];
        $auteur = $res['auteur'];
        echo '<item>
                <title>'.$titre.'</title>
                <description>
                    <![CDATA[ <img src="'.$photo.'" alt="'.$alt.'">'.$description.' ]]>
                </description>
                <dc:creator>'.$auteur.'</dc:creator>
                <source>'.$source.'</source>
                <category>'.$categorie.'</category>
                <enclosure url="'.$photo.'" length="50000" type="image/jpeg"/>
                <guid>'. $loc.'</guid>
                <pubDate>'.date(DATE_ISO8601, strtotime($date)).'</pubDate>
                <date>publié le '.utf8_encode(strftime("%A %d %B %Y - %H:%M:%S",strtotime($date))).'</date>
              </item>';
    }   
}













// Affichage
     header('Content-Type: text/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    ?>
    <rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">
    <channel>
    <title>allmarathon.fr - Les actualités du marathon en France et dans le monde.</title>
    <link>https://allmarathon.fr</link>
    <description>Les actualités du marathon en France et dans le monde. News, résultats, interviews, vidéos, comptes-rendus, brèves, sondages. | allmarathon.fr</description>
    <language>fr-fr</language>
    <copyright>Copyright allmarathon.fr</copyright>
    <pubDate><?php echo date();?></pubDate>
    <?php display_rss(get_data_rss()); ?>
    </channel>
    </rss>
