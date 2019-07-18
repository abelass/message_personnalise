<?php
/**
 * Fonctions utiles au plugin Message personnalisé
 *
 * @plugin     Message personnalisé
 * @copyright  2018 - 2019
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Inc\Message_personnalise
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Cherche un éventuel message personnalisé, sinon retourne l'original.
 *
 * @param string $message
 *        	Le message original.
 * @param integer $nom
 *        	Le nom de message.
 * @param array $args
 *        	Les variables du contexte.
 * @param boolean $traduire
 *        	Si message original est une chaîne de langue -> TRUE.
 *
 * @return string
 */
function chercher_message_personnalise($message, $nom, $args = array(), $traduire = TRUE) {
	$lang = isset($args['lang']) ? $args['lang'] : $GLOBALS['spip_lang'];
	$_id_objet = '';
	$data_objet = array();

	foreach ($args as $champ => $valeur) {
		$$champ = $valeur;
	}

	$args['lang'] = $nom;

	if (!$id_mp_message) {

		// Charger les définitions spécifiques.
		$definition = mp_charger_definition(
				$nom,
				array_merge(
					$args,
					array(
						'objet' => $objet,
						'nom' => $nom,
						'qui' => $qui,
						'id_objet' => $id_objet,
						'message' => $message,
						'objets_cibles' => $objets_cibles,
						'declencheurs' => $declencheurs,
					)
				)
			);

		// Générer la requête.
		$from = 'spip_mp_messages AS m LEFT JOIN spip_mp_messages_liens as ml USING (id_mp_message)';

		$where = array(
			'm.statut LIKE ' . sql_quote('publie'),
			'lang LIKE ' .sql_quote($lang),
		);

		if ($nom) {
			$where[] = 'm.type LIKE ' . sql_quote($nom);
		}

		if (is_array($declencheurs)) {
			foreach ($declencheurs as $declencheur => $valeur) {
				if($valeur) {
					$where[] = '(
												m.declencheur_' . $declencheur . ' LIKE ' . sql_quote('%"' . trim($valeur) . '"%') .
												' OR m.declencheur_' . $declencheur . '=""
											)';
				}
			}
		}
		$texte = '';
		// Si plusieurs objets cible on cherche le premier message disponible.
		if (is_array($objets_cibles)) {
			foreach ($objets_cibles as $objet_cible => $id_objet_cible) {
				if($id_objet_cible) {
					$where[] = '(ml.objet LIKE ' . sql_quote($objet_cible) .
						' AND ml.id_objet =' . $id_objet_cible . ' OR
						ml.id_mp_message IS NULL)';
				}
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
	}
	else {
			$message = sql_fetsel('id_trad, texte',
					'spip_mp_messages', 'statut LIKE "publie" AND id_mp_message=' . $id_mp_message);
			$texte = $message['texte'];
			$id_trad =$message['id_trad'];
			if ($id_trad > 0 and
				$id_trad == $id_mp_message and
				!$texte = sql_getfetsel(
					'id_trad, texte',
					'spip_mp_messages',
					'statut LIKE "publie" AND id_trad=' . $id_mp_message . ' AND lang LIKE ' . sql_quote($lang))) {
				$texte = $message['texte'];
			}
		}

	// On prend le message personnalisé
	if ($texte) {
		// Les infos de l'objet.
		$requete = isset($definition['raccoursis']['requete']) ? $definition['raccoursis']['requete'] : array();
		if ($objet && $id_objet) {
			$_id_objet = id_table_objet($objet);
			$args[$_id_objet] = $id_objet;
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

		$data_objet = pipeline('mp_data_objet', array(
			'args' => $args,
			'data' => $data_objet
		));

		// Les filtres de bases.
		$message = typo(propre($texte));

		// On remplace les champs
		preg_match_all('#@(.+?)@#s', $message, $match);
		$valeurs = array();
		foreach ($match[1] as $champ) {

			$valeur = isset($data_objet[$champ]) ? $data_objet[$champ] : generer_info_entite($id_objet, $objet, $champ);
			$valeurs[$champ] = mp_chercher_valeur_champ($champ, $valeur, $data_objet, $definition);
		}

		$message = _L($message, $valeurs);

		// On remplace les inclures
		preg_match_all('#\*I\*(.+?)\*I\*#s', $message, $match);

		foreach ($match[1] as $champ) {
			if (isset($definition['raccoursis']['inclures'][$champ]['fond']) and
					$chemin = $definition['raccoursis']['inclures'][$champ]['fond'] and
					find_in_path($chemin . '.html')) {
				$fond = recuperer_fond($chemin, $args);
				$message = str_replace('*I*' . $champ . '*I*', $fond, $message);
			}
			elseif(isset($definition['raccoursis']['inclures'][$champ]['function']) and
					$inclure_fonction = $definition['raccoursis']['inclures'][$champ]['function']) {
				if (isset($inclure_fonction['fichier']) and
						include_spip($inclure_fonction['fichier']) and
						$fonction_nom = $inclure_fonction['nom']) {
					$arguments = isset($inclure_fonction['arguments']) ? $inclure_fonction['arguments'] : array();
					$function = call_user_func_array($fonction_nom, $arguments);
					$message = str_replace('*I*' . $champ . '*I*', $function, $message);
				}
			}
		}
	}
	// Sinon, si nécessaire, le message original traduit.
	elseif ($traduire) {
		$message = _T($message);
	}

	return $message;
}

/**
 * Cherche un un set de configuration pour le nom de message.
 *
 * @param integer $nom
 * @param array $args
 *
 * @return array
 */
function mp_charger_definition($nom, $args = array()) {
	if ($definition = charger_fonction($nom, "messages_personnalises", true)) {
		$definition = pipeline('mp_charger_definition', array(
			'args' => $args,
			'data' => $definition($args)
		));
	}
	return $definition;
}

/**
 * Cherche si une fonctionne spécifique existe pour déterminer la valeur d'¡un champ.
 * Sinon, retourne la valeur original.
 *
 * @param integer $champ
 *        	Le nom du champ.
 * @param integer $valeur
 *        	La valeur du champ.
 * @param array $data_objet
 *        	Toutes les valeurs de la requete.
 * @param array $definitions_messages
 *        	Les définitions du nom de message
 *
 * @return integer
 */
function mp_chercher_valeur_champ($champ, $valeur, $data_objet, $definitions_messages) {

	// Une fonction spéciale qui modifie la valeur.
	if ($definition_champ = charger_fonction($champ, "messages_personnalises_champs", true)) {
		$valeur = $definition_champ($valeur, $data_objet);
	}
	// Sinon, si pas de valeur, on cherche une éventuelle valeur liée.
	elseif (!$valeur and
			isset($definitions_messages['raccoursis']['champs']['lies'][$champ]) and
			$champ_lie = $definitions_messages['raccoursis']['champs']['lies'][$champ]) {
		$valeur = isset($data_objet[$champ_lie]) ? $data_objet[$champ_lie] : '';
	}

	return $valeur;
}
