<?php

class champion_admin_externe_journal 
{
	private $id;
	private $type;
	private $date;
	private $user_id;
	private $champion_id;
	private $club_id;

		public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getChampion_id(){
		return $this->champion_id;
	}

	public function setChampion_id($champion_id){
		$this->champion_id = $champion_id;
	}

	public function getClub_id(){
		return $this->club_id;
	}

	public function setClub_id($club_id){
		$this->club_id = $club_id;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new champion_admin_externe_journal();
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
		public function update_fiche_athlète($champion_id,$user_id,$type,$ev_id)
		{
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id,evenement_id) VALUES (:type, :user_id , :champion_id, :evenement_id)");
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
				 $req->bindValue('evenement_id',$ev_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->bindValue('type',$type, PDO::PARAM_INT);
	             $req->execute();
				 return array('validation'=>true,'message'=>'');
				 $bdd=null;
	        }
	        catch(Exception $e)
	        {
				die('Erreur : ' . $e->getMessage());
	        }
		}

		public function getJournalByUser($id){
			try {
					 require('../database/connexion.php');
					 $req = $bdd->prepare('SELECT * FROM champion_admin_externe_journal WHERE user_id=:id ORDER BY ID DESC LIMIT 10');
	             	 $req->bindValue('id',$id, PDO::PARAM_INT);
					 $req->execute();
		             $res = array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						$ch = self::constructWithArray($row);
						array_push($res, $ch);
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