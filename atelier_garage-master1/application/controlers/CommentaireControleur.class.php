<?php
class CommentaireControleur{
	
	private $_commentaireManager;
		
	public function __construct(CommentaireManager $commentaireManager){
		$this->_commentaireManager=$commentaireManager;
	}
	
	public function get($voiture, $technicien, $datecommentaire){
		return $this->_commentaireManager->get(htmlspecialchars($voiture), htmlspecialchars($technicien), htmlspecialchars($datecommentaire));
	}
	
	public function getList(){
		$voiture = '%';
		if (!empty($_GET['immatriculation'])) {$voiture.=htmlspecialchars($_GET['immatriculation']).'%';}
		
		$technicien = '%';
		if (!empty($_POST['technicien'])) {$technicien.=htmlspecialchars($_POST['technicien']).'%';}
		
		$datecommentaire = '%';
		if (!empty($_POST['datecommentaire'])) {$datecommentaire.=htmlspecialchars($_POST['datecommentaire']).'%';}
		
		$texte = '%';
		if (!empty($_POST['texte'])) {$texte.=htmlspecialchars($_POST['texte']).'%';}
					
		$liste_commentaires = $this->_commentaireManager->getList($voiture, $technicien, $datecommentaire, $texte);
		return $liste_commentaires;
	}
	
	public function addCommentaire(){
		$out='';
		if (!empty($_POST['voiture']) AND !empty($_POST['technicien']) ) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}		
			$commentaire = new Commentaire($_POST);
			
			if (!$this->_commentaireManager->exists($commentaire)) {
				if($this->_commentaireManager->add($commentaire)){
					$out='Le commentaire a bien été ajouté.';
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
	
	public function deleteCommentaire(){
		$commentaire = $this->get($_GET['voiture'],$_GET['technicien'],$_GET['datecommentaire']);
		return ($this->_commentaireManager->delete($commentaire))?'Le commentaire a bien été supprimé.':'OUPS ! Il y a eu un problème.'; 
	}
	
}
?>