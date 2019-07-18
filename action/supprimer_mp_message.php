<?php
/**
 * Utilisation de l'action supprimer pour l'objet mp_message
 *
 * @plugin     Message personnalisé
 * @copyright  2018 - 2019
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Action
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}



/**
 * Action pour supprimer un·e mp_message
 *
 * Vérifier l'autorisation avant d'appeler l'action.
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, mp_message, #ID_MP_MESSAGE}|oui)
 *         [(#BOUTON_ACTION{<:mp_message:supprimer_mp_message:>,
 *             #URL_ACTION_AUTEUR{supprimer_mp_message, #ID_MP_MESSAGE, #URL_ECRIRE{mp_messages}},
 *             danger, <:mp_message:confirmer_supprimer_mp_message:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, mp_message, #ID_MP_MESSAGE}|oui)
 *         [(#BOUTON_ACTION{
 *             [(#CHEMIN_IMAGE{mp_message-del-24.png}|balise_img{<:mp_message:supprimer_mp_message:>}|concat{' ',#VAL{<:mp_message:supprimer_mp_message:>}|wrap{<b>}}|trim)],
 *             #URL_ACTION_AUTEUR{supprimer_mp_message, #ID_MP_MESSAGE, #URL_ECRIRE{mp_messages}},
 *             icone s24 horizontale danger mp_message-del-24, <:mp_message:confirmer_supprimer_mp_message:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     if (autoriser('supprimer', 'mp_message', $id_mp_message)) {
 *          $supprimer_mp_message = charger_fonction('supprimer_mp_message', 'action');
 *          $supprimer_mp_message($id_mp_message);
 *     }
 *     ```
 *
 * @param null|int $arg
 *     Identifiant à supprimer.
 *     En absence de id utilise l'argument de l'action sécurisée.
**/
function action_supprimer_mp_message_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	$arg = intval($arg);

	// cas suppression
	if ($arg) {
		sql_delete('spip_mp_messages',  'id_mp_message=' . sql_quote($arg));
	}
	else {
		spip_log("action_supprimer_mp_message_dist $arg pas compris");
	}
}
