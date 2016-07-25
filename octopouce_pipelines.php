<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Insertion dans le pipeline insert_head (SPIP)
 * Ajouter les scripts nécessaires au projet au head des pages
 *
 * @pipeline pre_edition
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
 */
function octopouce_insert_head($flux){
	$flux .="\n".'<script type="text/javascript" src="'. find_in_path('javascript/jquery.tooltipster.js') .'"></script>';
	return $flux;
}

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
		// traitement spécifique pour les dates, normaliser celle-ci
		if (_request('date_naissance')) {
			include_spip('inc/date_gestion');
			$erreurs = array();
			$flux['data']['date_naissance'] = date('Y-m-d',verifier_corriger_date_saisie('naissance', false, $erreurs));
		}
		// traitement spécifique pour le champ commune, lier la commune sélectionnée à l'auteur
		if (_request('commune')) {
			include_spip('action/editer_liens');
			objet_dissocier(array('geo_commune'=>'*'), array('auteur' => $flux['args']['id_objet']));
			objet_associer(array('geo_commune' => _request('commune')), array('auteur' => $flux['args']['id_objet']));
		}
	}
	return $flux;
}

/**
 * Insertion dans le pipeline formulaire_verifier (SPIP)
 * Vérifier les extras obligatoires sur les forms inscription & profil
 * Imposer le statut envoyé par le formulaire editer_auteur dans le public (pas de fourberie)
 * 
 * @pipeline formulaire_verifier
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
 */
function octopouce_formulaire_verifier($flux){
	if (!test_espace_prive() and in_array($flux['args']['form'], array('inscription','profil'))) {
		include_spip('base/octopouce');
		$extras = octopouce_declarer_champs_extras();
		foreach ($extras['spip_auteurs'] as $extra) {
			$nom = $extra['options']['nom'];
			if (isset($extra['options']['obligatoire']) and $extra['options']['obligatoire'] and !_request($nom)) {
				$flux['data'][$nom] = _T('info_obligatoire');
			}
		}
		// vérification spécifique pour les dates
		include_spip('inc/date_gestion');
		if (!$flux['data']['date_naissance'] and _request('date_naissance')) {
			$date_naissance = verifier_corriger_date_saisie('naissance', false, $flux['data']);
		}
		// vérification spécifique pour le champ commune, on affiche l'erreur sur l'input autcomplete ville
		if (!_request('commune')) {
			$flux['data']['ville'] = _T('info_obligatoire');
		}
		// vérification spécifique pour la checkbox de la charte lors de l'inscription
		if ($flux['args']['form'] == 'inscription' and !_request('cgu')) {
			$flux['data']['cgu'] = _T('info_obligatoire');
		}
	}
	if (!test_espace_prive() and $flux['args']['form'] == 'editer_auteur') {
		set_request('statut', '1comite');
	}
	return $flux;
}

/**
 * Insertion dans le pipeline formulaire_traiter (SPIP)
 * Supprimer le paramètre id_auteur de l'url lors du retour du formulaire editer_auteur dans le public
 * 
 * @pipeline formulaire_traiter
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
 */
function octopouce_formulaire_traiter($flux){
	if (!test_espace_prive() and $flux['args']['form'] == 'editer_auteur') {
		$flux['data']['redirect'] = $flux['args']['args'][1];
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
	if (!test_espace_prive() and $flux['args']['form'] == 'inscription') {
		$extras = recuperer_fond('formulaires/inc-inscription', $flux['args']['contexte']);
		$flux['data'] = preg_replace('%(<li class=["\'][^"\']*saisie_mail_inscription(.*?)</li>)%is', '$1'."\n".$extras, $flux['data']);
	}
	if (!test_espace_prive() and $flux['args']['form'] == 'editer_gis') {
		$flux['data'] = recuperer_fond('formulaires/editer_gis_public', $flux['args']['contexte']);
	}
	if (!test_espace_prive() and $flux['args']['form'] == 'editer_auteur') {
		$flux['data'] = recuperer_fond('formulaires/editer_profil', $flux['args']['contexte']);
	}
	return $flux;
}

 /**
 * Insertion dans le pipeline affichage_final (SPIP)
 * Surcharge de js.textes_interface afin d'annuler la fonction afficher_traduire()
 * 
 * @pipeline affichage_final
 * @param string $flux Données du pipeline
 * @return string      Données du pipeline
 */
function octopouce_affichage_final($flux){
	if (!$GLOBALS['html'] and strpos($flux,'afficher_traduire();') !== false) {
		$ajout = "function afficher_traduire() { return false; }\n\n";
		$flux = substr_replace($flux, $ajout, strpos($flux, 'afficher_traduire();'), 0);
	}
	return $flux;
}

 /**
 * Insertion dans le pipeline seenthis_notifierme_destinataires (Plugin seenthis)
 * Ajouter aux destinataires des notifications les personnes qui suivent les tags présents dans le post
 * 
 * @pipeline seenthis_notifierme_destinataires
 * @param array $flux Données du pipeline
 * @return array      Données du pipeline
 */
function octopouce_seenthis_notifierme_destinataires($flux){
	$texte = texte_de_me($flux['args']['id_me']);

	// auteurs abonnés aux tags cités dans le message ;
	include_spip('inc/traiter_texte');
	$t = preg_replace("/"._REG_URL."/ui", "", $texte);
	if (preg_match_all("/"._REG_HASH."/ui", $t, $tags)) {
		foreach(array_unique(array_values($tags[0])) as $tag) {
			$tag = implode('|', explode('_', substr($tag, 1)));
			$query_follow = sql_select("id_follow", "spip_me_follow_tag", "tag REGEXP '$tag'");
			while ($row_follow = sql_fetch($query_follow)) {
				$flux['data'][] = $row_follow['id_follow'];
			}
		}
	}

	return $flux;
}