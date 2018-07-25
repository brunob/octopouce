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
