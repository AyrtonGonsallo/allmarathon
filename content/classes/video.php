<?php
class video{
	
	private $id;
	private $titre;
	private $date;
	private $duree;
	private $objet;
	private $categorie;
	private $vignette;
	private $a_la_une;
	private $champion_id;
	private $description;
	private $technique_id;
	private $technique2_id;
	private $Evenement_id;
	private $poidID;
	private $sexe;
	private $top_ippon;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getTitre(){
		return $this->titre;
	}

	public function setTitre($titre){
		$this->titre = $titre;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getDuree(){
		return $this->duree;
	}

	public function setDuree($duree){
		$this->duree = $duree;
	}

	public function getObjet(){
		return $this->objet;
	}

	public function setObjet($objet){
		$this->objet = $objet;
	}
	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getCategorie(){
		return $this->categorie;
	}

	public function setCategorie($categorie){
		$this->categorie = $categorie;
	}

	public function getVignette(){
		return $this->vignette;
	}

	public function setVignette($vignette){
		$this->vignette = $vignette;
	}

	public function getA_la_une(){
		return $this->a_la_une;
	}

	public function setA_la_une($a_la_une){
		$this->a_la_une = $a_la_une;
	}

	public function getChampion_id(){
		return $this->champion_id;
	}

	public function setChampion_id($champion_id){
		$this->champion_id = $champion_id;
	}

	public function getTechnique_id(){
		return $this->technique_id;
	}

	public function setTechnique_id($technique_id){
		$this->technique_id = $technique_id;
	}

	public function getTechnique2_id(){
		return $this->technique2_id;
	}

	public function setTechnique2_id($technique2_id){
		$this->technique2_id = $technique2_id;
	}

	public function getEvenement_id(){
		return $this->evenement_id;
	}

	public function setEvenement_id($evenement_id){
		$this->evenement_id = $evenement_id;
	}

	public function getPoidID(){
		return $this->poidID;
	}

	public function setPoidID($poidID){
		$this->poidID = $poidID;
	}

	public function getSexe(){
		return $this->sexe;
	}

	public function setSexe($sexe){
		$this->sexe = $sexe;
	}

	public function getTop_ippon(){
		return $this->top_ippon;
	}

	public function setTop_ippon($top_ippon){
		$this->top_ippon = $top_ippon;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new video();
	        $instance->hydrate($donnees);
	        return $instance;
	    }

	public function hydrate(array $donnees){

		foreach ($donnees as $key => $value)
			{
				$method = 'set'.ucfirst($key);
			    if (method_exists($this, $method))
			    {
			    	$this->$method($value);
			    }
			  }
		}

	public function getLastVideos()
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE A_la_une='1'  ORDER BY Date DESC LIMIT 0,4");
	             $req->execute();
	             $last_videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $vd = self::constructWithArray($row);
					  array_push($last_videos, $vd);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$last_videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getLastNVideos($n)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE A_la_une='1'  ORDER BY Date DESC LIMIT ".$n);
	             $req->execute();
	             $last_videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $vd = self::constructWithArray($row);
					  array_push($last_videos, $vd);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$last_videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getVideoPerPage($page,$condition)
	{
		try {
				  include("../database/connexion.php");
				 if($condition!='')
				 {
				 	$req = $bdd->prepare("SELECT * FROM videos WHERE Categorie=:condition  ORDER BY date DESC LIMIT :offset,12");
				 	$req->bindValue('condition', $condition, PDO::PARAM_STR);
				 }
				 else{
				 	$req = $bdd->prepare("SELECT * FROM videos ORDER BY date DESC LIMIT :offset,12");
				 }
				 $req->bindValue('offset', $page*12, PDO::PARAM_INT);
	             
	             
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  // $vd = self::constructWithArray($row);
					  array_push($videos, $row);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getAllVideos($condition)
	{
		try {
				  include("../database/connexion.php");
				 if($condition!='')
				 {
				 	$req = $bdd->prepare("SELECT * FROM videos WHERE Categorie=:condition  ORDER BY date DESC");
				 	$req->bindValue('condition', $condition, PDO::PARAM_STR);
				 }
				 else{
				 	$req = $bdd->prepare("SELECT * FROM videos ORDER BY date DESC");
				 }
	             
	             
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  // $vd = self::constructWithArray($row);
					  array_push($videos, $row);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getVideosForNewsletter()
	{
		try {
				 require('../database/connexion.php');
				 
				 $req = $bdd->prepare("SELECT * FROM videos ORDER BY date DESC LIMIT 0,2");
				 
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {
					  array_push($videos, $row);
	             }
	                return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getVideosViaSearch($key_word,$page){
		if($page!=="page_resultat"){
			try {
					  include("../database/connexion.php");
					 $key_word="%".strtoupper($key_word)."%";
					 
					 $req = $bdd->prepare("SELECT e.id as 'Evenement_id',v.duree as Duree, v.titre as Titre, v.vignette as Vignette, v.top_ippon as top_ippon, v.id as ID  FROM videos v JOIN evenements e ON v.evenement_id=e.id WHERE UPPER(v.titre) LIKE :key_word OR UPPER(e.nom) LIKE :key_word ORDER BY date DESC LIMIT :offset,12");
					 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
					 $req->bindValue('offset', $page*12, PDO::PARAM_INT);
		             $req->execute();
		             $articles= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  array_push($articles, $row);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		    }else{
		    	try {
					 require('../database/connexion.php');
					 $key_word="%".strtoupper($key_word)."%";
					 
					 $req = $bdd->prepare("SELECT e.id as 'Evenement_id',v.duree as Duree, v.titre as Titre, v.vignette as Vignette, v.top_ippon as top_ippon, v.id as ID  FROM videos v JOIN evenements e ON v.evenement_id=e.id WHERE UPPER(v.titre) LIKE :key_word OR UPPER(e.nom) LIKE :key_word ORDER BY date DESC");
					 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
		             $req->execute();
		             $articles= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  array_push($articles, $row);
		             }
		                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		    }
		}
	public function getNumberPages($condition,$key_word){
		try {
				  include("../database/connexion.php");

				 if($key_word!='')
				 {
				 	$key_word="%".strtoupper($key_word)."%";
				 	$req = $bdd->prepare("SELECT COUNT(*) FROM videos v JOIN evenements e ON v.evenement_id=e.id WHERE UPPER(v.titre) LIKE :key_word OR UPPER(e.nom) LIKE :key_word ");
					$req->bindValue('key_word', $key_word, PDO::PARAM_STR);
				 }
				 else {
				 	if($condition!='')
					 {
					 	$req = $bdd->prepare("SELECT COUNT(*) FROM videos WHERE Categorie=:condition ");
					 	$req->bindValue('condition', $condition, PDO::PARAM_STR);
					 }
					 else{
					 	$req = $bdd->prepare("SELECT COUNT(*) FROM videos");
					 }
				 }
				 
	             $req->execute();
	             $nbr_pages= $req->fetch(PDO::FETCH_ASSOC);
	             // $nbr_pages= intval($req->fetch(PDO::FETCH_ASSOC)/7);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$nbr_pages,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getEventVideoById($id){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE Evenement_id=:id ORDER BY Titre ASC");
				 $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					   $vd = self::constructWithArray($row);
					  array_push($videos, $vd);
	             }
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getVideoById($id){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE ID=:id ");
				 $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->execute();
	             $video= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$video,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getVideosByChamp($champ_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM videos WHERE Champion_id=:champ_id ORDER BY Date DESC");
				 $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					   $vd = self::constructWithArray($row);
					  array_push($videos, $vd);
	             }
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getVideosByChampSuggest($champ_id,$id_video)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE Champion_id=:champ_id AND ID!=:id_video ORDER BY RAND() LIMIT 5");
				 $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
				 $req->bindValue('id_video',$id_video, PDO::PARAM_INT);
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					   $vd = self::constructWithArray($row);
					  array_push($videos, $vd);
	             }
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getVideoByTechnique($tech1,$id_video)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM videos WHERE (Technique_id=:tech1 OR Technique2_id=:tech1) AND ID!=:id_video ORDER BY RAND() LIMIT 3");
				 $req->bindValue('tech1',$tech1, PDO::PARAM_INT);
				 $req->bindValue('id_video',$id_video, PDO::PARAM_INT);
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					   $vd = self::constructWithArray($row);
					  array_push($videos, $vd);
	             }
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getNumberVideos()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM videos");
	             $req->execute();
	             $videos= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$videos,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getTechniqueVideosByCategorie($techId,$cat,$page=0)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM videos WHERE Categorie=:cat AND ( Technique_id=:techId OR Technique2_id=:techId )  LIMIT :offset,20");
				 $req->bindValue('techId',$techId, PDO::PARAM_INT);
				 $req->bindValue('offset',$page*20, PDO::PARAM_INT);
				 $req->bindValue('cat',$cat, PDO::PARAM_STR);
	             $req->execute();
	             $videos= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $video = self::constructWithArray($row);
					  array_push($videos, $video);
	             }
	             $nb_videos=$bdd->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
	             return array('validation'=>true,'donnees'=>$videos,'nb_videos'=>$nb_videos,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		
	}


}
?>