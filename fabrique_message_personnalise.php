<?php

/**
 *  Fichier généré par la Fabrique de plugin v6
 *   le 2018-02-12 14:16:11
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$data = array (
  'fabrique' => 
  array (
    'version' => 6,
  ),
  'paquet' => 
  array (
    'prefixe' => 'message_personnalise',
    'nom' => 'Message personnalisé',
    'slogan' => 'Permet de personnaliser certain message  système',
    'description' => 'Permet de personnaliser les messages système rendus personnalisables par un le créateur du messages (souven un plugin)',
    'version' => '1.0.0',
    'auteur' => 'Rainer Müller',
    'auteur_lien' => '',
    'licence' => 'GNU/GPL',
    'categorie' => 'communication',
    'etat' => 'dev',
    'compatibilite' => '[3.0.0;3.2.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '1.0.0',
    'formulaire_config' => 'on',
    'formulaire_config_titre' => 'Paramètres message personnalisé',
    'fichiers' => 
    array (
      0 => 'autorisations',
      1 => 'fonctions',
      2 => 'options',
      3 => 'pipelines',
    ),
    'inserer' => 
    array (
      'paquet' => '',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => 'on',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => 'Messages',
      'nom_singulier' => 'Message',
      'genre' => 'masculin',
      'logo' => 
      array (
        0 => '',
        32 => '',
        24 => '',
        16 => '',
        12 => '',
      ),
      'logo_variantes' => '',
      'table' => 'spip_messages',
      'cle_primaire' => 'id_message',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'message',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Titre',
          'champ' => 'titre',
          'sql' => 'varchar(50) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '10',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Sujet',
          'champ' => 'sujet',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '5',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        2 => 
        array (
          'nom' => 'Déclencheur statut',
          'champ' => 'declencheur_statut',
          'sql' => 'varchar(50) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => 'selection',
          'explication' => '',
          'saisie_options' => '',
        ),
        3 => 
        array (
          'nom' => 'Déclencheur qui',
          'champ' => 'declencheur_qui',
          'sql' => 'varchar(50) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => 'selection',
          'explication' => '',
          'saisie_options' => '',
        ),
        4 => 
        array (
          'nom' => 'Descriptif',
          'champ' => 'descriptif',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '5',
          'saisie' => 'textarea',
          'explication' => 'Usage interne',
          'saisie_options' => '',
        ),
        5 => 
        array (
          'nom' => 'Texte',
          'champ' => 'texte',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '8',
          'saisie' => 'textarea',
          'explication' => 'Message personnalisé',
          'saisie_options' => 'conteneur_class=pleine_largeur, class=inserer_barre_edition, rows=4',
        ),
      ),
      'champ_titre' => 'titre',
      'langues' => 
      array (
        0 => 'lang',
        1 => 'id_trad',
      ),
      'champ_date' => '',
      'statut' => 'on',
      'chaines' => 
      array (
        'titre_objets' => 'Messages',
        'titre_objet' => 'Message',
        'info_aucun_objet' => 'Aucun message',
        'info_1_objet' => 'Un message',
        'info_nb_objets' => '@nb@ messages',
        'icone_creer_objet' => 'Créer un message',
        'icone_modifier_objet' => 'Modifier ce message',
        'titre_logo_objet' => 'Logo de ce message',
        'titre_langue_objet' => 'Langue de ce message',
        'texte_definir_comme_traduction_objet' => 'Ce message est une traduction du message numéro :',
        'titre_\\objets_lies_objet' => 'Liés à ce message',
        'titre_objets_rubrique' => 'Messages de la rubrique',
        'info_objets_auteur' => 'Les messages de cet auteur',
        'retirer_lien_objet' => 'Retirer ce message',
        'retirer_tous_liens_objets' => 'Retirer tous les messages',
        'ajouter_lien_objet' => 'Ajouter ce message',
        'texte_ajouter_objet' => 'Ajouter un message',
        'texte_creer_associer_objet' => 'Créer et associer un message',
        'texte_changer_statut_objet' => 'Ce message est :',
        'supprimer_objet' => 'Supprimer cet message',
        'confirmer_supprimer_objet' => 'Confirmez-vous la suppression de cet message ?',
      ),
      'liaison_directe' => '',
      'table_liens' => 'on',
      'vue_liens' => 
      array (
        0 => 'spip_articles',
      ),
      'afficher_liens' => 'on',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'fichiers' => 
      array (
        'echafaudages' => 
        array (
          0 => 'prive/squelettes/contenu/objets.html',
          1 => 'prive/objets/infos/objet.html',
          2 => 'prive/squelettes/contenu/objet.html',
        ),
        'explicites' => 
        array (
          0 => 'action/supprimer_objet.php',
        ),
      ),
      'saisies' => 
      array (
        0 => 'objets',
      ),
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => 'png',
          'contenu' => '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',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
      ),
    ),
  ),
);
