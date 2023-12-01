<?php
class pari
{
	
	private $id;
	private $date_debut;
	private $date_fin;
	private $description;
	private $actif;
	private $corrige;
	private $evenement_id;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDate_debut(){
		return $this->date_debut;
	}

	public function setDate_debut($date_debut){
		$this->date_debut = $date_debut;
	}

	public function getDate_fin(){
		return $this->date_fin;
	}

	public function setDate_fin($date_fin){
		$this->date_fin = $date_fin;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public function getCorrige(){
		return $this->corrige;
	}

	public function setCorrige($corrige){
		$this->corrige = $corrige;
	}

	public function getEvenement_id(){
		return $this->evenement_id;
	}

	public function setEvenement_id($evenement_id){
		$this->evenement_id = $evenement_id;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new pari();
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

	public function getActifsPari(){

	try {
				require('../database/connexion.php');
				$req = $bdd->prepare("SELECT * FROM pari WHERE actif=1");
	            $req->execute();
		        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
		        	$p = self::constructWithArray($row);
	             	return array('validation'=>true,'donnees'=>$p,'message'=>'');
		        }
		        $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}

public function getQueryPari($user_id){

	try {
				require('../database/connexion.php');
				$req = $bdd->prepare("SELECT DISTINCT c.Intitule, e.Nom, e.DateDebut, p.date_fin, p.id FROM pari p INNER JOIN evenements e ON p.evenement_id = e.ID INNER JOIN evcategorieevenement c ON e.CategorieID = c.ID INNER JOIN pari_composition pc ON pc.pari_id = p.id INNER JOIN pari_user pu ON pc.id = pu.pari_comp_id WHERE p.date_fin < NOW() AND pu.user_id =:user_id");
				$req->bindValue('user_id', $user_id, PDO::PARAM_INT);
	            $req->execute();
	            $res=array();
		        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {
					array_push($res, $row);
		        }
	             return array('validation'=>true,'donnees'=>$res,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}

	public function getPariEnCours($pari_id){

		try {
					require('../database/connexion.php');
					$req = $bdd->prepare("SELECT c.Intitule, e.ID, e.Nom, e.DateDebut, p.date_fin FROM pari p INNER JOIN evenements e ON p.evenement_id = e.ID INNER JOIN evcategorieevenement c ON e.CategorieID = c.ID WHERE p.id =:pari_id");
				 	$req->bindValue('pari_id', $pari_id, PDO::PARAM_INT);
		            $req->execute();
			        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
			        	$p = $row;
			        }
		             return array('validation'=>true,'donnees'=>$p,'message'=>'');
		             $bdd=null;
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}

	public function getParticipantsBySexe($pari_id,$user_id,$sexe)
	{
		try {
					require('../database/connexion.php');
					$req = $bdd->prepare("SELECT c.*,pu.* FROM pari_composition c INNER JOIN pari p ON c.pari_id=p.id INNER JOIN pari_user pu ON pu.pari_comp_id=c.id WHERE c.sexe =:sexe AND p.id = :pari_id AND pu.user_id = :user_id ORDER BY CAST(c.poid AS unsigned) DESC");
				 	$req->bindValue('pari_id', $pari_id, PDO::PARAM_INT);
				 	$req->bindValue('user_id', $user_id, PDO::PARAM_INT);
				 	$req->bindValue('sexe', $sexe, PDO::PARAM_STR);
		            $req->execute();
		            $res=array();
			        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						array_push($res, $row);
			        }
		             return array('validation'=>true,'donnees'=>$res,'message'=>'');
		             $bdd=null;
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		
	}






}