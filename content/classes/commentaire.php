<?php
class commentaire{
	
	private $id;
	private $date;
	private $commentaire;
	private $valide;
	private $user_id;
	private $news_id;
	private $video_id;
	private $champion_id;
	private $positif;
	private $negatif;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
	
	public function getCommentaire(){
		return $this->commentaire;
	}

	public function setCommentaire($commentaire){
		$this->commentaire = $commentaire;
	}

	public function getValide(){
		return $this->valide;
	}

	public function setValide($valide){
		$this->valide = $valide;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getNews_id(){
		return $this->news_id;
	}

	public function setNews_id($news_id){
		$this->news_id = $news_id;
	}

	public function getVideo_id(){
		return $this->video_id;
	}

	public function setVideo_id($video_id){
		$this->video_id = $video_id;
	}

	public function getChampion_id(){
		return $this->champion_id;
	}

	public function setChampion_id($champion_id){
		$this->champion_id = $champion_id;
	}

	public function getPositif(){
		return $this->positif;
	}

	public function setPositif($positif){
		$this->positif = $positif;
	}

	public function getNegatif(){
		return $this->negatif;
	}

	public function setNegatif($negatif){
		$this->negatif = $negatif;
	}


	public static function constructWithArray( array $donnees ) {
	        $instance = new commentaire();
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

	public function getCommentairesNews(){
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM commentaires  WHERE valide = '1' ORDER BY date DESC LIMIT 8");
			             $req->execute();
			             $commentaires_news= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $com = self::constructWithArray($row);
							  array_push($commentaires_news, $com);
			             }
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$commentaires_news,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		
	}

	// public function getCommentairesChampion($champ_id){
	// 	try {
	// 					 require('/../database/connexion.php');
	// 					 $req = $bdd->prepare("SELECT  *  FROM commentaires  WHERE champion_id=:champ_id  ORDER BY date DESC");
	// 		             $req->bindValue('champ_id',$champ_id, PDO::PARAM_INT);
	// 		             // $req->bindValue('offset',$page*5, PDO::PARAM_INT);
	// 		             $req->execute();
	// 		             $commentaires_champ= array();
	// 		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
	// 						  $com = self::constructWithArray($row);
	// 						  array_push($commentaires_champ, $com);
	// 		             }
	// 		             return array('validation'=>true,'donnees'=>$commentaires_champ,'message'=>'');
	// 		        }
			       
	// 		        catch(Exception $e)
	// 		        {
	// 		            die('Erreur : ' . $e->getMessage());
	// 		        }
		
	// }

	public function getCommentairesByUser($user_id){

		
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT  *  FROM commentaires  WHERE user_id=".$user_id."  ORDER BY date DESC");
			             $req->execute();
			             $commentaires_champ= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $com = self::constructWithArray($row);
							  array_push($commentaires_champ, $com);
			             }
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$commentaires_champ,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		
	}

	public function getCommentaires($news_id,$video_id,$champion_id){

		if($news_id!=0) { $condition="news_id=".$news_id;}
		elseif ($video_id!=0) {
			$condition="video_id=".$video_id;
		}
		elseif ($champion_id!=0) {
			$condition="champion_id=".$champion_id;
		}

		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT  *  FROM commentaires  WHERE ".$condition."  ORDER BY date DESC");
			             $req->execute();
			             $commentaires_champ= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $com = self::constructWithArray($row);
							  array_push($commentaires_champ, $com);
			             }
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$commentaires_champ,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		
	}
	public function addCommentaire($user_id,$commentaire,$date_com,$news_id,$video_id,$champion_id){
		try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("INSERT INTO commentaires (user_id,commentaire,date,news_id,video_id,champion_id) VALUES (:user_id,:commentaire,:date_com,:news_id,:video_id,:champion_id)");
			             $req->bindValue('user_id',$user_id, PDO::PARAM_INT);
			             $req->bindValue('news_id',$news_id, PDO::PARAM_INT); 
			             $req->bindValue('video_id',$video_id, PDO::PARAM_INT);
			             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
			             $req->bindValue('commentaire',$commentaire, PDO::PARAM_STR);
			             $req->bindValue('date_com',$date_com, PDO::PARAM_STR);
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