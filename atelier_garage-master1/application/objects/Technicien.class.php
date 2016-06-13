<?php
class Technicien
{
	private $_numero;
	private $_nom;
	private $_prenom;
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

	public function numero(){return $this->_numero;}
	public function nom(){return $this->_nom;}
	public function prenom(){return $this->_prenom;}
	public function nombre(){return $this->_nombre;}
	
	public function setNumero($numero){$this->_numero = $numero;}
	public function setNom($nom){$this->_nom = $nom;}
	public function setPrenom($prenom){$this->_prenom = $prenom;}
	public function setNombre($nombre){$this->_nombre = $nombre;}
}
?>
