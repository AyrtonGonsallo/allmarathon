<?php

class news {

	 	private $id;
		private $date;
		private $source;
		private $auteur;
		private $titre;
		private $titre_en;
		private $chapo;
		private $texte;
		private $photo;
		private $Type;
		private $nom;
		private $url;
		private $legende;
		private $lien1;
		private $textlien1;
		private $lien2;
		private $textlien2;
		private $lien3;
		private $textlien3;
		private $aLaUne;
		private $aLaDeux;
		private $evenementID;
		private $championID;
		private $videoID;
		private $categorieID;
		private $admin;

		public static function constructWithArray( array $donnees ) {
	        $instance = new news();
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

	    public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getDate(){
			return $this->date;
		}

		public function setDate($date){
			$this->date = $date;
		}

		public function getSource(){
			return $this->source;
		}

		public function setSource($source){
			$this->source = $source;
		}

		public function getAuteur(){
			return $this->auteur;
		}

		public function setAuteur($auteur){
			$this->auteur = $auteur;
		}

		public function getTitre(){
			return $this->titre;
		}

		public function setTitre($titre){
			$this->titre = $titre;
		}

		public function getTitre_en(){
			return $this->titre_en;
		}

		public function setTitre_en($titre_en){
			$this->titre_en = $titre_en;
		}

		public function getChapo(){
			return $this->chapo;
		}

		public function setChapo($chapo){
			$this->chapo = $chapo;
		}

		public function getTexte(){
			return $this->texte;
		}

		public function setTexte($texte){
			$this->texte = $texte;
		}

		public function getPhoto(){
			return $this->photo;
		}

		public function setPhoto($photo){
			$this->photo = $photo;
		}

		public function getType(){
			return $this->Type;
		}

		public function setType($Type){
			$this->Type = $Type;
		}

		public function getNom(){
			return $this->nom;
		}

		public function setNom($nom){
			$this->nom = $nom;
		}

		public function getUrl(){
			return $this->url;
		}

		public function setUrl($url){
			$this->url = $url;
		}

		public function getLegende(){
			return $this->legende;
		}

		public function setLegende($legende){
			$this->legende = $legende;
		}

		public function getLien1(){
			return $this->lien1;
		}

		public function setLien1($lien1){
			$this->lien1 = $lien1;
		}

		public function getTextlien1(){
			return $this->textlien1;
		}

		public function setTextlien1($textlien1){
			$this->textlien1 = $textlien1;
		}

		public function getLien2(){
			return $this->lien2;
		}

		public function setLien2($lien2){
			$this->lien2 = $lien2;
		}

		public function getTextlien2(){
			return $this->textlien2;
		}

		public function setTextlien2($textlien2){
			$this->textlien2 = $textlien2;
		}

		public function getLien3(){
			return $this->lien3;
		}

		public function setLien3($lien3){
			$this->lien3 = $lien3;
		}

		public function getTextlien3(){
			return $this->textlien3;
		}

		public function setTextlien3($textlien3){
			$this->textlien3 = $textlien3;
		}

		public function getALaUne(){
			return $this->aLaUne;
		}

		public function setALaUne($aLaUne){
			$this->aLaUne = $aLaUne;
		}

		public function getALaDeux(){
			return $this->aLaDeux;
		}

		public function setALaDeux($aLaDeux){
			$this->aLaDeux = $aLaDeux;
		}

		public function getEvenementID(){
			return $this->evenementID;
		}

		public function setEvenementID($evenementID){
			$this->evenementID = $evenementID;
		}

		public function getChampionID(){
			return $this->championID;
		}

		public function setChampionID($championID){
			$this->championID = $championID;
		}
		public function getVideoID(){
			return $this->videoID;
		}

		public function setVideoID($videoID){
			$this->videoID = $videoID;
		}
		public function getCategorieID(){
			return $this->categorieID;
		}

		public function setCategorieID($categorieID){
			$this->categorieID = $categorieID;
		}

		public function getAdmin(){
			return $this->admin;
		}

		public function setAdmin($admin){
			$this->admin = $admin;
		}


		function changeDate() {
		    $timestamp = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
		    return date("Y-m-d",$timestamp)." 00:00:00";
		}

	     public function getLastNews(){

	        try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM news WHERE date < :date_pub AND aLaUne=\'1\' ORDER BY date DESC LIMIT 0,2');
				 $req->bindValue('date_pub', $this->changeDate(), PDO::PARAM_STR);
				 // $req->bindValue('part', $part, PDO::PARAM_INT);
	             $req->execute();
	             $news = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    

	                $article = self::constructWithArray($row);

	                 array_push($news, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }

	    }

