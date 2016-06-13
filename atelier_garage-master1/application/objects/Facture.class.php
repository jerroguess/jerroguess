<?php
class Facture
{
	private $_idFacture;
	private $_prixTotal;

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
	public function prixTotal(){return $this->_prixTotal;}
	public function prix(){return $this->_prix;}

	public function setIdFacture($idFacture){$this->_idFacture = $idFacture;}
	public function setPrixTotal($prixTotal){$this->_prixTotal = $prixTotal ?: 0;}
}
?>
