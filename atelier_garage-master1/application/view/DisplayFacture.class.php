<?php
class DisplayFacture{

	private $_factureControleur;
	private $_facture_interventionControleur;
	private $_interventionControleur;
	
	public function __construct(FactureControleur $factureControleur, Facture_InterventionControleur $facture_interventionControleur, InterventionControleur $interventionControleur){
		$this->_factureControleur=$factureControleur;
		$this->_facture_interventionControleur=$facture_interventionControleur;
		$this->_interventionControleur=$interventionControleur;
		
	}
	
	#Affiche la liste des factures précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherFactures(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de recherche de facture
			$out='	<h1>Recherche parmi les factures</h1>
					<div class="pageRecherche">
						<form action="?page=afficherFactures" id="getListFactures_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="idFacture" onblur="isInt(this)" placeholder="Id Facture : " >
								<input type="text" class="table-cell" name="prixTotal" placeholder="Prix Total : " >
								<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
							</div>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de facture
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='	<div class="alignRight">
							<form action="?page=formAjouterFacture" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
			}
			
			
			#Chargement de la liste des factures dans un tableau
			$liste_factures=$this->_factureControleur->getList();
			$out.='		<h1>Liste des factures</h1>
						<table>
							<tr>
								<th>Id Facture</th>
								<th>Prix Total</th>
								<th></th>
							</tr>';
			foreach ($liste_factures as $facture){
			$out.='			<tr>
								<td><a href="?page=ficheFacture&idFacture='.$facture->idFacture().'">'.$facture->idFacture().'</a></td>
								<td>'.$facture->prixTotal().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
					$out.='		<td><a href="?page=supprimerFacture&idFacture='.$facture->idFacture().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
				}
				$out.='		</tr>';
			}
			$out.='		</table>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;	
	}
	
	
	#Formulaire ajouter
	public function formAjouterFacture(){	
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de voiture
			$out='	<h1>Ajouter un facture</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterFacture" id="getListFactures_form" onsubmit="return verifFormFacture(this)" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="idFacture" placeholder="Id" onblur="verifPseudo(this) Facture : " required="required"  >
								<input hidden type="text" class="table-cell" name="prixTotal" value="0" placeholder="Prix Total : ">
							</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajouter facture
	public function ajouterFacture(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_factureControleur->addFacture();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Supprimer facture
	public function supprimerFacture(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_factureControleur->deleteFacture();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Affiche la fiche détaillée d'une facture avec la liste des interventions
	public function ficheFacture(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Affiche les informations sur la voiture
			$facture = $this->_factureControleur->get($_GET['idFacture']);
			if(!empty($facture)){
				$out='	<h1>Facture détaillée</h1>
						<div class="pageRecherche">
							<table>
								<tr><th>Id Facture : </th><td>'.$facture->idFacture().'</td></tr>
								<tr><th>Prix Total : </th><td>'.$facture->prixTotal().'</td></tr>
							</table>';
							
							
				#Formulaire d'ajout d'intervention
				#Disponible qu'aux référents et à l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
					$out.='		<h1>Ajouter une intervention : </h1>
								<form action="?page=ajouterFacture_Intervention" id="getListFactures_Intervention_form" method="post" >
									<div class="table">
										<label for="idFacture">Id Facture : </label>
										<input type="text" class="table-cell" name="idFacture" placeholder="Id Facture : " required="required" value="'.$facture->idFacture().'" readonly="readonly">
										<label for="idIntervention"> Id Intervention : </label>
										<select name="idIntervention" required="required" >
											<option value="" >Non sélectionné</option>';
					$liste_interventions = $this->_interventionControleur->getList();		#On charge la liste des techniciens dans un select
					foreach ($liste_interventions as $intervention){
						$out.='				<option value="'.$intervention->id().'">'.$intervention->nom().'</option>';
					}
					$out.='				</select>
									</div>
									<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
								</form>	';
				}
							
							
				#Récupère et affiche la liste des interventions associés
				$liste_factures_detail=$this->_facture_interventionControleur->getList();
				$out.='		<h1>Liste des interventions</h1>
							<table>
								<tr>
									<th>Id Facture</th>
									<th>Id Intervention</th>
									<th>Prix Total</th>
									<th>Nom</th>
									<th>prix</th>
									<th></th>
								</tr>';
				foreach ($liste_factures_detail as $facture_detail){
				$out.='			<tr>
									<td>'.$facture_detail->idFacture().'</td>
									<td>'.$facture_detail->idIntervention().'</td>
									<td>'.$facture_detail->prixTotal().'</td>
									<td>'.$facture_detail->nom().'</td>
									<td>'.$facture_detail->prix().'</td>';
				
					#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
					if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
					$out.='			<td><a href="?page=supprimerFacture_Intervention&idFacture='.$facture_detail->idFacture().'&idIntervention='.$facture_detail->idIntervention().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
					}
					$out.='		</tr>';
				}
				$out.='		</table>
						
						</div>';
			}else{
				$out='Cette facture n\'existe pas';
			}
		}else{
			return 'Vous ne devriez pas être ici';
		}
		return $out;
		
	}
}
?>