	    public function getBrefNews(){
	    	
	    	try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM news WHERE  categorieID='11' ORDER BY date DESC LIMIT 0,5");
	             $req->execute();
	             $news_bref = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($news_bref, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news_bref,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	    }



		public function getBootNews(){
	    	
	    	try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("(SELECT * FROM news WHERE aLaDeux = '1' ORDER BY date DESC limit 4) UNION ALL (SELECT * FROM news WHERE categorieID='11' and aLaDeux != '1' ORDER BY date DESC limit 4) ORDER BY  date DESC   ");
	             $req->execute();
	             $news_both = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($news_both, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news_both,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	    }

public function news_home($number){
	$lim=12;
	$offset=4;

			try {
				  include("../database/connexion.php");
				 if($number==0)
				 {
				 	$lim=2;
				 	$offset=0;
				 }
				 else if($number==1)
				 {
				 	$lim=6;
				 	$offset=2;
				 }
				 $req = $bdd->prepare("SELECT * FROM news  WHERE aLaDeux = '1' ORDER BY date DESC LIMIT :offset, :lim");
				 $req->bindValue('lim', $lim, PDO::PARAM_INT);
				 $req->bindValue('offset', $offset, PDO::PARAM_INT);
	             $req->execute();
	             $news_home = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($news_home, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news_home,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }	
}

public function getNewsByEventId($evenementID){
	
			try {
				  include("../database/connexion.php");
				
				 $req = $bdd->prepare("SELECT * FROM news  WHERE evenementID = :evenementID ORDER BY date DESC");
				 $req->bindValue('evenementID', $evenementID, PDO::PARAM_INT);
				 
	             $req->execute();
	             $news_event= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($news_event, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news_event,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }	
}


	public function getArticlesPerPage($page,$condition){
		try {
				  include("../database/connexion.php");
				 $nc=new newscategorie();
				  // $condition=$nc->getNewsCategoryByIntitule($condition)['id'];
				 if($condition!='')
				 {
				 	$req = $bdd->prepare("SELECT * FROM news WHERE categorieID=:condition  ORDER BY date DESC LIMIT :offset,20");
				 	$req->bindValue('condition', $condition, PDO::PARAM_STR);
				 }
				 else{
				 	$req = $bdd->prepare("SELECT * FROM news ORDER BY date DESC LIMIT :offset,20");
				 }
				 $req->bindValue('offset', $page*20, PDO::PARAM_INT);
	             
	             
	             $req->execute();
	             $articles= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $art = self::constructWithArray($row);
					  array_push($articles, $art);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getAllArticlesDesc(){
		try {
				  include("../database/connexion.php");
				 
				  // $condition=$nc->getNewsCategoryByIntitule($condition)['id'];
				 
				 	$req = $bdd->prepare("SELECT * FROM news ORDER BY date DESC limit 0,25");
				 
	             
	             $req->execute();
	             $articles= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $art = self::constructWithArray($row);
					  array_push($articles, $art);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getArticlesViaSearch($key_word,$page){
		if($page!=="page_resultat"){
		try {
				  include("../database/connexion.php");
				 $key_word="%".strtoupper($key_word)."%";
				 $req = $bdd->prepare("SELECT * FROM news WHERE UPPER(titre) LIKE :key_word OR UPPER(chapo) LIKE :key_word  ORDER BY date DESC LIMIT :offset,20");
				 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
				 $req->bindValue('offset', $page*20, PDO::PARAM_INT);
	             $req->execute();
	             $articles= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $art = self::constructWithArray($row);
					  array_push($articles, $art);
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
				 $req = $bdd->prepare("SELECT * FROM news WHERE UPPER(titre) LIKE :key_word OR UPPER(chapo) LIKE :key_word  ORDER BY date DESC");
				 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
	             $req->execute();
	             $articles= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $art = self::constructWithArray($row);
					  array_push($articles, $art);
	             }
	             $bdd=null;
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
				  $nc=new newscategorie();
				  // $condition=$nc->getNewsCategoryByIntitule($condition)['id'];
				 if($key_word!='')
				 {
				 	$key_word="%".strtoupper($key_word)."%";
				 	$req = $bdd->prepare("SELECT COUNT(*) FROM news WHERE UPPER(titre) LIKE :key_word OR UPPER(chapo) LIKE :key_word ");
					$req->bindValue('key_word', $key_word, PDO::PARAM_STR);
				 }
				 else{
				 	if($condition!='')
					 {
					 	$req = $bdd->prepare("SELECT COUNT(*) FROM news WHERE categorieID=:condition ");
					 	$req->bindValue('condition', $condition, PDO::PARAM_STR);
					 }
					 else{
					 	$req = $bdd->prepare("SELECT COUNT(*) FROM news");
					 }
				 }
				 
	             $req->execute();
	             $nbr_pages= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$nbr_pages,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getSuggestionByCat($catId,$current_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM news WHERE categorieID=:catId AND ID !=:current_id AND date < NOW() ORDER BY ID DESC LIMIT 5");
				 $req->bindValue('catId', $catId, PDO::PARAM_INT);
				 $req->bindValue('current_id', $current_id, PDO::PARAM_INT); 
	             $req->execute();
	             $news = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($news, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$news,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getNewsById($id){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM news WHERE ID=:id ");
				 $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->execute();
	             $news= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$news,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getNewsByChampId($cid){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM news WHERE championID=:cid order by date desc");
				 $req->bindValue('cid',$cid, PDO::PARAM_INT);
	             $req->execute();
				$news= array();
				while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
					$news_d= self::constructWithArray($row);
					array_push($news, $news_d);
				}
	             
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$news,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function gettotalNewsByChampId($cid){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT count(*) FROM news WHERE championID=:cid order by date desc");
				 $req->bindValue('cid',$cid, PDO::PARAM_INT);
	             $req->execute();
	             $nbr_pages= $req->fetch(PDO::FETCH_ASSOC);
				
	             
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$nbr_pages,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getNewsForNewsletter()
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare('SELECT * FROM news ORDER BY news.`date`DESC LIMIT 9');  //WHERE categorieID!=11
			$req->execute();
			$news= array();
		    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
				array_push($news, $row);
		    }
		return array('validation'=>true,'donnees'=>$news,'message'=>'');
		$bdd=null;
		}
		catch(Exception $e)
		{ 
		die('Erreur : ' . $e->getMessage());
		}
	}
	    
}
?>