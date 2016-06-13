<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestFactureManager</title>

<?php 
	require('../objects/Facture.class.php');
	require('../managers/FactureManager.class.php');

try
{
	$db = new PDO('mysql:host=127.0.0.1; port=3307;dbname=atelier_garage', 'root', 'toor');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$facture = new Facture([
		'prixTotal'=>'']);
	
print_r($facture);

//on cree le manager
$factureManager = new FactureManager($db);
//print_r($factureManager);

//on rajoute un facture en bdd
//$factureManager->add($facture);

print_r($facture);

//on compte, doit retourner 1
//echo $factureManager->count();

//on test le get
$facture = $factureManager->get(3);
if(empty($facture))
{echo "c'est vide";}
else
{print_r($facture);}


//on supprime la facture
//$factureManager->delete($facture);

//on verifie si elle existe en bdd (doit retourner 1 si oui, 0 si non)
//echo $factureManager->exists($facture);

//echo $factureManager->exists($facture);

//on test update
$facture->setPrixTotal(150);
$resultat = $factureManager->update($facture);
echo (string)$resultat;

?>

	<body>
		
	</body>
	
</html>

<!--INSERT INTO `atelier_garage`.`facture` (`id`, `nom`, `date`, `prix`, `kilometrage`, `date_arrivee`, `proprietaire`) VALUES ('abc-123-38', 'renault', 'sport', '1993', '200000', '1998', '20'); -->
