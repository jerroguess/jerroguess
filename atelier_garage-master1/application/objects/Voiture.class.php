<?php
class Voiture
{
	private $_immatriculation;
	private $_marque;
	private $_modele;
	private $_annee;
	private $_kilometrage;
	private $_date_arrivee;
	private $_proprietaire;
	private $_type;
	private $_prix;

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

	public function immatriculation(){return $this->_immatriculation;}
	public function marque(){return $this->_marque;}
	public function modele(){return $this->_modele;}
	public function annee(){return $this->_annee;}
	public function kilometrage(){return $this->_kilometrage;}
	public function date_arrivee(){return $this->_date_arrivee;}
	public function proprietaire(){return $this->_proprietaire;}
	public function type(){return $this->_type;}
	public function prix(){return $this->_prix;}

	public function setImmatriculation($immatriculation){$this->_immatriculation = $immatriculation;}
	public function setMarque($marque){$this->_marque = $marque;}
	public function setModele($modele){$this->_modele = $modele;}
	public function setAnnee($annee){$this->_annee = $annee;}
	public function setKilometrage($kilometrage){$this->_kilometrage = $kilometrage;}
	public function setDate_arrivee($date_arrivee){$this->_date_arrivee = $date_arrivee ?: date('Y-m-d');}
	public function setProprietaire($proprietaire){$this->_proprietaire = $proprietaire;}
	public function setType($type){$this->_type = $type;}
	public function setPrix($prix){$this->_prix = $prix;}
}
?>
