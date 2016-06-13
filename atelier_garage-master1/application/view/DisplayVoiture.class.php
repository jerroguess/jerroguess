<?php
class DisplayVoiture{
	
	private $_voitureControleur;
	private $_clientControleur;
	private $_repareControleur;
	private $_commentaireControleur;
	private $_technicienControleur;
	private $_utilisateurControleur;
	
	public function __construct(VoitureControleur $voitureControleur, ClientControleur $clientControleur, RepareControleur $repareControleur, CommentaireControleur $commentaireControleur, TechnicienControleur $technicienControleur, UtilisateurControleur $utilisateurControleur){
		$this->_voitureControleur=$voitureControleur;
		$this->_clientControleur=$clientControleur;
		$this->_repareControleur=$repareControleur;
		$this->_commentaireControleur=$commentaireControleur;
		$this->_technicienControleur=$technicienControleur;
		$this->_utilisateurControleur=$utilisateurControleur;
	}
	
	#Affiche la liste des voitures précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherVoitures(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Formulaire de recherche de voiture
			$out='	<h1>Recherche parmi les voitures</h1>
					<div class="pageRecherche">
						<form action="?page=afficherVoitures" id="getListVoitures_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="immatriculation" placeholder="Immatriculation : " >
								<input type="text" class="table-cell" name="marque" placeholder="Marque : " >
								<input type="text" class="table-cell" name="modele" placeholder="Modele : " ></div><div>
								<input type="text" class="table-cell" name="annee" placeholder="Année : " >
								<input type="text" class="table-cell" name="kilometrage" placeholder="Kilométrage : " >
								<input type="date" class="table-cell" name="date_arrivee" placeholder="Date d\'arrivée : " ></div><div>
								<label for="proprietaire"> Propriétaire : </label>
								<input type="hidden" class="table-cell" name="type" placeholder=" : " >
								<input type="hidden" class="table-cell" name="prix" placeholder=" : " >
								<select name="proprietaire">
									<option value="" >Non sélectionné</option>';
			$liste_clients = $this->_clientControleur->getList('numero');	#On récuprère la liste des client pour la charger dans un select
			foreach ($liste_clients as $client){
				$out.='				<option value="'.$client->numero().'" >'.$client->numero().'</option>';
			}
			$out.='				</select>
								<label for="reparateur"> Réparateur : </label>
								<select name="reparateur">
									<option value="" >Non sélectionné</option>';
			$liste_techniciens = $this->_technicienControleur->getList();	#On récuprère la liste des techniciens pour la charger dans un select
			foreach ($liste_techniciens as $technicien){
				$out.='				<option value="'.$technicien->numero().'" >'.$technicien->numero().'</option>';
			}
			$out.='				</select>
							</div>
							<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de voiture
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='	<div class="alignRight">
							<form action="?page=formAjouterVoiture" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
			}
			
			
			#Chargement de la liste des voitures dans un tableau
			$liste_voitures=$this->_voitureControleur->getList();
			$out.='		<h1>Liste des voitures</h1>
						<table>
							<tr>
								<th>Immatriculation</th>
								<th>Marque</th>
								<th>Modele</th>
								<th>Année</th>
								<th>Kilometrage</th>
								<th>Date d\'arrivée</th>
								<th>Proprietaire</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_voitures as $voiture){			#On itère avec toutes les voitures du résultat de la recherche
				$out.='		<tr>
								<td><a href="?page=ficheVoiture&immatriculation='.$voiture->immatriculation().'">'.$voiture->immatriculation().'</a></td>
								<td>'.$voiture->marque().'</td>
								<td>'.$voiture->modele().'</td>
								<td>'.$voiture->annee().'</td>
								<td>'.$voiture->kilometrage().'</td>
								<td>'.$voiture->date_arrivee().'</td>
								<td>'.$voiture->proprietaire().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='			<td><a href="?page=formModifierVoiture&immatriculation='.$voiture->immatriculation().'">Modifier</a></td>
								<td><a href="?page=supprimerVoiture&immatriculation='.$voiture->immatriculation().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
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
	
	#formulaire d'ajout
	public function formAjouterVoiture(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de voiture
			$out='	<h1>Ajouter une voiture</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterVoiture" id="getListVoitures_form" method="post" >
							<div class="table">
								<input type="text" class="table-cell" name="immatriculation" placeholder="Immatriculation : " required="required" >
								<input type="text" class="table-cell" name="marque" placeholder="Marque : " >
								<input type="text" class="table-cell" name="modele" placeholder="modele : " ></div><div>
								<input type="text" class="table-cell" name="annee" placeholder="Année : " >
								<input type="text" class="table-cell" name="kilometrage" placeholder="Kilométrage : " >
								<input type="date" class="table-cell" name="date_arrivee" placeholder="Date d\'arrivée : " >
							</div>
								<label for="proprietaire">Propriétaire : </label>
								<select name="proprietaire" required="required" >
									<option value="" rel="none">Non sélectionné</option>
									<option value="" rel="other_client">Autre</option>';
			$liste_clients = $this->_clientControleur->getList('numero');	#On récuprère la liste des client pour la charger dans un select
			foreach ($liste_clients as $client){
				$out.='				<option value="'.$client->numero().'" rel="none">'.$client->numero().'</option>';
			}
			$out.='				</select>';
			
			#div caché qui s'affiche si "autre" est sélectionné pour le champs propriétaire
			#Ce div permet d'ajout préalablement un client
			$out.='			<div rel="other_client" class="table"><div>
								<p>Nouveau client : </p>
								<input  type="text" class="table-cell" name="numero" placeholder="Numero : " required="required" ></div><div>
								<input  type="text" class="table-cell" name="nom" placeholder="Nom : " required="required" >
								<input  type="text" class="table-cell" name="prenom" placeholder="Prenom : " required="required" ></div><div>
								<input  type="text" class="table-cell" name="adresse" placeholder="Adresse : " >
								<label for="referent">Référent : </label>
								<select name="referent" >
									<option value="" >Non sélectionné</option>';
			$_POST['privileges']=2;
			$liste_utilisateurs = $this->_utilisateurControleur->getList();
			foreach ($liste_utilisateurs as $utilisateur){
				$out.='				<option value="'.$utilisateur->id().'" >'.$utilisateur->id().'</option>';
			}
			$out.='				</select></div>';
			$out.='			</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#ajoute une voiture
	public function ajouterVoiture(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_voitureControleur->addVoiture();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierVoiture(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire de modification de voiture
			$voiture = $this->_voitureControleur->get($_GET['immatriculation']);
			if(!empty($voiture)){
				$out='	<h1>Modifier une voiture</h1>
						<div class="pageRecherche">
							<form action="?page=modifierVoiture" id="getListVoitures_form" method="post" >
								<div class="table">
									<input type="text" class="table-cell" name="immatriculation" placeholder="Immatriculation : " value="'.$voiture->immatriculation().'" required="required" readonly="readonly" >
									<input type="text" class="table-cell" name="marque" placeholder="Marque : " value="'.$voiture->marque().'" >
									<input type="text" class="table-cell" name="modele" placeholder="modele : " value="'.$voiture->modele().'" ></div><div>
									<input type="text" class="table-cell" name="annee" placeholder="Année : " value="'.$voiture->annee().'" >
									<input type="text" class="table-cell" name="kilometrage" placeholder="Kilométrage : " value="'.$voiture->kilometrage().'" >
									<input type="date" class="table-cell" name="date_arrivee" placeholder="Date d\'arrivée : " value="'.$voiture->date_arrivee().'" >
								</div><div>
									<label for="proprietaire">Propriétaire : </label>
									<select name="proprietaire" required="required" >
										<option value="" rel="none">Non sélectionné</option>
										<option value="" rel="other_client">Autre</option>';
				$liste_clients = $this->_clientControleur->getList('numero');
				foreach ($liste_clients as $client){
					$selector = ($voiture->proprietaire()==$client->numero())?'selected':'';
					$out.='				<option value="'.$client->numero().'" rel="none" '.$selector.'>'.$client->numero().'</option>';
				}
				$out.='				</select>';
				$out.='			</div><div rel="other_client" class="table"><div>
									<p>Nouveau client : </p>
									<input  type="text" class="table-cell" name="numero" placeholder="Numero : " required="required" readonly="readonly" ></div><div>
									<input  type="text" class="table-cell" name="nom" placeholder="Nom : " required="required" >
									<input  type="text" class="table-cell" name="prenom" placeholder="Prenom : " required="required" ></div><div>
									<input  type="text" class="table-cell" name="adresse" placeholder="Adresse : " >
									<label for="referent">Référent : </label>
									<select name="referent" >
										<option value="" >Non sélectionné</option>';
				$_POST['privileges']=2;
				$liste_utilisateurs = $this->_utilisateurControleur->getList();
				foreach ($liste_utilisateurs as $utilisateur){
					$out.='				<option value="'.$utilisateur->id().'" >'.$utilisateur->id().'</option>';
				}
				$out.='				</select></div>';
				$out.='			</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Cette voiture n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie une voiture
	public function modifierVoiture(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_voitureControleur->editVoiture();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#supprime une voiture
	public function supprimerVoiture(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_voitureControleur->deleteVoiture();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Affiche la fiche détaillée d'une voiture avec la liste des factures et des commentaires
	public function ficheVoiture(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Affiche les informations sur la voiture
			$voiture = $this->_voitureControleur->get($_GET['immatriculation']);
			if(!empty($voiture)){
				$out='	<h1>Fiche voiture</h1>
						<div class="pageRecherche">
							<table>
								<tr><th>Immatriculation : </th><td>'.$voiture->immatriculation().'</td></tr>
								<tr><th>Marque : </th><td>'.$voiture->marque().'</td></tr>
								<tr><th>Modele : </th><td>'.$voiture->modele().'</td></tr>
								<tr><th>Année : </th><td>'.$voiture->annee().'</td></tr>
								<tr><th>Kilométrage : </th><td>'.$voiture->kilometrage().'</td></tr>
								<tr><th>Date d\'arrivée : </th><td>'.$voiture->date_arrivee().'</td></tr>
								<tr><th>Propriétaire : </th><td>'.$voiture->proprietaire().'</td></tr>
							</table>';
							
							
				#Récupère et affiche la liste des factures associées
				$_POST['voiture']=$voiture->immatriculation();
				$liste_repares=$this->_repareControleur->getList();
				$out.='		<h1>Liste des factures</h1>
							<table>
								<tr>
									<th>Id facture</th>
									<th>Technicien</th>
									<th>Date de début</th>
									<th>Date de fin</th>
								</tr>';
				foreach ($liste_repares as $repare){
					$out.='		<tr>
									<td><a href="?page=ficheFacture&idFacture='.$repare->idFacture().'">'.$repare->idFacture().'</a></td>
									<td>'.$repare->technicien().'</td>
									<td>'.$repare->dateDebut().'</td>
									<td>'.$repare->dateFin().'</td>
								</tr>';
				}
				$out.='		</table>';
				
				
				#Récupère et affiche la liste des commentaires associés
				$liste_commentaires=$this->_commentaireControleur->getList();
				$out.='		<h1>Liste des commentaires</h1>
							<table>
								<tr>
									<th>Immatriculation</th>
									<th>Technicien</th>
									<th>Date</th>
									<th>Texte</th>
									<th></th>
								</tr>';
				foreach ($liste_commentaires as $commentaire){
					$out.='			<tr>
									<td>'.$commentaire->voiture().'</td>
									<td>'.$commentaire->technicien().'</td>
									<td>'.$commentaire->datecommentaire().'</td>
									<td>'.$commentaire->texte().'</td>';
				
					#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
					if (($_SESSION['Privileges']==1)OR($_SESSION['Privileges']==3)){
					$out.='			<td><a href="?page=supprimerCommentaire&voiture='.$commentaire->voiture().'&technicien='.$commentaire->technicien().'&datecommentaire='.$commentaire->datecommentaire().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
					}
					$out.='		</tr>';
				}
				$out.='		</table>';
				
				
				#Formulaire d'ajout de comentaire
				#Disponible qu'aux techniciens et à l'admin
				if (($_SESSION['Privileges']==1)OR($_SESSION['Privileges']==3)){
					$out.='		<h1>Ajouter un commentaire : </h1>
								<form action="?page=ajouterCommentaire" id="getListCommentaire_form" method="post" >
									<div class="table">
										<label for="voiture"> Immatriculation : </label>
										<input type="text" class="table-cell" name="voiture" placeholder="Voiture : " required="required" value="'.$voiture->immatriculation().'" readonly="readonly">
										<label for="technicien"> Technicien : </label>
										<select name="technicien" required="required" >
											<option value="" >Non sélectionné</option>';
					$liste_techniciens = $this->_technicienControleur->getList();		#On charge la liste des techniciens dans un select
					foreach ($liste_techniciens as $technicien){
						$out.='				<option value="'.$technicien->numero().'">'.$technicien->numero().'</option>';
					}
					$out.='				</select></div><div>
										<input hidden type="text" class="table-cell" name="datecommentaire" placeholder="datecommentaire : " value="" ></div><div>
										<textarea rows="4" cols="50" name="texte" placeholder="Commentaire : " required="required" ></textarea>
									</div>
									<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
								</form>';
				}
				
				$out.='		</div>';
			}else{
				$out='Cette voiture n\'existe pas';
			}
		}else{
			return 'Vous ne devriez pas être ici';
		}
		return $out;
		
	}
}
?>