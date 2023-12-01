<?php

function creer(){

$titres=array("OFFRE SPECIALE JO : 2 cordes Force 1 et une corde Force 2 = 1 sangle de suspension offerte","Corde à uchi-komi Titanium 5 – Force 2 – Noir &amp; Argent","Echelle de rythme double","Kit de délimitation x40 coupelles","Pack de bandes élastiques (5 forces )","Sangle de Suspension Professionnelle");

$hrefs=array("https://www.alljudo.shop/product/3-corde-uchi-komis-mixte-sangle-suspension-offerte/","https://www.alljudo.shop/product/corde-a-uchi-komi-judo-titanium-5-noir-argent-clawtwin/","https://www.alljudo.shop/product/echelle-de-rythme-double/","https://www.alljudo.shop/product/kit-de-delimitation-x40-coupelles/","https://www.alljudo.shop/product/pack-de-bandes-elastiques-5-forces/","https://www.alljudo.shop/product/sangle-de-suspension-professionnelle/");

$imgs=array("https://www.alljudo.shop/wp-content/uploads/2021/04/corde-All-Judo-Clawtwin-738x738.jpeg","https://www.alljudo.shop/wp-content/uploads/2020/07/corde-uchi-komi-judo-clawtwin-noir-argent.jpg","https://www.alljudo.shop/wp-content/uploads/2020/08/PA658-P_2015-1-738x738.jpg","https://www.alljudo.shop/wp-content/uploads/2021/04/kit-delimitation-1-1.jpg","https://www.alljudo.shop/wp-content/uploads/2021/03/Bandes-elastiques-bleues-738x738.jpg","https://www.alljudo.shop/wp-content/uploads/2021/04/IMG_8800-738x738.jpg");

$noms=array("2 cordes Force 1 et une corde Force 2 = 1 sangle de suspension offerte","Corde à uchi-komi Titanium 5 – Force 2 – Noir","Echelle de rythme double","Kit de délimitation x40 coupelles","Pack de bandes élastiques (5 forces )","Sangle de Suspension Professionnelle");

$prix=array("132 €","46 €","49.50 €","25.00 €","14.90 €","84.90 €");

include("produit.php");

$articles=array();

for($i=0;$i<sizeof($titres);$i++){

    $prod=new produit();

    $prod->setId($i);

    $prod->setTitre($titres[$i]);

    $prod->setHref($hrefs[$i]);

    $prod->setImg($imgs[$i]);

    $prod->setNom($noms[$i]);

    $prod->setPrix($prix[$i]);

    array_push($articles,$prod);



}

return $articles;

}?>



<?php



function affichage_aleatoire($copie){

    $tableau_randomisé=array();

    while(count($copie)>0){

       

        $indice=rand(0, count($copie)-1);

        $obj=$copie[$indice];

        //echo "objet n°".$obj->getId()."\n";

        //print_r($obj);

        array_push($tableau_randomisé,$obj);

        for($i=$indice+1;$i<count($copie);$i++){

            $copie[$i-1] = $copie[$i];

        }

        unset($copie[count($copie)-1]);

        //echo "restants: ".count($copie)."\n";

        

        }

        return $tableau_randomisé;

}

?>



