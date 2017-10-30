<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

define('_SELECTEUR_GENERIQUE_ACTIVER_PUBLIC', true);

define('_UTILISER_PIE_HTC', false);
define('_UTILISER_BOXSIZING_HTC', false);

define('_OCTOPOUCE_AUTEUR', 3);

// autoriser le prive uniquement pour les webmestres
function autoriser_ecrire($faire, $type, $id, $qui, $opt) {
	return autoriser('webmestre', null, null, $qui, $opt);
}

// utiliser des mots de poulpes dans les urls
$GLOBALS['url_arbo_types'] = array(
	'mot' => 'tag',
	'auteur' => 'poulpes',
	'message' => 'pouits',
	'site' => 'sites'
);