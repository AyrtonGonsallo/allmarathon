<?php
class pub{

	private $id;
	private $nom;
	private $code;
	private $actif;
	private $restriction;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getCode(){
		return $this->code;
	}

	public function setCode($code){
		$this->code = $code;
	}

	public function getActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public function getRestriction(){
		return $this->restriction;
	}

	public function setRestriction($restriction){
		$this->restriction = $restriction;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new pub();
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
	public function getBanniere728_90($page)
			{
				$page="%".$page."%";
				try {
					//include_once('/content/database/connexion.php');
					 include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT * FROM banniere728x90 WHERE actif='actif' AND restriction LIKE :page ORDER BY RAND() LIMIT 1");
		             $req->bindValue('page', $page, PDO::PARAM_STR);
	             	$req->execute();
		             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
		             $bdd=null;
		             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
			}
		public function getBanniere300_60($page)
			{
				$page="%".$page."%";
				try {
					  include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT * FROM banniere300x60 WHERE actif='actif' AND restriction LIKE :page ORDER BY RAND() LIMIT 1");
		             $req->bindValue('page', $page, PDO::PARAM_STR);
	             	$req->execute();
		             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
		             $bdd=null;
		             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
			}

			public function getBanniere300_250($page)
		{
			$page="%".$page."%";
			try {
				  include("../database/connexion.php");				 $req = $bdd->prepare("SELECT * FROM banniere300x250 WHERE actif='actif' AND restriction LIKE :page ORDER BY RAND() LIMIT 1");
	             $req->bindValue('page', $page, PDO::PARAM_STR);
	             	$req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}

		public function getBanniere160_600($page)
		{
			$page="%".$page."%";
			try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM banniere160x600 WHERE actif='actif' AND restriction LIKE :page ORDER BY RAND() LIMIT 1");
	             $req->bindValue('page', $page, PDO::PARAM_STR);
	             $req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}
		public function getBanniere768_90($page)
		{
			$page="%".$page."%";
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM banniere768x90 WHERE actif='actif' AND restriction LIKE  :page ORDER BY RAND() LIMIT 1");
	             $req->bindValue('page', $page, PDO::PARAM_STR);
	             $req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}
		public function getBanniere336x280($page)
		{
			$page="%".$page."%";
			try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM banniere336x280 WHERE actif='actif' AND restriction LIKE  :page ORDER BY RAND() LIMIT 1");
	             $req->bindValue('page', $page, PDO::PARAM_STR);
	             $req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}

		public function getbannieremobile($page)
		{
			$page="%".$page."%";
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM bannieremobile WHERE actif='actif' AND restriction LIKE  :page ORDER BY RAND() LIMIT 1");
	             $req->bindValue('page', $page, PDO::PARAM_STR);
	             $req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}
		
        public function getMobileAds ($page)
		{
			$page="%".$page."%";
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM bannieremobile WHERE actif='actif' ORDER BY RAND() LIMIT 1");
	             
	             $req->execute();
	             ($req->rowCount()==0) ? $pub= "" : $pub=$req->fetch(PDO::FETCH_ASSOC) ;
	             return array('validation'=>true,'donnees'=>$pub,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}
	
}
?>