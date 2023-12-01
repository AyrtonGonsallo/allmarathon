<?php
function occurences($input)
{
    //compter le nombre d'occurences de chaque lettres de l'aphabet
    $nA= substr_count(strtolower($input),'a',0);
    $nB= substr_count(strtolower($input),'b',0);
    $nC= substr_count(strtolower($input),'c',0);
    $nD= substr_count(strtolower($input),'d',0);
    $nE= substr_count(strtolower($input),'e',0);
    $nF= substr_count(strtolower($input),'f',0);
    $nG= substr_count(strtolower($input),'g',0);
    $nH= substr_count(strtolower($input),'h',0);
    $nI= substr_count(strtolower($input),'i',0);
    $nJ= substr_count(strtolower($input),'j',0);
    $nK= substr_count(strtolower($input),'k',0);
    $nLL= substr_count(strtolower($input),'l',0);
    $nM= substr_count(strtolower($input),'m',0);
    $nN= substr_count(strtolower($input),'n',0);
    $nO= substr_count(strtolower($input),'o',0);
    $nP= substr_count(strtolower($input),'p',0);
    $nQ= substr_count(strtolower($input),'q',0);
    $nR= substr_count(strtolower($input),'r',0);
    $nS= substr_count(strtolower($input),'s',0);
    $nT= substr_count(strtolower($input),'t',0);
    $nU= substr_count(strtolower($input),'u',0);
    $nV= substr_count(strtolower($input),'v',0);
    $nW= substr_count(strtolower($input),'w',0);
    $nX= substr_count(strtolower($input),'x',0);
    $nY= substr_count(strtolower($input),'y',0);
    $nZ= substr_count(strtolower($input),'z',0);
    $nombres=array($nA,$nB,$nC,$nD,$nE,$nF,$nG,$nH,$nI,$nJ,$nK,$nLL,$nM,$nN,$nO,$nP,$nR,$nS,$nT,$nU,$nV,$nW,$nX,$nY,$nZ);
    return $nombres;
}
?>
<?php
function proximité($input,$mot2){
    //calculer les occurences du 1er mot
    $nombres1=occurences($input);
    //creer un tableau de scores
    $scores=array(1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000);
    $pos=0;//recuperer la position de la lettre dans l'alphabet
    foreach($nombres1 as $n){
        
        if($n!=0){//si la lettre est dans le mot 1
            $scores[$pos]=0;//on attribue un score null aux lettes du mot 1
            
        }
        $pos=$pos+1;
    }
    //calculer les occurences du second mot
    $nombres2=occurences($mot2);
    $Ic=0; 
    $pos=0;//recuperer la position de la lettre dans l'alphabet
    foreach($nombres2 as $n2){
        if($n2!=0){
            $produit=$n2*$scores[$pos];//l'indice est egal au produit de la somme des scores par le nombre d'occurences
            $Ic+=$produit;
        }
        $pos=$pos+1;
    }
    //print_r($scores);
    //echo "la proximité est: ".$Ic."\n";
    return $Ic;
}
?>
<?php
function pourcentage_de_similitude($input,$mot2){
    $Ic=proximité($input,$mot2);
    //eliminer les espaces
    $nombre_espace= substr_count(strtolower($mot2),' ',0);
    $longueur_valide=strlen($mot2)-$nombre_espace;
    $pourcentage=100-(($Ic*100)/(1000*$longueur_valide));
    //echo $mot2." a ".$pourcentage." % de lettres similaires avec ".$input."\n\n-------------\n";
    return $pourcentage;
}
?>
<?php
//pourcentage_de_similitude("AA ER","AAR REZ ae")
?>

<?php //<!-- ------------------------------------------------------------ -->?>

