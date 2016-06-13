<?php
class Display{
	
	private $_utilisateurControleur;
	
	public function __construct(UtilisateurControleur $utilisateurControleur){
		$this->_utilisateurControleur=$utilisateurControleur;
		
	}
	
	public function accueil(){
		if ($this->_utilisateurControleur->isConnected()){
			$out = '<h1>Tableau de bord</h1>
					<div id="tableau_de_bord" class="largeCenter">
						<a href="?page=afficherVoitures" title="Voitures" ><img src="src/img/voiture.png" alt="Voitures" id="voitures" /></a>
						<a href="?page=afficherClients" title="Clients" ><img src="src/img/client.png" alt="Clients" id="clients" /></a>
						<a href="?page=afficherTechniciens" title="Techniciens" ><img src="src/img/technicien.png" alt="Techniciens" id="techniciens" /></a></div><div id="tableau_de_bord" class="largeCenter">
						<a href="?page=afficherRepares" title="Repares" ><img src="src/img/repare.png" alt="Repares" id="repare" /></a>
						<a href="?page=afficherFactures" title="Factures" ><img src="src/img/facture.png" alt="Factures" id="facture" /></a>
						<a href="?page=afficherInterventions" title="Interventions" ><img src="src/img/intervention.png" alt="Interventions" id="interventions" /></a>';
			$out.=($_SESSION['Privileges']==3)?'<a href="?page=afficherUtilisateurs" title="Utilisateurs" ><img src="src/img/utilisateur.png" alt="Utilisateurs" id="utilisateurs" /></a>':'';
			$out.='	</div>';
			return $out;
		}else{
			//ON redirige vers le formulaire de connexion
			header ('Location: ?page=connexion_form');
			exit();
		}
		return $out;
	}
	
}
?>