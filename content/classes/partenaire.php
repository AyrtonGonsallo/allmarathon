<?php
/**
* 
*/
class partenaire
{
	
	private $id;
	private $nom;
	private $nom1;
	private $url;
	private $ecran;

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

	public function getNom1(){
		return $this->nom1;
	}

	public function setNom1($nom1){
		$this->nom1 = $nom1;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}

	public function getEcran(){
		return $this->ecran;
	}

	public function setEcran($ecran){
		$this->ecran = $ecran;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new partenaire();
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

	public function getRandomPartenaire(){

		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM partenaires ORDER BY RAND() DESC LIMIT 1");
	             $req->execute();
	             $partenaire= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             return array('validation'=>true,'donnees'=>$partenaire,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getAllPartenairesOrderByName()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM partenaires ORDER BY Nom");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $partenaire = self::constructWithArray($row);
					  array_push($liste, $partenaire);
	             }
	             return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		
	}
}