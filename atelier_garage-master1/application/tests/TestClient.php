<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>TestClient</title>

<?php
require('../objects/Client.class.php');

$client = new Client([
	'numero'=>'12345',
	'nom'=>'daho',
	'prenom'=>'e',
	'adresse'=>'Paris',
	'referent'=>'marc']);

echo $client->nom();
echo $client->referent();
print_r($client);
$client->setAdresse('lyon');
echo $client->adresse();
?>

	<body>
		
	</body>
	
</html>
