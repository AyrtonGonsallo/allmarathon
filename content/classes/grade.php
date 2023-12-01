<?php
class grade{
	
	private $id_grade;
	private $nom;

	public function getId_grade(){
		return $this->id_grade;
	}

	public function setId_grade($id_grade){
		$this->id_grade = $id_grade;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new grade();
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

		public function getAll(){

		try {
					require('../database/connexion.php');
					$req = $bdd->prepare("SELECT * FROM grades");
		            $req->execute();
		            $grade= array();
			        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
			        	$p = self::constructWithArray($row);
			            array_push($grade, $p);
			        }
		             return array('validation'=>true,'donnees'=>$grade,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
}

	
}
?>