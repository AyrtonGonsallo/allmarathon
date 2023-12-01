<?php
class technique{
	
	private $id;
	private $nom;
	private $presentation;
	private $presentation_en;
	private $conseil;
	private $conseil_en;
	private $Famille;

	public function getFamille(){
		return $this->famille;
	}

	public function setFamille($famille){
		$this->famille = $famille;
	}

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

	public function getPresentation(){
		return $this->presentation;
	}

	public function setPresentation($presentation){
		$this->presentation = $presentation;
	}

	public function getPresentation_en(){
		return $this->presentation_en;
	}

	public function setPresentation_en($presentation_en){
		$this->presentation_en = $presentation_en;
	}

	public function getConseil(){
		return $this->conseil;
	}

	public function setConseil($conseil){
		$this->conseil = $conseil;
	}

	public function getConseil_en(){
		return $this->conseil_en;
	}

	public function setConseil_en($conseil_en){
		$this->conseil_en = $conseil_en;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new technique();
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

	public function getTechniqueById($id){
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM technique WHERE ID=:id ");
				 $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->execute();
	             $technique= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$technique,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getAll()
	{ 
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT ID,Nom,Famille FROM technique ORDER BY Nom ");
	             $req->execute();
	             $techniques= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $tech = self::constructWithArray($row);
					  array_push($techniques, $tech);
	             }
	             return array('validation'=>true,'donnees'=>$techniques,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	
}
?>