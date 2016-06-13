<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestTechnicienManager</title>

<?php
	require('../objects/Technicien.class.php');
	require('../managers/TechnicienManager.class.php');

try
{
	$db = new PDO('mysql:host=127.0.0.1; port=3307;dbname=atelier_garage', 'root', 'toor');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$technicien = new Technicien([
	'numero'=>'213456',
	'nom'=>'weber',
	'prenom'=>'cecile',
	'nombre'=>0
	]);
	
//print_r($technicien);

//on cree le manager
$technicienManager = new TechnicienManager($db);
//print_r($nomManager);

//on rajoute un technicien en bdd
//$technicienManager->add($technicien);

//on compte, doit retourner 1
//echo $technicienManager->count();

//on supprime la technicien
//$technicienManager->delete($technicien);

//on verifie si elle existe en bdd (doit retourner 1 si oui, 0 si non)
//echo $technicienManager->exists($technicien);

//on test le get
/*$technicien = $technicienManager->get('7');
if(empty($technicien))
{echo "c'est vide";}
else
{print_r($technicien);}*/

//on test le getList
$list = $technicienManager->getList('%','%','%tho%','nom');
print_r($list);

//on test update
/*$technicien->setNom('lol');
$resultat = $technicienManager->update($technicien);
echo (string)$resultat;*/

?>

	<body>
		
	</body>
	
</html>

<!--INSERT INTO `atelier_garage`.`nom` (`numero`, `nom`, `prenom`, `texte`, `kilometrage`, `prenom_arrivee`, `proprietaire`) VALUES ('abc-123-38', 'renault', 'sport', '1993', '200000', '1998', '20'); -->
