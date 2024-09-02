<?php
include("pays.php");
include("evCategorieEvenement.php");
include("../classes/champion.php");


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

	function display_results($results){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
		$champion=new champion();
		$i=0;                             
        setlocale(LC_TIME, "fr_FR","French");
		if($results){
			$res="<span>Résultats de votre recherche (".count($results).") </span><br><ul class='athletes-liste-grid test-image'>";
			foreach ($results as $resultat) {
	
	
				$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
				$pays_nom=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['NomPays'];
				$champion_name=slugify($resultat['Nom']);
				
				$photos_count = $resultat['t_photos']; // Assuming 't_photos' contains the photo count

				// Fetch photos for the current 'resultat'
				$photos = $champion->getChampionsPhoto($resultat['ID'])["donnees"]; // Replace with your actual function

				$res.= '<div class="athletes-grid-element">';

				// Ensure 'photos' is an array
				if (!isset($photos) || !is_array($photos)) {
					$photos = []; // Initialize as empty array if not set
				}

				// Conditionally add the photo if there are photos
				if ($photos_count > 0 && is_array($photos)) {
					foreach ($photos as $photo) {
						if (isset($photo['Galerie_id']) && isset($photo['Nom'])) {
							$res.= '<img class="img-test" src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="auto" alt=""/>';
						} else {
							$res.= '<li>Photo details missing</li>';
						}
					}
				} else {
					if($resultat['Sexe']=="M"){
						$res.= '<img class="img-test" src="/images/homme.svg" width="116" height="auto" alt=""/>';
					}else{
						$res.= '<img class="img-test" src="/images/femme.svg" width="116" height="auto" alt=""/>';
					}
				}
				
				$res.= '<div><a href="athlete-'.$resultat['ID'].'-'.$champion_name.'.html"><strong>'.$resultat['Nom'].'</strong></a>
					<img src="../../images/flags/'.$pays_flag.'" class="float-r" alt=""/><br>'.$pays_nom.'<br>
					<span><i class="fa-solid fa-medal"></i> ('.$resultat['t_res'].')</span>
					<span>- <i class="fa-solid fa-newspaper"></i> ('.$resultat['t_news'].')</span>
					<span>- <i class="fa-solid fa-camera"></i> ('.$photos_count.')</span>
					<span>- <i class="fa-solid fa-video"></i> ('.$resultat['t_videos'].')</span>
					</div>
					</div>';
				

	
				$i++;
			}
			$res.= '</ul>';
			return $res;
		}else{
			return null;
		}
		
		
	}




	
	function getChampionsbyPays($pays_id,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  
					
					if($pays_id=="all"){
						$req = $bdd->prepare("select c.* from champions c order by c.Nom asc LIMIT :offset,$par_pages;");

						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("select c.* from champions c where c.PaysID like :pays_id  order by c.Nom asc LIMIT :offset,$par_pages;");

						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$champ_id =  $row["ID"];
						$req1 = $bdd->prepare('SELECT COUNT(*) as total FROM `evresultats` WHERE ChampionID=:cid');
						$req1->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req1->execute();
						$row1  = $req1->fetch(PDO::FETCH_ASSOC);

						$req12 = $bdd->prepare('SELECT COUNT(*) as total FROM `images` WHERE Champion_id=:cid or Champion2_id=:cid');
						$req12->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req12->execute();
						$row12  = $req12->fetch(PDO::FETCH_ASSOC);

						$req13 = $bdd->prepare('SELECT COUNT(*) as total FROM `videos` WHERE Champion_id=:cid');
						$req13->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req13->execute();
						$row13  = $req13->fetch(PDO::FETCH_ASSOC);
						$req14 = $bdd->prepare('SELECT COUNT(*) as total FROM `news` WHERE championID=:cid');
						$req14->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req14->execute();
						$row14  = $req14->fetch(PDO::FETCH_ASSOC);
			  
						$row["t_videos"]=$row13["total"];
						$row["t_photos"]=$row12["total"];
						$row["t_res"]=$row1["total"];
						$row["t_news"]=$row14["total"];
						array_push($results, $row);
					}

				
				
					 $bdd=null;
				 	echo display_results($results);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	function getChampionsbySearch($search,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  $req = $bdd->prepare("select c.* from champions c where c.Nom like :search and c.Nom like :search order by c.Nom asc LIMIT :offset,$par_pages;");

				  $req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
				  $req->bindValue('offset', $offset, PDO::PARAM_INT);
				  $req->execute();
				  $results= array();
				  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$champ_id =  $row["ID"];
						$req1 = $bdd->prepare('SELECT COUNT(*) as total FROM `evresultats` WHERE ChampionID=:cid');
						$req1->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req1->execute();
						$row1  = $req1->fetch(PDO::FETCH_ASSOC);

						$req12 = $bdd->prepare('SELECT COUNT(*) as total FROM `images` WHERE Champion_id=:cid or Champion2_id=:cid');
						$req12->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req12->execute();
						$row12  = $req12->fetch(PDO::FETCH_ASSOC);

						$req13 = $bdd->prepare('SELECT COUNT(*) as total FROM `videos` WHERE Champion_id=:cid');
						$req13->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req13->execute();
						$row13  = $req13->fetch(PDO::FETCH_ASSOC);
						$req14 = $bdd->prepare('SELECT COUNT(*) as total FROM `news` WHERE championID=:cid');
						$req14->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req14->execute();
						$row14  = $req14->fetch(PDO::FETCH_ASSOC);
			  
						$row["t_videos"]=$row13["total"];
						$row["t_photos"]=$row12["total"];
						$row["t_res"]=$row1["total"];
						$row["t_news"]=$row14["total"];
						array_push($results, $row);
				  }
				   $bdd=null;
				   //echo display_results($results);
				   if($results){
					echo display_results($results);
				   }else{
						echo "Pas de résultats pour votre recherche '".$search."'";
				   }
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	function getNextChampions($search,$pays_id,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  $fin=$par_pages;
				  if($search){
					if($pays_id=="all"){
						$req = $bdd->prepare("select c.* from champions c where c.Nom like :search order by c.Nom asc LIMIT :offset,$par_pages;");

						$req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
	  
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("select c.* from champions c where c.Nom like :search and c.PaysID like :pays_id  order by c.Nom asc LIMIT :offset,$par_pages;");

						//$req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe  FROM evenements e,marathons m WHERE e.PaysID like :pays_id and m.id=e.marathon_id and e.Visible='1' and (e.PaysID like :search or e.Nom like :search or m.nom like :search) ORDER BY e.DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
	  
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
				  }else{
					if($pays_id=="all"){
						$req = $bdd->prepare("select c.* from champions c  order by c.Nom asc  LIMIT :offset,$par_pages;");

						//$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1'  ORDER BY DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("select c.* from champions c where c.PaysID like :pays_id  order by c.Nom asc LIMIT :offset,$par_pages;");

						//$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1' and PaysID like :pays_id ORDER BY DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
				  }
				  
				  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						$champ_id =  $row["ID"];
						$req1 = $bdd->prepare('SELECT COUNT(*) as total FROM `evresultats` WHERE ChampionID=:cid');
						$req1->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req1->execute();
						$row1  = $req1->fetch(PDO::FETCH_ASSOC);

						$req12 = $bdd->prepare('SELECT COUNT(*) as total FROM `images` WHERE Champion_id=:cid or Champion2_id=:cid');
						$req12->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req12->execute();
						$row12  = $req12->fetch(PDO::FETCH_ASSOC);

						$req13 = $bdd->prepare('SELECT COUNT(*) as total FROM `videos` WHERE Champion_id=:cid');
						$req13->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req13->execute();
						$row13  = $req13->fetch(PDO::FETCH_ASSOC);

						$req14 = $bdd->prepare('SELECT COUNT(*) as total FROM `news` WHERE championID=:cid');
						$req14->bindValue('cid',$champ_id, PDO::PARAM_INT);
						$req14->execute();
						$row14  = $req14->fetch(PDO::FETCH_ASSOC);
			  
						$row["t_videos"]=$row13["total"];
						$row["t_photos"]=$row12["total"];
						$row["t_res"]=$row1["total"];
						$row["t_news"]=$row14["total"];
						array_push($results, $row);
				  }
				   $bdd=null;
				   echo display_results($results);
		}
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}










	if(isset($_POST['function'])){
		$function=$_POST['function'];
		if($function=="getChampionsbySearch"){
			$search=$_POST['search'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getChampionsbySearch($search,$offset,$par_pages,$page);
			//echo '<div>'.$order.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getChampionsbyPays"){
			$pays_id=$_POST['pays_id'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getChampionsbyPays($pays_id,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getNextChampions"){
			$pays_id=$_POST['pays_id'];
			$search=$_POST['search'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getNextChampions($search,$pays_id,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		
		

	}

?>