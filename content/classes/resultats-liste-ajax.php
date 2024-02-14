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

	function display_results($results){
		$pays=new pays();
		$ev_cat_event=new evCategorieEvenement();
		$i=0;                             
        setlocale(LC_TIME, "fr_FR","French");
		$res="";
		foreach ($results as $resultat) {

			$cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();
			$pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
			$pays_nom=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['NomPays'];
			$date_res=utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

			$nom_res='<strong>'.$cat_event.' '.$resultat['prefixe'].' '.$resultat['Nom'].'</strong>';
			$nom_res_lien=$cat_event.' - '.$resultat['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

			$res.= '<div class="resultats-grid-element"><a href="/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a>
			<img class="float-r" src="../../images/flags/'.$pays_flag.'" alt=""/><br>
				'.$pays_nom.
			'<br><span>'.
			$date_res.'</span>
			</div>';

			$i++;
		}
		return $res;
		
	}




	
	function getEvenementsbyPays($pays_id,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  
					
					if($pays_id=="all"){
						$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1'  ORDER BY DateDebut desc LIMIT :offset,$par_pages");
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1' and PaysID like :pays_id ORDER BY DateDebut desc LIMIT :offset,$par_pages");
						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
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

	function getEvenementsbySearch($search,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  
				  $req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe  FROM evenements e,marathons m WHERE m.id=e.marathon_id and e.Visible='1' and (e.PaysID like :search or e.Nom like :search or m.nom like :search) ORDER BY e.DateDebut desc LIMIT :offset,$par_pages");
				  $req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
				  $req->bindValue('offset', $offset, PDO::PARAM_INT);
				  $req->execute();
				  $results= array();
				  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
					  array_push($results, $row);
				  }
				   $bdd=null;
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

	function getNextEvenements($search,$pays_id,$offset,$par_pages,$page)
	{
		try {
				  include("../database/connexion.php");
				  $fin=$par_pages;
				  if($search){
					if($pays_id=="all"){
						$req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe  FROM evenements e,marathons m WHERE m.id=e.marathon_id and e.Visible='1' and (e.PaysID like :search or e.Nom like :search or m.nom like :search) ORDER BY e.DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
	  
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe  FROM evenements e,marathons m WHERE e.PaysID like :pays_id and m.id=e.marathon_id and e.Visible='1' and (e.PaysID like :search or e.Nom like :search or m.nom like :search) ORDER BY e.DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('search', '%'.$search.'%', PDO::PARAM_STR);
						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
	  
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
				  }else{
					if($pays_id=="all"){
						$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1'  ORDER BY DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}else{
						$req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1' and PaysID like :pays_id ORDER BY DateDebut desc LIMIT :offset,$fin");
						$req->bindValue('pays_id', '%'.$pays_id.'%', PDO::PARAM_STR);
						$req->bindValue('offset', $offset, PDO::PARAM_INT);
						$req->execute();
						$results= array();
					}
				  }
				  
				  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
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
		if($function=="getEvenementsbySearch"){
			$search=$_POST['search'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getEvenementsbySearch($search,$offset,$par_pages,$page);
			//echo '<div>'.$order.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getEvenementsbyPays"){
			$pays_id=$_POST['pays_id'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getEvenementsbyPays($pays_id,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		else if($function=="getNextEvenements"){
			$pays_id=$_POST['pays_id'];
			$search=$_POST['search'];
			$offset=$_POST['offset'];
			$par_pages=39;//$_POST['par_pages'];
			$page=$_POST['page'];
			getNextEvenements($search,$pays_id,$offset,$par_pages,$page);
			//echo '<div>'.$pays_id.' '.$offset.' '.$par_pages.' '.$page.'</div>';
		}
		
		

	}

?>