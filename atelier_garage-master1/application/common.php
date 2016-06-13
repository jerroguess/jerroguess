<?php
session_start();

// appel des fichiers de classes :


require_once ROOT_PATH.'/application/objects/Voiture.class.php';
require_once ROOT_PATH.'/application/objects/Client.class.php';
require_once ROOT_PATH.'/application/objects/Technicien.class.php';
require_once ROOT_PATH.'/application/objects/Repare.class.php';
require_once ROOT_PATH.'/application/objects/Facture.class.php';
require_once ROOT_PATH.'/application/objects/Intervention.class.php';
require_once ROOT_PATH.'/application/objects/Facture_Detail.class.php';
require_once ROOT_PATH.'/application/objects/Facture_Intervention.class.php';
require_once ROOT_PATH.'/application/objects/Commentaire.class.php';
require_once ROOT_PATH.'/application/objects/Ville.class.php';
require_once ROOT_PATH.'/application/objects/Utilisateur.class.php';

require_once ROOT_PATH.'/application/managers/VoitureManager.class.php';
require_once ROOT_PATH.'/application/managers/ClientManager.class.php';
require_once ROOT_PATH.'/application/managers/TechnicienManager.class.php';
require_once ROOT_PATH.'/application/managers/RepareManager.class.php';
require_once ROOT_PATH.'/application/managers/FactureManager.class.php';
require_once ROOT_PATH.'/application/managers/InterventionManager.class.php';
require_once ROOT_PATH.'/application/managers/Facture_InterventionManager.class.php';
require_once ROOT_PATH.'/application/managers/CommentaireManager.class.php';
require_once ROOT_PATH.'/application/managers/UtilisateurManager.class.php';

require_once ROOT_PATH.'/application/controlers/VoitureControleur.class.php';
require_once ROOT_PATH.'/application/controlers/ClientControleur.class.php';
require_once ROOT_PATH.'/application/controlers/TechnicienControleur.class.php';
require_once ROOT_PATH.'/application/controlers/RepareControleur.class.php';
require_once ROOT_PATH.'/application/controlers/FactureControleur.class.php';
require_once ROOT_PATH.'/application/controlers/InterventionControleur.class.php';
require_once ROOT_PATH.'/application/controlers/Facture_InterventionControleur.class.php';
require_once ROOT_PATH.'/application/controlers/CommentaireControleur.class.php';
require_once ROOT_PATH.'/application/controlers/UtilisateurControleur.class.php';

require_once ROOT_PATH.'/application/view/Display.class.php';
require_once ROOT_PATH.'/application/view/DisplayVoiture.class.php';
require_once ROOT_PATH.'/application/view/DisplayClient.class.php';
require_once ROOT_PATH.'/application/view/DisplayTechnicien.class.php';
require_once ROOT_PATH.'/application/view/DisplayRepare.class.php';
require_once ROOT_PATH.'/application/view/DisplayFacture.class.php';
require_once ROOT_PATH.'/application/view/DisplayIntervention.class.php';
require_once ROOT_PATH.'/application/view/DisplayFacture_Intervention.class.php';
require_once ROOT_PATH.'/application/view/DisplayCommentaire.class.php';
require_once ROOT_PATH.'/application/view/DisplayUtilisateur.class.php';

require_once ROOT_PATH.'/application/connexion.php';



