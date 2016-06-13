<?php
class UtilisateurControleur{
	
	private $_utilisateurManager;
	
	public function __construct(UtilisateurManager $utilisateurManager){
		$this->_utilisateurManager=$utilisateurManager;
	}
	
	public function isConnected(){
		if (!empty($_SESSION['id'])) { //Si l'utilisateur est connecter, on retourne true
			return true;
		}else{
			return false;
		}
	}
	
	public function connexion_form(){
		$out = '<div id=page_connexion_form>
				<h1>Bienvenu sur le site de gestion de Garage à Tout Prix</h1>
				<p>Connectez vous pour continuer...</p>';
		
		$out .= '<article>
					<h1>Connexion</h1>
					<form action="?page=connexion" id="connexion_form" method="post" >
						<table>
							<tr>	<td><label for="Pseudo" >Pseudo : </label></td>	<td><input type="text" required="required" name="Pseudo" ></td>	</tr>
							<tr>	<td><label for="Password" >Password : </label></td>	<td><input type="password" required="required" name="Password" ></td>	</tr>
						</table>
						<p><input type="submit" class="ok" name="connexion" value="Connexion"></p>
					</form>
				</article>
				</div>';
		return $out;
	}
	
	public function connexion(){
		if (!empty($_POST['Pseudo']) AND !empty($_POST['Password'])) { //On verifie qu'aucun champ n'est vide
		
			
			// Hachage du mot de passe, c'est à dire son cryptage
			$pass_hache = sha1($_POST['Password']);
			
			//On recherche si le pseudo et le mot de passe correspondent à un utilisateur dans la base de donnee
			$utilisateurs = $this->_utilisateurManager->getList('%','%'.htmlspecialchars($_POST['Pseudo']).'%', '%'.htmlspecialchars($pass_hache).'%', '%');
			
			if (!empty($utilisateurs)) { //Si on trouve un utilisateur avec le pseudo et le mot de passe, on connecte et on redirige vers l'accueil
				$_SESSION['id'] = $utilisateurs[0]->id();
				$_SESSION['pseudo'] = $utilisateurs[0]->pseudo();
				$_SESSION['Privileges'] = $utilisateurs[0]->privileges();
				
				header ('Location: ?page=accueil');
				exit();
			
			} else { //Si on ne trouve pas d'utilisateur avec ce pseudo et ce mot de passe, on affiche un msg
				$out = '<p class="msg_erreur" >Pseudo ou Password incorrect</p>';
			}
			
		} else { //Si il y a des champs vides alors qu'ils sont tous requis pour valider le formulaire, on affiche un msg
			$out = '<p class="msg_erreur" >Vous ne devriez pas être ici</p>';
		}
		
		//on retourne la variable $out pour qu'elle soit affichee plus tard
		return $out;
	}
	
	public function deconnexion() {
		session_destroy(); //On detruit les variables $_SESSION
		
		//ON redirige vers accueil
		header ('Location: ?page=accueil');
		exit();
	}
	
	public function get($id){
		return $this->_utilisateurManager->get(htmlspecialchars($id));
	}
	
	public function getList(){
	
		$id = '%';
		if (!empty($_POST['id'])) {$id.=htmlspecialchars($_POST['id']).'%';}
		
		$pseudo = '%';
		if (!empty($_POST['pseudo'])) {$pseudo.=htmlspecialchars($_POST['pseudo']).'%';}	
		
		$privileges = '%';
		if (!empty($_POST['privileges']) OR (isset($_POST['privileges'])AND($_POST['privileges']==0))) {$privileges.=htmlspecialchars($_POST['privileges']).'%';}
	
		return $this->_utilisateurManager->getList($id, $pseudo, '%',$privileges);
	}
	
	public function addUtilisateur(){
		$out='';
		if ((!empty($_POST['pseudo']) ) AND (!empty($_POST['pass']) )) {
			$_POST['pass'] = sha1($_POST['pass']);
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$utilisateur = new Utilisateur($_POST);
			
			if (!$this->_utilisateurManager->exists($utilisateur)) {
				if($this->_utilisateurManager->add($utilisateur)){
					$out='L\'utilisateur '.htmlspecialchars($_POST['pseudo']).' a bien été ajouté.';
				}else{
					$out='OUPS ! Il y a eu un problème.'; 
				}
			} else {
				$out='Erreur : ces identifiants sont déjà pris ! ';
			}
		}else{
			$out='Erreur : vous ne devriez pas être ici !';
		}
		return $out;
	}
	
	public function editUtilisateur(){
		$out='';
		if (!empty($_POST['id']) ) {
			if (!empty($_POST['newpass'])){
				$_POST['pass'] = sha1($_POST['newpass']);
			}
			foreach($_POST as $variable){
				$variable=htmlspecialchars($variable);
			}			
			$utilisateur = new Utilisateur($_POST);
			
			if ($this->_utilisateurManager->exists($utilisateur)) {
				if($this->_utilisateurManager->update($utilisateur)){
					$out='L\'utilisateur '.htmlspecialchars($_POST['pseudo']).' a bien été  modifié.';
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
	
	public function deleteUtilisateur(){
		$utilisateur = $this->get($_GET['id']);
		return ($this->_utilisateurManager->delete($utilisateur))?'L\'utilisateur '.$utilisateur->pseudo().' a bien été  supprimé.':'OUPS ! Il y a eu un problème.'; 
	}
	
	
}
?>