<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

function octopouce_declarer_champs_extras($champs = array()) {

	// Table : spip_auteurs
	if (!is_array($champs['spip_auteurs'])) {
		$champs['spip_auteurs'] = array();
	}

	$champs['spip_auteurs']['nom_famille'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'nom_famille',
			'label' => '<:octopouce:profil_nom_famille:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
			'obligatoire' => true,
			'traitements' => '_TRAITEMENT_TYPO',
		)
	);

	$champs['spip_auteurs']['prenom'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'prenom',
			'label' => '<:octopouce:profil_prenom:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
			'obligatoire' => true,
			'traitements' => '_TRAITEMENT_TYPO',
		)
	);

	$champs['spip_auteurs']['adresse'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'adresse',
			'label' => '<:octopouce:profil_adresse:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
			'obligatoire' => true,
			'traitements' => '_TRAITEMENT_TYPO',
		)
	);

	$champs['spip_auteurs']['tel_fixe'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'tel_fixe',
			'label' => '<:octopouce:profil_tel_fixe:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
		)
	);

	$champs['spip_auteurs']['tel_portable'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'tel_portable',
			'label' => '<:octopouce:profil_tel_portable:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
		)
	);

	return $champs;
}