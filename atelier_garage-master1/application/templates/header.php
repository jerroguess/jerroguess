			<header>
				<div id="titre_principal">
                    <a href="?"><img src="src/img/logo_garage.png" alt="Logo du garage" id="logo" /></a>
                    <h1>Atelier Garage A Tout Prix</h1>
				</div>
				<nav id="navigation">
					<ul>
						<li><a href="?page=afficherVoitures">Voitures</a></li>
						<li><a href="?page=afficherClients">Clients</a></li>
						<li><a href="?page=afficherTechniciens">Techniciens</a></li>
						<li><a href="?page=afficherRepares">Réparations</a></li>
						<li><a href="?page=afficherFactures">Factures</a></li>
					</ul>
				</nav>
				<nav id="utilisateur">
					<ul>
						<?php 
						if (!empty($_SESSION['pseudo'])) { //Si l'utilisateur est connecter, on lui souhaite la bienvenue
							echo ('<li><a href=#>'.$_SESSION['pseudo'].'</a>
										<ul>
											<li><a href="?page=monCompte">Mon Compte</a></li>
											<li><a href="?page=deconnexion">Déconnexion</a></li>
										</ul>	
									</li>');
						} else { //Si l'utilisateur n'est pas connecté, on affiche les liens pour se connecter
							echo ('<li><a href=?page=connexion_form>Connexion</a></li>');
						}
						?>
					</ul>
				</nav>
            </header>