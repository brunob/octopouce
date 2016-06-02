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
			'versionner' => true,
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
			'versionner' => true,
			'traitements' => '_TRAITEMENT_TYPO',
		)
	);

	$champs['spip_auteurs']['date_naissance'] = array (
		'saisie' => 'date',
		'options' => array (
			'nom' => 'date_naissance',
			'label' => '<:octopouce:profil_date_naissance:>',
			'obligatoire' => true,
			'sql' => "date NOT NULL DEFAULT '0000-00-00'",
			'rechercher' => false,
			'versionner' => true,
		),
		'verifier' => array (
			'type' => 'date',
			'options' => array (
				'normaliser' => 'datetime',
			),
		)
	);

	$champs['spip_auteurs']['adresse_voie'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'adresse_voie',
			'label' => '<:octopouce:profil_adresse_voie:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
			'obligatoire' => true,
			'versionner' => true,
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
			'versionner' => true,
		)
	);

	$champs['spip_auteurs']['tel_portable'] = array (
		'saisie' => 'input',
		'options' => array (
			'nom' => 'tel_portable',
			'label' => '<:octopouce:profil_tel_portable:>',
			'sql' => "text NOT NULL DEFAULT ''",
			'rechercher' => false,
			'versionner' => true,
		)
	);

	return $champs;
}