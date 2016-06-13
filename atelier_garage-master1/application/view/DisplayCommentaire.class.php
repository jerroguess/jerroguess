<?php
class DisplayCommentaire{
	
	private $_commentaireControleur;
	
	public function __construct(CommentaireControleur $commentaireControleur){
		$this->_commentaireControleur=$commentaireControleur;
		
	}
	//Commentaire
	public function ajouterCommentaire(){
		$this->_commentaireControleur->addCommentaire();
		header('Location:?page=ficheVoiture&immatriculation='.$_POST['immatriculation'].'');
		exit();
	}
	
	public function supprimerCommentaire(){
		$this->_commentaireControleur->deleteCommentaire();
		header('Location:?page=ficheVoiture&immatriculation='.$_GET['voiture'].'');
		exit();
	}
}
?>