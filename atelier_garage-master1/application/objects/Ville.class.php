<?php
class Ville
{
	private $_nom;
	private $_nombre;

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

	public function nom(){return $this->_nom;}
	public function nombre(){return $this->_nombre;}

	public function setNom($nom){$this->_nom = $nom;}
	public function setNombre($nombre){$this->_nombre = $nombre;}
}
?>
