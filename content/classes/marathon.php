<?php
include("pays.php");
include("evCategorieEvenement.php");

function slugify($text)

{
	$text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 

    $text = trim($text, '-');

    $text = strtolower($text);

    return $text;

}


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

	function display_results($results,$last_linked_events,$first_events){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
		$i=0;                             
        setlocale(LC_TIME, "fr_FR","French");
		$res="";
		foreach ($results as $resultat) {

			$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
		   $nom_res= $resultat['nom'];

			$res.= '<div class="col-sm-6 marathon-grid">
				<a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
					<h4 class="page-marathon-title">'.$nom_res.'<img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$pays_flag.'" alt=""/></h4></a> ';
					 
					$img_src='/images/marathons/thumb_'.$resultat['image'];
					$full_image_path="http://" . $_SERVER['HTTP_HOST'] .$img_src;
					//$res.= $full_image_path;
					
					if ($img_src)
						{
							$res.= '<div class="marathon-liste-image" style="background-image:url('.$img_src.')"></div>';
						}else{
							$res.= '<div class="marathon-liste-image" style="background-color:#000"></div>';
						}
					 if($last_linked_events[$i]){
						$res.= '<div><b>'.$ev_cat_event->getEventCatEventByID($last_linked_events[$i]['CategorieID'])['donnees']->getIntitule().'</b></div>';

					 }else{
						$res.= '<div><b>Pas d\'évenement</b></div>';

					 }
					if($first_events && $first_events[$i]){
						$premier_even= $first_events[$i];
						$nom_premier_even= $premier_even['Nom'];
						$id= $premier_even['ID'];
						$date_premier_even=strftime("%A %d %B %Y",strtotime($premier_even['DateDebut']));
						$res.= '<div>'.utf8_encode($date_premier_even).'</div>';
					}else{
						$res.= '<div>Prochaine date à venir</div>';
					}
					$res.= '<span class="tt-les-infos"><a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">Toutes les infos</a></span>';

				
			$res.= '</div>';
			$i++;
		}
		return $res;
		
	}


	function display_results_2($results){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
				$i=0;                             
				setlocale(LC_TIME, "fr_FR","French");
				$res="";
				foreach ($results as $resultat) {
		
					$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
				   $nom_res= $resultat['nom'];
		
					$res.= '<div class="col-sm-6 marathon-grid">
						<a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
							<h4 class="page-marathon-title">'.$nom_res.'<img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$pays_flag.'" alt=""/></h4></a> ';
							 
							$img_src='/images/marathons/thumb_'.$resultat['image'];
							$full_image_path="http://" . $_SERVER['HTTP_HOST'] .$img_src;
							//$res.= $full_image_path;
							
							if ($img_src)
								{
									$res.= '<div class="marathon-liste-image" style="background-image:url('.$img_src.')"></div>';
								}else{
									$res.= '<div class="marathon-liste-image" style="background-color:#000"></div>';
								}
							 if($resultat['last_linked_events_cat_id']){
								$res.= '<div><b>'.$ev_cat_event->getEventCatEventByID($resultat['last_linked_events_cat_id'])['donnees']->getIntitule().'</b></div>';
		
							 }else{
								$res.= '<div><b>Pas d\'évenement</b></div>';
		
							 }
							if($resultat["date_prochain_evenement"]!='NULL'){
								$nom_premier_even= $resultat["date_prochain_evenement_nom"];
                                $id= $resultat["date_prochain_evenement_id"];
                                $date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
                                        
								$res.= '<div>'.utf8_encode($date_premier_even).'</div>';
							}else{
								$res.= '<div>Prochaine date à venir</div>';
							}
							$res.= '<span class="tt-les-infos"><a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">Toutes les infos</a></span>';
		
						
					$res.= '</div>';
					$i++;
				}
				return $res;
	}


	function display_results_3($results){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
				$i=0;                             
				setlocale(LC_TIME, "fr_FR","French");
				$res="";
				foreach ($results as $resultat) {
		
					$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
				   $nom_res= $resultat['nom'];
				   $timestamp=strtotime($resultat["date_prochain_evenement"]);
					$res.= '<div class="col-sm-12 bottom-border"><a class="marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
					<div class="marathon-grid2">
						<div class="flag-part">
						<span class="calendar"><i>'.date("M",$timestamp ).'</i>'. date("d",$timestamp ).'</span> 
						
						</div>';
							 
							$img_src='/images/marathons/thumb_'.$resultat['image'];
							$full_image_path="https://" . $_SERVER['HTTP_HOST'] .$img_src;
							//$res.= $full_image_path;
							
							if ($img_src)
								{
									$res.= '<div class="marathon-image" style="background-image:url('.$img_src.')"></div>';
								}else{
									$res.= '<div class="marathon-image" style="background-color:#000"></div>';
								}
							
								$res.= '
								</div>
								<h4 class="marathon-title">Marathon '.$nom_res.'</h4></a> ';
		
							
							
						
					$res.= '</div>';
					$i++;
				}
				return $res;
	}

	function display_results_4($results){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
				$i=0;                             
				setlocale(LC_TIME, "fr_FR","French");
				$res="";
				foreach ($results as $resultat) {
		
					$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
				   $nom_res= $resultat['nom'];
				   $timestamp=strtotime($resultat["date_prochain_evenement"]);
					$res.= '<div class="col-sm-12 bottom-border"><a class="marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
					<div class="coming-soon-image">
						';
							$date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
							$img_src='/images/marathons/thumb_'.$resultat['image'];
							$full_image_path="https://" . $_SERVER['HTTP_HOST'] .$img_src;
							//$res.= $full_image_path;
							
							if ($img_src)
								{
									$res.= '<div class="marathon-image" style="background-image:url('.$img_src.')"></div>';
								}else{
									$res.= '<div class="marathon-image" style="background-color:#000"></div>';
								}
							
								$res.= '
								</div>
								<h4 class="marathon-title">Marathon '.$nom_res.'</h4> 
								<div class="capitalize">'.utf8_encode($date_premier_even).'</div></a> ';
								
                                   
							
							
						
					$res.= '</div>';
					$i++;
				}
				return $res;
	}


	function getMarathonsbyName($order,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  if($order=="ASC"){
					$req = $bdd->prepare("SELECT * FROM marathons ORDER BY nom asc LIMIT :offset,$par_pages");
					
				  }else{
					$req = $bdd->prepare("SELECT * FROM marathons ORDER BY nom desc LIMIT :offset,$par_pages");
				  }
				  $req->bindValue('offset', $offset, PDO::PARAM_INT);
				  $req->execute();
				  $results= array();
				  $first_events= array();
					$last_linked_events= array();
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
						$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
						$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
						$req2->execute();
						if($req2->rowCount()>0){
							while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($first_events, $row2);
							}
						}else {
							array_push($first_events, NULL);
						}

						$req3 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1 ORDER BY ID desc limit 1");
						$req3->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
					
						$req3->execute();
						if($req3->rowCount()>0){
							while ( $row3  = $req3->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($last_linked_events, $row3);
							}
						}else {
							array_push($last_linked_events, NULL);
						}
						
					array_push($results, $row);
	             }
				 $bdd=null;
				 
				 echo display_results($results,$last_linked_events,$first_events);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	
	function getMarathonsbyPays($pays_id,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  
					$req = $bdd->prepare("SELECT * FROM marathons where PaysID like :pays_id ORDER BY nom asc LIMIT :offset,$par_pages");
					$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
				  $req->bindValue('offset', $offset, PDO::PARAM_INT);
				  $req->execute();
				  $results= array();
				  $first_events= array();
					$last_linked_events= array();
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
						$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
						$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
						$req2->execute();
						if($req2->rowCount()>0){
							while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($first_events, $row2);
							}
						}else {
							array_push($first_events, NULL);
						}

						$req3 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1 ORDER BY ID desc limit 1");
						$req3->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
					
						$req3->execute();
						if($req3->rowCount()>0){
							while ( $row3  = $req3->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($last_linked_events, $row3);
							}
						}else {
							array_push($last_linked_events, NULL);
						}
						
					array_push($results, $row);
	             }
				 $bdd=null;
				 
				 echo display_results($results,$last_linked_events,$first_events);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	
	function getMarathonsbyDate($pays_id,$debut,$fin,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  
					$req = $bdd->prepare("SELECT * FROM marathons where PaysID like :pays_id  ORDER BY nom asc LIMIT :offset,$par_pages");
					$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
					$req->bindValue('offset', $offset, PDO::PARAM_INT);
					$req->execute();
					$results= array();
					$first_events= array();
					$last_linked_events= array();
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :debut) AND (DateDebut < :fin) ORDER BY DateDebut limit 1");
						$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
						if($debut){
							$req2->bindValue('debut', date($debut), PDO::PARAM_STR); 
						}else{
							$req2->bindValue('debut', date('Y-m-d'), PDO::PARAM_STR); 
						}
						if($fin){
							$req2->bindValue('fin', date($fin), PDO::PARAM_STR); 
						}else{
							$req2->bindValue('fin', date('9999-12-31'), PDO::PARAM_STR); 
						}
						$req2->execute();
						if($req2->rowCount()>0){
							while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($first_events, $row2);
								$row['date_prochain_evenement']=$row2['DateDebut'];
								$row['date_prochain_evenement_nom']=$row2['Nom'];
								$row['date_prochain_evenement_id']=$row2['ID'];
								$row['last_linked_events_cat_id']=$row2['CategorieID'];

							}
						}else {
							continue;
							array_push($first_events, NULL);
							$row['last_linked_events_id']=NULL;
						}

						
						
					array_push($results, $row);
	             }
				 $bdd=null;
				 $results_sorted_by_next_event=array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC));

				 echo display_results_2($results_sorted_by_next_event);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


	function getMarathonsbykeyword($pays_id,$debut,$fin,$offset,$par_pages,$page,$search)
	{
		try {
				  include("../database/connexion.php");
				  
					$req = $bdd->prepare("SELECT * FROM marathons where PaysID like :pays_id and nom like :search ORDER BY nom asc");
					$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
					$req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
				
					$req->execute();
					$results= array();
					$first_events= array();
					$last_linked_events= array();
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :debut) AND (DateDebut < :fin) ORDER BY DateDebut limit 1");
						$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
						
						if($debut){
							$req2->bindValue('debut', date($debut), PDO::PARAM_STR); 
						}else{
							$req2->bindValue('debut', date('Y-m-d'), PDO::PARAM_STR); 
						}
						if($fin){
							$req2->bindValue('fin', date($fin), PDO::PARAM_STR); 
						}else{
							$req2->bindValue('fin', date('9999-12-31'), PDO::PARAM_STR); 
						}
						$req2->execute();
						if($req2->rowCount()>0){
							while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
								//var_dump($row2);exit();  
								array_push($first_events, $row2);
								$row['date_prochain_evenement']=$row2['DateDebut'];
								$row['date_prochain_evenement_nom']=$row2['Nom'];
								$row['date_prochain_evenement_id']=$row2['ID'];
								$row['last_linked_events_cat_id']=$row2['CategorieID'];

							}
						}else {
							$row['date_prochain_evenement']='NULL';
							$row['last_linked_events_cat_id']=NULL;
						}

						
						
					array_push($results, $row);
	             }
				 $bdd=null;
				// $results_sorted_by_next_event=array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC));
				 $results_sorted_by_next_event=array_slice(array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC)),$offset,$par_pages);

				 echo display_results_2($results_sorted_by_next_event);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


	

	function getMarathonsbyNextEventDate($offset,$par_pages){
		try{
			include("../database/connexion.php");

			$req = $bdd->prepare("SELECT * FROM marathons");
			$req->execute();
			$results= array();
			//$first_events= array();
			$last_linked_events= array();
			while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
				$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
				$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
				
				$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
				$req2->execute();
				if($req2->rowCount()>0){
					while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
						//var_dump($row2);exit();  
						//array_push($first_events, $row2);
						$row['date_prochain_evenement']=$row2['DateDebut'];
						$row['date_prochain_evenement_nom']=$row2['Nom'];
						$row['date_prochain_evenement_id']=$row2['ID'];
						$row['last_linked_events_cat_id']=$row2['CategorieID'];

					}
				}else {
					//array_push($first_events, NULL);
					$row['date_prochain_evenement']='NULL';
					$row['last_linked_events_cat_id']=NULL;
		
				}
		
				
				
			  array_push($results, $row);
		  }}
		  catch(Exception $e)
		  {
			  die('Erreur : ' . $e->getMessage());
		  }

		  $results_sorted_by_next_event=array_slice(array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC)),$offset,$par_pages);
		  echo display_results_2($results_sorted_by_next_event);
	}


	function getHomeEvents(){
		try{
			include("../database/connexion.php");

			$req = $bdd->prepare("SELECT * FROM marathons");
			$req->execute();
			$results= array();
			//$first_events= array();
			$last_linked_events= array();
			while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
				$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
				$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
				
				$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
				$req2->execute();
				if($req2->rowCount()>0){
					while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
						//var_dump($row2);exit();  
						//array_push($first_events, $row2);
						$row['date_prochain_evenement']=$row2['DateDebut'];
						$row['date_prochain_evenement_nom']=$row2['Nom'];
						$row['date_prochain_evenement_id']=$row2['ID'];
						$row['last_linked_events_cat_id']=$row2['CategorieID'];

					}
				}else {
					//array_push($first_events, NULL);
					$row['date_prochain_evenement']='NULL';
					$row['last_linked_events_cat_id']=NULL;
		
				}
		
				
				
			  array_push($results, $row);
		  }}
		  catch(Exception $e)
		  {
			  die('Erreur : ' . $e->getMessage());
		  }

		  $results_sorted_by_next_event=array_slice(array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC)),0,10);
		  return display_results_3($results_sorted_by_next_event);
	}



	function getThisMonthEvents(){
		try{
			include("../database/connexion.php");

			$req = $bdd->prepare("SELECT * FROM marathons");
			$req->execute();
			$results= array();
			//$first_events= array();
			$last_linked_events= array();
			while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
				$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
				$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
				
				$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
				$req2->execute();
				if($req2->rowCount()>0){
					while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
						//var_dump($row2);exit();  
						//array_push($first_events, $row2);
						$row['date_prochain_evenement']=$row2['DateDebut'];
						$row['date_prochain_evenement_nom']=$row2['Nom'];
						$row['date_prochain_evenement_id']=$row2['ID'];
						$row['last_linked_events_cat_id']=$row2['CategorieID'];

					}
				}else {
					//array_push($first_events, NULL);
					$row['date_prochain_evenement']='NULL';
					$row['last_linked_events_cat_id']=NULL;
		
				}
		
				
				
			  array_push($results, $row);
		  }}
		  catch(Exception $e)
		  {
			  die('Erreur : ' . $e->getMessage());
		  }

		  $results_sorted_by_next_event=array_slice(array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC)),0,4);
		  return display_results_4($results_sorted_by_next_event);
	}


	function getBestMarathonResultsByYear($marathon_id,$sexe,$limit)
	{
		try {
			include("../database/connexion.php");
				  $liste= array();
				  $req0 = $bdd->prepare("SELECT DISTINCT YEAR(E.DateDebut) as d FROM evresultats R,evenements E,champions C where R.ChampionID=C.ID and E.ID=R.EvenementID and C.Sexe=:sexe AND E.marathon_id=:mar_id order by YEAR(E.DateDebut)");
				  $req0->bindValue('mar_id',$marathon_id, PDO::PARAM_INT);
				  $req0->bindValue('sexe',$sexe, PDO::PARAM_STR);
				  $req0->execute();
				  
				  while ( $row0  = $req0->fetch(PDO::FETCH_ASSOC)) {    
					  $req = $bdd->prepare("SELECT C.Nom,R.Temps,YEAR(E.DateDebut) as annee   FROM evresultats R,evenements E,champions C where R.ChampionID=C.ID and E.ID=R.EvenementID and C.Sexe=:sexe AND E.marathon_id=:mar_id and YEAR(E.DateDebut) like :annee order by R.Temps asc limit 1;");
					  $req->bindValue('mar_id',$marathon_id, PDO::PARAM_INT);
					  $req->bindValue('annee','%'.$row0['d'].'%', PDO::PARAM_STR);
					  $req->bindValue('sexe',$sexe, PDO::PARAM_STR);
					  $req->execute();
					  
					  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  array_push($liste, $row);
					  }
				 }
	             $bdd=null;
	            echo json_encode($liste);
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}



	if(isset($_POST['function'])){
		$function=$_POST['function'];
		if($function=="getMarathonsbyName"){
			$order=$_POST['order'];
			$offset=$_POST['offset'];
			$par_pages=500;//$_POST['par_pages'];
			$page=$_POST['page'];
			getMarathonsbyName($order,$offset,$par_pages,$page);
			//echo '<div>'.$order.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getMarathonsbyPays"){
			$pays_id=$_POST['pays_id'];
			$offset=$_POST['offset'];
			$par_pages=500;//$_POST['par_pages'];
			$page=$_POST['page'];
			getMarathonsbyPays($pays_id,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getTopChronosbyYears"){
			$sexe=$_POST['sexe'];
			$marathon_id=$_POST['marathon_id'];
			$limit=$_POST['limit'];
			getBestMarathonResultsByYear($marathon_id,$sexe,$limit);
			//echo json_encode('<div>'.$sexe.' '.$marathon_id.' '.$limit.'</div>') ;
		}
		else if($function=="getMarathonsbyDate"){
			$debut=$_POST['debut'];
			$pays_id=$_POST['pays_id'];
			$fin=$_POST['fin'];
			$offset=$_POST['offset'];
			$par_pages=500;//$_POST['par_pages'];
			$page=$_POST['page'];
			if($debut==NULL){
				$debut="1800-01-01";
			}else if($fin==NULL){
				$fin="2666-01-01";
			}
			getMarathonsbyDate($pays_id,$debut,$fin,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="search"){
			$debut=$_POST['debut'];
			$pays_id=$_POST['pays_id'];
			$fin=$_POST['fin'];
			$offset=$_POST['offset'];
			$search=$_POST['search'];
			$par_pages=500;//$_POST['par_pages'];
			$page=$_POST['page'];
			getMarathonsbykeyword($pays_id,$debut,$fin,$offset,$par_pages,$page,$search);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getMarathonsbyNextEventDate"){
			$par_pages=500;//$_POST['par_pages'];
			$offset=$_POST['offset'];
			getMarathonsbyNextEventDate($offset,$par_pages);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		

	}

    function getMarathonsAgendaByPays(){
		try {
			include("../database/connexion.php");
            $liste= array();
            $req0 = $bdd->prepare("SELECT DISTINCT pays.ID as pays_id, pays.Abreviation, pays.Abreviation_2,pays.Abreviation_3,pays.Abreviation_4,pays.NomPays FROM `evenements`,pays where DateDebut>DATE(NOW()) and evenements.PaysID=pays.Abreviation order by pays.NomPays asc;");
            
            $req0->execute();
            
            while ( $row0  = $req0->fetch(PDO::FETCH_ASSOC)) {    
                array_push($liste, $row0); 
            }
            $bdd=null;
            $len = (int) count($liste);
            $firsthalf = array_slice($liste, 0, $len / 2);
            $secondhalf = array_slice($liste, $len / 2);
            return array('validation'=>true,'donnees_1'=>$firsthalf ,'donnees_2'=>$secondhalf,'message'=>'');
	    }
	       
	    catch(Exception $e){
	        die('Erreur : ' . $e->getMessage());
	    }
	}

	

	function getMarathonsAgendaByPaysflitered($pays_ab1,$pays_ab2,$pays_ab3,$pays_ab4){
		try {
			include("../database/connexion.php");
            $results= array();
            $req0 = $bdd->prepare("select m.nom as nom,m.image,m.id,e.DateDebut,e.PaysID from marathons m,evenements e where e.marathon_id=m.id and e.DateDebut>DATE(NOW()) and (e.PaysID=:pays_ab1 or e.PaysID=:pays_ab2 or e.PaysID=:pays_ab3 or e.PaysID=:pays_ab4) order by e.DateDebut asc;");
            $req0->bindValue('pays_ab1',$pays_ab1, PDO::PARAM_STR);
			$req0->bindValue('pays_ab2',$pays_ab2, PDO::PARAM_STR);
			$req0->bindValue('pays_ab3',$pays_ab3, PDO::PARAM_STR);
			$req0->bindValue('pays_ab4',$pays_ab4, PDO::PARAM_STR);
            $req0->execute();
            
			$last_linked_events= array();
			while ( $row  = $req0->fetch(PDO::FETCH_ASSOC)) {  
				$req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
				$req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
				
				$req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
				$req2->execute();
				if($req2->rowCount()>0){
					while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
						//var_dump($row2);exit();  
						//array_push($first_events, $row2);
						$row['date_prochain_evenement']=$row2['DateDebut'];
						$row['date_prochain_evenement_nom']=$row2['Nom'];
						$row['date_prochain_evenement_id']=$row2['ID'];
						$row['last_linked_events_cat_id']=$row2['CategorieID'];

					}
				}else {
					//array_push($first_events, NULL);
					$row['date_prochain_evenement']='NULL';
					$row['last_linked_events_cat_id']=NULL;
		
				}
				array_push($results, $row);
		  }
		 	$results_sorted_by_next_event=array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC));
        	return array('validation'=>true,'donnees'=>$results_sorted_by_next_event,'message'=>'');
		}
		  catch(Exception $e)
		  {
			  die('Erreur : ' . $e->getMessage());
		  }

	}
	       
	   
    function getMarathonsAgendaByMois(){
		try {
			include("../database/connexion.php");
            $liste= array();
            $req0 = $bdd->prepare("SELECT CONCAT(MONTH(DateDebut),'/',YEAR(DateDebut)) AS 'year-date',MONTH(DateDebut) as mois,YEAR(DateDebut) as annee,DateDebut FROM `evenements` where DateDebut>DATE(NOW()) GROUP by mois,annee ORDER BY annee,mois asc;");
            
            $req0->execute();
            
            while ( $row0  = $req0->fetch(PDO::FETCH_ASSOC)) {    
                array_push($liste, $row0); 
            }
            $bdd=null;
            $len = (int) count($liste);
            $firsthalf = array_slice($liste, 0, $len / 2);
            $secondhalf = array_slice($liste, $len / 2);
            return array('validation'=>true,'donnees_1'=>$firsthalf ,'donnees_2'=>$secondhalf,'message'=>'');
	    }
	       
	    catch(Exception $e){
	        die('Erreur : ' . $e->getMessage());
	    }
	}
	function getMarathonsAgendaByMoisfiltered($mois,$annee){
		try {
			include("../database/connexion.php");
            $liste= array();
            $req0 = $bdd->prepare("select m.nom as Nom,m.id,e.DateDebut,e.PaysID from marathons m,evenements e where e.marathon_id=m.id and DateDebut like :datedeb and DateDebut>DATE(NOW()) ORDER BY DateDebut asc;");
            
			$req0->bindValue('datedeb','%'.$annee.'-'.$mois.'%', PDO::PARAM_STR);
            $req0->execute();
            
            while ( $row0  = $req0->fetch(PDO::FETCH_ASSOC)) {    
                array_push($liste, $row0); 
            }
            $bdd=null;
            $len = (int) count($liste);
            $firsthalf = array_slice($liste, 0, $len / 2);
            $secondhalf = array_slice($liste, $len / 2);
            return array('validation'=>true,'donnees_1'=>$firsthalf ,'donnees_2'=>$secondhalf,'message'=>'');
	    }
	       
	    catch(Exception $e){
	        die('Erreur : ' . $e->getMessage());
	    }
	}
	function getMarathonsById($id){
		try {
			include("../database/connexion.php");
            $liste= array();
            $req0 = $bdd->prepare("select * from marathons where id=:id");
            
			$req0->bindValue('id',$id, PDO::PARAM_INT);
            $req0->execute();
            while ( $row0  = $req0->fetch(PDO::FETCH_ASSOC)) {    
                array_push($liste, $row0); 
            }
           
            $bdd=null;
           
            return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	    }
	       
	    catch(Exception $e){
	        die('Erreur : ' . $e->getMessage());
	    }
	}
?>