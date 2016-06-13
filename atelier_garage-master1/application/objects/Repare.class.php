<?php
class Repare
{
	private $_idFacture;
	private $_technicien;
	private $_voiture;
	private $_dateDebut;
	private $_dateFin;
	
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

	public function idFacture(){return $this->_idFacture;}
	public function technicien(){return $this->_technicien;}
	public function voiture(){return $this->_voiture;}
	public function dateDebut(){return $this->_dateDebut;}
	public function dateFin(){return $this->_dateFin;}

	public function setIdFacture($idFacture){$this->_idFacture = $idFacture;}
	public function setTechnicien($technicien){$this->_technicien = $technicien;}
	public function setVoiture($voiture){$this->_voiture = $voiture;}
	public function setDateDebut($dateDebut){$this->_dateDebut = $dateDebut ?: date('Y-m-d');}
	public function setDateFin($dateFin){$this->_dateFin = $dateFin ?: null;}
}
?>
