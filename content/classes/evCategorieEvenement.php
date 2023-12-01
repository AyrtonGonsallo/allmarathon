<?php

class evCategorieEvenement{
	
	private $id;
	private $intitule;
	private $intituleEN;
	private $tri;
	private $club;
	private $inter;
	private $classement;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getIntitule(){
		return $this->intitule;
	}

	public function setIntitule($intitule){
		$this->intitule = $intitule;
	}

	public function getIntituleEN(){
		return $this->intituleEN;
	}

	public function setIntituleEN($intituleEN){
		$this->intituleEN = $intituleEN;
	}

	public function getTri(){
		return $this->tri;
	}

	public function setTri($tri){
		$this->tri = $tri;
	}

	public function getClub(){
		return $this->club;
	}

	public function setClub($club){
		$this->club = $club;
	}

	public function getInter(){
		return $this->inter;
	}

	public function setInter($inter){
		$this->inter = $inter;
	}

	public function getClassement(){
		return $this->classement;
	}

	public function setClassement($classement){
		$this->classement = $classement;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new evCategorieEvenement();
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

		public function getAll()
		{
			try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM evcategorieevenement ORDER BY Intitule");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $event = self::constructWithArray($row);
					  array_push($liste, $event);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		}
		public function getEventCatEventByID($id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM evcategorieevenement  WHERE id=:id");
	             $req->execute(array('id'=>$id));
	             $event= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$event,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
}
?>