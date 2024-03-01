<?php function getmin(){
    require '../database/connexion.php';
    $res=array();
    $req = $bdd->prepare('SELECT MIN(ID) as min FROM champions');
    $req->execute();
    $res=$req->fetch(PDO::FETCH_ASSOC);
    $min= $res['min'];
    return $min;
}
?>
<?php function fin_recherche(){
    $fin_recherche=100;
    return $fin_recherche;
}?>
<?php
class renvoyés{

	static $nbr;
    public static function setNbr($nbr){
		self::$nbr=$nbr;
	}
	public static function getNbr(){
		return self::$nbr;
	}}?>
<?php
function debut_recherche(){
    $debut_recherche=1;
    return $debut_recherche;
}?>
<?php
function getmax(){
    $max=60064;
    return $max;
}?>
<?php function similaires2($id,$nom_champion){
//celle la est utilisee au niveau de script accessible a la page script_fusion.php

//require 'test.php';
require '../database/connexion.php';
$tab       = explode(" ", str_replace("'","",$nom_champion));
$nbr       = count($tab);
//si le nom contient entierement le nom de famille et les autres prenoms on ajoute une condition similaire a 50%
$query7     ="SELECT * FROM champions WHERE (ID !=".$id.") AND (( UPPER(Nom) LIKE UPPER('%".$tab[0]."%'))";
for($i=1;$i<$nbr;$i++){
    if(strlen($tab[$i])>1)
        $query7 .= "AND (UPPER(Nom) LIKE UPPER('%".$tab[$i]."%'))";
}
$query7    .= ") ORDER BY Nom";

$req7=$bdd->prepare($query7);
$req7->execute();
$res7=array();
while($row=$req7->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_champion,$row['Nom'])>=50){
        array_push($res7,$row);
    }
}

//si le nom  est similaire a 90%
$query8     ="SELECT * FROM champions WHERE (ID !=".$id.") ORDER BY Nom";
$req8=$bdd->prepare($query8);
$req8->execute();
$resint=array();
while($row=$req8->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_champion,$row['Nom'])>=90){
        
            array_push($resint,$row);
        
    }
}

//le resultat final
$res_final2=array();

//ajouter ceux qui ont tous les noms et qui sont similaires a 50%

for($index=0;$index<count($res7);$index++){
    $candidat_potentiel=$res7[$index];
    $tab2= explode(" ", $candidat_potentiel['Nom']);
    $nbr2       = count($tab2);
    $nombre_de_matchs=0;
    for($j=0;$j<$nbr;$j++){//les noms de celui qui sera remplacé
        if(strlen($tab[$j])>=2){
            for($i=0;$i<$nbr2;$i++){//les noms des remplacants potentiels
                if(preg_match("/^(.*)".$tab[$j]."(.*)$/i",$tab2[$i])||preg_match("/^(.*)".$tab[$j]."$/i",$tab2[$i])||preg_match("/^".$tab[$j]."(.*)$/i",$tab2[$i]))
                    {
                        
                        $tab2[$i]="-";
                        $nombre_de_matchs++;
                        break 1;
                    }
            }
        }
        
    }
    //verifier qu'ils ont au moins 2 prenom en commun
    if($nombre_de_matchs>=2){
        array_push($res_final2,$candidat_potentiel);
    }
}

// filter ceux qui sont similaires a 90%

for($index=0;$index<count($resint);$index++){
    $candidat_potentiel=$resint[$index];
    $tab2= explode(" ", $candidat_potentiel['Nom']);
    $nbr2       = count($tab2);
    $nombre_de_matchs=0;
    for($j=0;$j<$nbr;$j++){//les noms de celui qui sera remplacé
        if(strlen($tab[$j])>=2){
            $troisprem=substr($tab[$j], 0, 3);
            $troisder=substr($tab[$j], -3);
            $deuxprem=substr($tab[$j], 0, 2);
            $deuxder=substr($tab[$j], -2);
            for($i=0;$i<$nbr2;$i++){//les noms des remplacants potentiels
                if(preg_match("/^".$deuxprem."(.*)".$deuxder."$/i",$tab2[$i])||preg_match("/^".$troisprem."(.*)$/i",$tab2[$i])||preg_match("/^(.*)".$troisder."$/i",$tab2[$i]) )  //verifier que les noms des remplacants potentiels commencent par les 3 memes lettres ou se terminent par les 3 memes lettres ou commencent et se terminent par les 2 memes lettres que ceux du remplacé  
                    {
                        //echo $tab2[$i]." commence par ".$deuxprem." et se termine par".$deuxder."\n";
                        $tab2[$i]="-";
                        $nombre_de_matchs++;
                        break 1;
                    }
            }
        }
        
    }
    //verifier qu'ils ont au moins 2 prenom en commun
    if($nombre_de_matchs>=2){
        array_push($res_final2,$candidat_potentiel);
    }
}



return $res_final2;
}
?>


