<?php
/**
 * Utilisation de l'action supprimer pour l'objet message
 *
 * @plugin     Message personnalisé
 * @copyright  2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Action
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}



/**
 * Action pour supprimer un·e message
 *
 * Vérifier l'autorisation avant d'appeler l'action.
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, message, #ID_MESSAGE}|oui)
 *         [(#BOUTON_ACTION{<:message:supprimer_message:>,
 *             #URL_ACTION_AUTEUR{supprimer_message, #ID_MESSAGE, #URL_ECRIRE{mp_messages}},
 *             danger, <:message:confirmer_supprimer_message:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, message, #ID_MESSAGE}|oui)
 *         [(#BOUTON_ACTION{
 *             [(#CHEMIN_IMAGE{message-del-24.png}|balise_img{<:message:supprimer_message:>}|concat{' ',#VAL{<:message:supprimer_message:>}|wrap{<b>}}|trim)],
 *             #URL_ACTION_AUTEUR{supprimer_message, #ID_MESSAGE, #URL_ECRIRE{mp_messages}},
 *             icone s24 horizontale danger message-del-24, <:message:confirmer_supprimer_message:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     if (autoriser('supprimer', 'message', $id_message)) {
 *          $supprimer_message = charger_fonction('supprimer_message', 'action');
 *          $supprimer_message($id_message);
 *     }
 *     ```
 *
 * @param null|int $arg
 *     Identifiant à supprimer.
 *     En absence de id utilise l'argument de l'action sécurisée.
**/
function action_supprimer_message_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	$arg = intval($arg);

	// cas suppression
	if ($arg) {
		sql_delete('spip_mp_messages',  'id_message=' . sql_quote($arg));
	}
	else {
		spip_log("action_supprimer_message_dist $arg pas compris");
	}
}
