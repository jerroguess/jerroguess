<?php
class VoitureControleur{
	
	private $_voitureManager;
	private $_clientControleur;
	
	public function __construct(VoitureManager $voitureManager, ClientControleur $clientControleur){
		$this->_voitureManager=$voitureManager;
		$this->_clientControleur=$clientControleur;
	}
	
	public function get($immatriculation){
		return $this->_voitureManager->get(htmlspecialchars($immatriculation));
	}
	
	public function getList(){
		$immatriculation = '%';
		if (!empty($_POST['immatriculation'])) {$immatriculation.=htmlspecialchars($_POST['immatriculation']).'%';}
		
		$marque = '%';
		if (!empty($_POST['marque'])) {$marque.=htmlspecialchars($_POST['marque']).'%';}
		
		$modele = '%';
		if (!empty($_POST['modele'])) {$modele.=htmlspecialchars($_POST['modele']).'%';}
			
		$annee = '%';
		if (!empty($_POST['annee'])) {$annee.=htmlspecialchars($_POST['annee']).'%';}		
		
		$kilometrage = '%';
		if (!empty($_POST['kilometrage'])) {$kilometrage.=htmlspecialchars($_POST['kilometrage']).'%';}	
		
		$date_arrivee = '%';
		if (!empty($_POST['date_arrivee'])) {$date_arrivee.=htmlspecialchars($_POST['date_arrivee']).'%';}
		
		$proprietaire = '%';
		if (!empty($_POST['proprietaire'])) {$proprietaire.=htmlspecialchars($_POST['proprietaire']).'%';}
		
		$reparateur = '%';
		if (!empty(	$_POST['reparateur'])) {$reparateur.=htmlspecialchars($_POST['reparateur']).'%';}
		
		$liste_voitures = $this->_voitureManager->getList($immatriculation, $marque, $modele, $annee, $kilometrage, $date_arrivee, $proprietaire, $reparateur);
		return $liste_voitures;
	}
	
	public function addVoiture(){
		$out='';
		if (!empty($_POST['immatriculation']) AND (!empty($_POST['proprietaire'])OR!empty($_POST['numero']))) {
			if(!empty($_POST['numero'])){
				$_POST['proprietaire']=$_POST['numero'];
			}
			
			
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$voiture = new Voiture($_POST);
			$client = new Client($_POST);
			
			$this->_clientControleur->addClient($client);
			
			if (!$this->_voitureManager->exists($voiture)) {
				if($this->_voitureManager->add($voiture)){
					$out='La voiture immatriculée '.htmlspecialchars($_POST['immatriculation']).' a bien été ajoutée.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : cette immatriculation est déjà prise ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function editVoiture(){		
		$out='';
		if (!empty($_POST['immatriculation']) AND (!empty($_POST['proprietaire'])OR!empty($_POST['numero']))) {
			if(!empty($_POST['numero'])){
				$_POST['proprietaire']=$_POST['numero'];
			}
			
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$voiture = new Voiture($_POST);
			$client = new Client($_POST);
			
			$this->_clientControleur->addClient($client);
			
			if ($this->_voitureManager->exists($voiture)) {
				if($this->_voitureManager->update($voiture)){
					$out='La voiture immatriculée '.htmlspecialchars($_POST['immatriculation']).' a bien été modifié.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : cette immatriculation n\'existe pas ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function deleteVoiture(){
			$voiture = $this->get($_GET['immatriculation']);		
		return ($this->_voitureManager->delete($voiture))?'La voiture immatriculée '.$voiture->immatriculation().' a bien été supprimée.':'OUPS ! Il y a eu un problème.'; 
	}
	
}
?>