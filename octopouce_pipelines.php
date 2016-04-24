<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Insertion dans le pipeline pre_edition (SPIP)
 * Ajouter les valeurs des extras lors de la soumission des forms inscription & profil
 *
 * @param array $flux
 * @return array
 */
function octopouce_pre_edition($flux){
	if ($flux['args']['table'] == 'spip_auteurs') {
		include_spip('base/octopouce');
		$extras = octopouce_declarer_champs_extras();
		foreach ($extras['spip_auteurs'] as $extra) {
			$nom = $extra['options']['nom'];
			$extra = _request($nom);
			$flux['data'][$nom] = $extra;
		}
	}
	return $flux;
}

/**
 * Insertion dans le pipeline formulaire_verifier (SPIP)
 * 
 * Vérifier les extras obligatoires sur les forms inscription & profil
 * 
 * @param array $flux
 * 		Le contexte du pipeline
 * @return array $flux
 */
function octopouce_formulaire_verifier($flux){
	if (in_array($flux['args']['form'], array('inscription','profil'))) {
		include_spip('base/octopouce');
		$extras = octopouce_declarer_champs_extras();
		foreach ($extras['spip_auteurs'] as $extra) {
			$nom = $extra['options']['nom'];
			if (isset($extra['options']['obligatoire']) and $extra['options']['obligatoire'] and !_request($nom)) {
				spip_log("erreur $nom","bb");
				$flux['data'][$nom] = _T('info_obligatoire');
			}
		}
	}
	return $flux;
}

/**
 * Insertion dans le traitement du formulaire inscription
 * 
 * Prendre en compte les extras
 * 
 * @param array $flux
 * 		Le contexte du pipeline
 * @return array $flux
 */
function octopouce_formulaire_traiter($flux){
	if ($flux['args']['form'] == 'inscription') {
		/*
		if (!intval($flux['args']['args'][0]) AND ($flux['args']['args'][1] == sql_getfetsel('id_article', 'spip_articles', 'page='.sql_quote('agenda')) AND autoriser('ecrire'))) {
			autoriser_exception('modifier','article',$flux['args']['args'][1]);
			evenement_modifier($flux['data']['id_evenement'],array('statut'=>'publie'));
			autoriser_exception('modifier','article',$flux['args']['args'][1],false);
		}
		*/
	}
	return $flux;
}
