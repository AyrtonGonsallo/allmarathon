<?php 
class championAdminExterne{
	private $id;
	private $nom;
	private $prenom;
	private $telephone;
	private $situation;
	private $video;
	private $user_id;
	private $champion_id;
	private $ip_creation;
	private $date_creation;
	private $ip_mod;
	private $date_mod;
	private $actif;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getPrenom(){
		return $this->prenom;
	}

	public function setPrenom($prenom){
		$this->prenom = $prenom;
	}

	public function getTelephone(){
		return $this->telephone;
	}

	public function setTelephone($telephone){
		$this->telephone = $telephone;
	}

	public function getSituation(){
		return $this->situation;
	}

	public function setSituation($situation){
		$this->situation = $situation;
	}

	public function getVideo(){
		return $this->video;
	}

	public function setVideo($video){
		$this->video = $video;
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

	public function getIp_creation(){
		return $this->ip_creation;
	}

	public function setIp_creation($ip_creation){
		$this->ip_creation = $ip_creation;
	}

	public function getDate_creation(){
		return $this->date_creation;
	}

	public function setDate_creation($date_creation){
		$this->date_creation = $date_creation;
	}

	public function getIp_mod(){
		return $this->ip_mod;
	}

	public function setIp_mod($ip_mod){
		$this->ip_mod = $ip_mod;
	}

	public function getDate_mod(){
		return $this->date_mod;
	}

	public function setDate_mod($date_mod){
		$this->date_mod = $date_mod;
	}

	public function getActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new championAdminExterne();
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

	public function isAdmin($user_id,$champion_id){
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM champion_admin_externe WHERE user_id=:user_id AND champion_id = :champion_id  AND actif=1');
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $isAdmin = $req->fetch(PDO::FETCH_ASSOC);
				 return array('validation'=>true,'donnees'=>$isAdmin,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }
		
	}
	public function updateChampAdmin($user_id,$champion_id,$ip_mod,$date_mod)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('UPDATE champion_admin_externe SET ip_mod=:ip_mod, date_mod=:date_mod WHERE user_id=:user_id AND champion_id = :champion_id AND actif=1');
	             $req->bindValue('ip_mod',$ip_mod, PDO::PARAM_INT);
	             $req->bindValue('date_mod',$date_mod, PDO::PARAM_INT);
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
				 return array('validation'=>true,'message'=>'');
	        }
	       
	        catch(Exception $e)

	        {
				die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getChampAdminExterne($user_id,$champion_id)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM champion_admin_externe WHERE user_id=:user_id AND champion_id = :champion_id AND actif=1');
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $champ_admin=$req->fetch(PDO::FETCH_ASSOC);
				 return array('validation'=>true,'donnees'=>$champ_admin,'message'=>'');
	        }
	       
	        catch(Exception $e)

	        {
				die('Erreur : ' . $e->getMessage());
	        }
	}

	public function updateVideoAdmin($user_id,$champion_id,$video)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('UPDATE champion_admin_externe SET video=:video WHERE user_id =:user_id AND champion_id =:champion_id');
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->bindValue('video',$video, PDO::PARAM_STR);
	             $req->execute();
	             $bdd=null;
				 return array('validation'=>true,'message'=>'');
	        }
	       
	        catch(Exception $e)

	        {
				die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getAdminsExterneByChampion($champion_id)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM champion_admin_externe WHERE champion_id = :champion_id AND actif=1');
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->execute();
	             $admins = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	                $ch = self::constructWithArray($row);
	                 array_push($admins, $ch);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$admins,'message'=>'');
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


	public function getChampionsByUser($user)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM champion_admin_externe   WHERE user_id=:user and actif=1');
	             $req->bindValue('user',$user, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	                $ch = self::constructWithArray($row);
	                 array_push($champions, $ch);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	

	
}
?>