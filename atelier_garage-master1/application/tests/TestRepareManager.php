<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestRepareManager</title>

<?php 
	require('../objects/Repare.class.php');
	require('../managers/RepareManager.class.php');

try
{
	$db = new PDO('mysql:host=127.0.0.1; port=3307;dbname=atelier_garage', 'root', 'toor');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$repare = new Repare([
	'idFacture'=>'1',
	'technicien'=>'213456',
	'voiture'=>'123-456',
	'dateDebut'=>'']);
	
//print_r($repare);

//on cree le manager
$repareManager = new RepareManager($db);
//print_r($repareManager);

//on rajoute un repare en bdd
//$repareManager->add($repare);

print_r($repare);

//on compte, doit retourner 1
//echo $repareManager->count();

//on supprime la repare
//$repareManager->delete($repare);

//on verifie si elle existe en bdd (doit retourner 1 si oui, 0 si non)
//echo $repareManager->exists($repare);

//on test le get
/*$repare = $repareManager->get(213456,'123-456');
if(empty($repare))
{echo "c'est vide";}
else
{print_r($repare);}*/

echo $repareManager->exists($repare);

//on test update
/*$repare->setTechnicien(7);
$resultat = $repareManager->update($repare);
echo (string)$resultat;*/

?>

	<body>
		
	</body>
	
</html>

<!--INSERT INTO `atelier_garage`.`repare` (`id`, `nom`, `date`, `prix`, `kilometrage`, `date_arrivee`, `proprietaire`) VALUES ('abc-123-38', 'renault', 'sport', '1993', '200000', '1998', '20'); -->
