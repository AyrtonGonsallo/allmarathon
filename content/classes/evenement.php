<?php
class evenement{
	
	private $id;
	private $nom;
	private $sexe;
	private $dateDebut;
	private $dateFin;
	private $datePub;
	private $presentation;
	private $visible;
	private $evenements_fils;
	private $type;
	private $document1;
	private $document2;
	private $document3;
	private $document4;
	private $affiche;
	private $telephone;
	private $mail;
	private $contact;
	private $web;
	private $pack;
	private $valider;
	private $video_teaser;
	private $lien_resultats_complet;	
	private $paysId;
	private $categorieId;
	private $categorieageId;
	private $compteur;
	private $insta;
	private $marathon_id;
	private $facebook;
	private $youtube;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	public function getmarathon_id(){
		return $this->marathon_id;
	}

	public function setmarathon_id($marathon_id){
		$this->marathon_id = $marathon_id;
	}
	public function getInsta(){
		return $this->insta;
	}

	public function setInsta($insta){
		$this->insta = $insta;
	}
	public function getFacebook(){
		return $this->facebook;
	}

	public function setFacebook($facebook){
		$this->facebook = $facebook;
	}
	public function getYoutube(){
		return $this->youtube;
	}

	public function setYoutube($youtube){
		$this->youtube = $youtube;
	}
	public function getlien_resultats_complet(){
		return $this->lien_resultats_complet;
	}

	public function setlien_resultats_complet($lien_resultats_complet){
		$this->lien_resultats_complet = $lien_resultats_complet;
	}
	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getSexe(){
		return $this->sexe;
	}

	public function setSexe($sexe){
		$this->sexe = $sexe;
	}

	public function getDateDebut(){
		return $this->dateDebut;
	}

	public function setDateDebut($dateDebut){
		$this->dateDebut = $dateDebut;
	}

	public function getDateFin(){
		return $this->dateFin;
	}

	public function setDateFin($dateFin){
		$this->dateFin = $dateFin;
	}

	public function getDatePub(){
		return $this->datePub;
	}

	public function setDate_pub($datePub){
		$this->datePub = $datePub;
	}

	public function getPresentation(){
		return $this->presentation;
	}
	public function getPack(){
		return $this->pack;
	}
	public function setContact($contact){
		$this->contact = $contact;
	}
	public function setPack($pack){
		$this->pack = $pack;
	}
	public function getContact(){
		return $this->contact;
	}
	public function setPresentation($presentation){
		$this->presentation = $presentation;
	}

	public function getVisible(){
		return $this->visible;
	}

	public function getEvenements_fils(){
		return $this->evenements_fils;
	}

