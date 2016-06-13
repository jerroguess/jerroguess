<?php
class DisplayClient{
	
	private $_clientControleur;
	private $_utilisateurControleur;
	
	public function __construct(ClientControleur $clientControleur, UtilisateurControleur $utilisateurControleur){
		$this->_utilisateurControleur=$utilisateurControleur;
		$this->_clientControleur=$clientControleur;
	}
	
	#Affiche la liste des clients précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout ainsi que la liste des villes et leur nombre 
	public function afficherClients(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de recherche de client
			$out='	<h1>Recherche parmi les clients</h1>
					<div class="pageRecherche">
						<form action="?page=afficherClients" id="getListClients_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="numero" onblur="isInt(this)" placeholder="Numéro : " >
								<input type="text" class="table-cell" name="nom" placeholder="Nom : " >
								<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " ></div><div>
								<input type="text" class="table-cell" name="adresse" placeholder="Adresse : " >
								<label for="referent">Référent : </label>
								<select name="referent" >
									<option value="" >Non sélectionné</option>';
			$_POST['privileges']=2;
			$liste_utilisateurs = $this->_utilisateurControleur->getList();
			foreach ($liste_utilisateurs as $utilisateur){
				$out.='				<option value="'.$utilisateur->id().'" >'.$utilisateur->id().'</option>';
			}
			$out.='				</select>';
			$out.='			<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
							</div>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de client
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='	<div class="alignRight">
							<form action="?page=formAjouterClient" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
			}
						
			
			#Chargement de la liste des clients dans un tableau
			$liste_clients=$this->_clientControleur->getList('nom');
			$out.='		<h1>Liste des clients</h1>
						<table>
							<tr>
								<th>Numéro</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Adresse</th>
								<th>Référent</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_clients as $client){
			$out.='			<tr>
								<td>'.$client->numero().'</td>
								<td>'.$client->nom().'</td>
								<td>'.$client->prenom().'</td>
								<td>'.$client->adresse().'</td>
								<td>'.$client->referent().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='			<td><a href="?page=formModifierClient&numero='.$client->numero().'">Modifier</a></td>
								<td><a href="?page=supprimerClient&numero='.$client->numero().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
				}
				$out.='		</tr>';
			}
			$out.='		</table>';
			
			
			#Formulaire de recherche de ville
			$out.='		<h1>Recherche parmi les villes</h1>
						<form action="?page=afficherClients" id="getListClients_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="nomVille" placeholder="Nom : " >
								<input type="text" class="table-cell" name="nombre" placeholder="Nombre : " >
							<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
						</form>';
						
			#Chargement de la liste des villes dans un tableau
			$liste_villes=$this->_clientControleur->getVilles();
			$out.='		<h1>Liste des villes</h1>
						<table>
							<tr>
								<th>Nom</th>
								<th>Nombre</th>
							</tr>';
			foreach ($liste_villes as $ville){
			$out.='			<tr>
								<td>'.$ville->nom().'</td>
								<td>'.$ville->nombre().'</td>
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
	public function formAjouterClient(){	
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de client
			$out='	<h1>Ajouter un client</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterClient" id="getListClients_form" onsubmit="return verifFormClient(this)" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="numero" onblur="isInt(this)" placeholder="Numéro : " required="required"  >
								<input type="text" class="table-cell" name="nom" placeholder="Nom : " required="required" >
								<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " required="required" ></div><div>
								<input type="text" class="table-cell" name="adresse" placeholder="Adresse : " >
								<label for="referent">Référent : </label>
								<select name="referent" required="required" >
									<option value="" >Non sélectionné</option>';
			$_POST['privileges']=2;
			$liste_utilisateurs = $this->_utilisateurControleur->getList();
			foreach ($liste_utilisateurs as $utilisateur){
				$out.='				<option value="'.$utilisateur->id().'" >'.$utilisateur->id().'</option>';
			}
			$out.='				</select>';
			$out.='			</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajoute un client
	public function ajouterClient(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_clientControleur->addClient();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierClient(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire de modification de client
			$client = $this->_clientControleur->get($_GET['numero']);
			if(!empty($client)){
				$out='	<h1>Modifier un client</h1>
						<div class="pageRecherche">
							<form action="?page=modifierClient" id="getListClients_form" method="post" >
								<div class="table">
									<input type="text" class="table-cell" name="numero" placeholder="Numéro : " value="'.$client->numero().'" required="required"  readonly="readonly" >
									<input type="text" class="table-cell" name="nom" placeholder="Nom : " value="'.$client->nom().'" required="required" >
									<input type="text" class="table-cell" name="prenom" placeholder="Prénom : " value="'.$client->prenom().'" required="required" ></div><div>
									<input type="text" class="table-cell" name="adresse" placeholder="Adresse : " value="'.$client->adresse().'">
									<label for="referent">Référent : </label>
									<select name="referent" required="required">
										<option value="" >Non sélectionné</option>';
				$_POST['privileges']=2;
				$liste_utilisateurs = $this->_utilisateurControleur->getList();
				foreach ($liste_utilisateurs as $utilisateur){
					$selector = ($client->referent()==$utilisateur->id())?'selected':'';
					$out.='				<option value="'.$utilisateur->id().'" '.$selector.' >'.$utilisateur->id().'</option>';
				}
				$out.='				</select>';
				$out.='			</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Ce client n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie un client
	public function modifierClient(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_clientControleur->editClient();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#supprime un client
	public function supprimerClient(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_clientControleur->deleteClient();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
}
?>