<?php
class Commentaire
{
	private $_voiture;
	private $_technicien;
	private $_datecommentaire;
	private $_texte;

	# prend en argument un immatriculation de voiture, un numéro de technicien, une date vide, et du texte (string)
	public function __construct(array $donnees){$this->hydrate($donnees);}
	
	public function hydrate(array $donnees)
	{
		foreach($donnees as $key => $value)
		{
			$method='set'.ucfirst($key);
			if(method_exists($this,$method))
				$this->$method($value);
		}
	}	

	public function voiture(){return $this->_voiture;}
	public function technicien(){return $this->_technicien;}
	public function datecommentaire(){return $this->_datecommentaire;}
	public function texte(){return $this->_texte;}

	public function setVoiture($voiture){$this->_voiture = $voiture;}
	public function setTechnicien($technicien){$this->_technicien = $technicien;}
	public function setDatecommentaire($datecommentaire){
		if(empty($datecommentaire)){
			$datecommentaire = new DateTime();
			$this->_datecommentaire = $datecommentaire->format('Y-m-d H:i:s');
			
		} else {
			$this->_datecommentaire = $datecommentaire;
		}
	}
	public function setTexte($texte){$this->_texte = $texte;}
}
?>
