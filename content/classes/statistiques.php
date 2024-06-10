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



	function display_results($results,$periode,$pays_1,$sexe,$nbr){
		$pays=new pays();
		$pays_datas=($pays_1!="tous")?$pays->getFlagByAbreviation($pays_1)['donnees']:null;
		$ev_cat_event=new evCategorieEvenement();
		$res="";
		setlocale(LC_TIME, "fr_FR","French");
		$res.='<h3>Les '.$nbr.' meilleurs chronos '.$periode.' ';
		if($sexe=='M'){
			$res.='chez les hommes';
		}else if($sexe=='F'){
			$res.='chez les femmes';
		}else{
			$res.='chez les hommes et les femmes';
		}
		if($pays_1!="tous"){
			$res.=' '.$pays_datas['prefixe'].' '.$pays_datas['NomPays'];

		}else{
			$res.=' dans tous les pays';

		}
		$res.=' </h3>';

		$res.=' <br>
		<table id="tableau-stats" data-page-length="10" class="display">
		<thead>
		<tr>
			<th style="text-transform: capitalize;">Rang</th>
			<th style="text-transform: capitalize;">Sexe</th>
			<th style="text-transform: capitalize;">Athlète</th>
			<th style="text-transform: capitalize;">Pays</th>
			<th style="text-transform: capitalize;">Temps</th>
			<th style="text-transform: capitalize;">Evenement</th>
			<th style="text-transform: capitalize;">Date</th>
        </tr>
        </thead>
        <tbody>';
		$i=1;
		foreach ($results as $result) {
			$cat_event=$ev_cat_event->getEventCatEventByID($result['CategorieID'])['donnees']->getIntitule();
			$nom_res='<strong>'.$cat_event.' '.$result['prefixe'].' '.$result['nom_ev'].'</strong>';
			$pays_datas=$pays->getFlagByAbreviation($result['PaysID'])['donnees'];
			$nom_res_lien=$cat_event.' - '.$result['nom_ev'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($result['DateDebut'])));
			$date_res=utf8_encode(strftime("%d %B %Y",strtotime($result['DateDebut'])));
			$res.= '<tr>';
			$res.= '<td>'.$i.'</td>';
			$res.= '<td>'.$result['Sexe'].'</td>';
			$res.= '<td><a href="athlete-'.$result['cid'].'-'.slugify($result['nom_champ']).'.html">'.$result['nom_champ'].'</a></td>';
			$res.= '<td>'.$pays_datas['NomPays'].'</td>';
			$res.= '<td>'.$result['Temps'].'</td>';
			$res.= '<td><a href="/resultats-marathon-'.$result['eid'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a></td>';
			$res.= '<td>'.$date_res.'</td>';
			$res.= '</tr>';
			$i+=1;
		}
		$res.='</tbody>
        </table>';
		return $res;
		
	}


	function getStatistiques_range($date_deb, $date_fin, $pays, $sexe, $nbr) {
		try {
			include("../database/connexion.php");
	
			// Préparation de la condition de date pour la plage spécifiée
			$datecond_deb = $date_deb;
			$datecond_fin = $date_fin;
	
			// Construction de la requête SQL de base
			$sql = "SELECT c.ID as cid, c.Nom as nom_champ, e.prefixe, e.CategorieID, c.Sexe, c.PaysID, e.ID as eid, e.Nom as nom_ev, e.DateDebut, r.Temps, r.Rang
					FROM evresultats r
					JOIN champions c ON r.ChampionID = c.ID
					JOIN evenements e ON r.EvenementID = e.ID
					WHERE c.Sexe IS NOT NULL
					AND c.Nom IS NOT NULL
					AND r.Rang != 0
					AND r.Temps IS NOT NULL
					AND e.DateDebut BETWEEN :datecond_deb AND :datecond_fin";
	
			// Ajouter la condition sur le sexe si différent de "MF"
			if ($sexe != 'MF') {
				$sql .= " AND c.Sexe = :sexe";
			}
	
			// Si le pays n'est pas "tous", on ajoute la condition sur le pays
			if ($pays != 'tous') {
				$sql .= " AND c.PaysID = :pid";
			}
	
			$sql .= " ORDER BY r.Temps ASC LIMIT :lim";
	
			// Préparation de la requête
			$req = $bdd->prepare($sql);
	
			// Liaison des paramètres
			$req->bindValue('datecond_deb', $datecond_deb, PDO::PARAM_STR);
			$req->bindValue('datecond_fin', $datecond_fin, PDO::PARAM_STR);
			$req->bindValue('lim', (int)$nbr, PDO::PARAM_INT);
	
			// Liaison du paramètre 'sexe' uniquement si différent de "MF"
			if ($sexe != 'MF') {
				$req->bindValue('sexe', $sexe, PDO::PARAM_STR);
			}
	
			// Liaison du paramètre 'pid' uniquement si le pays n'est pas "tous"
			if ($pays != 'tous') {
				$req->bindValue('pid', $pays, PDO::PARAM_STR);
			}
	
			// Exécution de la requête
			$req->execute();
			$results = array();
			while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
				array_push($results, $row);
			}
	
			$bdd = null;
			echo display_results($results, "du " . $date_deb . " au " . $date_fin, $pays, $sexe, $nbr);
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
	}
	



	function getStatistiques_by_year($year, $pays, $sexe, $nbr) {
		try {
			include("../database/connexion.php");
	
			// Préparation de la condition de date pour l'année spécifiée
			$datecond = $year . '-%';
	
			// Construction de la requête SQL de base
			$sql = "SELECT c.ID as cid, c.Nom as nom_champ, e.prefixe, e.CategorieID, c.Sexe, c.PaysID, e.ID as eid, e.Nom as nom_ev, e.DateDebut, r.Temps, r.Rang
					FROM evresultats r
					JOIN champions c ON r.ChampionID = c.ID
					JOIN evenements e ON r.EvenementID = e.ID
					WHERE c.Sexe IS NOT NULL
					AND c.Nom IS NOT NULL
					AND r.Rang != 0
					AND r.Temps IS NOT NULL
					AND e.DateDebut LIKE :datecond";
	
			// Ajouter la condition sur le sexe si différent de "MF"
			if ($sexe != 'MF') {
				$sql .= " AND c.Sexe = :sexe";
			}
	
			// Si le pays n'est pas "tous", on ajoute la condition sur le pays
			if ($pays != 'tous') {
				$sql .= " AND c.PaysID = :pid";
			}
	
			$sql .= " ORDER BY r.Temps ASC LIMIT :lim";
	
			// Préparation de la requête
			$req = $bdd->prepare($sql);
	
			// Liaison des paramètres
			$req->bindValue('datecond', $datecond, PDO::PARAM_STR);
			$req->bindValue('lim', (int)$nbr, PDO::PARAM_INT);
	
			// Liaison du paramètre 'sexe' uniquement si différent de "MF"
			if ($sexe != 'MF') {
				$req->bindValue('sexe', $sexe, PDO::PARAM_STR);
			}
	
			// Liaison du paramètre 'pid' uniquement si le pays n'est pas "tous"
			if ($pays != 'tous') {
				$req->bindValue('pid', $pays, PDO::PARAM_STR);
			}
	
			// Exécution de la requête
			$req->execute();
			$results = array();
			while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
				array_push($results, $row);
			}
	
			$bdd = null;
			echo display_results($results, "de l'année " . $year, $pays, $sexe, $nbr);
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
	}
	


	function getStatistiques($periode, $pays, $sexe, $nbr) {
		$annee = date('Y');
		$mois = date('m');
		try {
			include("../database/connexion.php");
	
			// Préparation de la condition de date en fonction de la période sélectionnée
			if ($periode == 'du mois en cours') {
				$datecond = $annee . '-' . $mois . '-%';
			} else if ($periode == 'de cette année') {
				$datecond = $annee . '-%';
			} else if ($periode == 'des 30 derniers jours') {
				$datecond = date('Y-m-d', strtotime('-30 days'));
			}
	
			// Construction de la requête SQL de base
			$sql = "SELECT c.ID as cid, c.Nom as nom_champ, e.prefixe, e.CategorieID, c.Sexe, c.PaysID, e.ID as eid, e.Nom as nom_ev, e.DateDebut, r.Temps, r.Rang
					FROM evresultats r
					JOIN champions c ON r.ChampionID = c.ID
					JOIN evenements e ON r.EvenementID = e.ID
					WHERE c.Sexe IS NOT NULL
					AND c.Nom IS NOT NULL
					AND r.Rang!=0
					AND r.Temps IS NOT NULL
					AND e.DateDebut LIKE :datecond";
	
			// Si la période est "des 30 derniers jours", on change l'opérateur de comparaison
			if ($periode == 'des 30 derniers jours') {
				$sql = str_replace('LIKE :datecond', '> :datecond', $sql);
			}
	
			// Ajouter la condition sur le sexe si différent de "MF"
			if ($sexe != 'MF') {
				$sql .= " AND c.Sexe = :sexe";
			}
	
			// Si le pays n'est pas "tous", on ajoute la condition sur le pays
			if ($pays != 'tous') {
				$sql .= " AND c.PaysID = :pid";
			}
	
			$sql .= " ORDER BY r.Temps ASC LIMIT :lim";
	
			// Préparation de la requête
			$req = $bdd->prepare($sql);
	
			// Liaison des paramètres
			$req->bindValue('datecond', $datecond, PDO::PARAM_STR);
			$req->bindValue('lim', (int)$nbr, PDO::PARAM_INT);
	
			// Liaison du paramètre 'sexe' uniquement si différent de "MF"
			if ($sexe != 'MF') {
				$req->bindValue('sexe', $sexe, PDO::PARAM_STR);
			}
	
			// Liaison du paramètre 'pid' uniquement si le pays n'est pas "tous"
			if ($pays != 'tous') {
				$req->bindValue('pid', $pays, PDO::PARAM_STR);
			}
	
			// Exécution de la requête
			$req->execute();
			$results = array();
			while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
				array_push($results, $row);
			}
	
			$bdd = null;
			echo display_results($results, $periode, $pays, $sexe, $nbr);
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
	}
	



	if (isset($_POST['function'])) {
		$function = $_POST['function'];
		if ($function == "getStatistiques") {
			if (isset($_POST['periode'], $_POST['pays'], $_POST['sexe'], $_POST['nbr'])) {
				$periode = $_POST['periode'];
				$pays = $_POST['pays'];
				$sexe = $_POST['sexe'];
				$year = $_POST['year'];
				$range_deb = $_POST['range_deb'];
				$range_fin = $_POST['range_fin'];
				$nbr = $_POST['nbr'];
				if($periode=="specific-year"){
					getStatistiques_by_year($year, $pays, $sexe, $nbr);
				}else if($periode=="range"){
					getStatistiques_range($range_deb,$range_fin, $pays, $sexe, $nbr);
				}else{
					getStatistiques($periode, $pays, $sexe, $nbr);
				}
			} else {
				echo 'Paramètres manquants.';
			}
		}
	}
    
?>