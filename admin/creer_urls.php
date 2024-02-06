<?php

function getTable(){

    $nomtable="champions";

    return $nomtable;

}?>

<?php

function slugify($text)

{

   $text = preg_replace('/[^\pL\d]+/u', '-', $text);

   $text = trim($text, '-');

   $text = strtolower($text);

   return $text;

}

?>

<?php function creer_Urls_partiel(){

    require '../database/connexion.php';

    $req2 = $bdd->prepare("SELECT * FROM ".getTable()." WHERE UPPER(Nom) LIKE UPPER('x%') ORDER BY Nom ");

    $req2->execute();

    while($resultat=$req2->fetch(PDO::FETCH_ASSOC)){

        $nom=$resultat['Nom'];

        $id=$resultat['ID'];

        echo "<url>

        <loc>https://alljudo.net/marathonka-".$id."-".slugify($nom).".html</loc>

</url>\n\n";

        

    }

        

    

    



}

?>





<?php function boucle(){

    $lettres=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

    $nombre=array(39,81,55,55,13,25,49,27,8,15,35,54,69,18,12,44,2,36,56,31,3,24,9,1,6,9);

    for($j=0;$j<26;$j++){

        for($i=0;$i<$nombre[$j];$i++){

            echo "<url>

            <loc>https://alljudo.net/liste-des-athletes-".$i."-".$lettres[$j].".html</loc>

</url>\n\n";

        }

    }

}

?>

<?php

creer_Urls_partiel();

?>



