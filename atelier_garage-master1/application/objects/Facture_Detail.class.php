<?php
class Facture_Detail
{
	private $_idFacture;
	private $_idIntervention;
	private $_prixTotal;
	private $_nom;
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

	public function idFacture(){return $this->_idFacture;}
	public function idIntervention(){return $this->_idIntervention;}
	public function prixTotal(){return $this->_prixTotal;}
	public function nom(){return $this->_nom;}
	public function prix(){return $this->_prix;}

	public function setIdFacture($idFacture){$this->_idFacture = $idFacture;}
	public function setIdIntervention($idIntervention){$this->_idIntervention = $idIntervention;}
	public function setPrixTotal($prixTotal){$this->_prixTotal = $prixTotal;}
	public function setNom($nom){$this->_nom = $nom;}
	public function setPrix($prix){$this->_prix = $prix;}
}
?>
