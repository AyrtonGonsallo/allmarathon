<?php
class abonnement{
	
	private $id;
	private $user;
	private $champion;
	private $date_creation;

		public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
	}

	public function getChampion(){
		return $this->champion;
	}

	public function setChampion($champion){
		$this->champion = $champion;
	}

	public function getDate_creation(){
		return $this->date_creation;
	}

	public function setDate_creation($date_creation){
		$this->date_creation = $date_creation;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new abonnement();
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

	public function isUserAbonne($champ_id,$user_id)
	{
		try {
				 include("../database/connexion.php");
				 $req = $bdd->prepare('select * from abonnement where user=:user_id and champion=:champ_id');
	             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $champ=$req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$champ,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}

	public function abonnement_champ($user_id,$champion_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('INSERT INTO  abonnement (user,champion,date_creation) values (:user_id,:champ_id,now())');
	             $req->bindValue('champ_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             return array('validation'=>true,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
		
	}
	public function desabonnement_champ($user_id,$champion_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('delete from abonnement where user=:user_id and champion=:champ_id');
	             $req->bindValue('champ_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             return array('validation'=>true,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
		
	}
	

}
?>