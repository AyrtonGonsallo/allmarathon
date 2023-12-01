<?php
class format_remplacement{
    private $champion_courant;
	private $remplacants;
	private $id;

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getChampion_courant(){
		return $this->champion_courant;
	}

	public function setChampion_courant($champion_courant){
		$this->champion_courant = $champion_courant;
	}

	public function getRemplacants(){
		return $this->remplacants;
	}
    public function setRemplacants($remplacant){
		$this->remplacants = $remplacant;
	}


}
?>