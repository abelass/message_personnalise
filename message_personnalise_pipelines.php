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
