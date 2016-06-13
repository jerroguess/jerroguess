<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestInterventionManager</title>

<?php
	require('../objects/Intervention.class.php');
	require('../managers/InterventionManager.class.php');

try
{
	$db = new PDO('mysql:host=127.0.0.1; port=3307;dbname=atelier_garage', 'root', 'toor');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$intervention = new Intervention([
	'nom'=>'Changement des pneus',
	'prix'=>'190']);
	
print_r($intervention);

//on cree le manager
$interventionManager = new InterventionManager($db);
//print_r($interventionManager);

//on rajoute un intervention en bdd
//$interventionManager->add($intervention);

print_r($intervention);

//on compte, doit retourner 1
//echo $interventionManager->count();

//on supprime la intervention
//$interventionManager->delete($intervention);

//on verifie si elle existe en bdd (doit retourner 1 si oui, 0 si non)
//echo $interventionManager->exists($intervention);

//on test le get
$intervention = $interventionManager->get('11');
/*if(empty($intervention))
{echo "c'est vide";}
else
{print_r($intervention);}*/

//on test update
/*$intervention->setNom('lol');
$resultat = $interventionManager->update($intervention);
echo (string)$resultat;*/

?>

	<body>
		
	</body>
	
</html>

<!--INSERT INTO `atelier_garage`.`intervention` (`id`, `nom`, `date`, `prix`, `kilometrage`, `date_arrivee`, `proprietaire`) VALUES ('abc-123-38', 'renault', 'sport', '1993', '200000', '1998', '20'); -->
