<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

define('_SELECTEUR_GENERIQUE_ACTIVER_PUBLIC', true);

// autoriser le prive uniquement pour les webmestres
function autoriser_ecrire($faire, $type, $id, $qui, $opt) {
	return autoriser('webmestre', null, null, $qui, $opt);
}