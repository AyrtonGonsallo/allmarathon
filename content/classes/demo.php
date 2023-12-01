<?php
function getResultClassement($event_id,$pays_club,$type,$cond)

	{
		if($cond=="pays") {
			$order_cond="C.PaysID";
			$where_con="C.PaysID='".$pays_club."'";
		}else {
			$order_cond="R.Club";
			$where_con="R.Club LIKE '".$pays_club."'";
		}

		if( ($type!="homme") && ($type!="femme") ) $type_cond="";
		else {$type_cond= ($type=="homme") ?" AND C.Sexe = 'M' ":(($type == "femme")?" AND C.Sexe = 'F' ":"");}

		try {
				  include("../database/connexion.php");
				  //ceux qui ont la nationalité comme 1ere nationalité [verifier dateevenenement < datechangementNat]
				 $req = $bdd->prepare("SELECT COUNT(*) AS nb ,C.PaysID,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=:event_id AND E.DateFin<C.DateChangementNat AND ".$where_con." ".$type_cond." GROUP BY ".$order_cond.",R.Rang");
	             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req->execute();
	             $liste1= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste1, $row);
	             }
				 //ceux qui ont la nationalite comme seconde nationalité [verifier dateevenenement > datechangementNat]
				 $req2 = $bdd->prepare("SELECT COUNT(*) AS nb ,C.NvPaysID as PaysID,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=:event_id AND E.DateFin>C.DateChangementNat AND C.NvPaysID=:pays_id".$type_cond." GROUP BY ".$order_cond.",R.Rang");
	             $req2->bindValue('event_id',$event_id, PDO::PARAM_INT);
				 $req2->bindValue('pays_id',$pays_club, PDO::PARAM_STR);
	             $req2->execute();
	             $liste2= array();
	             while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste2, $row);
	             }
	             $bdd=null;
				 //faire la somme
				 
				 foreach ($liste1 as $key1=>$val1) {
					foreach ($liste2 as $key2=>$val2) {
						if(($val1['PaysID']===$val2['PaysID'])&&($val1['Rang']==$val2['Rang'])){
							$liste1[$key1]['nb']=$val1['nb']+$val2['nb'];
							
						}
					}
				}
                
	                return array('validation'=>true,'donnees'=>$liste1,'message'=>'');
	        }
            
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
			
	}
?>
<?php
getResultClassement(6751,'FRA','hf','pays');

?>