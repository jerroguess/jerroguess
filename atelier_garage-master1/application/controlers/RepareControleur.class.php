<?php
class RepareControleur{
	
	private $_repareManager;	
	
	public function __construct(RepareManager $repareManager){
		$this->_repareManager=$repareManager;
	}
	
	public function get($technicien, $voiture, $dateDebut){
		return $this->_repareManager->get(htmlspecialchars($technicien), htmlspecialchars($voiture), htmlspecialchars($dateDebut));
	}
	
	public function getList(){
		$idFacture = '%';
		if (!empty($_POST['idFacture'])) {$idFacture.=htmlspecialchars($_POST['idFacture']).'%';}
		
		$technicien = '%';
		if (!empty($_POST['technicien'])) {$technicien.=htmlspecialchars($_POST['technicien']).'%';}
		
		$voiture = '%';
		if (!empty($_POST['voiture'])) {$voiture.=htmlspecialchars($_POST['voiture']).'%';}
		
		$dateDebut = '%';
		if (!empty($_POST['dateDebut'])) {$dateDebut.=htmlspecialchars($_POST['dateDebut']).'%';}
		
		$_dateFin = '';
		if (!empty($_POST['dateFin'])) {'%'.$_dateFin.=htmlspecialchars($_POST['dateFin']).'%';}
					
		$liste_repares = $this->_repareManager->getList($technicien, $voiture, $idFacture, $dateDebut, $_dateFin);
		return $liste_repares;
	}
	
	public function addRepare(){
		$out='';
		if (!empty($_POST['technicien']) AND !empty($_POST['voiture']) AND !empty($_POST['dateDebut'])) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$repare = new Repare($_POST);
			
			if (!$this->_repareManager->exists($repare)) {
				if($this->_repareManager->add($repare)){
					$out='La réparation de la voiture immatriculé '.htmlspecialchars($_POST['voiture']).' par le technicient numéro '.htmlspecialchars($_POST['technicien']).' en date du '.htmlspecialchars($_POST['dateDebut']).' a bien été ajoutée.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : cette réparation existe déjà ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function editRepare(){
		$out='';
		if (!empty($_POST['technicien']) AND !empty($_POST['voiture']) AND !empty($_POST['dateDebut'])) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$repare = new Repare($_POST);
			
			if ($this->_repareManager->exists($repare)) {
				if($this->_repareManager->update($repare)){
					$out='La réparation de la voiture immatriculé '.htmlspecialchars($_POST['voiture']).' par le technicient numéro '.htmlspecialchars($_POST['technicien']).' en date du '.htmlspecialchars($_POST['dateDebut']).' a bien été modifiée.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : cette réparation n\'existe pas ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function deleteRepare(){
		$repare = $this->get($_GET['technicien'],$_GET['voiture'],$_GET['dateDebut']);
		return ($this->_repareManager->delete($repare))?'La réparation de la voiture immatriculé '.$repare->voiture().' par le technicient numéro '.$repare->technicien().' en date du '.$repare->dateDebut().' a bien été supprimée.':'OUPS ! Il y a eu un problème.'; 
	
	}
}
?>