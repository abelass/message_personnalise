<?php
/**
 * Gestion du formulaire de d'édition de mp_message
 *
 * @plugin     Message personnalisé
 * @copyright  2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Formulaires
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_mp_message
 *        	Identifiant du mp_message. 'new' pour un nouveau mp_message.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param string $associer_objet
 *        	Éventuel `objet|x` indiquant de lier le mp_message créé à cet objet,
 *        	tel que `article|3`
 * @param int $lier_trad
 *        	Identifiant éventuel d'un mp_message source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du mp_message, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string Hash du formulaire
 */
function formulaires_editer_mp_message_identifier_dist($id_mp_message = 'new', $retour = '', $associer_objet = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	return serialize(array(
		intval($id_mp_message),
		$associer_objet
	));
}

/**
 * Chargement du formulaire d'édition de mp_message
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_mp_message
 *        	Identifiant du mp_message. 'new' pour un nouveau mp_message.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param string $associer_objet
 *        	Éventuel `objet|x` indiquant de lier le mp_message créé à cet objet,
 *        	tel que `article|3`
 * @param int $lier_trad
 *        	Identifiant éventuel d'un mp_message source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du mp_message, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Environnement du formulaire
 */
function formulaires_editer_mp_message_charger_dist($id_mp_message = 'new', $retour = '', $associer_objet = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	include_spip('inc/message_personnalise');
	$valeurs = formulaires_editer_objet_charger('mp_message', $id_mp_message, '', $lier_trad, $retour, $config_fonc, $row, $hidden);

	foreach (array('declencheur_statut', 'declencheur_qui') AS $champ) {
		if (isset($valeurs[$champ])) {
			$valeurs[$champ] = json_decode($valeurs[$champ]);
		}
	}
	$messages_personalisables = find_all_in_path("messages_personnalises/", '^');

	if (is_array($messages_personalisables)) {
		foreach (array_keys($messages_personalisables) as $fichier) {
			$explode = explode('.', $fichier);
			$nom = $explode[0];

			// Charger les valeurs spécifiques.
			if ($message = mp_charger_definition($nom, $valeurs)) {

				// Les message par nom.
				$valeurs['_types'][$nom] = $message['label'];


				// Le reste  par nom de message.
				foreach ($message as $champ => $valeur) {

					// Le cas normal
					if (!in_array($champ, array('declencheurs', 'raccoursis'))) {
						$valeurs[$champ][$nom] = $valeur;
					}
					// Les raccoursis
					elseif($champ == 'raccoursis') {

						// Les remplacement de champs
						// Si'il y des champs lies, on n'affiche que le principal.
						$exclus = isset($valeur['champs']['lies']) ?
							array_values($valeur['champs']['lies']) :
							array();
						$champs_disponibles = isset($valeur['champs']['disponibles']) ?
							$valeur['champs']['disponibles'] :
							array();

						foreach ($champs_disponibles AS $champ_disponible) {
							if (!in_array($champ_disponible, $exclus)) {
								$valeurs['champs_disponibles'][$nom][] = $champ_disponible;
							}
						}

						// Les inclures
						if (isset($valeur['inclures'])) {
							$valeurs['inclures'][$nom] = $valeur['inclures'];
						}
					}
					// Les déclencheurs.
					elseif (is_array($valeur)) {
						foreach ($valeur as $declencheur => $data) {
							$valeurs['_definitions'][$nom]['declencheur_' . $declencheur] = $data;
						}
					}
				}
			}
		}
	}

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de mp_message
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_mp_message
 *        	Identifiant du mp_message. 'new' pour un nouveau mp_message.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param string $associer_objet
 *        	Éventuel `objet|x` indiquant de lier le mp_message créé à cet objet,
 *        	tel que `article|3`
 * @param int $lier_trad
 *        	Identifiant éventuel d'un mp_message source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du mp_message, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Tableau des erreurs
 */
function formulaires_editer_mp_message_verifier_dist($id_mp_message = 'new', $retour = '', $associer_objet = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	$erreurs = array();

	$erreurs = formulaires_editer_objet_verifier('mp_message', $id_mp_message, array(
		'titre',
		'nom',
		'texte'
	));

	if (count($erreurs) == 0) {
		foreach (array('declencheur_statut', 'declencheur_qui') AS $champ) {
			if (_request($champ)) {
				set_request($champ, json_encode(_request($champ)));
			}
		}
	}

	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de mp_message
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_mp_message
 *        	Identifiant du mp_message. 'new' pour un nouveau mp_message.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param string $associer_objet
 *        	Éventuel `objet|x` indiquant de lier le mp_message créé à cet objet,
 *        	tel que `article|3`
 * @param int $lier_trad
 *        	Identifiant éventuel d'un mp_message source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du mp_message, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Retours des traitements
 */
function formulaires_editer_mp_message_traiter_dist($id_mp_message = 'new', $retour = '', $associer_objet = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {

	$retours = formulaires_editer_objet_traiter('mp_message', $id_mp_message, '', $lier_trad, $retour, $config_fonc, $row, $hidden);

	// Un lien a prendre en compte ?
	if ($associer_objet and $id_mp_message = $retours['id_mp_message']) {
		list($objet, $id_objet) = explode('|', $associer_objet);

		if ($objet and $id_objet and autoriser('modifier', $objet, $id_objet)) {
			include_spip('action/editer_liens');

			objet_associer(array(
				'mp_message' => $id_mp_message
			), array(
				$objet => $id_objet
			));

			if (isset($retours['redirect'])) {
				$retours['redirect'] = parametre_url($retours['redirect'], 'id_lien_ajoute', $id_mp_message, '&');
			}
		}
	}

	return $retours;
}
