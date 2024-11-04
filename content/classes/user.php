<?php
class user{
	private $id;
	private $nom;
	private $prenom;
	private $username;
	private $email;
	private $date_naissance;
	private $code_postale;
	private $ville;
	private $pays;
	private $grade;
	private $club;
	private $profile_pic;
	private $password;
	private $newsletter;
	private $offres;
	private $user_ip;
	private $gcoo1;
	private $gcoo2;
	private $gaddress;
	private $user_regdate;

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

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getDate_naissance(){
		return $this->date_naissance;
	}

	public function setDate_naissance($date_naissance){
		$this->date_naissance = $date_naissance;
	}

	public function getCode_postale(){
		return $this->code_postale;
	}

	public function setCode_postale($code_postale){
		$this->code_postale = $code_postale;
	}

	public function getVille(){
		return $this->ville;
	}

	public function setVille($ville){
		$this->ville = $ville;
	}

	public function getPays(){
		return $this->pays;
	}

	public function setPays($pays){
		$this->pays = $pays;
	}

	public function getGrade(){
		return $this->grade;
	}

	public function setGrade($grade){
		$this->grade = $grade;
	}

	public function getClub(){
		return $this->club;
	}

	public function setClub($club){
		$this->club = $club;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

		public function getNewsletter(){
		return $this->newsletter;
	}

	public function setNewsletter($newsletter){
		$this->newsletter = $newsletter;
	}

	public function getOffres(){
		return $this->offres;
	}

	public function setOffres($offres){
		$this->offres = $offres;
	}

	public function getProfile_pic(){
		return $this->profile_pic;
	}

	public function setProfile_pic($profile_pic){
		$this->profile_pic = $profile_pic;
	}

	public function getUser_ip(){
		return $this->user_ip;
	}

	public function setUser_ip($user_ip){
		$this->user_ip = $user_ip;
	}

	public function getGcoo1(){
		return $this->gcoo1;
	}

	public function setGcoo1($gcoo1){
		$this->gcoo1 = $gcoo1;
	}

	public function getGcoo2(){
		return $this->gcoo2;
	}

	public function setGcoo2($gcoo2){
		$this->gcoo2 = $gcoo2;
	}

	public function getGaddress(){
		return $this->gaddress;
	}

	public function setGaddress($gaddress){
		$this->gaddress = $gaddress;
	}

	public function getUser_regdate(){
		return $this->user_regdate;
	}

	public function setUser_regdate($user_regdate){
		$this->user_regdate = $user_regdate;
	}

    public static function constructWithArray( array $donnees ) {
	        $instance = new user();
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

	public function getUserById($id){
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM users  WHERE id=:id");
			             $req->execute(array('id'=>$id));
			             $res=$req->fetch(PDO::FETCH_ASSOC);
			             if($res){
			            	$user= self::constructWithArray($res);
			             	return array('validation'=>true,'donnees'=>$user,'message'=>'');
			             }
			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getNumberUsers()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM users");
	             $req->execute();
	             $users= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$users,'message'=>'');
	             $bdd=null;

	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getUserByLogin($username,$password){
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM users  WHERE username LIKE :username and password LIKE :password");
			             $req->bindValue('username', $username, PDO::PARAM_STR);
			             $req->bindValue('password', $password, PDO::PARAM_STR);
			             $req->execute();
			             if ($req->rowCount() > 0) {
							  $user= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
							  $bdd=null;
			             	  return array('validation'=>true,'donnees'=>$user,'message'=>'');
							} else {
								$bdd=null;
			             	  return array('validation'=>false,'message'=>'');
							   
							}
			             
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}
	public function getUserByUsername($username,$email){
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM users  WHERE username LIKE :username or email LIKE :email");
			             $req->bindValue('username', '%'.$username.'%', PDO::PARAM_STR);
						 $req->bindValue('email', '%'.$email.'%', PDO::PARAM_STR);
			             $req->execute();
			             if ($req->rowCount() > 0) {
							  $user= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
							  $bdd=null;
			             	  return array('validation'=>true,'donnees'=>$user,'message'=>'');
							} else {
								$bdd=null;
			             	  return array('validation'=>false,'message'=>'');
							   
							}
			             
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}
	public function genererCsvNewsletterUsers()
	{
		$output = fopen('php://output', 'w');

		fputcsv($output, array('NOM', 'PRENOM', 'USERNAME','EMAIL'));

		try {
		require('../database/connexion.php');
		$req = $bdd->prepare('SELECT nom,prenom,username, email FROM `users` WHERE `newsletter` != 0');
		$req->execute();
		while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {   
			fputcsv($output, $row);
		}
		$bdd=null;
		}
		catch(Exception $e)
		{ 
		die('Erreur : ' . $e->getMessage());
		}
	}

		public function getAbonnesNewsLetter()
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare('SELECT nom,prenom, email FROM `users` WHERE `email` != "" AND `newsletter` != 0');
			$req->execute();
			$abonnes= array();
		    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
				array_push($abonnes, $row);
		    }
		return array('validation'=>true,'donnees'=>$abonnes,'message'=>'');
		$bdd=null;
		}
		catch(Exception $e)
		{ 
		die('Erreur : ' . $e->getMessage());
		}
	}

	public function getAddedResults($uid)
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare('SELECT p.*,e.Nom,e.DateDebut,m.prefixe FROM `champion_admin_externe_palmares` p,evenements e,marathons m WHERE `userID` = :uid and e.ID=p.EvenementID and m.id=e.marathon_id');
			$req->bindValue('uid', $uid, PDO::PARAM_INT);
			$req->execute();
			$res= array();
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


	public function subscribe_to_newsletter($user_id){
		try {
			
			require('../database/connexion.php');
			
			$req = $bdd->prepare("UPDATE users SET newsletter= 1  WHERE id = :membre_id ");
			$req->bindValue('membre_id', $user_id, PDO::PARAM_INT);

			$req->execute();

			return 1;

			$bdd=null;
	   }
	  
	   catch(Exception $e)
	   {
		   die('Erreur : ' . $e->getMessage());
		   return 0;

	   }
	}

	public function updateUserById($nom,$prenom,$sexe,$email,$dn,$pays,$newsletter,$offres,$membre_id,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$p,$taille,$Facebook,$Bio,$c_id)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("UPDATE users SET nom= :nom ,prenom= :prenom, email= :email ,date_naissance= :date_naissance , pays= :pays ,newsletter= :newsletter , offres= :offres WHERE id = :membre_id ");

			             $req->bindValue('nom', $nom, PDO::PARAM_STR);
			             $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->bindValue('date_naissance', $dn, PDO::PARAM_STR);
			             $req->bindValue('pays', $pays, PDO::PARAM_STR);
			             $req->bindValue('newsletter', $newsletter, PDO::PARAM_INT);
			             $req->bindValue('offres', $offres, PDO::PARAM_INT);
			             $req->bindValue('membre_id', $membre_id, PDO::PARAM_INT);

			             $req->execute();

						// mettre a jour champion
						 $req4 = $bdd->prepare("UPDATE champions SET Nom=:Nom ,Poids=:p ,Taille=:t,Equipementier=:Equipementier ,Sexe=:Sexe ,PaysID=:PaysID ,DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Lien_site_équipementier=:lien_equip,Instagram=:Instagram,Facebook=:Facebook, Bio=:Bio WHERE ID=:id");

						$req4->bindValue('Nom',$nom.' '.$prenom, PDO::PARAM_STR);
						$req4->bindValue('Sexe',$sexe, PDO::PARAM_STR);
						$req4->bindValue('PaysID',$pays, PDO::PARAM_STR);
						$req4->bindValue('DateNaissance',$dn, PDO::PARAM_STR);
						$req4->bindValue('LieuNaissance',$LieuNaissance, PDO::PARAM_STR);
						$req4->bindValue('Equipementier',$Equipementier, PDO::PARAM_STR);
						$req4->bindValue('lien_equip',$lien_equip, PDO::PARAM_STR);
					   $req4->bindValue('Instagram',$Instagram, PDO::PARAM_STR);
					   $req4->bindValue('p',$p, PDO::PARAM_INT);
					   $req4->bindValue('t',$taille, PDO::PARAM_INT);
					   $req4->bindValue('Facebook',$Facebook, PDO::PARAM_STR);
					   $req4->bindValue('Bio',$Bio, PDO::PARAM_STR);
						
						$req4->bindValue('id',$c_id, PDO::PARAM_INT);
						$statut=$req4->execute();
			             return array('validation'=>true,'message'=>'');

			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}

	public function updateSimpleUserById(
		$nom,
		$prenom,
		$username,
		$email,
		$date_naissance,
		$pays,
		$newsletter,
		$offres,
		$membre_id,
		$code_postale,
		$ville
	) {
		try {
			require('../database/connexion.php');
	
			// Préparation de la requête SQL
			$req = $bdd->prepare("
				UPDATE users 
				SET nom = :nom, 
					prenom = :prenom, 
					username = :username, 
					email = :email, 
					date_naissance = :date_naissance, 
					pays = :pays, 
					newsletter = :newsletter, 
					offres = :offres, 
					code_postale = :code_postale, 
					ville = :ville 
				WHERE id = :membre_id
			");
	
			// Liaison des paramètres
			$req->bindValue(':nom', $nom, PDO::PARAM_STR);
			$req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
			$req->bindValue(':username', $username, PDO::PARAM_STR);
			$req->bindValue(':email', $email, PDO::PARAM_STR);
			$req->bindValue(':date_naissance', $date_naissance, PDO::PARAM_STR);
			$req->bindValue(':pays', $pays, PDO::PARAM_STR);
			$req->bindValue(':newsletter', (int)$newsletter, PDO::PARAM_INT);
			$req->bindValue(':offres', (int)$offres, PDO::PARAM_INT);
			$req->bindValue(':code_postale', $code_postale, PDO::PARAM_STR);
			$req->bindValue(':ville', $ville, PDO::PARAM_STR);
			$req->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
	
			// Exécution de la requête
			$req->execute();
	
			return array('validation' => true, 'message' => 'Membre mis à jour avec succès.');
		} catch (Exception $e) {
			return array('validation' => false, 'message' => 'Erreur : ' . $e->getMessage());
		} finally {
			// Toujours fermer la connexion, même en cas d'erreur
			$bdd = null;
		}
	}
	



	public function updateUserIGById($nom,$prenom,$sexe,$email,$pays,$membre_id,$c_id)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("UPDATE users SET nom= :nom ,prenom= :prenom, email= :email , pays= :pays  WHERE id = :membre_id ");

			             $req->bindValue('nom', $nom, PDO::PARAM_STR);
			             $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->bindValue('pays', $pays, PDO::PARAM_STR);
			             $req->bindValue('membre_id', $membre_id, PDO::PARAM_INT);

			             $req->execute();

						// mettre a jour champion
						 $req4 = $bdd->prepare("UPDATE champions SET Nom=:Nom ,Sexe=:Sexe ,PaysID=:PaysID  WHERE ID=:id");

						$req4->bindValue('Nom',$nom.' '.$prenom, PDO::PARAM_STR);
						$req4->bindValue('Sexe',$sexe, PDO::PARAM_STR);
						$req4->bindValue('PaysID',$pays, PDO::PARAM_STR);
						
						
						$req4->bindValue('id',$c_id, PDO::PARAM_INT);
						$statut=$req4->execute();
			             return array('validation'=>true,'message'=>'');

			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}


	public function updateUserDCById($dn,$newsletter,$offres,$membre_id,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$p,$taille,$Facebook,$Bio,$c_id)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("UPDATE users SET date_naissance= :date_naissance , newsletter= :newsletter , offres= :offres WHERE id = :membre_id ");

			            
			             $req->bindValue('date_naissance', $dn, PDO::PARAM_STR);
			             $req->bindValue('newsletter', $newsletter, PDO::PARAM_INT);
			             $req->bindValue('offres', $offres, PDO::PARAM_INT);
			             $req->bindValue('membre_id', $membre_id, PDO::PARAM_INT);

			             $req->execute();

						// mettre a jour champion
						 $req4 = $bdd->prepare("UPDATE champions SET Poids=:p ,Taille=:t,Equipementier=:Equipementier  ,DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Lien_site_équipementier=:lien_equip,Instagram=:Instagram,Facebook=:Facebook, Bio=:Bio WHERE ID=:id");

						$req4->bindValue('DateNaissance',$dn, PDO::PARAM_STR);
						$req4->bindValue('LieuNaissance',$LieuNaissance, PDO::PARAM_STR);
						$req4->bindValue('Equipementier',$Equipementier, PDO::PARAM_STR);
						$req4->bindValue('lien_equip',$lien_equip, PDO::PARAM_STR);
					   $req4->bindValue('Instagram',$Instagram, PDO::PARAM_STR);
					   $req4->bindValue('p',$p, PDO::PARAM_INT);
					   $req4->bindValue('t',$taille, PDO::PARAM_INT);
					   $req4->bindValue('Facebook',$Facebook, PDO::PARAM_STR);
					   $req4->bindValue('Bio',$Bio, PDO::PARAM_STR);
						
						$req4->bindValue('id',$c_id, PDO::PARAM_INT);
						$statut=$req4->execute();
			             return array('validation'=>true,'message'=>'');

			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}

	public function updatePassprdByUserId($membre_id,$password)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req22 = $bdd->prepare("UPDATE users SET password= :pass WHERE id = :membre_id ");
			             $req22->bindValue('pass', $password, PDO::PARAM_STR);
			             $req22->bindValue('membre_id', $membre_id, PDO::PARAM_INT);
			             $req22->execute();
			             return array('validation'=>true,'message'=>'');
			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}
	public function checkEmailUsername($email,$username)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("SELECT * FROM users WHERE email LIKE :email OR username LIKE :username ");
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->bindValue('username', $username, PDO::PARAM_STR);
			             $req->execute();
			             if ($req->rowCount() > 0) {
							  $user= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
							  $bdd=null;
			             	  return array('validation'=>true,'donnees'=>$user,'message'=>'');
							} else {
								$bdd=null;
			             	  return array('validation'=>false,'message'=>'');
							   
							}
			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}
	
	public function addNewUser($username,$password,$nom,$prenom,$email,$dn,$sexe,$pays,$newsletter,$offres,$t,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$p,$taille,$Facebook,$Bio)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("INSERT INTO `users` (`id` ,`nom` ,`prenom` ,`username` ,`email` ,`date_naissance`  ,`pays` ,`newsletter` ,`offres` ,`password`,`user_regdate`)
		VALUES (NULL , :nom, :prenom, :username, :email, :dn, :pays, :newsletter, :offres, :password,:t)");
			             
			             $req->bindValue('nom', $nom, PDO::PARAM_STR);
			             $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
			             $req->bindValue('username', $username, PDO::PARAM_STR);
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->bindValue('dn', $dn, PDO::PARAM_STR);
			            
			             $req->bindValue('pays', $pays, PDO::PARAM_STR);
			             
			             $req->bindValue('newsletter', $newsletter, PDO::PARAM_STR);
			             $req->bindValue('offres', $offres, PDO::PARAM_STR);
			             $req->bindValue('password', $password, PDO::PARAM_STR);
			             $req->bindValue('t', $t, PDO::PARAM_STR);
			             $req->execute();
			             $id=$bdd->lastInsertId();
			             

						 //CREEER LE CHAMPION
						 $req4 = $bdd->prepare("INSERT INTO champions (Nom,Sexe,PaysID,DateNaissance,LieuNaissance,Equipementier,Lien_site_équipementier,Instagram,Facebook, Bio,Poids,Taille) VALUES (:Nom,:Sexe,:PaysID,:DateNaissance,:LieuNaissance,:Equipementier,:lien_equip,:Instagram,:Facebook,:Bio,:p,:t)");
	   
						$req4->bindValue('Nom',$nom.' '.$prenom, PDO::PARAM_STR);
						$req4->bindValue('Sexe',$sexe, PDO::PARAM_STR);
						$req4->bindValue('PaysID',$pays, PDO::PARAM_STR);
						
						$req4->bindValue('DateNaissance',$dn, PDO::PARAM_STR);
						$req4->bindValue('LieuNaissance',$LieuNaissance, PDO::PARAM_STR);
						$req4->bindValue('Equipementier',$Equipementier, PDO::PARAM_STR);
						$req4->bindValue('lien_equip',$lien_equip, PDO::PARAM_STR);
					   $req4->bindValue('Instagram',$Instagram, PDO::PARAM_STR);
					   $req4->bindValue('p',$p, PDO::PARAM_INT);
					   $req4->bindValue('t',$taille, PDO::PARAM_INT);
					   $req4->bindValue('Facebook',$Facebook, PDO::PARAM_STR);
					   $req4->bindValue('Bio',$Bio, PDO::PARAM_STR);
						
						$statut=$req4->execute();


			            return array('validation'=>true,'id'=>$id,'message'=>'');
						$bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
					
	}

	public function addNewUser2($username,$password,$nom,$prenom,$email)
	{
		try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("INSERT INTO `users` (`id` ,`nom` ,`prenom` ,`username` ,`email` ,`password`)
		VALUES (NULL , :nom, :prenom, :username, :email, :password)");
			             
			             $req->bindValue('nom', $nom, PDO::PARAM_STR);
			             $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
			             $req->bindValue('username', $username, PDO::PARAM_STR);
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->bindValue('password', $password, PDO::PARAM_STR);
			             $req->execute();
			             $id=$bdd->lastInsertId();
			             
/*
						 //CREEER LE CHAMPION
						 $req4 = $bdd->prepare("INSERT INTO champions (Nom,user_id) VALUES (:Nom,:uid)");
	   
						$req4->bindValue('Nom',$nom.' '.$prenom, PDO::PARAM_STR);
						$req4->bindValue('uid',$id, PDO::PARAM_INT);
						
						$statut=$req4->execute();
*/

			            return array('validation'=>true,'id'=>$id,'message'=>'');
						$bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
					
	}

	public function unsubscribe($email)
	{
			try {
			
						 require('../database/connexion.php');
						 
						 $req = $bdd->prepare("UPDATE users SET newsletter=0  WHERE email=:email");
			             
			             $req->bindValue('email', $email, PDO::PARAM_STR);
			             $req->execute();
			             return array('validation'=>true,'message'=>'');
						 $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			            return array('validation'=>false,'message'=>'');

			        }
	}


}
?>