<?php
/**
* 
*/
class pari_resultat
{
	private $id;
	private $points;
	private $pari_id;
	private $user_id;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPoints(){
		return $this->points;
	}

	public function setPoints($points){
		$this->points = $points;
	}

	public function getPari_id(){
		return $this->pari_id;
	}

	public function setPari_id($pari_id){
		$this->pari_id = $pari_id;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new pari_resultat();
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

	public function getTotalPoints($user_id){

	try {
				require('../database/connexion.php');
				$req = $bdd->prepare("SELECT SUM(points) AS total FROM pari_resultat WHERE user_id =:user_id GROUP BY user_id ");
		        $req->bindValue('user_id', $user_id, PDO::PARAM_INT);
	            $req->execute();
	            $total= ($req->rowCount() > 0)?  $req->fetch(PDO::FETCH_ASSOC): 0 ;
	           
		        return array('validation'=>true,'donnees'=>$total,'message'=>'');
		        $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}
	
}