<?php
class DisplayFacture_Intervention{
	
	private $_facture_interventionControleur;
	
	public function __construct(Facture_InterventionControleur $facture_interventionControleur){
		$this->_facture_interventionControleur=$facture_interventionControleur;		
	}
	//Facture_Intervention
	public function ajouterFacture_Intervention(){
		$this->_facture_interventionControleur->addFacture_Intervention($_POST['idFacture'],$_POST['idIntervention']);
		header('Location:?page=ficheFacture&idFacture='.$_POST['idFacture'].'');
		exit();
	}
	
	public function supprimerFacture_Intervention(){
		$this->_facture_interventionControleur->deleteFacture_Intervention();
		header('Location:?page=ficheFacture&idFacture='.$_GET['idFacture'].'');
		exit();
	}
}
?>