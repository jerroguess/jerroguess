<?php
class DisplayTechnicien{
	
	private $_technicienControleur;
	private $_utilisateurControleur;
	
	public function __construct(TechnicienControleur $technicienControleur, UtilisateurControleur $utilisateurControleur){
		$this->_technicienControleur=$technicienControleur;		
		$this->_utilisateurControleur=$utilisateurControleur;		
	}
	
	
	#Affiche la liste des techniciens précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherTechniciens(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de recherche de technicien
			$out='	<h1>Recherche parmi les techniciens</h1>
					<div class="pageRecherche">
						<form action="?page=afficherTechniciens" id="getListTechniciens_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="numero" onblur="isInt(this)" placeholder="Numéro : " >
								<input type="text" class="table-cell" name="nom" placeholder="Nom : " >
								<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " ></div><div>
							<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de technicien
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='		<div class="alignRight">
								<form action="?page=formAjouterTechnicien" method="post" >
									<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
								</form>
							</div>
						</div>';
			}
			
			
			#Chargement de la liste des techniciens dans un tableau
			$liste_techniciens=$this->_technicienControleur->getList();
			$out.='		<h1>Liste des techniciens</h1>
						<table>
							<tr>
								<th>Numéro</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Nombre de réparations terminées</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_techniciens as $technicien){
				$out.='		<tr>
								<td>'.$technicien->numero().'</td>
								<td>'.$technicien->nom().'</td>
								<td>'.$technicien->prenom().'</td>
								<td>'.$technicien->nombre().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
					$out.='		<td><a href="?page=formModifierTechnicien&numero='.$technicien->numero().'">Modifier</a></td>
								<td><a href="?page=supprimerTechnicien&numero='.$technicien->numero().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
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
	public function formAjouterTechnicien(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de technicien	
			$out='	<h1>Ajouter un technicien</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterTechnicien" id="getListTechniciens_form" method="post" >
							<div class="table">
								<label for="numero">Utilisateur : </label>
								<select name="numero" >
									<option value="" >Non sélectionné</option>';
			$_POST['privileges']=0;
			$liste_utilisateurs = $this->_utilisateurControleur->getList();
			foreach ($liste_utilisateurs as $utilisateur){
				$out.='				<option value="'.$utilisateur->id().'" >'.$utilisateur->id().'</option>';
			}
			$out.='				</select>';
			$out.='				<input type="text" class="table-cell" name="nom" placeholder="Nom : " required="required" >
								<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " required="required" >
							</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajoute un technicien
	public function ajouterTechnicien(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			$_POST['id']=$_POST['numero'];
			$utilisateur = $this->_utilisateurControleur->get($_POST['numero']);
			$_POST['pseudo']=$utilisateur->pseudo();
			$_POST['pass']=$utilisateur->pass();
			$_POST['privileges']=1;
			
			$this->_utilisateurControleur->editUtilisateur();
			return $this->_technicienControleur->addTechnicien();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierTechnicien(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire de modification de technicien
			$technicien = $this->_technicienControleur->get($_GET['numero']);
			if(!empty($technicien)){
				$out='	<h1>Modifier un technicien</h1>
						<div class="pageRecherche">
							<form action="?page=modifierTechnicien" id="getListTechniciens_form" method="post" >
								<div class="table">
									<input type="text" class="table-cell" name="numero" placeholder="Numéro : " value="'.$technicien->numero().'" required="required"  readonly="readonly" >
									<input type="text" class="table-cell" name="nom" placeholder="Nom : " value="'.$technicien->nom().'" required="required" >
									<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " value="'.$technicien->prenom().'" required="required" >
								</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Ce technicien n\'existe pas';
			}
		}else{
			return 'Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie un technicien
	public function modifierTechnicien(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_technicienControleur->editTechnicien();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#supprime un technicien
	public function supprimerTechnicien(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_technicienControleur->deleteTechnicien();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
}
?>