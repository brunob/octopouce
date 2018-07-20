<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

// merci cedric :)
// http://zone.spip.org/trac/spip-zone/browser/_plugins_/bootstrap/trunk/bootstrap_fonctions.php?rev=81537#L94

/**
 * Generer un bouton_action
 * utilise par #BOUTON_ACTION
 *
 * @param string $libelle
 * @param string $url
 * @param string $class
 * @param string $confirm
 *   message de confirmation oui/non avant l'action
 * @param string $title
 * @param string $callback
 *   callback js a appeler lors de l'evenement action (apres confirmation eventuelle si $confirm est non vide)
 *   et avant execution de l'action. Si la callback renvoie false, elle annule le declenchement de l'action
 * @return string
 */
function filtre_bouton_action_dist($libelle, $url, $class="", $confirm="", $title="", $callback=""){
	if ($confirm) {
		$confirm = "confirm(\"" . attribut_html($confirm) . "\")";
		if ($callback)
			$callback = "$confirm?($callback):false";
		else
			$callback = $confirm;
	}
	$ajax = explode(" ",$class);
	if (in_array("ajax",$ajax))
		$ajax = " ajax";
	else
		$ajax = "";
	$onclick = $callback?" onclick='return ".addcslashes($callback,"'")."'":"";
	$title = $title ? " title='$title'" : "";
	return "<form class='bouton_action_post$ajax' method='post' action='$url'><div>".form_hidden($url)
		."<button type='submit' class='submit btn $class'$title$onclick>$libelle</button></div></form>";
}

/**
 * Générer le texte des notifications (seenthis/seenthis_notifier)
 * Ajouter la super mention "cliquez sur ce lien" avant le lien...
 *
 * @param int $id_parent
 * @param int $id_me
 * @return string le texte du message
 */
function notifier_construire_texte($id_parent, $id_me) {
	if (!$id_parent) $id_parent = $id_me;
	$conversation = sql_allfetsel("id_me, id_auteur", "spip_me", "(id_me=$id_parent OR id_parent=$id_parent) AND statut='publi' AND id_me <= $id_me ORDER BY date");

	$max = 5;
	if (count($conversation) <= $max + 3)
		$max = 10000;

	$blabla = "\n(... " . (count($conversation) - $max - 1) . " messages...)\n\n";
	$ret = '';
	foreach ($conversation as $i => $row) {
		if ($i == 0 OR $i >= count($conversation) - $max) {
			$nom_auteur = nom_auteur($row["id_auteur"]);
			$id_c = $row["id_me"];
			$texte = texte_de_me($id_c);
			$ret .= ($id_c == $id_me)
				? "\n" . message_texte(($texte)) . "\n\n"
				: seenthis_email_quote( $nom_auteur . ' ' . trim(extraire_titre($texte)) )
					. "\n> ---------\n";
		} else {
			$ret .= $blabla;
			$blabla = '';
		}
	}

	return $ret ."\n". _T('octopouce:notification_prelien');

}

/**
 * seenthisrecherche -> tableau
 * @param string $u "env"
 * @return array|bool
 */
function inc_seenthisrecherche_to_array($u) {
	if (!$env = @unserialize($u))
		return false;
	$follow = strval(@$env['follow']);

	# valeur maximum de la pagination sur cette boucle DATA
	$max_pagination = 100;

	$debut = intval($env['debut_messages']);

	$moi = intval($GLOBALS['visiteur_session']['id_auteur']);

	$where = array();
	
	$auteurs_bloques = auteurs_bloques($moi);

	if (count($auteurs_bloques)) {
		$where[] = sql_in('m.id_auteur', $auteurs_bloques, 'NOT');
	}

	# fulltext
	$key = "`texte`";
	// construire la chaine de recherche, on souhaite tous les tags demandés
	$r = '';
	if ($env['type_message']) {
		$r .= '+' . $env['type_message'];
	}
	// depart & arrivee => chercher l'expression exacte
	// depart uniquement => depart_*
	// arrivee uniquement => *_arrivee
	if ($env['depart'] and $env['arrivee']) {
		$r .= ' +"' . $env['depart'] . '_' . $env['arrivee'] . '"';
	} elseif ($env['depart'] and !$env['arrivee']) {
		$r .= ' +' . $env['depart'] . '_*';
	} elseif (!$env['depart'] and $env['arrivee']) {
		$r .= ' +' . $env['arrivee'];
	}
	// on quote la date pour chercher l'expression exacte
	if ($env['quand']['date']) {
		$r .= ' +"' . $env['quand']['date'] . '"';
	}
	// et l'heure au format xxhxx pour finir
	if ($env['quand']['heure']) {
		$r .= ' +"' . str_replace(':', 'h', $env['quand']['heure']) . '"';
	}
	
	// il faut tout placer entre quotes, ou au moins la date et le trajet !

	// On utilise la translitteration pour contourner le pb des bases
	// declarees en iso-latin mais remplies d'utf8
	if (($r2 = translitteration($r)) != $r)
		$r .= ' '.$r2;

	$p = sql_quote(trim("$r"));

	// toujours en mode booleen
	$val = $match = "MATCH(r.$key) AGAINST ($p IN BOOLEAN MODE)";

	$where[] = "($match) > 0";
	$where[] = "m.statut='publi'";

	$res = sql_allfetsel("SQL_CALC_FOUND_ROWS r.id_me AS id, m.date, $val AS score", "spip_me_recherche AS r INNER JOIN spip_me AS m ON r.id_me=m.id_me", $where, null, 'score DESC'
	, "$debut,$max_pagination"
	);

	$t = sql_fetch(sql_query("SELECT FOUND_ROWS() as total"));
	# remplir avant debut, avec du vide
	for ($i=0; $i< $debut; $i++) {
		array_unshift($res, 0);
	}
	# remplir apres fin, avec du vide
	$grand_total = min(2000, intval($t['total']));
	for ($i=count($res); $i < $grand_total; $i++) {
		array_push($res, 0);
	}

	return $res;
}