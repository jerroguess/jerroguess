<?php
class Intervention
{
	private $_id;
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

	public function id(){return $this->_id;}
	public function nom(){return $this->_nom;}
	public function prix(){return $this->_prix;}

	public function setId($id){$this->_id = $id;}
	public function setNom($nom){$this->_nom = $nom;}
	public function setPrix($prix){$this->_prix = $prix;}
}
?>