<?php function similaires3($id,$nom_champion){
//celle la est utilisee au niveau du correcteur de la page championdetails.php

//require 'test.php';
require '../database/connexion.php';
$tab       = explode(" ", $nom_champion);
$nbr       = count($tab);
//condition1: si le nom contient entierement le nom de famille et les autres prenoms on ajoute une condition similaire a 50%
$query7     ="SELECT * FROM champions WHERE (ID !=".$id.") AND (( UPPER(Nom) LIKE UPPER('%".$tab[0]."%'))";
for($i=1;$i<$nbr;$i++){
    if(strlen($tab[$i])>1)
        $query7 .= "AND (UPPER(Nom) LIKE UPPER('%".$tab[$i]."%'))";
}
$query7    .= ") ORDER BY Nom";

$req7=$bdd->prepare($query7);
$req7->execute();
$res7=array();
while($row=$req7->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_champion,$row['Nom'])>=50){
        array_push($res7,$row);
    }
}

//condition 2:
$query8     ="SELECT * FROM champions WHERE (ID !=".$id.") ORDER BY Nom";

$req8=$bdd->prepare($query8);
$req8->execute();
$resint=array();
while($row=$req8->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_champion,$row['Nom'])>=70){//si le nom  est similaire a 70%
        if(!in_array($row, $resint)){
            array_push($resint,$row);
        }
    }
}

//le resultat final
$res_final2=array();

//ajouter ceux qui ont tous les noms et qui sont similaires a 50%

for($index=0;$index<count($res7);$index++){
    $candidat_potentiel=$res7[$index];
    $tab2= explode(" ", $candidat_potentiel['Nom']);
    $nbr2       = count($tab2);
    $nombre_de_matchs=0;
    for($j=0;$j<$nbr;$j++){//les noms de celui qui sera remplacé
        if(strlen($tab[$j])>=2){
            for($i=0;$i<$nbr2;$i++){//les noms des remplacants potentiels
                if(preg_match("/^(.*)".$tab[$j]."(.*)$/i",$tab2[$i])||preg_match("/^(.*)".$tab[$j]."$/i",$tab2[$i])||preg_match("/^".$tab[$j]."(.*)$/i",$tab2[$i]))
                    {
                        
                        $tab2[$i]="-";
                        $nombre_de_matchs++;
                        break 1;
                    }
            }
        }
        
    }
    //verifier qu'ils ont au moins 2 prenom en commun
    if($nombre_de_matchs>=2){
        array_push($res_final2,$candidat_potentiel);
    }
}

// filter ceux qui sont similaires a 70%

for($index=0;$index<count($resint);$index++){
    $candidat_potentiel=$resint[$index];
    $tab2= explode(" ", $candidat_potentiel['Nom']);
    $nbr2       = count($tab2);
    $nombre_de_matchs=0;
    for($j=0;$j<$nbr;$j++){//les noms de celui qui sera remplacé
        if(strlen($tab[$j])>=2){
            $troisprem=substr($tab[$j], 0, 3);
            $troisder=substr($tab[$j], -3);
            $deuxprem=substr($tab[$j], 0, 2);
            $deuxder=substr($tab[$j], -2);
            for($i=0;$i<$nbr2;$i++){//les noms des remplacants potentiels
                if(preg_match("/^".$deuxprem."(.*)".$deuxder."$/i",$tab2[$i])||preg_match("/^".$troisprem."(.*)$/i",$tab2[$i])||preg_match("/^(.*)".$troisder."$/i",$tab2[$i]) )  //verifier que les noms des remplacants potentiels commencent par les 3 memes lettres ou se terminent par les 3 memes lettres ou commencent et se terminent par les 2 memes lettres que ceux du remplacé  
                    {
                        //echo $tab2[$i]." commence par ".$deuxprem." et se termine par".$deuxder."\n";
                        $tab2[$i]="-";
                        $nombre_de_matchs++;
                        break 1;
                    }
            }
        }
        
    }
    //verifier qu'ils ont au moins 2 prenom en commun
    if($nombre_de_matchs>=2){
        array_push($res_final2,$candidat_potentiel);
    }
}



return $res_final2;
}
?>

<?php function recherche_et_fusion($debut,$fin){
//require 'test.php';
include("format_remplacement.php");
$resultat=array();
require '../database/connexion.php';
$req = $bdd->prepare('SELECT MAX(ID) as max,MIN(ID) as min FROM champions');
$req->execute();
$res=$req->fetch(PDO::FETCH_ASSOC);
/*
//sur toute la base 

$max= $res['max'];
$min= $res['min'];*/


$renvoyés=0;
$req2 = $bdd->prepare("SELECT * FROM champions WHERE (ID =:champ_id) ");
for($id_courant=$debut;$id_courant<=$fin;$id_courant++){
    $req2->bindValue('champ_id',$id_courant, PDO::PARAM_INT);
    $req2->execute();
    $champion=$req2->fetch(PDO::FETCH_ASSOC);
    $enregistrement=new format_remplacement();
    $enregistrement->setId($id_courant);
    $enregistrement->setChampion_courant($champion);
   
    if($champion){
        $remplacants=similaires2($id_courant,$champion['Nom']);
        if($remplacants){
            $remplacants2=array();
            foreach($remplacants as $remplacant){
                if($remplacant['ID']<$champion['ID']){
                    array_push($remplacants2,$remplacant);
                    $renvoyés++;
                }
            }
            $enregistrement->setRemplacants($remplacants2);
            array_push($resultat,$enregistrement);
        }
        
    }
    
}
renvoyés::setNbr($renvoyés);
return $resultat;
}
?>

<?php //pour avoir le nombre total?>


