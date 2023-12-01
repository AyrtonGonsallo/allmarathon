<?php
/**
* 
*/
class champion_admin_externe_palmares 
{
	private $Id;
	private $Rang;
	private $ChampionId;
	private $PoidsId;

	private $Date;
	private $CategorieAge;
	private $CompetitionType;
	private $CompetitionLieu;

	private $CompetitionDepID;
	private $CompetitionRegID;
	private $CompetitionFr;

	public function getId(){
		return $this->Id;
	}

	public function setId($Id){
		$this->Id = $Id;
	}

	public function getRang(){
		return $this->Rang;
	}

	public function setRang($Rang){
		$this->Rang = $Rang;
	}

	public function getChampionId(){
		return $this->ChampionId;
	}

	public function setChampionId($ChampionId){
		$this->ChampionId = $ChampionId;
	}

	public function getPoidsId(){
		return $this->PoidsId;
	}

	public function setPoidsId($PoidsId){
		$this->PoidsId = $PoidsId;
	}

	public function getDate(){
		return $this->Date;
	}

	public function setDate($Date){
		$this->Date = $Date;
	}

	public function getCategorieAge(){
		return $this->CategorieAge;
	}

	public function setCategorieAge($CategorieAge){
		$this->CategorieAge = $CategorieAge;
	}

	public function getCompetitionType(){
		return $this->CompetitionType;
	}

	public function setCompetitionType($CompetitionType){
		$this->CompetitionType = $CompetitionType;
	}

	public function getCompetitionLieu(){
		return $this->CompetitionLieu;
	}

	public function setCompetitionLieu($CompetitionLieu){
		$this->CompetitionLieu = $CompetitionLieu;
	}

	public function getCompetitionDepID(){
		return $this->CompetitionDepID;
	}

	public function setCompetitionDepID($CompetitionDepID){
		$this->CompetitionDepID = $CompetitionDepID;
	}

	public function getCompetitionRegID(){
		return $this->CompetitionRegID;
	}

	public function setCompetitionRegID($CompetitionRegID){
		$this->CompetitionRegID = $CompetitionRegID;
	}

	public function getCompetitionFr(){
		return $this->CompetitionFr;
	}

	public function setCompetitionFr($CompetitionFr){
		$this->CompetitionFr = $CompetitionFr;
	}

	public static function constructWithArray( array $donnees ) {
		$instance = new champion_admin_externe_palmares();
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
	public function addResults($rang,$championID,$evID,$user,$date,$compFr,$temps)
	{
		try {
			require('../database/connexion.php');

			
			

			$req = $bdd->prepare("INSERT INTO champion_admin_externe_palmares (Rang, ChampionID,EvenementID, utilisateur,Date,CompetitionFr,Temps) VALUES(:rang,:championID,:evenementID,:user,:date,:compFr,:temps)");
			$req->bindValue('rang',$rang, PDO::PARAM_INT);
			$req->bindValue('championID',$championID, PDO::PARAM_INT);
			$req->bindValue('evenementID',$evID, PDO::PARAM_INT);
			$req->bindValue('temps',$temps, PDO::PARAM_STR);
			$req->bindValue('date',$date, PDO::PARAM_STR);
			$req->bindValue('user',$user, PDO::PARAM_STR);
			$req->bindValue('compFr',$compFr, PDO::PARAM_STR);
			$req->execute();
			return array('validation'=>true,'message'=>'');
		}

		catch(Exception $e)

		{
			die('Erreur : ' . $e->getMessage());
		}

	}
	public function getAdminResults($champion_id)
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare("SELECT caep.*, c.Nom, e.Intitule FROM champion_admin_externe_palmares caep"
				." LEFT JOIN champions c ON c.ID = caep.ChampionID"
				." LEFT JOIN evcategorieage e ON caep.CategorieAge = e.ID"
				." WHERE caep.ChampionID = :champion_id");
			$req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
			$req->execute();
			$results = array();
			while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
				array_push($results, $row);
			}
			return array('validation'=>true,'donnees'=>$results,'message'=>'');
		}
		catch(Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public function deleteResultAdmin($id)
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare("DELETE FROM champion_admin_externe_palmares WHERE ID = :id LIMIT 1");
			$req->bindValue('id',$id, PDO::PARAM_INT);
			$req->execute();
			return array('validation'=>true,'message'=>'');
		}
		catch(Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		
	}
	public function getResultsPerso($champion_id)
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare("SELECT c.ID, c.Rang, c.Temps, DATE_FORMAT(c.Date, '%Y') AS Date, e.Intitule, c.CompetitionType, c.CompetitionLieu,  c.CompetitionFr, c.CategorieAge FROM champion_admin_externe_palmares c
				
				LEFT JOIN evcategorieage e ON c.CategorieAge = e.ID
				WHERE ChampionID =:champion_id ORDER BY c.Date DESC");
			$req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
			$req->execute();
			$results = array();
			while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
				array_push($results, $row);
			}
			return array('validation'=>true,'donnees'=>$results,'message'=>'');
		}
		catch(Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public function getNumberAdminExternResult()
	{
		try {
			require('../database/connexion.php');
			$req = $bdd->prepare("SELECT count(*) as nbr FROM champion_admin_externe_palmares caep  LEFT JOIN champions c ON c.ID = caep.ChampionID  LEFT JOIN evcategorieage e ON caep.CategorieAge = e.ID ORDER BY caep.ID DESC");
			$req->execute();
			$results = $req->fetch(PDO::FETCH_ASSOC);
			return array('validation'=>true,'donnees'=>$results,'message'=>'');
			$bdd=null;
		}
		catch(Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

}