<?php
class newsgalerie{
	
	private $id;
	private $news_id;
	private $path;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNews_id(){
		return $this->news_id;
	}

	public function setNews_id($news_id){
		$this->news_id = $news_id;
	}

	public function getPath(){
		return $this->path;
	}

	public function setPath($path){
		$this->path = $path;
	}


	public static function constructWithArray( array $donnees ) {
	        $instance = new newsgalerie();
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

	public function getGalerieByNewsID($news_id){
			try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM news_galerie WHERE news_id=:news_id ORDER BY ID ASC");
			             $req->bindValue('news_id',$news_id, PDO::PARAM_INT);
			             $req->execute();
			             $galerie= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $news = self::constructWithArray($row);
							  array_push($galerie, $news);
			             }
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$galerie,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
				
		}
		

	public function addGalerie($news_id,$path){
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("INSERT INTO news_galerie (path,news_id) VALUES (:path,:news_id)");
			             $req->bindValue('news_id',$news_id, PDO::PARAM_INT);
			             $req->bindValue('path',$path, PDO::PARAM_STR);
			             $req->execute();
			             $bdd=null;
			             return array('validation'=>true,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
				
		}

		public function deleteGalerie($news_id,$path){
			try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("DELETE FROM news_galerie WHERE news_id=:news_id AND path=:path");
			             $req->bindValue('news_id',$news_id, PDO::PARAM_INT);
			             $req->bindValue('path',$path, PDO::PARAM_STR);
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