	public function setVisible($visible){
		$this->visible = $visible;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getDocument1(){
		return $this->document1;
	}

	public function setDocument1($document1){
		$this->document1 = $document1;
	}

	public function getDocument2(){
		return $this->document2;
	}

	public function setDocument2($document2){
		$this->document2 = $document2;
	}

	public function getDocument3(){
		return $this->document3;
	}

	public function setDocument3($document3){
		$this->document3 = $document3;
	}

	public function getDocument4(){
		return $this->document4;
	}

	public function setDocument4($document4){
		$this->document4 = $document4;
	}

	public function getAffiche(){
		return $this->affiche;
	}

	public function setEvenements_fils($evenements_fils){
		$this->evenements_fils=$evenements_fils;
	}

	public function setAffiche($affiche){
		$this->affiche = $affiche;
	}

	public function getTelephone(){
		return $this->telephone;
	}

	public function setTelephone($telephone){
		$this->telephone = $telephone;
	}

	public function getMail(){
		return $this->mail;
	}

	public function setMail($mail){
		$this->mail = $mail;
	}

	public function getWeb(){
		return $this->web;
	}

	public function setWeb($web){
		$this->web = $web;
	}

	public function getValider(){
		return $this->valider;
	}

	public function setValider($valider){
		$this->valider = $valider;
	}

	public function getVideo_teaser(){
		return $this->video_teaser;
	}

	public function setVideo_teaser($video_teaser){
		$this->video_teaser = $video_teaser;
	}

	public function getPaysId(){
		return $this->paysId;
	}

	public function setPaysId($paysId){
		$this->paysId = $paysId;
	}

	public function getCategorieId(){
		return $this->categorieId;
	}

	public function setCategorieId($categorieId){
		$this->categorieId = $categorieId;
	}

	public function getCategorieageID(){
		return $this->categorieageId;
	}

	public function setCategorieageID($categorieageId){
		$this->categorieageId = $categorieageId;
	}

	public function getCompteur(){
		return $this->compteur;
	}

	public function setCompteur($compteur){
		$this->compteur = $compteur;
	}

	public static function constructWithArray( array $donnees ) {
	        $instance = new evenement();
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

	public function getEvenementByID($id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM evenements  WHERE id=:id");
	             $req->execute(array('id'=>$id));
	             if($req->rowCount() > 0){
	             $event= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             return array('validation'=>true,'donnees'=>$event,'message'=>'');
	         	}
	         	else{
	             return array('validation'=>false,'message'=>'');

	         	}
	             $bdd=null;
	         	
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getEvenementFilsByID($id)
	{
		try {
				  include("../database/connexion.php");
				 $req = $bdd->prepare("SELECT * FROM evenements_fils  WHERE id=:id");
	             $req->execute(array('id'=>$id));
	             if($req->rowCount() > 0){
	             $event= self::constructWithArray($req->fetch(PDO::FETCH_ASSOC));
	             return array('validation'=>true,'donnees'=>$event,'message'=>'');
	         	}
	         	else{
	             return array('validation'=>false,'message'=>'');

	         	}
	             $bdd=null;
	         	
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}


	public function getHomeEvents()
		{
			try {
					 include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT * FROM evenements WHERE Valider=1 AND DateDebut>=NOW() ORDER BY DateDebut ASC LIMIT 14");
		             $req->execute();
		             $home_events = array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $event = self::constructWithArray($row);
						  array_push($home_events, $event);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$home_events,'message'=>'');
			        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}

	public function getHomeEventsPack(){
		try {
					  include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT * FROM evenements WHERE  Pack=1 ORDER BY DateDebut ASC LIMIT 25");
		             $req->execute();
		             $home_events = array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $event = self::constructWithArray($row);
						  array_push($home_events, $event);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$home_events,'message'=>'');
			        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		
	}
function search_array($tab_pack_a_afficher_fct,$tab_indice_fct)
                    {
                        if(sizeof($tab_pack_a_afficher_fct)>0){
                            $nb_no_match=0;
                            foreach ($tab_pack_a_afficher_fct as $key => $single_line) {
                                if(($single_line['intitule']==$tab_indice_fct['intitule']) && ($single_line['date']==$tab_indice_fct['date']) && ($single_line['ville']==$tab_indice_fct['ville']) )
                                
                                {
                                    $single_line['cat_age']= $single_line['cat_age']." ".$tab_indice_fct['cat_age'];
									$single_line['compteur']= $single_line['compteur']+$tab_indice_fct['compteur'];
                                    $tab_pack_a_afficher_fct[$key]=$single_line;

                                }
                                else{
                                    $nb_no_match++;
                                }

                            }
                            if($nb_no_match==sizeof($tab_pack_a_afficher_fct))
                            {
                               array_push($tab_pack_a_afficher_fct, $tab_indice_fct); 
                            }

                        }
                        else{
                            array_push($tab_pack_a_afficher_fct, $tab_indice_fct);

                        }
                        $bdd=null;
                        return $tab_pack_a_afficher_fct;
                    }

    
    function getDernierResultatsArchive(){
				try {
					  include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT ID,Nom,Sexe,DateDebut,CategorieID FROM evenements WHERE Visible=1 AND YEAR(DateDebut)!=YEAR(NOW()) ORDER BY ID DESC LIMIT 10");
		             $req->execute();
		             $archive_events = array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $event = self::constructWithArray($row);
						  array_push($archive_events, $event);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$archive_events,'message'=>'');
			        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
    	
    }

    function getResultsPerPage($annee,$type,$age,$page){
    	$condition='';
    	if ($annee!=''){$condition.=' AND  YEAR(DateDebut)='.$annee.'';}
        if ($type!='') {$condition.=' AND CategorieID='.$type.' ';}
        if ($age!='') {$condition.=' AND CategorieageID='.$age.' ';}
        try {
					  include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1' ".$condition." ORDER BY DateDebut DESC LIMIT :offset,40");
				 	 // $req->bindValue('condition', $condition, PDO::PARAM_STR);
				 	 $req->bindValue('offset', $page*40, PDO::PARAM_INT);

		             $req->execute();
		             $results = array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
						  array_push($results, $row);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$results,'message'=>'');
			        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }    	
    }

    public function getResultsForNewsletter()
	{       	
        try {
					 require('../database/connexion.php');
					 $req = $bdd->prepare("SELECT CategorieageID,CategorieID,Nom,DateDebut,PaysID,ID,Sexe  FROM evenements WHERE Visible='1' ORDER BY DateDebut DESC LIMIT 0,5");

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
	
    public function getNumberPages($annee,$type,$age,$page,$key_word){
		$condition='';
		if($key_word!=""){
			$key_word="%".strtoupper($key_word)."%";
			$condition=" AND UPPER(e.nom) LIKE :key_word OR UPPER(eve.Intitule) LIKE :key_word OR UPPER(ea.Intitule) LIKE :key_word";
		}else{
			if ($annee!=''){$condition.=' AND  YEAR(DateDebut)='.$annee.'';}
	        if ($type!='') {$condition.=' AND CategorieID='.$type.' ';}
	        if ($age!='') {$condition.=' AND CategorieageID='.$age.' ';}
		}
    	
        try {
					  include("../database/connexion.php");
					 $req = $bdd->prepare("SELECT COUNT(*)  FROM evenements e JOIN evcategorieevenement eve ON e.CategorieID=eve.id JOIN evcategorieage ea ON e.CategorieageID=ea.id WHERE Visible='1' ".$condition);
				 	 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
				 	 $req->execute();
		             $nbr_pages= $req->fetch(PDO::FETCH_ASSOC);
		             $bdd=null;
		             return array('validation'=>true,'donnees'=>$nbr_pages,'message'=>'');
			        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }   
	}



	public function getResultsViaSearch($key_word,$page){
		if($page!=="page_resultat"){
			try {
					  include("../database/connexion.php");
					 $key_word="%".strtoupper($key_word)."%";
					 
					 $req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe FROM evenements e JOIN evcategorieevenement eve ON e.CategorieID=eve.id JOIN evcategorieage ea ON e.CategorieageID=ea.id WHERE UPPER(e.nom) LIKE :key_word  OR CONCAT(UPPER(e.nom), ' ', YEAR(e.dateDebut)) LIKE :key_word  OR  UPPER(eve.Intitule) LIKE :key_word OR UPPER(ea.Intitule) LIKE :key_word ORDER BY e.dateDebut DESC LIMIT :offset,40");
					 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
					 $req->bindValue('offset', $page*40, PDO::PARAM_INT);
		             $req->execute();
		             $articles= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  array_push($articles, $row);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		    }

		    else{
		    	try {
					 require('../database/connexion.php');
					 $key_word="%".strtoupper($key_word)."%";
					 
					 $req = $bdd->prepare("SELECT e.CategorieageID,e.CategorieID,e.Nom,e.DateDebut,e.PaysID,e.ID,e.Sexe FROM evenements e JOIN evcategorieevenement eve ON e.CategorieID=eve.id JOIN evcategorieage ea ON e.CategorieageID=ea.id WHERE (UPPER(e.nom) LIKE :key_word OR CONCAT(UPPER(e.nom), ' ', YEAR(e.dateDebut)) LIKE :key_word  OR UPPER(eve.Intitule) LIKE :key_word OR UPPER(ea.Intitule) LIKE :key_word) AND (e.Visible=1) ORDER BY e.dateDebut DESC");
					 $req->bindValue('key_word', $key_word, PDO::PARAM_STR);
		             $req->execute();
		             $articles= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  array_push($articles, $row);
		             }
		                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
		    }
	}

	public function getEventByTrimestre($trimestre,$type,$cat_age)
	{
		try {
					  include("../database/connexion.php");

					 $condition='';
			        if ($type!='') {$condition.=' AND CategorieID='.$type.' ';}
			        if ($cat_age!='') {$condition.=' AND CategorieageID='.$cat_age.' ';}

					 $avant_hier  = date("Y-m-d",mktime(0, 0, 0, date("n") , date("d")-2, date("Y")));
					 $trimestre_actuel = (date('n')-1)/3;
    				 $trimestre_mois = (date('n')-1)%3;
					 
					 $tr_debut= date("Y-m-d",mktime(0, 0, 0, date("n")+(($trimestre*3)-$trimestre_mois)  , 1, date("Y")));
    				 $tr_fin  = date("Y-m-d",mktime(0, 0, 0, date("n")+(($trimestre*3)+3-$trimestre_mois)  , 0, date("Y")));

    				 $req = $bdd->prepare("SELECT * FROM evenements WHERE Valider=1 ".$condition." AND (DateDebut BETWEEN :tr_debut AND :tr_fin) AND (DateDebut > :today) ORDER BY DateDebut");
					 
					 $req->bindValue('today', date('Y-m-d'), PDO::PARAM_STR);
					 $req->bindValue('tr_debut', $tr_debut, PDO::PARAM_STR);
					 $req->bindValue('tr_fin', $tr_fin, PDO::PARAM_STR);
		             
		             $req->execute();
		             $articles= array();
		             $articles= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $art = self::constructWithArray($row);
						  array_push($articles, $art);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$articles,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}

	public function getEventResults($id)
	{
		try {
					  include("../database/connexion.php");

    				 $req = $bdd->prepare("SELECT * FROM evenements  WHERE ID=:id");
					 $req->bindValue('id', $id, PDO::PARAM_INT);
		             $req->execute();
		             $results= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $art = self::constructWithArray($row);
						  array_push($results, $art);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$results,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}
	
	
	

	public function incrementerCompteur($event_id){
		try {
						 require('../database/connexion.php');
						 $req = $bdd->prepare("UPDATE evenements SET compteur =compteur+1 WHERE  ID=:event_id");
			             $req->bindValue('event_id',$event_id, PDO::PARAM_INT);
			             $req->execute();
			             $bdd=null;
			             return array('validation'=>true,'message'=>'');
			        }
			       
			        catch(Exception $e)
			        {
			            die('Erreur : ' . $e->getMessage());
			        }
		
	}

	public function getNumberEvenements()
	{
		try {
				 require('../database/connexion.php');
				 $req = $bdd->prepare("SELECT count(*) AS nbr FROM evenements");
	             $req->execute();
	             $evenements= $req->fetch(PDO::FETCH_ASSOC);
			     $bdd=null;
	             return array('validation'=>true,'donnees'=>$evenements,'message'=>'');
	        }
	       
	        catch(Exception $e)
	        {
	            die('Erreur : ' . $e->getMessage());
	        }
	}

	public function getMarathonEvents($id)
	{
		try {
					  include("../database/connexion.php");

    				 $req = $bdd->prepare("SELECT * FROM evenements  WHERE marathon_id=:id and Visible=1 order by DateDebut desc");
					 $req->bindValue('id', $id, PDO::PARAM_INT);
		             $req->execute();
		             $results= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $art = self::constructWithArray($row);
						  array_push($results, $art);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$results,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}
	public function getMarathon($id)
	{
		try {
					  include("../database/connexion.php");

    				 $req2 = $bdd->prepare("SELECT * FROM marathons where id=:mar_id  ");
					 $req2->bindValue('mar_id', $id, PDO::PARAM_INT);
					$req2->execute();
					$next_date= $req2->fetch(PDO::FETCH_ASSOC);
					
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$next_date,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}

	public function getNextMarathonEvents($id)
	{
		try {
					  include("../database/connexion.php");

    				 $req = $bdd->prepare("SELECT * FROM evenements  WHERE marathon_id=:id and DateDebut > :today order by DateDebut asc limit 1");
					 $req->bindValue('id', $id, PDO::PARAM_INT);
					 $req->bindValue('today', date('Y-m-d'), PDO::PARAM_STR);
		             $req->execute();
		             $results= array();
		             while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
						  $art = self::constructWithArray($row);
						  array_push($results, $art);
		             }
		             $bdd=null;
		                return array('validation'=>true,'donnees'=>$results,'message'=>'');
		        }
		       
		        catch(Exception $e)
		        {
		            die('Erreur : ' . $e->getMessage());
		        }
	}
}
?>