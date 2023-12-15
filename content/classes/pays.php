<?php
class pays{
	private $ID;
	private $Abreviation;
	private $Abreviation_2;
	private $Abreviation_3;
	private $prefixe;
	private $continent;
	private $texte;
	private $Abreviation_4;
	private $NomPays;
	private $Flag;

	public function getID(){
		return $this->ID;
	}

	public function setID($ID){
		$this->ID = $ID;
	}

	public function getAbreviation(){
		return $this->Abreviation;
	}
	public function getAbreviation2(){
		return $this->Abreviation_2;
	}
	public function getAbreviation3(){
		return $this->Abreviation_3;
	}
	public function getAbreviation4(){
		return $this->Abreviation_4;
	}

	public function setAbreviation($Abreviation){
		$this->Abreviation = $Abreviation;
	}


	public function getNomPays(){
		return $this->NomPays;
	}

	public function setNomPays($NomPays){
		$this->NomPays = $NomPays;
	}

	public function getPrefixe(){
		return $this->prefixe;
	}

	public function setPrefixe($prefixe){
		$this->prefixe = $prefixe;
	}

	public function getContinent(){
		return $this->continent;
	}

	public function setContinent($continent){
		$this->continent = $continent;
	}

	public function getTexte(){
		return $this->texte;
	}

	public function setTexte($texte){
		$this->texte = $texte;
	}

	public function getFlag(){
		return $this->Flag;
	}

	public function setFlag($Flag){
		$this->Flag = $Flag;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new pays();
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

public function getFlagByAbreviation($abv){

	try {
				 include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM pays  WHERE Abreviation=:abv or Abreviation_2=:abv or Abreviation_3=:abv or Abreviation_4=:abv");
				 $req->bindValue('abv',$abv, PDO::PARAM_STR);
	             $req->execute();
	             $pays= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$pays,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}
public function getFlagByName($abv){

	try {
				 include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM pays  WHERE NomPays=:abv");
				 $req->bindValue('abv',$abv, PDO::PARAM_STR);
	             $req->execute();
	             $pays= $req->fetch(PDO::FETCH_ASSOC);
	             $bdd=null;
	             return array('validation'=>true,'donnees'=>$pays,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}
public function getAll(){

	try {
				require('../database/connexion.php');
				$req = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays ASC");
	            $req->execute();
	            $pays= array();
		        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
		        	$p = self::constructWithArray($row);
		            array_push($pays, $p);
		        }
	             $bdd=null;
		        
	             return array('validation'=>true,'donnees'=>$pays,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}
public function getPaysById($id){

	try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM pays  WHERE ID=:id");
				 $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->execute();
	             if($req->rowCount() > 0){
		             $pays=self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
		             return array('validation'=>true,'donnees'=>$pays,'message'=>'');
	             }
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}

public function getAllPaysWithClubs()
{
	try {
				require('../database/connexion.php');
				$req = $bdd->prepare("SELECT P.Abreviation,P.NomPays,P.Flag FROM pays P INNER JOIN clubs C ON C.pays=P.Abreviation ORDER BY NomPays");
	            $req->execute();
	            $pays= array();
		        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
		            array_push($pays, $row);
		        }
	             return array('validation'=>true,'donnees'=>$pays,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
}


}
?>