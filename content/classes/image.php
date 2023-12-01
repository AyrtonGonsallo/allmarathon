<?php 
class image{
	
	private $id;
	private $nom;
	private $Champion_id;
	private $Champion2_id;
	private $Evenement_id;
	private $News_id;
	private $Galerie_id;
	private $Technique_id;
	private $Description;
	private $actif;

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

	public function getChampion_id(){
		return $this->Champion_id;
	}

	public function setChampion_id($Champion_id){
		$this->Champion_id = $Champion_id;
	}

	public function getChampion2_id(){
		return $this->Champion2_id;
	}

	public function setChampion2_id($Champion2_id){
		$this->Champion2_id = $Champion2_id;
	}

	public function getEvenement_id(){
		return $this->Evenement_id;
	}

	public function setEvenement_id($Evenement_id){
		$this->Evenement_id = $Evenement_id;
	}

	public function getNews_id(){
		return $this->News_id;
	}

	public function setNews_id($News_id){
		$this->News_id = $News_id;
	}

	public function getGalerie_id(){
		return $this->Galerie_id;
	}

	public function setGalerie_id($Galerie_id){
		$this->Galerie_id = $Galerie_id;
	}

	public function getTechnique_id(){
		return $this->Technique_id;
	}

	public function setTechnique_id($Technique_id){
		$this->Technique_id = $Technique_id;
	}

	public function getDescription(){
		return $this->Description;
	}

	public function setDescription($Description){
		$this->Description = $Description;
	}

	public function getActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new image();
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

	public function add_image_admin($image,$champion_id)
		{
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("INSERT INTO images (Nom,Galerie_id,champion_id) VALUES (:image,'26',:champion_id)");
	             $req->bindValue('image',$image, PDO::PARAM_STR);
	             $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->execute();
				 return array('validation'=>true,'message'=>'');
				 $bdd=null;
	        }
	        catch(Exception $e)
	        {
				die('Erreur : ' . $e->getMessage());
	        }
		}

		public function delete_image_admin($image,$champion_id)
		{
			try {
			require('../database/connexion.php');
			$req = $bdd->prepare("DELETE FROM images WHERE Nom = :image AND champion_id=:champion_id AND Galerie_id='26' LIMIT 1");
			$req->bindValue('image',$image, PDO::PARAM_INT);
			$req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
			$req->execute();
			return array('validation'=>true,'message'=>'');
			$bdd=null;
			}
			catch(Exception $e)
			{
				die('Erreur : ' . $e->getMessage());
			}
		}

		public function getImagesByChampion($champion_id)
		{
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT I.ID AS imageID, I.Nom, I.Champion_id, I.Champion2_id, I.Evenement_id, I.News_id, I.Galerie_id, I.Technique_id, I.Description, I.actif FROM images I LEFT OUTER JOIN galeries G ON I.Galerie_id=G.ID WHERE I.Champion_id=:champion_id OR I.Champion2_id=:champion_id");
				 $req->bindValue('champion_id',$champion_id, PDO::PARAM_INT);
	             $req->execute();
	             $images= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
					  array_push($images, $row);
	             }
	             return array('validation'=>true,'donnees'=>$images,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
			
		}
		public function modif_image_admin($id,$description,$actif)
		{
			try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("UPDATE images SET Description=:description,actif='".$actif."' WHERE ID=:id");
	             $req->bindValue('id',$id, PDO::PARAM_INT);
	             $req->bindValue('description',$description, PDO::PARAM_STR);
	             $req->execute();
				 return array('validation'=>true,'message'=>'');
				 $bdd=null;
	        }
	        catch(Exception $e)
	        {
				die('Erreur : ' . $e->getMessage());
	        }
		}

		public function getNumberImages()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM images");
	             $req->execute();
	             $images= $req->fetch(PDO::FETCH_ASSOC);
	             return array('validation'=>true,'donnees'=>$images,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getImagesByTechnique($tech)
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT * FROM images I INNER JOIN galeries G ON I.Galerie_id=G.ID WHERE I.Technique_id=:tech");
				 $req->bindValue('tech',$tech, PDO::PARAM_INT);
	             $req->execute();
	             $images= array();
	             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) { 
	                $image = self::constructWithArray($row);
	                array_push($images, $image);
	             }
	             return array('validation'=>true,'donnees'=>$images,'message'=>'');
	             $bdd=null;
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
		
	}
}