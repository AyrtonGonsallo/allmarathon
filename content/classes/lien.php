<?php
class lien{
	private $ID;
	private $Site;
	private $Presentation;
	private $ENG_presentation;
	private $PaysID;
	private $Departement;
	private $Langue_2;
	private $Langue_3;
	private $Langue_4;
	private $Etoile;
	private $Asavoir;
	private $Valide;
	private $Email;

		public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}

	public function getSite(){
		return $this->Site;
	}

	public function setSite($Site){
		$this->Site = $Site;
	}

	public function getPresentation(){
		return $this->Presentation;
	}

	public function setPresentation($Presentation){
		$this->Presentation = $Presentation;
	}

	public function getENG_presentation(){
		return $this->ENG_presentation;
	}

	public function setENG_presentation($ENG_presentation){
		$this->ENG_presentation = $ENG_presentation;
	}

	public function getPaysID(){
		return $this->PaysID;
	}

	public function setPaysID($PaysID){
		$this->PaysID = $PaysID;
	}

	public function getDepartement(){
		return $this->Departement;
	}

	public function setDepartement($Departement){
		$this->Departement = $Departement;
	}

	public function getLangue_2(){
		return $this->Langue_2;
	}

	public function setLangue_2($Langue_2){
		$this->Langue_2 = $Langue_2;
	}

	public function getLangue_3(){
		return $this->Langue_3;
	}

	public function setLangue_3($Langue_3){
		$this->Langue_3 = $Langue_3;
	}

	public function getLangue_4(){
		return $this->Langue_4;
	}

	public function setLangue_4($Langue_4){
		$this->Langue_4 = $Langue_4;
	}

	public function getEtoile(){
		return $this->Etoile;
	}

	public function setEtoile($Etoile){
		$this->Etoile = $Etoile;
	}

	public function getAsavoir(){
		return $this->Asavoir;
	}

	public function setAsavoir($Asavoir){
		$this->Asavoir = $Asavoir;
	}

	public function getValide(){
		return $this->Valide;
	}

	public function setValide($Valide){
		$this->Valide = $Valide;
	}

	public function getEmail(){
		return $this->Email;
	}

	public function setEmail($Email){
		$this->Email = $Email;
	}

public static function constructWithArray( array $donnees ) {
	        $instance = new lien();
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

		public function getNumberLiens()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM liens");
	             $req->execute();
	             $liens= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$liens,'message'=>'');
	             $bdd=null;

	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

}
?>