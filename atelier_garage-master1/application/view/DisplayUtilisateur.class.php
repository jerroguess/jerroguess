<?php
class DisplayUtilisateur{
	
	private $_utilisateurControleur;
	
	public function __construct(UtilisateurControleur $utilisateurControleur){
		$this->_utilisateurControleur=$utilisateurControleur;
	}
	
	#Affiche la liste des utilisateurs précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherUtilisateurs(){
		$out='';
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			#Formulaire de recherche de utilisateur
			$out='	<h1>Recherche parmi les utilisateurs</h1>
					<div class="pageRecherche">
						<form action="?page=afficherUtilisateurs" id="getListUtilisateurs_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="id" onblur="isInt(this)" placeholder="Id : " >
								<input type="text" class="table-cell" name="pseudo" placeholder="Pseudo : " >
								<select name="privileges">
									<option value="" >Non sélectionné</option>
									<option value="0" >Visiteur (0)</option>
									<option value="1" >Technicien (1)</option>
									<option value="2" >Référent (2)</option>
									<option value="3" >Admin (3)</option>
								</select>
								<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
							</div>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de utilisateur
			$out.='		<div class="alignRight">
							<form action="?page=formAjouterUtilisateur" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
									
			
			#Chargement de la liste des utilisateurs dans un tableau
			$liste_utilisateurs=$this->_utilisateurControleur->getList();
			$out.='		<h1>Liste des utilisateurs</h1>
						<table>
							<tr>
								<th>Id</th>
								<th>Pseudo</th>
								<th>Privileges</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_utilisateurs as $utilisateur){
				$out.='		<tr>
								<td>'.$utilisateur->id().'</td>
								<td>'.$utilisateur->pseudo().'</td>
								<td>'.$utilisateur->privileges().'</td>
								<td><a href="?page=formModifierUtilisateur&id='.$utilisateur->id().'">Modifier</a></td>
								<td><a href="?page=supprimerUtilisateur&id='.$utilisateur->id().'" onclick="return verifjs_suppr();">Supprimer</a></td>
							</tr>';
			}
			$out.='		</table>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;		
	}
	
	#Formulaire d'ajout
	public function formAjouterUtilisateur(){	
		$out='';
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de utilisateur
			$out='	<h1>Ajouter un utilisateur</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterUtilisateur" id="getListUtilisateurs_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="pseudo" placeholder="Pseudo : "  required="required" >
								<input type="text" class="table-cell" name="pass" placeholder="Pass : "  required="required" >
								<select name="privileges" required="required" >
									<option value="" >Non sélectionné</option>
									<option value="0" >Visiteur (0)</option>
									<option value="1" >Technicien (1)</option>
									<option value="2" >Référent (2)</option>
									<option value="3" >Admin (3)</option>
								</select>
							</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajoute un utilisateur
	public function ajouterUtilisateur(){
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			return $this->_utilisateurControleur->addUtilisateur();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierUtilisateur(){
		$out='';
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			#Formulaire de modification de utilisateur
			$utilisateur = $this->_utilisateurControleur->get($_GET['id']);
			if(!empty($utilisateur)){
				$out='	<h1>Modifier un utilisateur</h1>
						<div class="pageRecherche">
							<form action="?page=modifierUtilisateur" id="getListUtilisateurs_form" method="post" >
								<div class="table">
									<input hidden type="text" class="table-cell" name="id" value="'.$utilisateur->id().'"  required="required" >
									<input type="text" class="table-cell" name="pseudo" placeholder="Pseudo : "  value="'.$utilisateur->pseudo().'"  required="required" >
									<select name="pass" required="required" >
										<option value="'.$utilisateur->pass().'" rel="none">Pass éxistant</option>
										<option value="newpass" rel="newpass">New Password</option>
									</select>
									<select name="privileges" required="required" >
										<option value="" >Non sélectionné</option>
										<option value="0" ';	$out.=($utilisateur->privileges()==0)?'selected':'';	$out.=' >Visiteur (0)</option>
										<option value="1" ';	$out.=($utilisateur->privileges()==1)?'selected':'';	$out.='  >Technicien (1)</option>
										<option value="2" ';	$out.=($utilisateur->privileges()==2)?'selected':'';	$out.='  >Référent (2)</option>
										<option value="3" ';	$out.=($utilisateur->privileges()==3)?'selected':'';	$out.='  >Admin (3)</option>
									</select>
									<div rel="newpass"><input type="text" class="table-cell" name="newpass" placeholder="New Pass : "  required="required" ></div>
								</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Cet utilisateur n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie un utilisateur
	public function modifierUtilisateur(){
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			if ($_SESSION['id']==$_POST['id']){
				$_SESSION['pseudo']=$_POST['pseudo'];
			}
			return $this->_utilisateurControleur->editUtilisateur();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#supprime un utilisateur
	public function supprimerUtilisateur(){
		if (!empty($_SESSION['id']) AND ($_SESSION['Privileges']==3)){
			return $this->_utilisateurControleur->deleteUtilisateur();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	public function connexion_form(){
		return $this->_utilisateurControleur->connexion_form();
	}
	
	public function connexion(){
		return $this->_utilisateurControleur->connexion();
	}
	
	public function deconnexion(){
		return $this->_utilisateurControleur->deconnexion();
	}
	
	public function monCompte(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de modification de compte
			$utilisateur = $this->_utilisateurControleur->get($_SESSION['id']);
			if(!empty($utilisateur)){
				$out='	<h1>Modifier mon compte</h1>
						<div class="pageRecherche">
							<form action="?page=modifierUtilisateur" id="getListUtilisateurs_form" method="post" >
								<div class="table">
									<input hidden type="text" class="table-cell" name="id" value="'.$utilisateur->id().'"  required="required" >
									<input type="text" class="table-cell" name="pseudo" placeholder="Pseudo : "  value="'.$utilisateur->pseudo().'"  required="required" >
									<select name="pass" required="required" >
										<option value="'.$utilisateur->pass().'" rel="none">Pass éxistant</option>
										<option value="" rel="newpass">New Password</option>
									</select>
									<input hidden type="text" class="table-cell" name="privileges" value="'.$utilisateur->privileges().'"  required="required" >
									<div rel="newpass"><input type="text" class="table-cell" name="newpass" placeholder="New Pass : "  required="required" ></div>
								</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Cet utilisateur n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
}
?>