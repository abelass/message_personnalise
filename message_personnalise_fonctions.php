<?php
/**
 * Fonctions utiles au plugin Message personnalisé
 *
 * @plugin     Message personnalisé
 * @copyright  2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Fonctions
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Crée la balise MESSAGE_PERSONNALISE
 *
 * @param object $p
 * @return object
 */
function balise_MESSAGE_PERSONNALISE_dist($p) {
	$nom = interprete_argument_balise(1, $p);
	$message = interprete_argument_balise(2, $p);
	$objets_cibles = interprete_argument_balise(3, $p);
	$declencheurs = interprete_argument_balise(4, $p);
	$options = interprete_argument_balise(5, $p);
	$_id_objet = $p->boucles[$p->id_boucle]->primary;
	$id_objet = champ_sql($_id_objet, $p);
	$objet = $p->boucles[$p->id_boucle]->id_table;

	$p->code = "calculer_balise_MESSAGE_PERSONNALISE('$objet', $id_objet, $nom, $message, $objets_cibles, $declencheurs, $options)";

	return $p;
}

/**
 * Calculs spécifiques pour génerer le réultat.
 *
 * @param string $objet
 *        	Objet du context.
 * @param integer $id_objet
 *        	identifiant de l'objet du context.
 * @param string $nom
 *        	Type de message.
 * @param string $message
 *        	Les message original.
 * @param array $objets_cibles
 *        	Les objets auxquelles sont liés des messages.
 * @param array $declencheurs
 * @param array $options
 *
 * @return string
 */
function calculer_balise_MESSAGE_PERSONNALISE($objet, $id_objet, $nom, $message, $objets_cibles, $declencheurs, $options = array()) {
	include_spip('inc/message_personnalise');

	$traduction = isset($options['traduction']) ? $options['traduction'] : '';

	$args = array(
		'objet' => $objet,
		'id_objet' => $id_objet,
		'objets_cibles' => $objets_cibles,
		'declencheurs' => $declencheurs,
	);

	if (is_array($options)) {
		$args = array_merge($args,$options);
	}

	return chercher_message_personnalise($message, $nom, $args, $traduction);
}
