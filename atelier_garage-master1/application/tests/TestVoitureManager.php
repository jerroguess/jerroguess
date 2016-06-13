<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestVoitureManager</title>

<?php
	require('../objects/Voiture.class.php');
	require('../managers/VoitureManager.class.php');

try
{
	$db = new PDO('mysql:host=127.0.0.1; port=3307;dbname=atelier_garage', 'root', 'toor');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$voiture = new Voiture([
	'immatriculation'=>'xyz-789-38',
	'marque'=>'peugeot',
	'type'=>'sport',
	'annee'=>1993,
	'kilometrage'=>'120000',
	'date_arrivee' => '',
	'proprietaire'=>0001]);
	
print_r($voiture);

//on cree le manager
$voitureManager = new VoitureManager($db);
//print_r($voitureManager);

//on rajoute une voiture en bdd
//$voitureManager->add($voiture);

//on compte, doit retourner 1
//echo $voitureManager->count();

//on supprime la voiture
//$voitureManager->delete($voiture);

//on verifie si elle existe en bdd (doit retourner 1 si oui, 0 si non)
//echo $voitureManager->exists($voiture);

//on test le get
//$voiture = $voitureManager->get('11');
/*if(empty($voiture))
{echo "c'est vide";}
else
{print_r($voiture);}*/

//on test update
/*$voiture->setProprietaire(19);
$voiture->setDate_arrivee('2025');
$resultat = $voitureManager->update($voiture);
echo (string)$resultat;
/*
//on test getList
//on rempli d'abord la bdd
$voitures = [];
$voitures[] = new Voiture([
	'immatriculation'=>'abc-456-69',
	'marque'=>'peugeot',
	'type'=>'sport',
	'annee'=>2005,
	'kilometrage'=>'1000',
	'date_arrivee'=>new DateTime("2012-07-08 11:14:15.638276"),
	'proprietaire'=>'djoka']);

$voitures[] = new Voiture([
	'immatriculation'=>'abc-123-38',
	'marque'=>'peugeot',
	'type'=>'sport',
	'annee'=>1993,
	'kilometrage'=>'120000',
	'date_arrivee'=>new DateTime("2012-07-08 11:14:15.638276"),
	'proprietaire'=>'002']);

$voitures[] = new Voiture([
	'immatriculation'=>'def-123-38',
	'marque'=>'renault',
	'type'=>'citadine',
	'annee'=>2003,
	'kilometrage'=>'8000',
	'date_arrivee'=>new DateTime("2012-07-08 11:14:15.638276"),
	'proprietaire'=>'192']);

$voitureManager->add($voitures[0]);
$voitureManager->add($voitures[1]);
$voitureManager->add($voitures[2]);
*/
//on test la requete
//print_r($voitureManager->getList('abc%','%','%','%','%','%','%','%'));
//print_r($voitureManager->getList('%','%','%','%','%','%','%','2'));

?>

	<body>
		
	</body>
	
</html>

<!--INSERT INTO `atelier_garage`.`voiture` (`immatriculation`, `marque`, `type`, `annee`, `kilometrage`, `date_arrivee`, `proprietaire`) VALUES ('abc-123-38', 'renault', 'sport', '1993', '200000', '1998', '20'); -->
