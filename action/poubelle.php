<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function action_poubelle_dist() {
	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();

	list($id_me) = preg_split(',[^0-9],', $arg);
	
	if (intval($id_me) and ($GLOBALS['auteur_session']['statut'] == '0minirezo')) {
		supprimer_me($id_me);
	}

	header('Location:'.$GLOBALS['meta']['adresse_site']);
	exit;
}
