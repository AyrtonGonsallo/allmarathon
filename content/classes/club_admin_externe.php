<?php
class club_admin_externe{

	private $id;
	private $nom;
	private $prenom;
	private $telephone;
	private $fonction;
	private $user_id;
	private $club_id;
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

	public function getFonction(){
		return $this->fonction;
	}

	public function setFonction($fonction){
		$this->fonction = $fonction;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getClub_id(){
		return $this->club_id;
	}

	public function setClub_id($club_id){
		$this->club_id = $club_id;
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
	        $instance = new club_admin_externe();
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

		public function getClubsByUser($user)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM club_admin_externe WHERE user_id=:user');
	             $req->bindValue('user',$user, PDO::PARAM_INT);
	             $req->execute();
	             $clubs = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	                $club = self::constructWithArray($row);
	                 array_push($clubs, $club);
	             }
	                return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
	                $bdd=null;
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getDemandesByUser($user_id,$club_id,$actif)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM club_admin_externe WHERE user_id=:user_id AND club_id =:club_id AND actif=:actif');
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->bindValue('club_id',$club_id, PDO::PARAM_INT);
	             $req->bindValue('actif',$actif, PDO::PARAM_INT);
	             $req->execute();
	             $clubs = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	                $club = self::constructWithArray($row);
	                 array_push($clubs, $club);
	             }
	             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
	             $bdd=null;
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getClubAdmins($club_id)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM club_admin_externe WHERE actif = 1 AND club_id = :club_id');
	             $req->bindValue('club_id',$club_id, PDO::PARAM_INT);
	             $req->execute();
	             $clubs = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	                $club = self::constructWithArray($row);
	                 array_push($clubs, $club);
	             }
	             return array('validation'=>true,'donnees'=>$clubs,'message'=>'');
	             $bdd=null;
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		
	}

	public function isAdmin($club_id,$user_id)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM club_admin_externe WHERE user_id = :user_id AND club_id = :club_id');
	             $req->bindValue('club_id',$club_id, PDO::PARAM_INT);
	             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             $req->execute();
	             $clubs = array();
	             if ($req->fetch(PDO::FETCH_ASSOC) ){    
	                return true;
	             }
	             else {return false;}
	             $bdd=null;
	        }
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		
	}

	public function addAdminClub($nom,$prenom,$telephone,$fonction,$user_id,$club_id,$ip_creation,$date_creation)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("INSERT INTO club_admin_externe (nom,prenom,telephone,fonction, user_id, club_id, ip_creation, date_creation) VALUES (:nom,:prenom,:telephone,:fonction,:user_id,:club_id,:ip_creation,:date_creation)");
	             		 $req->bindValue('nom',$nom, PDO::PARAM_STR);
	             		 $req->bindValue('prenom',$prenom, PDO::PARAM_STR);
	             		 $req->bindValue('telephone',$telephone, PDO::PARAM_STR);
	             		 $req->bindValue('fonction',$fonction, PDO::PARAM_STR);
	             		 $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
	             		 $req->bindValue('club_id',$club_id, PDO::PARAM_INT);
	             		 $req->bindValue('ip_creation',$ip_creation, PDO::PARAM_STR);
	             		 $req->bindValue('date_creation',$date_creation, PDO::PARAM_STR);
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