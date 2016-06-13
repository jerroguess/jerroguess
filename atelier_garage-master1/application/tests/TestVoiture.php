<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestVoiture</title>

<?php
require('../objects/Voiture.class.php');

$voiture = new Voiture([
	'immatriculation'=>'abc-123-38',
	'marque'=>'peugeot',
	'type'=>'sport',
	'annee'=>1993,
	'kilometrage'=>'120000',
	'date_arrivee'=>'2001',
	'proprietaire'=>'daho']);

echo $voiture->annee();
echo $voiture->immatriculation();
print_r($voiture);
$voiture->setAnnee(1900);
echo $voiture->annee();
?>

	<body>
		
	</body>
	
</html>
