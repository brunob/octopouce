<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function formulaires_rechercher_pouit_charger_dist() {
	return
		array(
			'action' => 'recherche',
			'type_message' => _request('type_message'),
			'depart' => _request('depart'),
			'arrivee' => _request('arrivee'),
			'quand' => _request('quand')
		);
}
/*
function formulaires_rechercher_pouit_traiter_dist() {
	$res = array('editable' => true);

	// construire la chaine de tags pour la recherche
	$recherche = _request('type_message') . ' ' . _request('depart') . '_' . _request('arrivee');
	if (_request('date', _request('date'))) {
		$recherche .= ' #' . _request('date', _request('date'));
	}
	if (_request('heure', _request('date'))) {
		$recherche .= ' ' . str_replace(':', 'h', _request('heure', _request('date')));
	}

	$cible = parametre_url('recherche', 'recherche', $recherche, '&');

	include_spip('inc/headers');
	$res['redirect'] = $cible;
	return $res;
}
*/