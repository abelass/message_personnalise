<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Message personnalisé
 * @copyright  2018 - 2022
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Message_personnalise\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function message_personnalise_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['mp_messages'] = 'mp_messages';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function message_personnalise_declarer_tables_objets_sql($tables) {

	$tables['spip_mp_messages'] = array(
		'type' => 'mp_message',
		'principale' => 'oui',
		'table_objet_surnoms' => array('mpmessage'), // table_objet('mp_message') => 'mp_messages'
		'field'=> array(
			'id_mp_message'      => 'bigint(21) NOT NULL',
			'titre'              => 'varchar(50) NOT NULL DEFAULT ""',
			'sujet'              => 'varchar(255) NOT NULL DEFAULT ""',
			'type'               => 'varchar(50) NOT NULL DEFAULT ""',
			'declencheur_statut' => 'varchar(255) NOT NULL DEFAULT ""',
			'declencheur_qui'    => 'varchar(255) NOT NULL DEFAULT ""',
			'declencheur_quoi'    => 'varchar(255) NOT NULL DEFAULT ""',
			'descriptif'         => 'text NOT NULL DEFAULT ""',
			'texte'              => 'text NOT NULL DEFAULT ""',
			'date'               => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'statut'             => 'varchar(20)  DEFAULT "0" NOT NULL',
			'lang'               => 'VARCHAR(10) NOT NULL DEFAULT ""',
			'langue_choisie'     => 'VARCHAR(3) DEFAULT "non"',
			'id_trad'            => 'bigint(21) NOT NULL DEFAULT 0',
			'maj'                => 'TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_mp_message',
			'KEY lang'           => 'lang',
			'KEY id_trad'        => 'id_trad',
			'KEY statut'         => 'statut',
		),
		'titre' => 'titre AS titre, lang AS lang',
		'date' => 'date',
		'champs_editables'  => array('titre', 'sujet', 'type', 'declencheur_statut', 'declencheur_qui', 'declencheur_quoi', 'descriptif', 'texte'),
		'champs_versionnes' => array('titre', 'sujet', 'type', 'declencheur_statut', 'declencheur_qui', 'declencheur_quoi', 'descriptif', 'texte'),
		'rechercher_champs' => array("titre" => 10, "sujet" => 5, "type" => 2, "descriptif" => 5, "texte" => 8),
		'tables_jointures'  => array('spip_mp_messages_liens'),
		'statut_textes_instituer' => array(
			'prepa'    => 'texte_statut_en_cours_redaction',
			'prop'     => 'texte_statut_propose_evaluation',
			'publie'   => 'texte_statut_publie',
			'refuse'   => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle',
		),
		'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'publie',
				'previsu'   => 'publie,prop,prepa',
				'post_date' => 'date',
				'exception' => array('statut','tout')
			)
		),
		'texte_changer_statut' => 'mp_message:texte_changer_statut_mp_message',


	);

	return $tables;
}


/**
 * Déclaration des tables secondaires (liaisons)
 *
 * @pipeline declarer_tables_auxiliaires
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function message_personnalise_declarer_tables_auxiliaires($tables) {

	$tables['spip_mp_messages_liens'] = array(
		'field' => array(
			'id_mp_message'      => 'bigint(21) DEFAULT "0" NOT NULL',
			'id_objet'           => 'bigint(21) DEFAULT "0" NOT NULL',
			'objet'              => 'VARCHAR(25) DEFAULT "" NOT NULL',
			'vu'                 => 'VARCHAR(6) DEFAULT "non" NOT NULL',
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_mp_message,id_objet,objet',
			'KEY id_mp_message'  => 'id_mp_message',
		)
	);

	return $tables;
}
