<?php
class Utilisateur
{
	private $_id;
	private $_pseudo;
	private $_pass;
	private $_privileges;

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
	public function pseudo(){return $this->_pseudo;}
	public function pass(){return $this->_pass;}
	public function privileges(){return $this->_privileges;}

	public function setId($id){$this->_id = $id;}
	public function setPseudo($pseudo){$this->_pseudo = $pseudo;}
	public function setPass($pass){$this->_pass = $pass;}
	public function setPrivileges($privileges){$this->_privileges = $privileges;}
}
?>
