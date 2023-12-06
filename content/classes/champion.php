<?php
class champion{
	
	private $ID;
	private $IDold;
	private $Nom;
	private $Sexe;
	private $PaysID;
	private $DateNaissance;
	private $LieuNaissance;
	private $Grade;
	private $Clubs;
	private $Taille;
	private $Poids;
	private $TokuiWaza;
	private $MainDirectrice;
	private $Activite;
	private $Forces;
	
	private $DateChangementNat;
	private $NvPaysID;
	
	private $Site;
	private $Equipementier;
	private $Lien_site_équipementier;
	private $Facebook;
	private $Instagram;
	private $Bio;
	public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}
	public function getEquipementier(){
		return $this->Equipementier;
	}

	public function setEquipementier($Equipementier){
		$this->Equipementier = $Equipementier;
	}
	public function getLien_site_équipementier(){
		return $this->Lien_site_équipementier;
	}

	public function setLien_site_équipementier($Lien_site_équipementier){
		$this->Lien_site_équipementier = $Lien_site_équipementier;
	}
	public function getFacebook(){
		return $this->Facebook;
	}

	public function setFacebook($Facebook){
		$this->Facebook = $Facebook;
	}
	public function getInstagram(){
		return $this->Instagram;
	}

	public function setInstagram($Instagram){
		$this->Instagram = $Instagram;
	}
	public function getBio(){
		return $this->Bio;
	}

	public function setBio($Bio){
		$this->Bio = $Bio;
	}
	public function getIDold(){
		return $this->IDold;
	}

	public function setIDold($IDold){
		$this->IDold = $IDold;
	}

	public function getNom(){
		return $this->Nom;
	}

	public function setNom($Nom){
		$this->Nom = $Nom;
	}

	public function getSexe(){
		return $this->Sexe;
	}

	public function setSexe($Sexe){
		$this->Sexe = $Sexe;
	}

	public function getPaysID(){
		return $this->PaysID;
	}

	public function setPaysID($PaysID){
		$this->PaysID = $PaysID;
	}
	public function getNvPaysID(){
		return $this->NvPaysID;
	}

	public function setNvPaysID($NvPaysID){
		$this->NvPaysID = $NvPaysID;
	}
	public function getDateChangementNat(){
		return $this->DateChangementNat;
	}

	public function setDateChangementNat($DateChangementNat){
		$this->DateChangementNat = $DateChangementNat;
	}
	public function getDateNaissance(){
		return $this->DateNaissance;
	}

	public function setDateNaissance($DateNaissance){
		$this->DateNaissance = $DateNaissance;
	}
	public function getLieuNaissance(){
		return $this->LieuNaissance;
	}

	public function setLieuNaissance($LieuNaissance){
		$this->LieuNaissance = $LieuNaissance;
	}

	public function getGrade(){
		return $this->Grade;
	}

	public function setGrade($Grade){
		$this->Grade = $Grade;
	}

	public function getClubs(){
		return $this->Clubs;
	}

	public function setClubs($Clubs){
		$this->Clubs = $Clubs;
	}

	public function getTaille(){
		return $this->Taille;
	}

	public function setTaille($Taille){
		$this->Taille = $Taille;
	}

	public function getPoids(){
		return $this->Poids;
	}

	public function setPoids($Poids){
		$this->Poids = $Poids;
	}

	public function getTokuiWaza(){
		return $this->TokuiWaza;
	}

	public function setTokuiWaza($TokuiWaza){
		$this->TokuiWaza = $TokuiWaza;
	}

	public function getMainDirectrice(){
		return $this->MainDirectrice;
	}

	public function setMainDirectrice($MainDirectrice){
		$this->MainDirectrice = $MainDirectrice;
	}

	public function getActivite(){
		return $this->Activite;
	}

	public function setActivite($Activite){
		$this->Activite = $Activite;
	}

	public function getForces(){
		return $this->Forces;
	}

	public function setForces($Forces){
		$this->Forces = $Forces;
	}

	public function getIdole(){
		return $this->Idole;
	}

	public function setIdole($Idole){
		$this->Idole = $Idole;
	}

	public function getIdole2(){
		return $this->Idole2;
	}

	public function setIdole2($Idole2){
		$this->Idole2 = $Idole2;
	}

	public function getIdole3(){
		return $this->Idole3;
	}

	public function setIdole3($Idole3){
		$this->Idole3 = $Idole3;
	}

	public function getIdole4(){
		return $this->Idole4;
	}

	public function setIdole4($Idole4){
		$this->Idole4 = $Idole4;
	}

	public function getIdole5(){
		return $this->Idole5;
	}

	public function setIdole5($Idole5){
		$this->Idole5 = $Idole5;
	}

	public function getIdole6(){
		return $this->Idole6;
	}

	public function setIdole6($Idole6){
		$this->Idole6 = $Idole6;
	}

	public function getIdole7(){
		return $this->Idole7;
	}

	public function setIdole7($Idole7){
		$this->Idole7 = $Idole7;
	}

	public function getLidole2(){
		return $this->Lidole2;
	}

	public function setLidole2($Lidole2){
		$this->Lidole2 = $Lidole2;
	}

	public function getLidole3(){
		return $this->Lidole3;
	}

	public function setLidole3($Lidole3){
		$this->Lidole3 = $Lidole3;
	}

	public function getLidole4(){
		return $this->Lidole4;
	}

	public function setLidole4($Lidole4){
		$this->Lidole4 = $Lidole4;
	}

	public function getLidole5(){
		return $this->Lidole5;
	}

	public function setLidole5($Lidole5){
		$this->Lidole5 = $Lidole5;
	}

	public function getLidole6(){
		return $this->Lidole6;
	}

	public function setLidole6($Lidole6){
		$this->Lidole6 = $Lidole6;
	}

	public function getLidole7(){
		return $this->Lidole7;
	}

	public function setLidole7($Lidole7){
		$this->Lidole7 = $Lidole7;
	}

	public function getAnecdote(){
		return $this->Anecdote;
	}

	public function setAnecdote($Anecdote){
		$this->Anecdote = $Anecdote;
	}

	public function getPhrase(){
		return $this->Phrase;
	}

	public function setPhrase($Phrase){
		$this->Phrase = $Phrase;
	}

	public function getVuPar(){
		return $this->VuPar;
	}

	public function setVuPar($VuPar){
		$this->VuPar = $VuPar;
	}

	public function getSite(){
		return $this->Site;
	}

	public function setSite($Site){
		$this->Site = $Site;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new champion();
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

	public function getLastChampions(){
		try {
				 include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM champions ORDER BY ID DESC LIMIT 10');
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

	public function getChampionsPhotos($champ_id){
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM images I LEFT OUTER JOIN galeries G ON I.Galerie_id=G.ID WHERE I.actif = '1' AND (I.Champion_id=:champ_id OR I.Champion2_id=:champ_id)");
	             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					array_push($champions, $row);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }

	}



    public function getChampionBirthday()
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT *,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), DateNaissance)), "%Y")+0 AS age from `champions` WHERE MONTH(DateNaissance) = MONTH(NOW()) AND DAY(DateNaissance)=DAY(NOW()) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), DateNaissance)), "%Y")+0<=40 ORDER BY age DESC');
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
	public function ageBirthday($bithdayDate){
		 $date = new DateTime($bithdayDate);
		 $now = new DateTime();
		 $interval = $now->diff($date);
		 return $interval->y;
	}
   
    public function getListChampionsByInitial($i,$page)
	{
		if($page!=="page_resultat"){
		try {
				  include("../database/connexion.php");
				 (strlen ($i)==1) ? $con=strtoupper($i)."%" : $con="%".strtoupper($i)."%";
				 // print_r($con);
				 // die;
				 $req = $bdd->prepare('SELECT ID,Nom,PaysID FROM champions WHERE UPPER(Nom) LIKE :lettre ORDER BY UPPER(Nom) LIMIT :offset,80');
	             $req->bindValue('lettre',$con, PDO::PARAM_STR);
	             $req->bindValue('offset',$page*80, PDO::PARAM_INT);
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
	    }else{
	    	try {
				  include("../database/connexion.php");
				 
				 $con="%".strtoupper($i)."%";
				 $req = $bdd->prepare('SELECT ID,Nom,PaysID FROM champions WHERE UPPER(Nom) LIKE :lettre ORDER BY UPPER(Nom) ');
	             $req->bindValue('lettre',$con, PDO::PARAM_STR);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    

	                $ch = self::constructWithArray($row);

	                 array_push($champions, $ch);
	             }
	              return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }
	    }
	}

	public function getNumberPage($i)
	{
		try {
				  include("../database/connexion.php");
				 (strlen ($i)==1) ? $con=strtoupper($i)."%" : $con="%".strtoupper($i)."%";
				 $req = $bdd->prepare('SELECT COUNT(*) FROM champions WHERE UPPER(Nom) LIKE :lettre ORDER BY UPPER(Nom)');
	             $req->bindValue('lettre',$con, PDO::PARAM_STR);
	             $req->execute();
	             $nb_page=$req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$nb_page,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }
	}
	


	public function getNumberCom($idChamp)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT COUNT(*) FROM commentaires WHERE champion_id =:id');
	             $req->bindValue('id',$idChamp, PDO::PARAM_INT);
	             $req->execute();
	             $numberCom= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$numberCom,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
	public function getNumberVideos($idChamp)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM videos WHERE Champion_id=:id');
	             $req->bindValue('id',$idChamp, PDO::PARAM_INT);
	             $req->execute();
	             $numberVideos= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$numberVideos,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
	public function getNumberImages($idChamp)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM images WHERE Champion_id=:id AND actif="1"');
	             $req->bindValue('id',$idChamp, PDO::PARAM_INT);
	             $req->execute();
	             $numberImages= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$numberImages,'message'=>'');
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}

	public function getChampionById($idChamp)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM champions WHERE ID=:id');
	             $req->bindValue('id',$idChamp, PDO::PARAM_INT);
	             $req->execute();
	             if($req->rowCount() > 0){
		             $champ=self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
		             return array('validation'=>true,'donnees'=>$champ,'message'=>'');
	             }
	             $bdd=null;
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
	public function getUserChampion($nom,$prenom,$date,$pays)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM champions WHERE Nom like :nom and Nom like :prenom and DateNaissance =:date and PaysID =:pays');
	             $req->bindValue('nom','%'.$nom.'%', PDO::PARAM_STR);
				 $req->bindValue('prenom','%'.$prenom.'%', PDO::PARAM_STR);
				 $req->bindValue('date',$date, PDO::PARAM_STR);
				 $req->bindValue('pays',$pays, PDO::PARAM_STR);
	             $req->execute();
	             if($req->rowCount() > 0){
		             $champ=self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
		             return array('validation'=>true,'donnees'=>$champ,'message'=>'');
	             }
	             $bdd=null;
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}
	public function getChampionResults($champ_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT DISTINCT E.CategorieID,E.CategorieageID,R.Rang,R.Temps,C.Intitule, C.tri, E.Nom, DATE_FORMAT(E.DateDebut, "%Y") AS DateDebut, E.ID FROM evcategorieevenement C INNER JOIN evenements E ON C.ID=E.CategorieID
                            INNER JOIN evresultats R ON R.EvenementID=E.ID
                            WHERE R.ChampionID=:champ_id ORDER BY E.DateDebut DESC');
	             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					array_push($champions, $row);
	             }
	             $bdd=null;
	              return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	        }
	      catch(Exception $e)
			{ 
				die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getChampionFans($champ_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT distinct(u.username),u.id FROM users u INNER JOIN champion_popularite c ON u.id = c.user_id WHERE c.champion_id=:champ_id');
	             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					array_push($champions, $row);
	             }
	             $bdd=null;
	              return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	        }
	      catch(Exception $e)
			{ 
				die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getChampionParEquipe($evresId)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT C.ID,C.Nom FROM evresultats R INNER JOIN champions C ON R.ChampionID = C.ID WHERE R.equipeID=:evresId');
	             $req->bindValue('evresId',$evresId, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					array_push($champions, $row);
	             }
	             $bdd=null;
	              return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	        }
	      catch(Exception $e)
			{ 
				die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getTabMedailleByChampion($champion_id)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT DISTINCT E.CategorieageID,R.Rang,C.Intitule, C.tri, E.Nom,E.DateDebut, DATE_FORMAT(E.DateDebut, "%Y") AS DateDebut, E.ID FROM evcategorieevenement C INNER JOIN evenements E ON C.ID=E.CategorieID INNER JOIN evresultats R ON R.EvenementID=E.ID WHERE R.ChampionID=:champion_id ORDER BY C.tri, C.Intitule DESC');//E.DateDebut DESC
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->execute();
	             $champions = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					array_push($champions, $row);
	             }
	              return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	              $bdd=null;
	        }
	      catch(Exception $e)
			{ 
				die('Erreur : ' . $e->getMessage());
	        }
	}
	public function updateChampByAdminExterne($id,$DateNaissance,$LieuNaissance,$Grade,$Clubs,$Taille,$Poids,$TokuiWaza,$MainDirectrice,$Activite,$Forces,$Idole,$Idole2,$Idole3,$Idole4,$Idole5,$Idole6,$Idole7,$Lidole2,$Lidole3,$Lidole4,$Lidole5,$Lidole6,$Lidole7,$Anecdote,$Phrase,$Site)
	{
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("UPDATE champions SET DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Grade=:Grade,Clubs=:Clubs,Taille=:Taille, Poids=:Poids, TokuiWaza=:TokuiWaza, MainDirectrice=:MainDirectrice, Activite=:Activite, Forces=:Forces, Idole=:Idole, Idole2=:Idole2, Idole3=:Idole3, Idole4=:Idole4, Idole5=:Idole5, Idole6=:Idole6, Idole7=:Idole7, Lidole2=:Lidole2, Lidole3=:Lidole3, Lidole4=:Lidole4, Lidole5=:Lidole5, Lidole6=:Lidole6, Lidole7=:Lidole7, Anecdote=:Anecdote, Phrase=:Phrase, Site=:Site WHERE ID=:id");
			             
			             $req->bindValue('id',$id, PDO::PARAM_INT);
			             $req->bindValue('DateNaissance',$DateNaissance, PDO::PARAM_STR); 
			             $req->bindValue('LieuNaissance',$LieuNaissance, PDO::PARAM_STR);
			             $req->bindValue('Grade',$Grade, PDO::PARAM_INT);
			             $req->bindValue('Clubs',$Clubs, PDO::PARAM_STR);
			             $req->bindValue('Taille',$Taille, PDO::PARAM_INT);
			             $req->bindValue('Poids',$Poids, PDO::PARAM_INT);
			             $req->bindValue('TokuiWaza',$TokuiWaza, PDO::PARAM_STR); 
			             $req->bindValue('MainDirectrice',$MainDirectrice, PDO::PARAM_STR);
			             $req->bindValue('Activite',$Activite, PDO::PARAM_STR);
			             $req->bindValue('Forces',$Forces, PDO::PARAM_STR);
			             $req->bindValue('Idole',$Idole, PDO::PARAM_STR);
						 $req->bindValue('Idole2',$Idole2, PDO::PARAM_STR);
			             $req->bindValue('Idole3',$Idole3, PDO::PARAM_STR);
			             $req->bindValue('Idole4',$Idole4, PDO::PARAM_STR);
			             $req->bindValue('Idole5',$Idole5, PDO::PARAM_STR); 
			             $req->bindValue('Idole6',$Idole6, PDO::PARAM_STR);
			             $req->bindValue('Idole7',$Idole7, PDO::PARAM_STR);
			             $req->bindValue('Lidole2',$Lidole2, PDO::PARAM_STR);
			             $req->bindValue('Lidole3',$Lidole3, PDO::PARAM_STR);
			             $req->bindValue('Lidole3',$Lidole3, PDO::PARAM_STR);
			             $req->bindValue('Lidole4',$Lidole4, PDO::PARAM_STR);
			             $req->bindValue('Lidole5',$Lidole5, PDO::PARAM_STR);
			             $req->bindValue('Lidole6',$Lidole6, PDO::PARAM_STR);
			             $req->bindValue('Lidole7',$Lidole7, PDO::PARAM_STR);
						 $req->bindValue('Anecdote',$Anecdote, PDO::PARAM_STR);
			             $req->bindValue('Phrase',$Phrase, PDO::PARAM_STR);
			             $req->bindValue('Site',$Site, PDO::PARAM_STR);

			             $req->execute();
			             return array('validation'=>true,'message'=>'');
			             $bdd=null;
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
	}

	public function getNumberChampions()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM champions");
	             $req->execute();
	             $champions= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$champions,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

}
?>