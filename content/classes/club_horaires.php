<?php

class club_horaires 
{
	private $id;
	private $id_club;
	private $h_deb;
	private $h_fin;
	private $desc;
	private $num_cours;
	private $jour;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId_club(){
		return $this->id_club;
	}

	public function setId_club($id_club){
		$this->id_club = $id_club;
	}

	public function getH_deb(){
		return $this->h_deb;
	}

	public function setH_deb($h_deb){
		$this->h_deb = $h_deb;
	}

	public function getH_fin(){
		return $this->h_fin;
	}

	public function setH_fin($h_fin){
		$this->h_fin = $h_fin;
	}

	public function getDesc(){
		return $this->desc;
	}

	public function setDesc($desc){
		$this->desc = $desc;
	}

	public function getNum_cours(){
		return $this->num_cours;
	}

	public function setNum_cours($num_cours){
		$this->num_cours = $num_cours;
	}

	public function getJour(){
		return $this->jour;
	}

	public function setJour($jour){
		$this->jour = $jour;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new club_horaires();
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
						 $req = $bdd->prepare("SELECT * FROM club_horaires WHERE id_club =:clubID");
	             		 $req->bindValue('clubID',$clubID, PDO::PARAM_INT);
			             $req->execute();
			             $liste= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $cl_h = self::constructWithArray($row);
							  array_push($liste, $cl_h);
			             }
			             return array('validation'=>true,'donnees'=>$liste,'message'=>'');
			             $bdd=null;
			}
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		}

		public function deleteHoraireClub($clubID)
		{
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("DELETE FROM club_horaires WHERE id=:clubID");
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

		public function addHoraireClub($clubID,$h_jour,$h_hdeb,$h_hfin,$h_desc,$h_num)
		{
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("INSERT INTO club_horaires VALUES ('',:clubID,:h_jour,:h_hdeb,:h_hfin,:h_desc,:h_num)");
	             		 $req->bindValue('clubID',$clubID, PDO::PARAM_INT);
	             		 $req->bindValue('h_jour',$h_jour, PDO::PARAM_STR);
	             		 $req->bindValue('h_hdeb',$h_hdeb, PDO::PARAM_STR);
	             		 $req->bindValue('h_hfin',$h_hfin, PDO::PARAM_STR);
	             		 $req->bindValue('h_desc',$h_desc, PDO::PARAM_STR);
	             		 $req->bindValue('h_num',$h_num, PDO::PARAM_INT);
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