//Connection à la base de donnee ; En cas d'erreur, on affiche un message et on arrête tout grâce aux ligne de code en fin de cette page.
try {
	$bdd = new PDO('mysql:host=localhost;dbname=atelier_garage', $identifiant, $motdepasse);

//instancie les managers, les controleurs, et la vue

$clientManager = new ClientManager($bdd);
$voitureManager = new VoitureManager($bdd);
$technicienManager = new TechnicienManager($bdd);
$repareManager = new RepareManager($bdd);
$factureManager = new FactureManager($bdd);
$interventionManager = new InterventionManager($bdd);
$facture_interventionManager = new Facture_InterventionManager($bdd);
$commentaireManager = new CommentaireManager($bdd);
$utilisateurManager = new UtilisateurManager($bdd);

$clientControleur = new ClientControleur($clientManager);
$voitureControleur = new VoitureControleur($voitureManager, $clientControleur);
$technicienControleur = new TechnicienControleur($technicienManager);
$repareControleur = new RepareControleur($repareManager);
$factureControleur = new FactureControleur($factureManager);
$interventionControleur = new InterventionControleur($interventionManager);
$facture_interventionControleur = new Facture_InterventionControleur($facture_interventionManager);
$commentaireControleur = new CommentaireControleur($commentaireManager);
$utilisateurControleur = new UtilisateurControleur($utilisateurManager);

$display = new Display($utilisateurControleur);
$displayVoiture = new DisplayVoiture($voitureControleur, $clientControleur, $repareControleur, $commentaireControleur, $technicienControleur, $utilisateurControleur);
$displayClient = new DisplayClient($clientControleur, $utilisateurControleur);
$displayTechnicien = new DisplayTechnicien($technicienControleur, $utilisateurControleur);
$displayRepare = new DisplayRepare($repareControleur, $factureControleur, $technicienControleur, $voitureControleur);
$displayFacture = new DisplayFacture($factureControleur, $facture_interventionControleur, $interventionControleur);
$displayIntervention = new DisplayIntervention($interventionControleur);
$displayFacture_Intervention = new DisplayFacture_Intervention($facture_interventionControleur);
$displayCommentaire = new DisplayCommentaire($commentaireControleur);
$displayUtilisateur = new DisplayUtilisateur($utilisateurControleur);
	
	
//recupère le nom de la page demandee, ou redirige vers accueil s'il n'y en a pas
if( isset($_GET['page']) ){
	$page = $_GET['page'];
} else {
	$page = 'accueil';
}
//definie le nom de l'onglet dans le navigateur avec le nom de la page
$titre = 'Garage à tout prix - '.$page;

//Active la fonction correspondant à la page demandee
//Une fonction permet soit le deroulement d'un process invisible, soit l'edition dans la variable $out du contenu de la page (hors header, menu, ou footer)
switch($page){
	case 'accueil': //"Dans le cas où $page==accueil, faire ce qui suit jusqu'au prochain break;
		$out = $display->accueil();
		break;
		
	//utilisateurs
	case 'connexion_form':
		$out = $displayUtilisateur->connexion_form();
		break;
	case 'connexion':
		$out = $displayUtilisateur->connexion();
		break;
	case 'deconnexion':
		$out = $displayUtilisateur->deconnexion();
		break;
	case 'afficherUtilisateurs':
		$out = $displayUtilisateur->afficherUtilisateurs();
		break;
	case 'formAjouterUtilisateur':
		$out = $displayUtilisateur->formAjouterUtilisateur();
		break;
	case 'ajouterUtilisateur':
		$out = $displayUtilisateur->ajouterUtilisateur();
		break;
	case 'formModifierUtilisateur':
		$out = $displayUtilisateur->formModifierUtilisateur();
		break;
	case 'modifierUtilisateur':
		$out = $displayUtilisateur->modifierUtilisateur();
		break;
	case 'supprimerUtilisateur':
		$out = $displayUtilisateur->supprimerUtilisateur();
		break;
	case 'monCompte':
		$out = $displayUtilisateur->monCompte();
		break;
		
	//voitures
	case 'afficherVoitures':
		$out = $displayVoiture->afficherVoitures();
		break;
	case 'formAjouterVoiture':
		$out = $displayVoiture->formAjouterVoiture();
		break;
	case 'ajouterVoiture':
		$out = $displayVoiture->ajouterVoiture();
		break;
	case 'formModifierVoiture':
		$out = $displayVoiture->formModifierVoiture();
		break;
	case 'modifierVoiture':
		$out = $displayVoiture->modifierVoiture();
		break;
	case 'supprimerVoiture':
		$out = $displayVoiture->supprimerVoiture();
		break;
	case 'ficheVoiture':
		$out = $displayVoiture->ficheVoiture();
		break;
		
	//clients
	case 'afficherClients':
		$out = $displayClient->afficherClients();
		break;
	case 'formAjouterClient':
		$out = $displayClient->formAjouterClient();
		break;
	case 'ajouterClient':
		$out = $displayClient->ajouterClient();
		break;
	case 'formModifierClient':
		$out = $displayClient->formModifierClient();
		break;
	case 'modifierClient':
		$out = $displayClient->modifierClient();
		break;
	case 'supprimerClient':
		$out = $displayClient->supprimerClient();
		break;
		
	//techniciens
	case 'afficherTechniciens':
		$out = $displayTechnicien->afficherTechniciens();
		break;
	case 'formAjouterTechnicien':
		$out = $displayTechnicien->formAjouterTechnicien();
		break;
	case 'ajouterTechnicien':
		$out = $displayTechnicien->ajouterTechnicien();
		break;
	case 'formModifierTechnicien':
		$out = $displayTechnicien->formModifierTechnicien();
		break;
	case 'modifierTechnicien':
		$out = $displayTechnicien->modifierTechnicien();
		break;
	case 'supprimerTechnicien':
		$out = $displayTechnicien->supprimerTechnicien();
		break;
		
	//repares
	case 'afficherRepares':
		$out = $displayRepare->afficherRepares();
		break;
	case 'formAjouterRepare':
		$out = $displayRepare->formAjouterRepare();
		break;
	case 'ajouterRepare':
		$out = $displayRepare->ajouterRepare();
		break;
	case 'formModifierRepare':
		$out = $displayRepare->formModifierRepare();
		break;
	case 'modifierRepare':
		$out = $displayRepare->modifierRepare();
		break;
	case 'supprimerRepare':
		$out = $displayRepare->supprimerRepare();
		break;
		
	//factures
	case 'afficherFactures':
		$out = $displayFacture->afficherFactures();
		break;
	case 'formAjouterFacture':
		$out = $displayFacture->formAjouterFacture();
		break;
	case 'ajouterFacture':
		$out = $displayFacture->ajouterFacture();
		break;
	case 'supprimerFacture':
		$out = $displayFacture->supprimerFacture();
		break;
	case 'ficheFacture':
		$out = $displayFacture->ficheFacture();
		break;
		
	//interventions
	case 'afficherInterventions':
		$out = $displayIntervention->afficherInterventions();
		break;
	case 'formAjouterIntervention':
		$out = $displayIntervention->formAjouterIntervention();
		break;
	case 'ajouterIntervention':
		$out = $displayIntervention->ajouterIntervention();
		break;
	case 'formModifierIntervention':
		$out = $displayIntervention->formModifierIntervention();
		break;
	case 'modifierIntervention':
		$out = $displayIntervention->modifierIntervention();
		break;
	case 'supprimerIntervention':
		$out = $displayIntervention->supprimerIntervention();
		break;
		
	//facture_intervention
	case 'ajouterFacture_Intervention':
		$out = $displayFacture_Intervention->ajouterFacture_Intervention();
		break;
	case 'supprimerFacture_Intervention':
		$out = $displayFacture_Intervention->supprimerFacture_Intervention();
		break;
		
	//commentaire
	case 'ajouterCommentaire':
		$out = $displayCommentaire->ajouterCommentaire();
		break;
	case 'supprimerCommentaire':
		$out = $displayCommentaire->supprimerCommentaire();
		break;

		
		

	default:	//cas où le nom de la page ne correspond à aucun cas precedent.
		$out = 'la page '.$page.' n\'éxiste pas';
		$titre = 'erreur';
		break;
}

// appel de la page skeleton.phtml, qui affiche le contenu des page. Jusqu'ici, rien n'est affiche, le contenu est dans $out. 
require_once ROOT_PATH.'/application/templates/skeleton.phtml';



// En cas d'erreur, on affiche un message et on arrête tout
} catch(PDOException $e) {
	$titre = 'erreur';
	$out = $e->getMessage().'<br />'.print_r($bdd->errorInfo(), true);
	require_once ROOT_PATH.'/application/templates/skeleton.phtml';
}
?>