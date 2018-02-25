<?php
/**
 * Fonctions utiles au plugin Message personnalisé
 *
 * @plugin     Message personnalisé
 * @copyright  2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Inc\Message_personnalise
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Regarde our un éventuel message personnalisé, sino retourne l'original.
 *
 * @param string $message
 *        	Le message original.
 * @param integer $type
 *        	Le type de message.
 * @param array $args
 *        	Les variables du contexte.
 *
 * @return string
 */
function chercher_message_personnalise($message, $type, $args = array()) {

	foreach ($args AS $champ => $valeur) {
		$$champ = $valeur;
	}

	// Charger les définitions spécifiques.
	$definition = mp_charger_definition($type, array(
		'objet' => $objet,
		'type' => $type,
		'id_objet' => $id_objet,
		'message' => $message,
		'objets_cibles' => $objets_cibles,
		'declencheurs' => $declencheurs,
		)
	);

	// Les infos de l'objet.
	$requete = isset($definition['requete']) ? $definition['requete'] : array();
	if ($objet && $id_objet) {
		$champs = isset($requete['champs']) ? $requete['champs'] : '*';
		$from = isset($requete['from']) ? $requete['from'] : table_objet_sql($objet);
		if (isset($requete['where'])) {
			$where = $requete['where'];
		}
		else {
			$where = id_table_objet($objet) . '=' . $id_objet;
		}
		$data_objet = sql_fetsel($champs, $from, $where);
	}

	// Générer la requête.
	$where = array(
		'm.statut LIKE ' . sql_quote('publie')
	);
	$from = 'spip_mp_messages AS m LEFT JOIN spip_mp_messages_liens as ml USING (id_mp_message)';

	if ($type) {
		$where[] = 'm.type LIKE' . sql_quote($type);
	}

	if (is_array($declencheurs)) {
		foreach ($declencheurs as $declencheur => $valeur) {
			$where[] = 'declencheur_' . $declencheur . ' LIKE ' . sql_quote('%["' . $valeur . '"]%');
		}
	}

	// Si plusieurs objets cible on cherche le premier message disponible.
	if (is_array($objets_cibles)) {
		foreach ($objets_cibles as $objet_cible => $id_objet_cible) {

			$where[] = 'ml.objet LIKE ' . sql_quote($objet_cible) . ' AND ml.id_objet =' . $id_objet_cible;
			if ($texte = sql_getfetsel('texte', $from, $where)) {
				break;
			}
		}
	}
	// Sinon on prend un message non lié correspondnant
	else {
		$where[] = 'ml.id_mp_message IS NULL';
		$texte = sql_getfetsel('texte', $from, $where);
	}

	// On prend le message personnalisé
	if ($texte) {
		// On rempace les raccoursis
		preg_match_all('#@(.+?)@#s', $texte, $match);

		foreach ($match[1] as $champ) {
			$valeur = isset($data_objet[$champ]) ? $data_objet[$champ] : generer_info_entite($id_objet, $objet, $champ);
			$args[$champ] = mp_chercher_valeur_champ($champ, $valeur, $data_objet);
		}

		$message = propre(_L($texte, $args));

		// in remplace les inclures
		preg_match_all('#\*I\*(.+?)\*I\*#s', $texte, $match);
		$pattern = array();
		$replace = array();
		foreach ($match[1] as $champ) {
			$pattern[] = '#\*I\*#';
			print $champ;
			$valeur = 'test';
			$args[$champ] = 'test';
		}



	}
	// Sinon le message original.
	else {
		$message = _T($message);
	}

	return $message;
}

/**
 * Cherche un un set de configuration pour le type de message.
 *
 * @param integer $type
 * @param array $args
 *
 * @return array
 */
function mp_charger_definition($type, $args = array()) {
	if ($definition = charger_fonction($type, "messages_personnalises", true)) {
		$definition = pipeline('mp_charger_definition', array(
			'args' => $args,
			'data' => $definition($args),
			)
		);
	}
	return $definition;
}

/**
 * Cherche si une fonctionne spécifique existe pour déterminer la valeur d'¡un champ.
 * Sinon, retourne la valeur original.
 *
 * @param integer $champ
 * @param integer $valeur
 * @param array $data_objet
 * @return integer
 */
function mp_chercher_valeur_champ($champ, $valeur, $data_objet) {
	if($definition_champ = charger_fonction(
			$champ,
			"messages_personnalises_champs",
			true)) {
		$valeur = $definition_champ($valeur, $data_objet);
	}
	return $valeur;
}