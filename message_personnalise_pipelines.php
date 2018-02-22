<?php
/**
 * Utilisations de pipelines par Message personnalisé
 *
 * @plugin     Message personnalisé
 * @copyright  2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Ajout de contenu sur certaines pages,
 * notamment des formulaires de liaisons entre objets
 *
 * @pipeline affiche_milieu
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function message_personnalise_affiche_milieu($flux) {
	include_spip('inc/config');
	$texte = '';
	$e = trouver_objet_exec($flux['args']['exec']);

	// mp_messages sur les objets choisis
	if (!$e['edition'] and in_array($e['table_objet_sql'],
			lire_config('message_personnalise/objets', array ()))) {
		$texte .= recuperer_fond('prive/objets/editer/liens', array(
			'table_source' => 'mp_messages',
			'objet' => $e['type'],
			'id_objet' => $flux['args'][$e['id_table_objet']]
		));
}

	if ($texte) {
		if ($p = strpos($flux['data'], '<!--affiche_milieu-->')) {
			$flux['data'] = substr_replace($flux['data'], $texte, $p, 0);
		} else {
			$flux['data'] .= $texte;
		}
	}

	return $flux;
}

/**
 * Optimiser la base de données
 *
 * Supprime les liens orphelins de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 * Supprime les objets à la poubelle.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function message_personnalise_optimiser_base_disparus($flux) {

	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array('mp_message'=>'*'), '*');

	sql_delete('spip_mp_messages', "statut='poubelle' AND maj < " . $flux['args']['date']);

	return $flux;
}

/**
 * Permet de compléter ou modifier le résultat de la compilation d’un squelette donné.
 *
 * @pipeline recuperer_fond
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array Données du pipeline
 */
function message_personnalise_recuperer_fond($flux) {
	$fond = $flux['args']['fond'];
	$contexte = $flux['data']['contexte'];

	$messages_personalisables = find_all_in_path("messages_personnalises/", '^');

	if (is_array($messages_personalisables)) {

		foreach (array_keys($messages_personalisables) as $fichier) {
			$explode = explode('.', $fichier);
			$type = $explode[0];

			// Charger les définitions spécifiques.
			if (include_spip('inc/message_personnalise') AND
					$definition = mp_charger_definition($type, $valeurs) AND
					isset($definition['fond']) AND
					$definition['fond'] == $fond) {

				if (!isset($contexte['objet']) AND isset($definition['objet'])) {
					$contexte['objet'] = $definition['objet'];
				}

				if (isset($contexte['objet']) AND !isset($contexte['id_objet'])) {
					if (isset($definition['_id_objet'])) {
						$contexte['id_objet'] = $contexte[$definition['_id_objet']];
					}
					else {
						$_id_objet = id_table_objet($contexte['objet']);
						$contexte['id_objet'] = isset($contexte[$_id_objet]) ? $contexte[$_id_objet] : '';
					}
				}

				$flux['data']['texte'] = chercher_message_personnalise(
						$contexte['texte'],
						$type,
						$contexte
					);
			}
		}
	}

	return $flux;
}
