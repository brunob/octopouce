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