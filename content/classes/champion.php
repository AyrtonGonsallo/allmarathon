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
	private $Visible;
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
	public function getVisible(){
		return $this->Visible;
	}

	public function setVisible($Visible){
		$this->Visible = $Visible;
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

	public function getChampionsPhoto($champ_id){
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM images I LEFT OUTER JOIN galeries G ON I.Galerie_id=G.ID WHERE I.actif = '1' AND (I.Champion_id=:champ_id OR I.Champion2_id=:champ_id) limit 1");
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


	public function getListChampionsOlympics()
	{
		try {
				include("../database/connexion.php");
				$req = $bdd->prepare('select c.*,e.ID as evenementid,e.CategorieID as categorieid,e.DateDebut,r.Rang from champions c,evresultats r,evenements e where r.ChampionID=c.ID and r.EvenementID=e.ID and e.CategorieID=1 and r.Rang=1 group by c.ID order by e.DateDebut desc;');
				$req->execute();
				$champions = array();
				
				while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
					$champ_id =  $row["ID"];
					$req1 = $bdd->prepare('SELECT COUNT(*) as total FROM `evresultats` WHERE ChampionID=:cid');
					$req1->bindValue('cid',$champ_id, PDO::PARAM_INT);
					$req1->execute();
					$row1  = $req1->fetch(PDO::FETCH_ASSOC);

					$req12 = $bdd->prepare('SELECT COUNT(*) as total FROM `images` WHERE Champion_id=:cid or Champion2_id=:cid');
					$req12->bindValue('cid',$champ_id, PDO::PARAM_INT);
					$req12->execute();
					$row12  = $req12->fetch(PDO::FETCH_ASSOC);

					$req13 = $bdd->prepare('SELECT COUNT(*) as total FROM `videos` WHERE Champion_id=:cid');
					$req13->bindValue('cid',$champ_id, PDO::PARAM_INT);
					$req13->execute();
					$row13  = $req13->fetch(PDO::FETCH_ASSOC);

					$req14 = $bdd->prepare('SELECT COUNT(*) as total FROM `news` WHERE championID=:cid');
					$req14->bindValue('cid',$champ_id, PDO::PARAM_INT);
					$req14->execute();
					$row14  = $req14->fetch(PDO::FETCH_ASSOC);
		  
					$row["t_videos"]=$row13["total"];
					$row["t_photos"]=$row12["total"];
					$row["t_res"]=$row1["total"];
					$row["t_news"]=$row14["total"];

					
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

	
	public function getTotalChampionsParPays()
	{
		try {
				include("../database/connexion.php");
				//$req = $bdd->prepare('SELECT distinct c.PaysID,c.ID,c.Nom FROM `champions` c,evresultats r where c.ID=r.ChampionID and r.Rang<=3 ORDER BY `c`.`PaysID` ASC');
				//SELECT distinct p.ID,p.NomPays,c.ID,c.Nom FROM `champions` c,evresultats r,pays p where c.ID=r.ChampionID and (p.Abreviation=c.PaysID or p.Abreviation_2=c.PaysID or p.Abreviation_3=c.PaysID or p.Abreviation_4=c.PaysID or p.Abreviation_5=c.PaysID) and r.Rang<=3 ORDER BY p.NomPays ASC;
				//SELECT count(p.ID) as total,p.NomPays FROM `champions` c,evresultats r,pays p where c.ID=r.ChampionID and (p.Abreviation=c.PaysID or p.Abreviation_2=c.PaysID or p.Abreviation_3=c.PaysID or p.Abreviation_4=c.PaysID or p.Abreviation_5=c.PaysID) and r.Rang<=3 group by p.ID ORDER BY p.NomPays ASC;
				$req = $bdd->prepare('SELECT count( DISTINCT c.ID) as total,p.NomPays,p.ID FROM `champions` c,evresultats r,pays p where c.ID=r.ChampionID and (p.Abreviation=c.PaysID or p.Abreviation_2=c.PaysID or p.Abreviation_3=c.PaysID or p.Abreviation_4=c.PaysID or p.Abreviation_5=c.PaysID) and r.Rang<=3 group by p.ID ORDER BY p.NomPays ASC;;');
				$req->execute();
				$res = array();
				
				while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
					array_push($res, $row);
				}
				$bdd=null;
				return array('validation'=>true,'donnees'=>$res,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	
	public function getListChampionsParPays($paysab1,$paysab2,$paysab3,$paysab4,$paysab5)
	{
		
		function array_msort($array, $cols)
	{
		$colarr = array();
		foreach ($cols as $col => $order) {
			$colarr[$col] = array();
			foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
		}
		$eval = 'array_multisort(';
		foreach ($cols as $col => $order) {
			$eval .= '$colarr[\''.$col.'\'],'.$order.',';
		}
		$eval = substr($eval,0,-1).');';
		eval($eval);
		$ret = array();
		foreach ($colarr as $col => $arr) {
			foreach ($arr as $k => $v) {
				$k = substr($k,1);
				if (!isset($ret[$k])) $ret[$k] = $array[$k];
				$ret[$k][$col] = $array[$k][$col];
			}
		}
		return $ret;

	}
		try {
				include("../database/connexion.php");
				//$req = $bdd->prepare('SELECT distinct c.PaysID,c.ID,c.Nom FROM `champions` c,evresultats r where c.ID=r.ChampionID and r.Rang<=3 ORDER BY `c`.`PaysID` ASC');
								
				$req = $bdd->prepare('SELECT distinct c.*  FROM `champions` c,evresultats r where c.ID=r.ChampionID and (c.PaysID=:pays1 or c.PaysID=:pays2 or c.PaysID=:pays3 or c.PaysID=:pays4 or c.PaysID=:pays5) and r.Rang<=3  ORDER BY `c`.`PaysID` ASC;');
				$req->bindValue('pays1',$paysab1, PDO::PARAM_STR);
				$req->bindValue('pays2',$paysab2, PDO::PARAM_STR);
				$req->bindValue('pays3',$paysab3, PDO::PARAM_STR);
				$req->bindValue('pays4',$paysab4, PDO::PARAM_STR);
				$req->bindValue('pays5',$paysab5, PDO::PARAM_STR);
				$req->execute();
				$res = array();
				
				while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
					$req1 = $bdd->prepare('SELECT COUNT(*) as total FROM `evresultats` WHERE ChampionID=:cid');
					$req1->bindValue('cid',$row["ID"], PDO::PARAM_INT);
					$req1->execute();
					$row1  = $req1->fetch(PDO::FETCH_ASSOC);

					$req12 = $bdd->prepare('SELECT COUNT(*) as total FROM `images` WHERE Champion_id=:cid or Champion2_id=:cid');
					$req12->bindValue('cid',$row["ID"], PDO::PARAM_INT);
					$req12->execute();
					$row12  = $req12->fetch(PDO::FETCH_ASSOC);

					$req13 = $bdd->prepare('SELECT COUNT(*) as total FROM `videos` WHERE Champion_id=:cid');
					$req13->bindValue('cid',$row["ID"], PDO::PARAM_INT);
					$req13->execute();
					$row13  = $req13->fetch(PDO::FETCH_ASSOC);


					$req14 = $bdd->prepare('SELECT COUNT(*) as total FROM `news` WHERE championID=:cid');
					$req14->bindValue('cid',$row["ID"], PDO::PARAM_INT);
					$req14->execute();
					$row14  = $req14->fetch(PDO::FETCH_ASSOC);

					$row["t_videos"]=$row13["total"];
					$row["t_photos"]=$row12["total"];
					$row["t_res"]=$row1["total"];
					$row["t_news"]=$row14["total"];
					array_push($res, $row);
				}
				$bdd=null;
				$sorted_by_medailles=array_msort($res, array('Nom'=>SORT_ASC));
				return array('validation'=>true,'donnees'=>$sorted_by_medailles,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
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


	public function getChampionByUserId($uid)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare('SELECT * FROM champions   WHERE user_id=:uid limit 1');
	             $req->bindValue('uid',$uid, PDO::PARAM_INT);
	             $req->execute();
				 if($req->rowCount() > 0){
					$champ=self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
					return array('validation'=>true,'donnees'=>$champ,'message'=>'');
				}else{
					return array('validation'=>true,'donnees'=>null,'message'=>'');
				}
	             $bdd=null;
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
	public function getUserChampion($uid)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM champions WHERE user_id=:uid');
	            
				 $req->bindValue('uid',$uid, PDO::PARAM_INT);
	             $req->execute();
	             if($req->rowCount() > 0){
		             $champ=self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
		             return array('validation'=>true,'donnees'=>$champ,'message'=>'');
	             }else{
					return array('validation'=>true,'donnees'=>null,'message'=>'');
				 }
	             $bdd=null;
	         }
	     catch(Exception $e)
			 {
			 	die('Erreur : ' . $e->getMessage());
	         }
	}

	public function getUserChampions($uid)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT * FROM champions WHERE user_id=:uid');
				 $champions = array();
				 $req->bindValue('uid',$uid, PDO::PARAM_INT);
	             $req->execute();
				 if($req->rowCount() > 0){
					while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {   
						$champ=self::constructWithArray($row);
						array_push($champions, $champ);
					}
					return array('validation'=>true,'donnees'=>$champions,'message'=>'');
				}
				 else{
					return array('validation'=>true,'donnees'=>null,'message'=>'');
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
				 $req = $bdd->prepare('SELECT DISTINCT E.CategorieID,E.CategorieageID,R.Rang,R.Temps,C.Intitule, C.tri, E.Nom, DateDebut,DATE_FORMAT(E.DateDebut, "%Y") AS annee, E.ID FROM evcategorieevenement C INNER JOIN evenements E ON C.ID=E.CategorieID
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

	public function addchampion($uid,$nom, $sexe, $pays, $dateNaissance, $lieuNaissance, $taille, $poids, $site, $nvpays, $dateChangementNat, $lien_equip, $facebook, $equipementier, $instagram, $bio)
{
    try {
        require('../database/connexion.php');

        // Prepare the SQL insert statement
        $req = $bdd->prepare("INSERT INTO champions (Nom, Sexe,user_id, PaysID, DateNaissance, LieuNaissance, Taille, Poids, Site, NvPaysID, DateChangementNat, Lien_site_équipementier	, Facebook, Equipementier, Instagram, Bio,Visible) 
                               VALUES (:nom, :sexe,:user_id, :pays, :dateNaissance, :lieuNaissance, :taille, :poids, :site,  :nvpays, :dateChangementNat, :lien_equip, :facebook, :equipementier, :instagram, :bio,0)");

        // Bind the parameters
        $req->bindValue('nom', $nom, PDO::PARAM_STR);
        $req->bindValue('sexe', $sexe, PDO::PARAM_STR);
        $req->bindValue('pays', $pays, PDO::PARAM_STR);
		$req->bindValue('user_id', $uid, PDO::PARAM_INT);
        $req->bindValue('dateNaissance', $dateNaissance, PDO::PARAM_STR);
        $req->bindValue('lieuNaissance', $lieuNaissance, PDO::PARAM_STR);
        $req->bindValue('taille', $taille, PDO::PARAM_INT);
        $req->bindValue('poids', $poids, PDO::PARAM_INT);
        $req->bindValue('site', $site, PDO::PARAM_STR);
        $req->bindValue('nvpays', $nvpays, PDO::PARAM_STR);
        $req->bindValue('dateChangementNat', $dateChangementNat, PDO::PARAM_STR);
        $req->bindValue('lien_equip', $lien_equip, PDO::PARAM_STR);
        $req->bindValue('facebook', $facebook, PDO::PARAM_STR);
        $req->bindValue('equipementier', $equipementier, PDO::PARAM_STR);
        $req->bindValue('instagram', $instagram, PDO::PARAM_STR);
        $req->bindValue('bio', $bio, PDO::PARAM_STR);

        // Execute the query
        $req->execute();

		$champID = $bdd->lastInsertId();

		$req5 = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('ajout', :user_id, :champion_id)");
            $req5->bindValue('user_id',$uid, PDO::PARAM_STR);
            $req5->bindValue('champion_id',$champID, PDO::PARAM_INT);
            $req5->execute();
        // Return success message
        return array('validation' => true, 'message' => 'Champion added successfully.');

        // Close the database connection
        $bdd = null;
    } catch (Exception $e) {
        // Handle errors
        return array('validation' => false, 'message' => 'Error: ' . $e->getMessage());
    }
}


public function updatechampion($cid,$uid,$nom, $sexe, $pays, $dateNaissance, $lieuNaissance, $taille, $poids, $site, $nvpays, $dateChangementNat, $lien_equip, $facebook, $equipementier, $instagram, $bio)
{
    try {
        require('../database/connexion.php');

        $req = $bdd->prepare("UPDATE champions SET 
            Nom = :nom,
            Sexe = :sexe,
            PaysID = :pays,
            DateNaissance = :dateNaissance,
            LieuNaissance = :lieuNaissance,
            Taille = :taille,
            Poids = :poids,
            Site = :site,
            NvPaysID = :nvpays,
            DateChangementNat = :dateChangementNat,
            Lien_site_équipementier = :lien_equip,
            Facebook = :facebook,
            Equipementier = :equipementier,
            Instagram = :instagram,
            Bio = :bio,
            Visible = 0
            WHERE ID = :champion_id");

        // Bind the parameters
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->bindValue(':sexe', $sexe, PDO::PARAM_STR);
        $req->bindValue(':pays', $pays, PDO::PARAM_STR);
        $req->bindValue(':dateNaissance', $dateNaissance, PDO::PARAM_STR);
        $req->bindValue(':lieuNaissance', $lieuNaissance, PDO::PARAM_STR);
        $req->bindValue(':taille', $taille, PDO::PARAM_INT);
        $req->bindValue(':poids', $poids, PDO::PARAM_INT);
        $req->bindValue(':site', $site, PDO::PARAM_STR);
        $req->bindValue(':nvpays', $nvpays, PDO::PARAM_STR);
        $req->bindValue(':dateChangementNat', $dateChangementNat, PDO::PARAM_STR);
        $req->bindValue(':lien_equip', $lien_equip, PDO::PARAM_STR);
        $req->bindValue(':facebook', $facebook, PDO::PARAM_STR);
        $req->bindValue(':equipementier', $equipementier, PDO::PARAM_STR);
        $req->bindValue(':instagram', $instagram, PDO::PARAM_STR);
        $req->bindValue(':bio', $bio, PDO::PARAM_STR);
        $req->bindValue(':champion_id', $cid, PDO::PARAM_INT);

        // Execute the query
        $req->execute();

        // Log the update in champion_admin_externe_journal
        $req5 = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('mise_a_jour', :user_id, :champion_id)");
        $req5->bindValue(':user_id', $uid, PDO::PARAM_STR);
        $req5->bindValue(':champion_id', $cid, PDO::PARAM_INT);
        $req5->execute();

        return array('validation' => true, 'message' => 'Champion updated successfully.');

        // Close the database connection
        $bdd = null;
    } catch (Exception $e) {
        // Handle errors
        return array('validation' => false, 'message' => 'Error: ' . $e->getMessage());
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