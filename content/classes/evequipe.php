<?php
class evequipe{
	private $ID;
	private $Rang;
	private $NomEquipe;
	private $Equipe;
	private $Sexe;
	private $evenementID;

	public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}

	public function getRang(){
		return $this->Rang;
	}

	public function setRang($Rang){
		$this->Rang = $Rang;
	}

	public function getNomEquipe(){
		return $this->NomEquipe;
	}

	public function setNomEquipe($NomEquipe){
		$this->NomEquipe = $NomEquipe;
	}

	public function getEquipe(){
		return $this->Equipe;
	}

	public function setEquipe($Equipe){
		$this->Equipe = $Equipe;
	}

	public function getSexe(){
		return $this->Sexe;
	}

	public function setSexe($Sexe){
		$this->Sexe = $Sexe;
	}

	public function getEvenementID(){
		return $this->evenementID;
	}

	public function setEvenementID($evenementID){
		$this->evenementID = $evenementID;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new evequipe();
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

		public function getEquipesByEvent($sexe,$eventID){

	        try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM evequipe WHERE EvenementID=:eventID AND Sexe =:sexe ORDER BY Rang ASC');
				 $req->bindValue('sexe', $sexe, PDO::PARAM_STR);
				  $req->bindValue('eventID', $eventID, PDO::PARAM_INT);
	             $req->execute();
	             $res = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    

	                $resultat = self::constructWithArray($row);

	                 array_push($res, $resultat);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$res,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }

	    }

}
?>