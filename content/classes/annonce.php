<?php

class annonce{
	
	private $id;
	private $sous_categorie_id;
	private $user_ID;
	private $titre;
	private $descriptif;
	private $date_publication;
	private $date_modification;
	private $nom;
	private $mail;
	private $code_postal;
	private $ville;
	private $pays;
	private $telephone;
	private $prenium;
	private $valide;
	
public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getSous_categorie_id(){
		return $this->sous_categorie_id;
	}

	public function setSous_categorie_id($sous_categorie_id){
		$this->sous_categorie_id = $sous_categorie_id;
	}

	public function getUser_ID(){
		return $this->user_ID;
	}

	public function setUser_ID($user_ID){
		$this->user_ID = $user_ID;
	}

	public function getTitre(){
		return $this->titre;
	}

	public function setTitre($titre){
		$this->titre = $titre;
	}

	public function getDescriptif(){
		return $this->descriptif;
	}

	public function setDescriptif($descriptif){
		$this->descriptif = $descriptif;
	}

	public function getDate_publication(){
		return $this->date_publication;
	}

	public function setDate_publication($date_publication){
		$this->date_publication = $date_publication;
	}

	public function getDate_modification(){
		return $this->date_modification;
	}

	public function setDate_modification($date_modification){
		$this->date_modification = $date_modification;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getMail(){
		return $this->mail;
	}

	public function setMail($mail){
		$this->mail = $mail;
	}

	public function getCode_postal(){
		return $this->code_postal;
	}

	public function setCode_postal($code_postal){
		$this->code_postal = $code_postal;
	}

	public function getVille(){
		return $this->ville;
	}

	public function setVille($ville){
		$this->ville = $ville;
	}

	public function getPays(){
		return $this->pays;
	}

	public function setPays($pays){
		$this->pays = $pays;
	}

	public function getTelephone(){
		return $this->telephone;
	}

	public function setTelephone($telephone){
		$this->telephone = $telephone;
	}

	public function getPrenium(){
		return $this->prenium;
	}

	public function setPrenium($prenium){
		$this->prenium = $prenium;
	}

	public function getValide(){
		return $this->valide;
	}

	public function setValide($valide){
		$this->valide = $valide;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new annonce();
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

	public function get_last_annonces(){

		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM annonce WHERE Valide=1 ORDER BY Date_publication DESC LIMIT 0,5");
	             $req->execute();
	             $last_annonces= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $article = self::constructWithArray($row);
					  array_push($last_annonces, $article);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$last_annonces,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}




}


?>