<?php
class FactureControleur{
	
	private $_factureManager;
		
	public function __construct(FactureManager $factureManager){
		$this->_factureManager=$factureManager;
	}
	
	public function get($idFacture){
		return $this->_factureManager->get(htmlspecialchars($idFacture));
	}
	
	public function getList(){
		$idFacture = '%';
		if (!empty($_POST['idFacture'])) {$idFacture.=htmlspecialchars($_POST['idFacture']).'%';}
		
		$prixTotal = '%';
		if (!empty($_POST['prixTotal'])) {$prixTotal.=htmlspecialchars($_POST['prixTotal']).'%';}
		
					
		$liste_factures = $this->_factureManager->getList($idFacture, $prixTotal);
		return $liste_factures;
	}
	
	public function addFacture(){
		$out='';
		if (!empty($_POST['idFacture']) ) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$facture = new Facture($_POST);
			
			if (!$this->_factureManager->exists($facture)) {
				if($this->_factureManager->add($facture)){
					$out='Le facture numéro '.htmlspecialchars($_POST['idFacture']).' a bien été ajoutée.';
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
	
	public function editFacture(){
		$out='';
		if (!empty($_POST['idFacture']) ) {
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}		
			$facture = new Facture($_POST);
			
			if ($this->_factureManager->exists($facture)) {
				if($this->_factureManager->update($facture)){
					$out='Le facture numéro '.htmlspecialchars($_POST['idFacture']).' a bien été modifiée.';
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
	
	public function deleteFacture(){
		$facture = $this->get($_GET['idFacture']);
		return ($this->_factureManager->delete($facture))?'Le facture immatriculée '.$facture->idFacture().' a bien été supprimée.':'OUPS ! Il y a eu un problème.'; 
	}
	
}
?>