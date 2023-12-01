<?php
class championPopularite{
	private $id;
	private $champion_id;
	private $user_id;
	private $date;
	private $ip;
	private $user_agent;
	private $host;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getChampion_id(){
		return $this->champion_id;
	}

	public function setChampion_id($champion_id){
		$this->champion_id = $champion_id;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getIp(){
		return $this->ip;
	}

	public function setIp($ip){
		$this->ip = $ip;
	}

	public function getUser_agent(){
		return $this->user_agent;
	}

	public function setUser_agent($user_agent){
		$this->user_agent = $user_agent;
	}

	public function getHost(){
		return $this->host;
	}

	public function setHost($host){
		$this->host = $host;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new championPopularite();
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
	
	public function isUserFan($champ_id,$user_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT id FROM champion_popularite WHERE champion_id=:champ_id AND user_id=:user_id');
	             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $champ=$req->fetch(PDO::FETCH_ASSOC);$bdd=null;
	             return array('validation'=>true,'donnees'=>$champ,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
	public function devenirFan($champion_id,$user_id,$date_fan,$ip,$user_agent,$host)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('INSERT INTO champion_popularite (champion_id,user_id,date,ip,user_agent,host) VALUES (:champ_id,:user_id,:date_fan,:ip,:user_agent,:host)');
	             $req->bindValue('champ_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->bindValue('ip',$ip, PDO::PARAM_STR);
	             $req->bindValue('date_fan',$date_fan, PDO::PARAM_STR);
	             $req->bindValue('user_agent',$user_agent, PDO::PARAM_STR);
	             $req->bindValue('host',$host, PDO::PARAM_STR);
	             $req->execute();
	             $bdd=null;
	             return array('validation'=>true,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
		
	}
	public function ne_plus_devenir_fan($champion_id,$user_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('DELETE FROM champion_popularite WHERE champion_id =:champ_id AND user_id =:user_id LIMIT 1');
	             $req->bindValue('champ_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $bdd=null;
	             return array('validation'=>true,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
}

?>