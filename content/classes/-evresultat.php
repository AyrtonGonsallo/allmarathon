<?php

class evresultat{
	
	private $id;
	private $Rang;
	private $Commentaire;
	private $Club;
	private $EvenementID;
	private $ChampionID;
	private $equipeID;
	private $PoidID;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getRang(){
		return $this->Rang;
	}

	public function setRang($Rang){
		$this->Rang = $Rang;
	}

	public function getCommentaire(){
		return $this->Commentaire;
	}

	public function setCommentaire($Commentaire){
		$this->Commentaire = $Commentaire;
	}

	public function getClub(){
		return $this->Club;
	}

	public function setClub($Club){
		$this->Club = $Club;
	}

	public function getEvenementID(){
		return $this->EvenementID;
	}

	public function setEvenementID($EvenementID){
		$this->EvenementID = $EvenementID;
	}

	public function getChampionID(){
		return $this->ChampionID;
	}

	public function setChampionID($ChampionID){
		$this->ChampionID = $ChampionID;
	}

	public function getEquipeID(){
		return $this->equipeID;
	}

	public function setEquipeID($equipeID){
		$this->equipeID = $equipeID;
	}

	public function getPoidID(){
		return $this->PoidID;
	}

	public function setPoidID($PoidID){
		$this->PoidID = $PoidID;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new evresultat();
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
	public function getEvResultByEventID($event_id,$sexe,$poidId)
	{
		try {
			//SELECT C.NvPaysID ,C.Nom,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=6717 and C.DateChangementNat<E.DateDebut and C.NvPaysID!='';
				
			//les resulats ceux qui ont un 2Eme pays comptant pour la nouvelle nationalité
			include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT R.*,C.Nom,C.ID AS idChampion,C.NVPaysID as PaysID FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE C.Sexe=:sexe AND R.EvenementID=:event_id AND C.DateChangementNat<E.DateDebut and C.NvPaysID!='' AND R.PoidID LIKE :poidId ORDER BY R.PoidID,R.Rang ASC");
	             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req->bindValue('sexe',$sexe, PDO::PARAM_STR);
	             $req->bindValue('poidId',$poidId, PDO::PARAM_STR);
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  // $event_res = self::constructWithArray($row);
					  array_push($liste, $row);
	             }
	             
				 //les resulats ceux qui ont un 2Eme pays comptant pour la premiere nationalité
				 $req2 = $bdd->prepare("SELECT R.*,C.Nom,C.ID AS idChampion,C.PaysID as PaysID FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE C.Sexe=:sexe AND R.EvenementID=:event_id AND C.DateChangementNat>E.DateDebut and C.NvPaysID!='' AND R.PoidID LIKE :poidId ORDER BY R.PoidID,R.Rang ASC");
	             $req2->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req2->bindValue('sexe',$sexe, PDO::PARAM_STR);
	             $req2->bindValue('poidId',$poidId, PDO::PARAM_STR);
	             $req2->execute();
	             while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {    
					  // $event_res = self::constructWithArray($row);
					  array_push($liste, $row2);
	             }
				 //les resulats ceux qui n'ont pas de 2eme pays
				 $req3 = $bdd->prepare("SELECT R.*,C.Nom,C.ID AS idChampion,C.PaysID as PaysID FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE C.Sexe=:sexe AND R.EvenementID=:event_id AND C.DateChangementNat>E.DateFin and C.NvPaysID='' AND R.PoidID LIKE :poidId ORDER BY R.PoidID,R.Rang ASC");
	             $req3->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req3->bindValue('sexe',$sexe, PDO::PARAM_STR);
	             $req3->bindValue('poidId',$poidId, PDO::PARAM_STR);
	             $req3->execute();
	             while ( $row3  = $req3->fetch(PDO::FETCH_ASSOC)) {    
					  // $event_res = self::constructWithArray($row);
					  array_push($liste, $row3);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
			
	}
	public function getResultBySexe($event_id,$sexe)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT distinct R.poidId FROM evresultats R INNER JOIN champions C ON R.ChampionID=C.ID WHERE C.Sexe=:sexe AND R.EvenementID=:event_id ORDER BY R.PoidID,R.Rang ASC");
	             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req->bindValue('sexe',$sexe, PDO::PARAM_STR);
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste, $row);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


	
	public function getResultClassement($event_id,$pays_club,$type,$cond)
	{
		if($cond=="pays") {
			$order_cond="C.PaysID";
			$where_con="C.PaysID='".$pays_club."'";
		}else {
			$order_cond="R.Club";
			$where_con="R.Club LIKE '".$pays_club."'";
		}

		if( ($type!="homme") && ($type!="femme") ) $type_cond="";
		else {$type_cond= ($type=="homme") ?" AND C.Sexe = 'M' ":(($type == "femme")?" AND C.Sexe = 'F' ":"");}

		try {
			//SELECT C.NvPaysID ,C.Nom,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=6717 and C.NvPaysID='FRA';
				  include("../database/connexion.php");
				  //ceux qui ont la nationalité comme 1ere nationalité [verifier dateevenenement < datechangementNat]
				 $req = $bdd->prepare("SELECT COUNT(*) AS nb ,C.PaysID,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=:event_id AND E.DateDebut<C.DateChangementNat AND ".$where_con." ".$type_cond." GROUP BY ".$order_cond.",R.Rang");
	             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req->execute();
	             $liste1= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste1, $row);
	             }
				 //ceux qui ont la nationalite comme seconde nationalité [verifier dateevenenement > datechangementNat]
				 $req2 = $bdd->prepare("SELECT COUNT(*) AS nb ,C.NvPaysID as PaysID,R.Club,R.Rang FROM ((evresultats R INNER JOIN champions C ON R.ChampionID=C.ID) INNER JOIN evenements E ON R.EvenementID=E.ID) WHERE R.EvenementID=:event_id AND E.DateDebut>C.DateChangementNat AND C.NvPaysID=:pays_id".$type_cond." GROUP BY ".$order_cond.",R.Rang");
	             $req2->bindValue('event_id',$event_id, PDO::PARAM_INT);
				 $req2->bindValue('pays_id',$pays_club, PDO::PARAM_STR);
	             $req2->execute();
	             while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste1, $row);
	             }
	             $bdd=null;
				 
	                return array('validation'=>true,'donnees'=>$liste1,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
			
	}
	public function getResultClassementByPays($event_id,$type,$cond)
	{
		($cond=="pays") ? $condition_class="C.PaysID" : $condition_class="R.Club";
		if($type=="") $type_cond="";
		else {$type_cond= ($type=="homme") ?" AND C.Sexe = 'M' ":(($type == "femme")?" AND C.Sexe = 'F' ":"");}
		
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT distinct ".$condition_class." FROM evresultats R INNER JOIN champions C ON R.ChampionID=C.ID WHERE R.EvenementID=:event_id ".$type_cond." GROUP BY ".$condition_class.",R.Rang");
	             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
	             $req->execute();
	             $liste= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
					  array_push($liste, $row);
	             }
	             $bdd=null;
	            
	                return array('validation'=>true,'donnees'=>$liste,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}
	public function getPoidsByChampID($champ_id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare('SELECT  DISTINCT PoidID as p FROM evresultats WHERE ChampionID =:champ_id ORDER BY PoidID ASC');
	             $req->bindValue('champ_id', $champ_id, PDO::PARAM_INT);
	             $req->execute();
	             $champ_poids = array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    

	                // $ch_poids = self::constructWithArray($row);

	                 array_push($champ_poids, $row);
	             }
	             $bdd=null;
	                return array('validation'=>true,'donnees'=>$champ_poids,'message'=>'');
	            
	             
	        }
	       
	        catch(Exception $e)

	        {

	            die('Erreur : ' . $e->getMessage());
	        }
		
	}

	public function getNumberEvresultats()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM evresultats");
	             $req->execute();
	             $evresultats= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$evresultats,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

}

?>
