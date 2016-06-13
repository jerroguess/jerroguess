<?php
class Facture_Intervention
{
	private $_idFacture;
	private $_idIntervention;

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

	public function setIdFacture($idFacture){$this->_idFacture = $idFacture;}
	public function setIdIntervention($idIntervention){$this->_idIntervention = $idIntervention;}
}
?>
