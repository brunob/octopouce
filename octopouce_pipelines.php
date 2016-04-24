<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Insertion dans le pipeline pre_edition (SPIP)
 * Ajouter les valeurs des extras lors de la soumission des forms inscription & profil
 *
 * @pipeline pre_edition
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
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
 * @pipeline formulaire_verifier
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
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
