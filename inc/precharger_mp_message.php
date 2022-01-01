<?php
/**
 * Préchargement des formulaires d'édition de mp_message
 *
 * @plugin     Message personnalisé
 * @copyright  2018 - 2022
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

include_spip('inc/precharger_objet');

/**
 * Retourne les valeurs à charger pour un formulaire d'édition d'un mp_message
 *
 * Lors d'une création, certains champs peuvent être préremplis
 * (c'est le cas des traductions)
 *
 * @param string|int $id_mp_message
 *     Identifiant de mp_message, ou "new" pour une création
 * @param int $id_rubrique
 *     Identifiant éventuel de la rubrique parente
 * @param int $lier_trad
 *     Identifiant éventuel de la traduction de référence
 * @return array
 *     Couples clés / valeurs des champs du formulaire à charger.
**/
function inc_precharger_mp_message_dist($id_mp_message, $id_rubrique=0, $lier_trad=0) {
	return precharger_objet('mp_message', $id_mp_message, $id_rubrique, $lier_trad, 'titre');
}

/**
 * Récupère les valeurs d'une traduction de référence pour la création
 * d'un mp_message (préremplissage du formulaire).
 *
 * @note
 *     Fonction facultative si pas de changement dans les traitements
 *
 * @param string|int $id_mp_message
 *     Identifiant de mp_message, ou "new" pour une création
 * @param int $id_rubrique
 *     Identifiant éventuel de la rubrique parente
 * @param int $lier_trad
 *     Identifiant éventuel de la traduction de référence
 * @return array
 *     Couples clés / valeurs des champs du formulaire à charger
**/
function inc_precharger_traduction_mp_message_dist($id_mp_message, $id_rubrique=0, $lier_trad=0) {
	return precharger_traduction_objet('mp_message', $id_mp_message, $id_rubrique, $lier_trad, 'titre');
}
