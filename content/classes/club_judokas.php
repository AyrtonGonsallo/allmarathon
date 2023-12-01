<?php
/**
* 
*/
class club_athlètes {
	
	private $id_club_marathon;
	private $id_club;
	private $id_marathonda;
	private $nom_athlète;

	public function getId_club_marathon(){
		return $this->id_club_marathon;
	}

	public function setId_club_marathon($id_club_marathon){
		$this->id_club_marathon = $id_club_marathon;
	}

	public function getId_club(){
		return $this->id_club;
	}

	public function setId_club($id_club){
		$this->id_club = $id_club;
	}

	public function getId_marathonda(){
		return $this->id_marathonda;
	}

	public function setId_marathonda($id_marathonda){
		$this->id_marathonda = $id_marathonda;
	}

	public function getNom_athlète(){
		return $this->nom_athlète;
	}

	public function setNom_athlète($nom_athlète){
		$this->nom_athlète = $nom_athlète;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new club_athlètes();
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

		public function getAllByClubID($clubID)
		{
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("SELECT * FROM club_athlètes WHERE id_club =:clubID");
	             		 $req->bindValue('clubID',$clubID, PDO::PARAM_INT);
			             $req->execute();
			             $liste= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $cl_j = self::constructWithArray($row);
							  array_push($liste, $cl_j);
			             }
			             return array('validation'=>true,'donnees'=>$liste,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		}

		public function deleteJuokasClub($clubID)
		{
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("DELETE FROM club_athlètes WHERE id_club_marathon=:clubID");
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

		public function addJuokasClub($clubID,$j_idchamp,$j_name)
		{
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("INSERT INTO club_athlètes VALUES('',:clubID,:j_idchamp,:j_name)");
	             		 $req->bindValue('clubID',$clubID, PDO::PARAM_INT);
	             		 $req->bindValue('j_idchamp',$j_idchamp, PDO::PARAM_INT);
	             		 $req->bindValue('j_name',$j_name, PDO::PARAM_STR);
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