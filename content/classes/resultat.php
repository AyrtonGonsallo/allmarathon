<?php

class resultat{
	
	private $categorieAgeID;
	private $categorieID;
	private $nom;
	private $dateDebut;
	private $paysID;
	private $ID;	
	private $type;
	private $marathon_id;
	private $sexe;
	private $categorie;
	private $age;

	public static function constructWithArray( array $donnees ) {
	        $instance = new resultat();
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


	public function getCategorieAgeID(){
		return $this->categorieAgeID;
	}

	public function setCategorieAgeID($categorieAgeID){
		$this->categorieAgeID = $categorieAgeID;
	}

	public function getCategorieID(){
		return $this->categorieID;
	}

	public function setCategorieID($categorieID){
		$this->categorieID = $categorieID;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getDateDebut(){
		return $this->dateDebut;
	}

	public function setDateDebut($dateDebut){
		$this->dateDebut = $dateDebut;
	}

	public function getPaysID(){
		return $this->paysID;
	}

	public function setPaysID($paysID){
		$this->paysID = $paysID;
	}

	public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getSexe(){
		return $this->sexe;
	}

	public function setSexe($sexe){
		$this->sexe = $sexe;
	}

	public function getCategorie(){
		return $this->categorie;
	}

	public function setCategorie($categorie){
		$this->categorie = $categorie;
	}
	public function getmarathon_id(){
		return $this->marathon_id;
	}

	public function setmarathon_id($marathon_id){
		$this->marathon_id = $marathon_id;
	}
	public function getAge(){
		return $this->age;
	}

	public function setAge($age){
		$this->age = $age;
	}

	public function getLastResults(){
		 	
		 	try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe,e.marathon_id,c.Intitule AS Categorie,a.Intitule AS Age FROM evenements e LEFT JOIN evcategorieevenement c ON e.CategorieID = c.ID LEFT JOIN evcategorieage a ON e.CategorieageID = a.ID WHERE e.Visible=1 and not marathon_id=0 ORDER BY e.DateDebut DESC LIMIT 10");
	            
				 $req->execute();
	             $last_results = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  $resultat = self::constructWithArray($row);
					  array_push($last_results, $resultat);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$last_results,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }

	    }

		public function getAllYearsResults($mar_id,$ev_id){
		 	
			try {
				 include("../database/connexion.php");
				$req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe,e.marathon_id,c.Intitule AS Categorie,a.Intitule AS Age FROM evenements e LEFT JOIN evcategorieevenement c ON e.CategorieID = c.ID LEFT JOIN evcategorieage a ON e.CategorieageID = a.ID WHERE e.Visible=1 and marathon_id=:mar_id and not e.id=:ev_id ORDER BY e.DateDebut DESC LIMIT 10");
				$req->bindValue('ev_id', $ev_id, PDO::PARAM_INT);
				$req->bindValue('mar_id', $mar_id, PDO::PARAM_INT);
				$req->execute();
				$last_results = array();
				while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					 $resultat = self::constructWithArray($row);
					 array_push($last_results, $resultat);
				}
				$bdd=null;
				   return array('validation'=>true,'donnees'=>$last_results,'message'=>'');
		   }
		  
		   catch(Exception $e)
		   {
			   die('Erreur : ' . $e->getMessage());
		   }

	   }

	    public function getPhotos($id){
	    	try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT  * FROM images I INNER JOIN galeries G ON I.Galerie_id=G.ID WHERE G.Evenement_id=:id");
	             $req->bindValue('id', $id, PDO::PARAM_INT);
	             $req->execute();
	             $result_photos = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
					  array_push($result_photos, $row);
	             }
	             $nb_photos = $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$result_photos,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	    	
	    }


}

?>