<?php
 setlocale(LC_TIME, "fr_FR","French");

function array_msort($array, $cols)
	{
		$colarr = array();
		foreach ($cols as $col => $order) {
			$colarr[$col] = array();
			foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
		}
		$eval = 'array_multisort(';
		foreach ($cols as $col => $order) {
			$eval .= '$colarr[\''.$col.'\'],'.$order.',';
		}
		$eval = substr($eval,0,-1).');';
		eval($eval);
		$ret = array();
		foreach ($colarr as $col => $arr) {
			foreach ($arr as $k => $v) {
				$k = substr($k,1);
				if (!isset($ret[$k])) $ret[$k] = $array[$k];
				$ret[$k][$col] = $array[$k][$col];
			}
		}
		return $ret;
	
	}

try{
    include("../content/database/connexion.php");

    $req = $bdd->prepare("SELECT * FROM marathons");
    $req->execute();
    $results= array();
    //$first_events= array();
    $last_linked_events= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  //ceux qui sont a venir
        $req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
        $req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
        
        $req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
        $req2->execute();
        if($req2->rowCount()>0){
            while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
                //var_dump($row2);exit();  
                //array_push($first_events, $row2);
                $row['date_prochain_evenement']=$row2['DateDebut'];
                $row['is_top_prochain_evenement']=$row2['a_l_affiche'];
                $row['type_evenement']="prochain";
                $row['date_prochain_evenement_nom']=$row2['Nom'];
                $row['date_prochain_evenement_id']=$row2['ID'];
                $row['last_linked_events_cat_id']=$row2['CategorieID'];

            }
        }else {//ceux qui ont une date passée
            $req24 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  ORDER BY DateDebut limit 1");
            $req24->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
            
            $req24->execute();
            if($req24->rowCount()>0){//ceux qui ont une date passée
                while ( $row24  = $req24->fetch(PDO::FETCH_ASSOC)) {
                    //var_dump($row2);exit();  
                    //array_push($first_events, $row2);
                    $row['date_prochain_evenement']='NULL';
                    $row['last_linked_events_cat_id']=$row24['CategorieID'];
                    $row['type_evenement']="dernier";
                    $row['date_dernier_evenement']=$row24['DateDebut'];
                    $row['date_dernier_evenement_nom']=$row24['Nom'];
                    $row['date_dernier_evenement_id']=$row24['ID'];
    
                }
            }else{//ceux qui n'ont aucune date
                $row['type_evenement']="aucun";
                $row['date_prochain_evenement']='NULL';
                $row['last_linked_events_cat_id']=7;
            }
            //array_push($first_events, NULL);
            //$row['date_prochain_evenement']='NULL';
            //$row['last_linked_events_cat_id']=NULL;

        }

        
        
      array_push($results, $row);

      
  }
  $results_sorted_by_next_event=array_msort($results, array('type_evenement'=>SORT_DESC,'date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC));

 


$i=1;
    foreach ($results_sorted_by_next_event as $resultat) {
        if($resultat["type_evenement"]=='prochain'){
           
            $eid= $resultat["date_prochain_evenement_id"];
            $date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
            $mdate=$resultat["date_prochain_evenement"] ;  
            $top=$resultat['is_top_prochain_evenement'];
            $cat=$resultat['last_linked_events_cat_id'];
            $date_presentation_string= utf8_encode($date_premier_even);
        }else if($resultat["type_evenement"]=='dernier'){
            
            $eid= $resultat["date_prochain_evenement_id"];
            $date_premier_even=strftime("%B",strtotime($resultat["date_dernier_evenement"]));
            $mdate=$resultat["date_dernier_evenement"];
            $top=$resultat['is_top_prochain_evenement'];
            $cat=$resultat['last_linked_events_cat_id'];
            $date_presentation_string= utf8_encode($date_premier_even).' - <span class="marathon-to-come">En attente de date</span>';
        }else if($resultat["type_evenement"]=='aucun'){
            $date_presentation_string.= 'Prochaine date À venir';
            $mdate=null;
            $eid=null;
            $top=null;
            $cat=null;
        }

        $reqf = $bdd->prepare("UPDATE `marathons` SET `date`=:mdate,evenement_presentation=:ep,is_top_prochain_evenement=:topp,last_linked_events_cat_id=:cat,date_presentation_string=:dps,ordre=:ordre WHERE id=:mid");
        $reqf->bindValue('mid', $resultat["id"], PDO::PARAM_INT);
        $reqf->bindValue('mdate',$mdate , PDO::PARAM_STR); 
        $reqf->bindValue('dps',$date_presentation_string , PDO::PARAM_STR); 
        $reqf->bindValue('ordre',$i , PDO::PARAM_INT); 
        $reqf->bindValue('topp',$top , PDO::PARAM_INT); 
        $reqf->bindValue('cat',$cat , PDO::PARAM_INT); 
        $reqf->bindValue('ep',$eid , PDO::PARAM_INT); 
        $reqf->execute();
        $i+=1;
        
    }
      
    echo "données mises a jour";
      
  }
  catch(Exception $e)
  {
    var_dump($e);
      die('Erreur : ' . $e->getMessage());
  }
  
?>