<?php
class DisplayRepare{
	
	private $_repareControleur;
	private $_technicienControleur;
	private $_voitureControleur;
	
	public function __construct(RepareControleur $repareControleur, FactureControleur $factureControleur, TechnicienControleur $technicienControleur, VoitureControleur $voitureControleur){
		$this->_repareControleur=$repareControleur;
		$this->_factureControleur=$factureControleur;
		$this->_technicienControleur=$technicienControleur;
		$this->_voitureControleur=$voitureControleur;
	}
	
	#Affiche la liste des réparations précédée d'un formulaire de recherche et d'un lien vers le formulaire d'ajout
	public function afficherRepares(){
		$out='';
		if (!empty($_SESSION['id'])){
			#Onsauvegarde $_POST le temps de remplir les <select>, pour qu'il ne soient pas influencés par les paramètres de la recherche
			$POST_save=$_POST;
			$_POST=[];
			
			#Formulaire de recherche de réparation
			$out='	<h1>Recherche parmi les réparations</h1>
					<div class="pageRecherche">
						<form action="?page=afficherRepares" id="getListRepares_form" method="post" >
							<div class="table">
								<label for="idFacture"> Id Facture : </label>
								<select name="idFacture">
									<option value="" >Non sélectionné</option>';
			$liste_factures = $this->_factureControleur->getList();	#On récuprère la liste des factures pour la charger dans un select
			foreach ($liste_factures as $facture){
				$out.='				<option value="'.$facture->idFacture().'" >'.$facture->idFacture().'</option>';
			}
			$out.='				</select>
								<label for="technicien"> Technicien : </label>
								<select name="technicien">
									<option value="" >Non sélectionné</option>';
			$liste_techniciens = $this->_technicienControleur->getList();	#On récuprère la liste des techniciens pour la charger dans un select
			foreach ($liste_techniciens as $technicien){
				$out.='				<option value="'.$technicien->numero().'" >'.$technicien->numero().'</option>';
			}
			$out.='				</select>
								<label for="voiture"> Voiture : </label>
								<select name="voiture">
									<option value="" >Non sélectionné</option>';
			$liste_voitures = $this->_voitureControleur->getList();	#On récuprère la liste des voitures pour la charger dans un select
			foreach ($liste_voitures as $voiture){
				$out.='				<option value="'.$voiture->immatriculation().'" >'.$voiture->immatriculation().'</option>';
			}
			$out.='				</select></div><div>
								<input type="date" class="table-cell" name="dateDebut" placeholder="Date de début : " >
								<input type="date" class="table-cell" name="dateFin" placeholder="Date de fin : " >
								<p><input type="submit" class="ok" name="Rechercher" value="Rechercher"></p>
							</div>
						</form>';
						
						
			#Lien vers le formulaire d'ajout de reparation
			#Disponible uniquement aux référents et à l'admin
			if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='	<div class="alignRight">
							<form action="?page=formAjouterRepare" method="post" >
								<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
							</form>
						</div>';
			}
			
			
			#Chargement de la liste des réparations dans un tableau
			$_POST=$POST_save;
			$liste_repares=$this->_repareControleur->getList();
			$out.='		<h1>Liste des réparations</h1>
						<table>
							<tr>
								<th>Id Facture</th>
								<th>Technicien</th>
								<th>Voiture</th>
								<th>Date de début</th>
								<th>Date de fin</th>
								<th></th>
								<th></th>
							</tr>';
			foreach ($liste_repares as $repare){
			$out.='			<tr>
								<td><a href="?page=ficheFacture&idFacture='.$repare->idFacture().'">'.$repare->idFacture().'</a></td>
								<td>'.$repare->technicien().'</td>
								<td><a href="?page=ficheVoiture&immatriculation='.$repare->voiture().'">'.$repare->voiture().'</a></td>
								<td>'.$repare->dateDebut().'</td>
								<td>'.$repare->dateFin().'</td>';
			
				#Lien vers la modification et la suppression disponibles uniquement aux référents et l'admin
				if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
				$out.='			<td><a href="?page=formModifierRepare&technicien='.$repare->technicien().'&voiture='.$repare->voiture().'&dateDebut='.$repare->dateDebut().'">Modifier</a></td>
								<td><a href="?page=supprimerRepare&technicien='.$repare->technicien().'&voiture='.$repare->voiture().'&dateDebut='.$repare->dateDebut().'" onclick="return verifjs_suppr();">Supprimer</a></td>';
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
	public function formAjouterRepare(){	
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire d'ajout de repare
			$out='	<h1>Ajouter une réparation</h1>
					<div class="pageRecherche">
						<form action="?page=ajouterRepare" id="getListRepares_form" method="post" >
							<div class="table">
								<label for="idFacture"> Id Facture : </label>
								<select name="idFacture">
									<option value="" >Non sélectionné</option>';
			$liste_factures = $this->_factureControleur->getList();	#On récuprère la liste des factures pour la charger dans un select
			foreach ($liste_factures as $facture){
				$out.='				<option value="'.$facture->idFacture().'" >'.$facture->idFacture().'</option>';
			}
			$out.='				</select>
								<label for="technicien"> Technicien : </label>
								<select name="technicien" required="required"  >
									<option value="" >Non sélectionné</option>';
			$liste_techniciens = $this->_technicienControleur->getList();	#On récuprère la liste des techniciens pour la charger dans un select
			foreach ($liste_techniciens as $technicien){
				$out.='				<option value="'.$technicien->numero().'" >'.$technicien->numero().'</option>';
			}
			$out.='				</select>
								<label for="voiture"> Voiture : </label>
								<select name="voiture" required="required"  >
									<option value="" >Non sélectionné</option>';
			$liste_voitures = $this->_voitureControleur->getList();	#On récuprère la liste des voitures pour la charger dans un select
			foreach ($liste_voitures as $voiture){
				$out.='				<option value="'.$voiture->immatriculation().'" >'.$voiture->immatriculation().'</option>';
			}
			$out.='				</select></div><div>
								<input type="date" class="table-cell" name="dateDebut" placeholder="Date de début : " required="required"  >
								<input type="date" class="table-cell" name="dateFin" placeholder="Date de fin : " >
							</div>
							<p><input type="submit" class="ok" name="Ajouter" value="Ajouter"></p>
						</form>
					</div>';
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Ajout de réparation
	public function ajouterRepare(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_repareControleur->addRepare();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Formulaire de modification
	public function formModifierRepare(){
		$out='';
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			#Formulaire de modification de voiture
			$repare = $this->_repareControleur->get($_GET['technicien'],$_GET['voiture'],$_GET['dateDebut']);
			if(!empty($repare)){
				$out='	<h1>Modifier une réparation</h1>
						<div class="pageRecherche">
							<form action="?page=modifierRepare" id="getListRepares_form" method="post" >
								<div class="table">
									<label for="idFacture"> Id Facture : </label>
									<select name="idFacture">
										<option value="" >Non sélectionné</option>';
				$liste_factures = $this->_factureControleur->getList();	#On récuprère la liste des factures pour la charger dans un select
				foreach ($liste_factures as $facture){
					$selector = ($facture->idFacture()==$repare->idFacture())?'selected':'';
					$out.='				<option value="'.$facture->idFacture().'"  '.$selector.'>'.$facture->idFacture().'</option>';
				}
				$out.='				</select>
									<input type="text" class="table-cell" name="technicien" placeholder="Technicien : " value="'.$repare->technicien().'" required="required"  readonly="readonly"  >
									<input type="text" class="table-cell" name="voiture" placeholder="Voiture : " value="'.$repare->voiture().'" required="required"   readonly="readonly" ></div><div>
									<input type="date" class="table-cell" name="dateDebut" placeholder="Date de début : " value="'.$repare->dateDebut().'" required="required"   readonly="readonly" >
									<input type="date" class="table-cell" name="dateFin" placeholder="Date de fin : " value="'.$repare->dateFin().'" >
								</div>
								<p><input type="submit" class="ok" name="Modifier" value="Modifier"></p>
							</form>
						</div>';
			}else{
				$out='Cette réparation n\'existe pas';
			}
		}else{
			$out='Vous ne devriez pas être ici';
		}
		return $out;
	}
	
	#Modifie une réparation
	public function modifierRepare(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_repareControleur->editRepare();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
	
	#Supprime une réparation
	public function supprimerRepare(){
		if (($_SESSION['Privileges']==2)OR($_SESSION['Privileges']==3)){
			return $this->_repareControleur->deleteRepare();
		}else{
			return 'Vous ne devriez pas être ici';
		}
	}
}
?>