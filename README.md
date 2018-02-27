# Message Personnalisé
Permet de personaliser des messages depuis l'espace prive

## Rendre les messages personnalisables


### Définir un message


#### Tableau de définition.

les définions se trouvent dans un fichier à l'interieur d'un dossier "message_personnalise", le nom du fichier est égale au type de message.

La fonction qui contient la définition est composé de manier suivante: message_personnalise_typemessage_dist($args)

donc pour le type de message "example"


message_personnalise/example.php


messages_personnalises_example_dist($args) {
``` php
$definition = array(
	'nom' = _T('reservation:titre_reservation'), 	// Obligatoire!
	'objet' = 'reservation',						// Nécessaire por les racoursis et si l'objet ne sort pas du contexte.
	'declencheurs' = array(						// Les déclenchers permettent de définir le contexte dans le quel un message personnalisé
		'statut' = array(							// doit templacer l'original. Si pas de déclencheur,
			'nom_statut' = 'label_statut',			// le message du type donné sera toujours remplacé
		),
		'qui' = array(
			'client' = _T('reservation:notifications_client_label'),
			'vendeur' = _T('reservation:notifications_vendeur_label')
		),
	),
	'raccoursis' = array(							// Les raccoursis permettent `l'éditeur d'insérer de contenus dynamique,
		'champs' = array(							// Des champs de l'objet principal .
			'disponibles' = array(					// Tableau des champs disponibles
				'champ1',
				'champ',2
			),
			'lies' = array(						// Tableau d'éventuels champs liés,
				'champ_original' = 'champ_lie',	// si le premier n'a pas de valeur, on prend la valeur du second.
			),
		),
		'inclures' = array(						// Les inclures à utiliser.
			'reservations' = array(				// Le nom qui sera utilisé pour faire le raccoursis.
				'titre' = _T('reservation:mp_titre_reservation_details'), // Le Titre, expliquant mieux le type d'inclure.
				'fond' = 'inclure/reservation',	// Genre noisette, chemin de la noisette.
			),
			'paiement' = array(
				'titre' = _T('reservation_bank:titre_mp_message_paiement'),
				'function' = array(				// Genre fonction
					'fichier' = 'inc/reservation_bank', // Chemin du fichier dans lequel se trouve la fonction.
					'nom' = 'reservation_bank_message_paiement', // Nom de la fonction
					'arguments' = array($flux['args']['id_objet'], $flux['args']['qui']), // Tableau d'éventuueles arguments
				),
			),
		),
	),
	'requete' = array( 							// Par défaut, si objet et id_objet es présent une requete de base retourne s
													// les valeurs de la table de l'objet, 'requete' pèrmet de personnaliser ceci.
		'champs' = $champs_sql,					// tableau des champs à utiliser, defaut '*'.
		'from' ='spip_reservations AS reservation LEFT JOIN spip_auteurs AS auteur USING(id_auteur)' // défaut table de l'objet.
		'where' = 'id_reservation=' . $args['id_objet'] . ' AND statut LIKE ' .$args['id_objet']', // Par défaut identifiant objet
	),
	'fond' = 'notifications/contenu_reservation_mail',	// Si déclaré le contenu de la noistte fond sera remplacé via la pipeline
														// 'recuperer_fond'
	);
);
	return $definition;
}
```


#### Squelettes

Le plugin propose une balise `#MESSAGE_PERSONNALISE{type_message,texte_original,objets_cibles,declencheurs} `

Placé à l'intérieur d'un boucle elle récupère l'objet et l'identifiant de l'objet.
``` html
<BOUCLE_reservation(RESERVATIONS){id_reservation}{tout}>
		#MESSAGE_PERSONNALISE{
				reservation,
				reservation:merci_de_votre_reservation,
				#ARRAY{article, 2},
				#ARRAY{statut, #STATUT, qui, client}}
</BOUCLE_reservation>
```