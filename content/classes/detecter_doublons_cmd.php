<?php function similaires2($id,$nom_doublon){
//require 'test.php';
require '../database/connexion.php';
$tab       = explode(" ", $nom_doublon);
$nbr       = count($tab);
//si le nom contient entierement le nom de famille et les autres prenoms on ajoute une condition similaire a 50%

//moins complet --> plus complet ex(vinc sil trouve vincent silva)
$query7     ="SELECT * FROM doublons WHERE (ID !=".$id.") AND (( UPPER(Nom) LIKE UPPER('%".$tab[0]."%'))";
for($i=1;$i<$nbr;$i++){
    if(strlen($tab[$i])>1)
        $query7 .= "OR (UPPER(Nom) LIKE UPPER('%".$tab[$i]."%'))";
}
$query7    .= ") ORDER BY Nom";

$req7=$bdd->prepare($query7);
$req7->execute();
$res7=array();
while($row=$req7->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_doublon,$row['Nom'])>=50){
        array_push($res7,$row);
    }
}
//plus complet --> moins complet (vincent silva doit pouvoir trouver vinc sil)
$query8     ="SELECT * FROM doublons WHERE (ID !=".$id.") ORDER BY Nom";
$req8=$bdd->prepare($query8);
$req8->execute();
while($row=$req8->fetch(PDO::FETCH_ASSOC)){
    if(pourcentage_de_similitude($nom_doublon,$row['Nom'])>=80){
        if(!in_array($row, $res7)){
            array_push($res7,$row);
        }
    }
}
$res_final=array();
for($index=0;$index<count($res7);$index++){
    $candidat_potentiel=$res7[$index];
    $tab2= explode(" ", $candidat_potentiel['Nom']);
    $nbr2       = count($tab2);
    $nombre_de_matchs=0;
    for($i=0;$i<$nbr2;$i++){//les noms des remplacants potentiels
        for($j=0;$j<$nbr;$j++){//les nom de celui qui sera remplacé
            
            if(strlen($tab2[$i])>=strlen($tab[$i])){//si le nom 2(remplacant potentiel) ex(vincent) est superieur au nom 1 ex(vinc)
                if(preg_match("/^".$tab[$j]."(.*)$/i",$tab2[$i]))//verifier que les noms des remplacants potentiels commencent par les memes lettres que ceux du remplacé
                {
                    //echo $tab2[$i]." commence par ".$tab[$j]."\n";
                    
                    $nombre_de_matchs++;
                    break 1;
                }
            }
            
            //si le nom 2(remplacant potentiel) ex(vinc) est inferieur au nom 1 ex(vincent)
            else{
                $taille=strlen($tab2[$i]);
                //prendre la sous chaine ex(vinc) du nom 1
                $chaine=substr($tab[$j], 0, $taille);
                if(preg_match("/^".$chaine."(.*)$/i",$tab2[$i]))//verifier que les noms des remplacants potentiels commencent par les memes lettres que ceux du remplacé
                {
                    $nombre_de_matchs++;
                    break 1;
                }
            }
            
        }
    }
    if($nombre_de_matchs==$nbr){
        array_push($res_final,$candidat_potentiel);
    }
    elseif($nombre_de_matchs==$nbr2){
        array_push($res_final,$candidat_potentiel);
    }
}
return $res_final;
}
?>
<?php function recherche_et_fusion(){
require 'test.php';
require '../database/connexion.php';
$req = $bdd->prepare('SELECT COUNT(*) as total FROM doublons');
$req->execute();
$res=$req->fetch(PDO::FETCH_ASSOC);
$total= $res['total'];
$req2 = $bdd->prepare("SELECT * FROM doublons WHERE (ID =:champ_id) ");
for($id_courant=1;$id_courant<100;$id_courant++){
    $req2->bindValue('champ_id',$id_courant, PDO::PARAM_INT);
    $req2->execute();
    $doublon=$req2->fetch(PDO::FETCH_ASSOC);
   
    if($doublon){
        $remplacants=similaires2($id_courant,$doublon['Nom']);
        if($remplacants){
            echo "* Doublon n° ".$doublon['ID']."   ".$doublon['Nom']."\n";
            echo "      similaires:\n";
            foreach($remplacants as $r){
                echo "          - n° ".$r['ID']."  ".$r['Nom']."\n \n\n";
            }
        }
    }
}
return $resultat;
}
?>
<?php
//require 'test.php';

$re=recherche_et_fusion();
?>