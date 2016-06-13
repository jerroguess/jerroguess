<?php
class ClientControleur{
	
	private $_clientManager;
		
	public function __construct(ClientManager $clientManager){
		$this->_clientManager=$clientManager;
	}
	
	public function get($numero){
		return $this->_clientManager->get(htmlspecialchars($numero));
	}
	
	public function getList($critereTri){
		$numero = '%';
		if (!empty($_POST['numero'])) {$numero.=htmlspecialchars($_POST['numero']).'%';}
		
		$nom = '%';
		if (!empty($_POST['nom'])) {$nom.=htmlspecialchars($_POST['nom']).'%';}
		
		$prenom = '%';
		if (!empty($_POST['prenom'])) {$prenom.=htmlspecialchars($_POST['prenom']).'%';}
			
		$adresse = '%';
		if (!empty($_POST['adresse'])) {$adresse.=htmlspecialchars($_POST['adresse']).'%';}		
		
		$referant = '%';
		if (!empty($_POST['referant'])) {$referant.=htmlspecialchars($_POST['referant']).'%';}	
		
		$liste_clients = $this->_clientManager->getList($numero, $nom, $prenom, $adresse, $referant, 'nom');
		return $liste_clients;
	}
	
	public function addClient(){
		$out='';
		if (!empty($_POST['numero']) ) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$client = new Client($_POST);
			
			if (!$this->_clientManager->exists($client)) {
				if($this->_clientManager->add($client)){
					$out='Le client numéro '.htmlspecialchars($_POST['numero']).' a bien été ajouté.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : ce numéro est déjà pris ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function editClient(){
		$out='';
		if (!empty($_POST['numero']) ) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$client = new Client($_POST);
			
			if ($this->_clientManager->exists($client)) {
				if($this->_clientManager->update($client)){
					$out='Le client numéro '.htmlspecialchars($_POST['numero']).' a bien été modifié.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : ce numéro n\'existe pas ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function deleteClient(){
		$client = $this->get($_GET['numero']);
		return ($this->_clientManager->delete($client))?'Le client immatriculée '.$client->numero().' a bien été supprimé.':'OUPS ! Il y a eu un problème.'; 
	}
	
	public function getVilles(){
		
		$nom = '%';
		if (!empty($_POST['nomVille'])) {$nom.=htmlspecialchars($_POST['nomVille']).'%';}
		
		$nombre = '%';
		if (!empty($_POST['nombre'])) {$nombre.=htmlspecialchars($_POST['nombre']).'%';}
		return $this->_clientManager->getVilles($nom,$nombre);
	}
}
?>