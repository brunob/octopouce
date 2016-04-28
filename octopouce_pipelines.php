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
			if ($extra = _request($nom)) {
				$flux['data'][$nom] = $extra;
			}
		}
		// traitement spécifique pour les dates
		include_spip('inc/date_gestion');
		if (_request('date_naissance')) {
			$erreurs = array();
			$flux['data']['date_naissance'] = date('Y-m-d',verifier_corriger_date_saisie('naissance', false, $erreurs));
		}
	}
	return $flux;
}

/**
 * Insertion dans le pipeline formulaire_verifier (SPIP)
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
		// verification spécifique pour les dates
		include_spip('inc/date_gestion');
		if (!$flux['data']['date_naissance'] and _request('date_naissance')) {
			$date_naissance = verifier_corriger_date_saisie('naissance', false, $flux['data']);
		}
	}
	return $flux;
}

 /**
 * Insertion dans le pipeline formulaire_fond (SPIP)
 * Ajouter nos champs extras dans le formulaire d'inscription
 * 
 * @pipeline formulaire_fond
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
 */
function octopouce_formulaire_fond($flux){
	if ($flux['args']['form'] == 'inscription') {
		$extras = recuperer_fond('formulaires/inc-inscription', $flux['args']['contexte']);
		$flux['data'] = preg_replace('%(<li class=["\'][^"\']*saisie_mail_inscription(.*?)</li>)%is', '$1'."\n".$extras, $flux['data']);
	}
	return $flux;
}