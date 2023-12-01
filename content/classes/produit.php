<?php
class produit{
    private $id;
	private $titre;
	private $href;
	private $img;
	private $nom;
    private $prix;

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getTitre(){
		return $this->titre;
	}

	public function setTitre($titre){
		$this->titre = $titre;
	}

	public function getHref(){
		return $this->href;
	}

	public function setHref($href){
		$this->href = $href;
	}

	public function getImg(){
		return $this->img;
	}

	public function setImg($img){
		$this->img = $img;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($Nom){
		$this->nom = $Nom;
	}
    public function getPrix(){
		return $this->prix;
	}

	public function setPrix($Prix){
		$this->prix = $Prix;
	}

}
?>