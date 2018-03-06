# Message Personnalisé
Permet de personaliser des messages depuis l'espace prive

## Rendre les messages personnalisables


### Définir un message


#### Tableau de définition.

les définions se trouvent dans un fichier à l'interieur d'un dossier "message_personnalise", le nom du fichier est égale au type de message.

La fonction qui contient la définition est composé de manier suivante: message_personnalise_typemessage_dist($args)

donc pour le type de message "example"


message_personnalise/example.php

``` php
messages_personnalises_example_dist($args) {

	$definition = array(
		'label' => _T('reservation:titre_reservation'),
		'objet' => 'reservation',
		'fond' => 'notifications/contenu_reservation_mail',
		'declencheurs' => array(
			'statut' => array(
				'data' => $statuts,
			),
			'qui' => array(
				'data' =>
					array(
						'client' => _T('reservation:notifications_client_label'),
						'vendeur' => _T('reservation:notifications_vendeur_label'),
					),
			),
		),
		'raccoursis' => array(
			'requete' => array(
				'champs' => $champs_sql,
				'from' =>'spip_reservations AS reservation LEFT JOIN spip_auteurs AS auteur USING(id_auteur)'
			),
			'champs' => array(
				'disponibles' => $champs_disponibles,
				'lies' => $champs_lies,
			),
			'inclures' => array(
				'reservations' => array(
					'fond' => 'inclure/reservation',
					'titre' => _T('reservation:mp_titre_reservation_details'),
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
	);
	return $definition;
}
```


#### Squelettes

Le plugin propose une balise `#MESSAGE_PERSONNALISE{type_message,texte_original,objets_cibles,declencheurs, options} `

Placé à l'intérieur d'un boucle elle récupère l'objet et l'identifiant de l'objet.

``` html
<BOUCLE_reservation(RESERVATIONS){id_reservation}{tout}>
		#MESSAGE_PERSONNALISE{
				reservation,
				reservation:merci_de_votre_reservation,
				#ARRAY{article, 2},
				#ARRAY{statut, #STATUT, qui, client}}
				#ARRAY{option1,valeur1}
</BOUCLE_reservation>
```