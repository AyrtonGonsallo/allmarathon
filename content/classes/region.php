<?php
/**
* 
*/
class region 
{
	private $id;
	private $NomRegion;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNomRegion(){
		return $this->NomRegion;
	}

	public function setNomRegion($NomRegion){
		$this->NomRegion = $NomRegion;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new region();
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
	public function getAllRegions()
	{
		
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM regions ORDER BY NomRegion ASC");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $age = self::constructWithArray($row);
					  array_push($liste, $age);
	             }
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
}
