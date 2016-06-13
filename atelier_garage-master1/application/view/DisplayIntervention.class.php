<?php
class DisplayIntervention{
	
	private $_interventionControleur;
	
	public function __construct(InterventionControleur $interventionControleur){
		$this->_interventionControleur=$interventionControleur;	
	}
	
	#Affiche la liste des interventions précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherInterventions(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de recherche d'intervention
			$out='	<h1>Recherche parmi les interventions</h1>
					<div class="pageRecherche">
						<form action="?page=afficherInterventions" id="getListInterventions_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="id" placeholder="Id : " >
								<input type="text" class="table-cell" name="nom" placeholder="Nom : " >
								<input type="text" class="table-cell" name="prix" placeholder="Prix : " >
							<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
							</div>
						</form>';
						
						
			#Lien vers le formulaire d'ajout d'intervention
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='	<div class="alignRight">
							<form action="?page=formAjouterIntervention" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
			}
			
			
			#Chargement de la liste des interventions dans un tableau
			$liste_interventions=$this->_interventionControleur->getList();
			$out.='		<h1>Liste des interventions</h1>
						<table>
							<tr>
								<th>Id</th>
								<th>Nom</th>
								<th>Prix</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_interventions as $intervention){
			$out.='			<tr>
								<td>'.$intervention->id().'</td>
								<td>'.$intervention->nom().'</td>
								<td>'.$intervention->prix().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
					$out.='		<td><a href="?page=formModifierIntervention&id='.$intervention->id().'">Modifier</a></td>
								<td><a href="?page=supprimerIntervention&id='.$intervention->id().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
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
	
	#Formulaire d'ajout
	public function formAjouterIntervention(){	
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de voiture
			$out='	<h1>Ajouter un intervention</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterIntervention" id="getListInterventions_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="nom" placeholder="Nom : " required="required"   >
								<input type="text" class="table-cell" name="prix" placeholder="Prix : " required="required"   >
							</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajoute une intervention
	public function ajouterIntervention(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_interventionControleur->addIntervention();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierIntervention(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire de modification de voiture
			$intervention = $this->_interventionControleur->get($_GET['id']);
			if(!empty($intervention)){
				$out='	<h1>Modifier un intervention</h1>
						<div class="pageRecherche">
							<form action="?page=modifierIntervention" id="getListInterventions_form" method="post" >
								<div class="table">
									<input type="text" class="table-cell" name="id" placeholder="Id : " required="required" value="'.$intervention->id().'"  readonly="readonly"  >
									<input type="text" class="table-cell" name="nom" placeholder="Nom : " required="required" value="'.$intervention->nom().'">
									<input type="text" class="table-cell" name="prix" placeholder="Prix : " required="required" value="'.$intervention->prix().'">
								</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Cette intervention n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie une intervention
	public function modifierIntervention(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_interventionControleur->editIntervention();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Supprime une intervention
	public function supprimerIntervention(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_interventionControleur->deleteIntervention();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
}
?>