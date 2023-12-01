<?php

class club{
	
	private $ID;
	private $pays;
	private $departement;
	private $club;
	private $responsable;
	private $telephone;
	private $mel;
	private $site;
	private $description;
	private $ville;
	private $CP;
	private $adresse;
	private $gcoo1;
	private $gcoo2;
	private $gaddress;
	private $Valide;

		public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}

	public function getPays(){
		return $this->pays;
	}

	public function setPays($pays){
		$this->pays = $pays;
	}

	public function getDepartement(){
		return $this->departement;
	}

	public function setDepartement($departement){
		$this->departement = $departement;
	}

	public function getClub(){
		return $this->club;
	}

	public function setClub($club){
		$this->club = $club;
	}

	public function getResponsable(){
		return $this->responsable;
	}

	public function setResponsable($responsable){
		$this->responsable = $responsable;
	}

	public function getTelephone(){
		return $this->telephone;
	}

	public function setTelephone($telephone){
		$this->telephone = $telephone;
	}

	public function getMel(){
		return $this->mel;
	}

	public function setMel($mel){
		$this->mel = $mel;
	}

	public function getSite(){
		return $this->site;
	}

	public function setSite($site){
		$this->site = $site;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getVille(){
		return $this->ville;
	}

	public function setVille($ville){
		$this->ville = $ville;
	}

	public function getCP(){
		return $this->CP;
	}

	public function setCP($CP){
		$this->CP = $CP;
	}

	public function getAdresse(){
		return $this->adresse;
	}

	public function setAdresse($adresse){
		$this->adresse = $adresse;
	}

	public function getGcoo1(){
		return $this->gcoo1;
	}

	public function setGcoo1($gcoo1){
		$this->gcoo1 = $gcoo1;
	}

	public function getGcoo2(){
		return $this->gcoo2;
	}

	public function setGcoo2($gcoo2){
		$this->gcoo2 = $gcoo2;
	}

	public function getGaddress(){
		return $this->gaddress;
	}

	public function setGaddress($gaddress){
		$this->gaddress = $gaddress;
	}

	public function getValide(){
		return $this->Valide;
	}

	public function setValide($Valide){
		$this->Valide = $Valide;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new club();
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
	public function getNumberClubs()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM clubs");
	             $req->execute();
	             $clubs= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
			     $bdd=null;

	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getClubById($id){
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM clubs  WHERE ID=:id");
			             $req->execute(array('id'=>$id));
			             $res=$req->fetch(PDO::FETCH_ASSOC);
			             if($res){
			            	$club= self::constructWithArray($res);
			             	return array('validation'=>true,'donnees'=>$club,'message'=>'');
			             }
			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getValidsClubs()
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM clubs WHERE Valide = 1 ORDER BY club ASC");
			             $req->execute();
			             $clubs= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $club = self::constructWithArray($row);
							  array_push($clubs, $club);
			             }
			             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getValidsClubsViaSearch($search)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM clubs WHERE Valide = 1 AND club LIKE :search ORDER BY club ASC");
	             		 $req->bindValue('search',"%%".$search."%%", PDO::PARAM_STR);
			             $req->execute();
			             $clubs= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $club = self::constructWithArray($row);
							  array_push($clubs, $club);
			             }
			             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getValidsClubsViaPays($pays)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM clubs WHERE Valide = 1 AND pays LIKE :pays ORDER BY club ASC");
	             		 $req->bindValue('pays',$pays, PDO::PARAM_STR);
			             $req->execute();
			             $clubs= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $club = self::constructWithArray($row);
							  array_push($clubs, $club);
			             }
			             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getValidsClubsViaDepartement($pays,$departement)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM clubs WHERE Valide = 1 AND pays=:pays AND departement=:departement ORDER BY club ASC");
	             		 $req->bindValue('pays',$pays, PDO::PARAM_STR);
	             		 $req->bindValue('departement',$departement, PDO::PARAM_INT);
			             $req->execute();
			             $clubs= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $club = self::constructWithArray($row);
							  array_push($clubs, $club);
			             }
			             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function editClub($clubID,$pays,$responsable,$telephone,$mel,$site,$description,$ville,$CP,$departement,$adresse,$gcoo1,$gcoo2,$gaddress)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("UPDATE clubs SET pays=:pays, responsable=:responsable, telephone=:telephone, mel=:mel, site=:site, description=:description, ville=:ville, CP=:CP, departement=:departement, adresse=:adresse, gcoo1=:gcoo1, gcoo2=:gcoo2, gaddress=:gaddress  WHERE ID=:clubID");
	             		 $req->bindValue('pays',$pays, PDO::PARAM_STR);
	             		 $req->bindValue('responsable',$responsable, PDO::PARAM_STR);
	             		 $req->bindValue('telephone',$telephone, PDO::PARAM_STR);
	             		 $req->bindValue('mel',$mel, PDO::PARAM_STR);
	             		 $req->bindValue('site',$site, PDO::PARAM_STR);
	             		 $req->bindValue('description',$description, PDO::PARAM_STR);
	             		 $req->bindValue('ville',$ville, PDO::PARAM_STR);
	             		 $req->bindValue('CP',$CP, PDO::PARAM_STR);
	             		 $req->bindValue('departement',$departement, PDO::PARAM_STR);
	             		 $req->bindValue('adresse',$adresse, PDO::PARAM_STR);
	             		 $req->bindValue('gcoo1',$gcoo1, PDO::PARAM_INT);
	             		 $req->bindValue('gcoo2',$gcoo2, PDO::PARAM_INT);
	             		 $req->bindValue('gaddress',$gaddress, PDO::PARAM_INT);
	             		 $req->bindValue('clubID',$clubID, PDO::PARAM_INT);
			             $req->execute();
			             return array('validation'=>true,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}


}
?>