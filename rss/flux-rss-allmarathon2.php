<?php
setlocale(LC_TIME, "fr_FR","French");
function changeDate() {
		    $timestamp = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
		    return date("Y-m-d",$timestamp)." 00:00:00";
		}
function slugify($text)
{
// Swap out Non "Letters" with a -
$text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = str_replace('à', 'a', $text);
    $text = str_replace('è', 'e', $text);
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $text);
    $text = trim($text, '-');
    $text = strtolower($text);

   return $text;
}
//select  from  where ;

function get_data_rss(){
    // Récupération des données dans la base de données (MySQL)
    require_once '../database/connexion.php';//se connecter a la base de donnees
    $result1= array();// creer un tableau resultats qui va recuperer les resultats
    try{
            $req = $bdd->prepare("SELECT e.ID,e.DateDebut,e.Nom,e.affiche,m.image,m.description,cat.Intitule as cat_event,e.PaysID FROM evenements e,marathons m,evcategorieevenement cat WHERE e.marathon_id=m.id and e.CategorieID=cat.ID and e.Visible=1  order by e.DateDebut DESC,e.affiche Desc limit 300;");// preparer la requette sql pour avoir les actualites
            $req->execute();// executer la requette
            
            while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  // recuperer le tableau des actualites et faire une boucle while pour ajouter chaque actualite au tableau resultats
                array_push($result1, $row);// ajouter une actualite dans le tableau resultats
            }
            return $result1; //renvoyer le tableau
        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
}
    
function display_rss($result1){
    include("../content/classes/pays.php");
include("../content/classes/evresultat.php");
$pays=new pays();
$evresultat=new evresultat();
    // Boucle qui liste les URL
    foreach ($result1 as $res) {
        
        $ev_cat_event_int_titre=$res['cat_event'];
   $date = $res['DateDebut'];
$annee_titre=substr($date, 0, 4);
     
        $titre = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', 'Resultats du marathon de '.$res['Nom'].' '.strftime("%Y",strtotime($res['DateDebut'])));
        $paysname=$pays->getFlagByAbreviation($res['PaysID'])['donnees']['NomPays'];
        $homme=$evresultat->getResultBySexe($res['ID'],"M")['donnees'][0]['Nom'];
        $femme=$evresultat->getResultBySexe($res['ID'],"F")['donnees'][0]['Nom'];
         $description = 'Le '.$ev_cat_event_int_titre.' '.$annee_titre.' de '. $res['Nom'].' '.$paysname.' a eu lieu le '.changeDate($date).' Les vainqueurs sont '.$homme.'(hommes) et '.$femme.'(femmes). Résultats complets, classements et temps.';
        $cat_event = $res['cat_event'];
        $nom_res_lien=$cat_event.' - '.$res['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($res['DateDebut'])));
        if($res['affiche']){
            $image_url='https://dev.allmarathon.fr/images/events/'.$res['affiche'];
        }else{
            $image_url='https://dev.allmarathon.fr/images/marathons/'.$res['image'];
        }        $url='https://dev.allmarathon.fr/resultats-marathon-'.$res['ID'].'-'.slugify($nom_res_lien).'.html';
        
        echo '<item>
                <title>'.$titre.'</title>
                <link>'.$url.'</link>
                <guid isPermaLink="false">'. $url.'</guid>
                <description>
                    <![CDATA[ '.$description.']]>
                </description>
                <enclosure url="'.$image_url.'" length="'.strlen($image_url).'" type="image/jpeg"/>
                <pubDate>'.date(DATE_RSS, strtotime($date)).'</pubDate>
            
              </item>';
    }   
}


// en haut les fonctions
// elles ne s'executent pas quand on charge la page


// Affichage
     header('Content-Type: text/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    ?>
    <rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">
    <channel>
    <title>Résultats de tous les marathons nationaux et internationaux | allmarathon.fr</title>
    <link>https://dev.allmarathon.fr</link>
    <atom:link href="https://dev.allmarathon.fr/flux-resultats-rss.xml" rel="self" type="application/rss+xml" />
    <description>Résultats de tous les marathons nationaux et internationaux :  Championnats de France, Championnats d'Europe, Championnats du Monde, Jeux Olympiques, World Major</description>
    <language>fr-fr</language>
    <copyright>Copyright allmarathon.fr</copyright>
    <pubDate><?php echo date(DATE_RSS);?></pubDate>
    <?php display_rss(get_data_rss()); ?>
    </channel>
    </rss>