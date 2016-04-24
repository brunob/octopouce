<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Insertion dans le pipeline pre_edition (SPIP)
 * Ajouter le type_auteur lors de la soumission du formulaire CVT editer_auteur
 * et lors de l'update d'un auteur a l'inscription
 *
 * @param array $flux
 * @return array
 */
function octopouce_pre_edition($flux){
	/*
	if ($flux['args']['table']=='spip_auteurs') {
		$requests = collecter_requests(array('prenom', 'famille', 'adresse'),array(),$flux['data']);
		$flux['data'] = $requests;
	}
	*/
	return $flux;
}

/**
 * Insertion dans la vérification du formulaire inscription
 * 
 * Vérifier les extras obligatoires
 * 
 * @param array $flux
 * 		Le contexte du pipeline
 * @return array $flux
 */
function octopouce_formulaire_verifier($flux){
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
