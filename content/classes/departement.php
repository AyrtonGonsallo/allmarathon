<?php

class departement
{
	private $id;
	private $cp;
	private $nomDepartement;
	private $paysID;

		public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCp(){
		return $this->cp;
	}

	public function setCp($cp){
		$this->cp = $cp;
	}

	public function getNomDepartement(){
		return $this->nomDepartement;
	}

	public function setNomDepartement($nomDepartement){
		$this->nomDepartement = $nomDepartement;
	}

	public function getPaysID(){
		return $this->paysID;
	}

	public function setPaysID($paysID){
		$this->paysID = $paysID;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new departement();
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
	public function getAllDepartements()
	{
		
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM departements ORDER BY CP ASC");
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $age = self::constructWithArray($row);
					  array_push($liste, $age);
	             }
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	                $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getClubDepartements($pays)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT DISTINCT D.CP, NomDepartement FROM departements D INNER JOIN clubs C ON C.departement=D.CP WHERE  PaysID=:pays ORDER BY NomDepartement");
				 $req->bindValue('pays',$pays, PDO::PARAM_STR);
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {
					  array_push($liste, $row);
	             }
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	                $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	
}