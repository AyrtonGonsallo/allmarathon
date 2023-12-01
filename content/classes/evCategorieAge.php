<?php
class evCategorieAge{
	
	private $id;
	private $intitule;
	private $intituleEN;

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

	public static function constructWithArray( array $donnees ) {
	        $instance = new evCategorieAge();
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
				 $req = $bdd->prepare("SELECT * FROM evcategorieage ORDER BY Intitule");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $age = self::constructWithArray($row);
					  array_push($liste, $age);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getEventCatAgeByID($id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM evcategorieage  WHERE id=:id");
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

	public function getCatAgeFiche()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM evcategorieage WHERE ID != '5' AND ID != '8'");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $age = self::constructWithArray($row);
					  array_push($liste, $age);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getCatParentes()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM evcategorieage WHERE not (ID >=8 and ID <=18) ORDER BY ID");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $age = self::constructWithArray($row);
					  array_push($liste, $age);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


}
?>