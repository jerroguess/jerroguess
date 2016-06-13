<?php
// Récupère le dossier parent du dossier parent de index.php ; c'est à dire le dossier du projet
define('ROOT_PATH', dirname(dirname(__FILE__ )) );

// appel de la page common.php
require_once ROOT_PATH . '/application/common.php'; //le require_once s'assure que la page n'est appelé qu'une fois.

?>