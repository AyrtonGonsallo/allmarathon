<?php
class newscategorie{

	private $id;
	private $intitule;
	private $description;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getIntitule(){
		return $this->intitule;
	}

	public function setIntitule($intitule){
		$this->intitule = $intitule;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new newscategorie();
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

		public function getNewsCategoryByID($id)
			{
				try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM newscategorie  WHERE ID=:id");
			             $req->execute(array('id'=>$id));
			             $news_cat= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$news_cat,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
				

			}
			public function getNewsCategoryByIntitule($intitule)
			{
				try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT id FROM newscategorie  WHERE Intitule LIKE :intitule");
			             $req->execute(array('intitule'=>$intitule));
			             return $req->fetch(PDO::FETCH_ASSOC);
			             // return array('validation'=>true,'donnees'=>$news_cat,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
				

			}
		public function getAllNewsCat(){
			try {
						  include("../database/connexion.php");
						 $req = $bdd->prepare("SELECT * FROM newscategorie");
			             $req->execute();
			             $news_cat= array();
			             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
							  $news = self::constructWithArray($row);
							  array_push($news_cat, $news);
			             }
			             $bdd=null;
			             return array('validation'=>true,'donnees'=>$news_cat,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
				
		}
		

}
?>