<?php

return [
    'meta_menu_common' => 'Annonces',
    'meta_menu_admin' => 'Admin',
    'meta_menu_user' => 'Utilisateur',
    'menu_home' => 'Annonces',
    'menu_sells' => 'Offres',
    'menu_research' => 'Demandes',
    'menu_offer' => 'Déposer une annonce',
    'menu_mines' => 'Mes annonces',
    'menu_bookmarks' => 'Mes favoris',
    'menu_login' => 'S\'identifier',
    'menu_logout' => 'Se déconnecter',
    'menu_register' => 'S\'enregistrer',
    'menu_subscribe' => 'S\'inscrire',
    'menu_account' => 'Mon compte',
    'menu_category' => 'Catégories',
    'menu_parameters' => 'Paramètres Application',
    'menu_dashboard' => 'Tableau de bord',
    'menu_cleanApp' => 'Nettoyage Application',
    'menu_transfertMedias' => 'Alleger disque local',
    'menu_advert_to_approve' => 'Annonces à valider',
    'menu_advert_delegations' => 'Annonces en délégation',

    'title_reset_password' => 'Renouvellement du mot de passe',
    'title_new_password' => 'Nouveau mot de passe',

    'flash_header_success' => 'Bravo! :)',
    'flash_header_info' => 'Information:',
    'flash_header_status' => 'Information:',
    'flash_header_error' => 'Ho non, une erreur est survenue! :(',

    'notification_action-text' => 'Si vous rencontrez des problèmes en cliquant sur le button ":actiontext", copiez et collez l\'adresse URL ci-dessous dans la barre d\'adresse de votre navigateur:',
    'notification_regards1' => 'Bien Cordialement',
    'notification_regards2' => 'L\'équipe :teamname',
    'notification_rights' => 'Tous droits réservés',

    'mail_firstSendToken_subject' => 'Inscription sur DestockEurope',
    'mail_firstSendToken_greeting' => 'Bienvenue :username !',
    'mail_firstSendToken_line' => 'Votre compte :mail a bien été créé',
    'mail_firstSendToken_action' => 'Confirmez votre email',
    'mail_firstSendToken_line2' => 'Merci d\'utiliser notre application! A tout de suite',

    'mail_customerToSeller_subject' => 'Votre annonce ":title" a une réponse',
    'mail_customerToSeller_greeting' => 'Bonjour :sellername',
    'mail_customerToSeller_line' => ':customername a répondu à propos de votre annonce ":title"',
    'mail_customerToSeller_line2' => 'Coordonnées de :customername:',
    'mail_customerToSeller_mail' => 'Mail: :mail',
    'mail_customerToSeller_phone' => 'Téléphone: :phone',
    'mail_customerToSeller_comapgnyName' => 'Société: :compagnyName',
    'mail_customerToSeller_line3' => 'Merci d\'utiliser notre application!',
    'mail_customerToSeller_send_error' => 'Ho non! Nous n\'avons pas réussi à envoyé l\'email \'-(',

    'mail_report_send_error' => 'Ho non! Nous n\'avons pas réussi à envoyer le signalement \'-(',
    'mail_report_subject' => 'Une annonce vient d\'être signalée par un utilisateur',
    'mail_report_greeting' => 'Bonjour chèr(e) administrateur/administratice',
    'mail_report_line' => ':customermail a signalé une annonce en laissant le message suivant:',
    'mail_report_action' => 'Vérifier l\'annonce',

    'mail_advertApprove_subject' => 'Votre annonce est en ligne!',
    'mail_advertApprove_greeting' => 'Félicitation :username !',
    'mail_advertApprove_line' => 'Votre annonce ":title" est approuvée et en ligne',
    'mail_advertApprove_action' => 'Voir mon annonce',
    'mail_advertApprove_line2' => 'Merci d\'utiliser notre application! A tout de suite',

    'mail_advertNotApprove_subject' => 'Votre annonce n\'a pas pu être approuvée',
    'mail_advertNotApprove_greeting' => 'Désolé chère utilisateur :username !',
    'mail_advertNotApprove_line' => 'Votre annonce ":title" ne respecte pas les règles et par conséquent n\'a pas été approuvée par notre équipe \':(.',
    'mail_advertNotApprove_line_voidPayment' => 'Le paiement de celle-ci a été annulé.',
    'mail_advertNotApprove_line2' => 'Nous espérons vous revoir très vite pour poster une annonce valide. Merci d\'utiliser notre application! A tout de suite!',

    'mail_alertObsoleteAdvert_subject' => 'Votre annonce arrive a expiration. Renouvellez là',
    'mail_alertObsoleteAdvert_subject_lastday' => 'Rappel: votre annonce arrive a expiration dans moins de 24h. Renouvellez là',
    'mail_alertObsoleteAdvert_subject_afterday' => 'Information: votre annonce est expirée. Vous pouvez encore la renouveller',
    'mail_alertObsoleteAdvert_greeting' => 'Bonjour :username !',
    'mail_alertObsoleteAdvert_line' => 'Votre annonce ":title" arrive a expiration dans :days jours. Vous pouvez cliquer le lien suivant pour la renouveller.',
    'mail_alertObsoleteAdvert_line_lastday' => 'Votre annonce ":title" arrive a expiration dans moins de 24 heures. Vous pouvez cliquer le lien suivant pour la renouveller.',
    'mail_alertObsoleteAdvert_line_afterdays' => 'Votre annonce ":title" est expirée. Vous pouvez cliquer le lien suivant pour la renouveller dans un délai de'. env('DELAY_DAYS_FOR_RENEW_ADVERT') .  'jours.',
    'mail_alertObsoleteAdvert_action' => 'Renouveller mon annonce',
    'mail_alertObsoleteAdvert_line2' => 'Merci d\'utiliser notre application!',

    'mail_advertRenew_subject' => 'Votre annonce est renouvellée!',
    'mail_advertRenew_greeting' => 'Félicitation :username !',
    'mail_advertRenew_line' => 'Votre annonce ":title" est renouvellée à partir du :date pour une durée de '. env('ADVERT_LIFE_TIME') . ' jours',
    'mail_advertRenew_line2' => 'Merci d\'utiliser notre application! A tout de suite',

    'form_label_name' => 'Votre nom',
    'form_label_email' => 'Votre E-mail',
    'form_label_phone' => 'Votre n° de téléphone',
    'form_validation_email' => 'Entrez un E-mail valide',
    'form_label_password' => 'Mot de passe',
    'form_label_confimr_password' => 'Confirmation mot de passe',
    'form_label_remember_me' => 'Se souvenir de moi',
    'form_label_message_input' => 'Votre message',
    'form_button_new_password' => 'Valider le nouveau mot de passe',
    'form_button_validation' => 'Soumettre',
    'form_pointing_minimum_chars' => ' caractères minimum',
    'form_pointing_maximum_chars' => ' caractères maximum',
    'form_input_search_label' => 'Cherchez ...',
    'form_input_search_label2' => 'Cherchez dans le titre ou dans la description...',
    'form_input_search_view_all' => 'Afficher :nb résultat | Afficher les :nb résultats',
    'form_input_location_label' => 'Pays, région, département, ville ...',
    'form_dropdown_all_item' => 'Tous',
    'form_dropdown_move_item' => 'Déplacer vers...',
    'form_dropdown_move_as_root' => 'Catégorie principale',
    'form_dropdown_move_already_child_of' => 'Cette catégory est déjà bien placée',
    'form_file_add_photo_separator' => 'Ajouter des photos',
    'form_file_add_photo_label' => 'Fichier: mini :minwidth px * :minheight px, :maxsize Mb maxi',
    'form_file_add_free_photo_help_header' => 'photo gratuite restante|photos gratuites restantes',
    'form_file_add_pay_photo_help_header' => 'photo payante|photos payantes',
    'form_file_add_photo_help_content' => 'Vous pouvez ajouter jusqu\'à :nb photos gratuites à votre annonce. Vous pouvez ajouter d\'autres photo suivant <a href=":link">la grille tarifaire ici</a>' ,
    'form_radio_main_photo_label' => 'Photo Principale',
    'form_googlemap_label' => 'Localisation',
    'form_googlemap_marker' => 'La vente se situe ici',
    'form_googlemap_geoloc_fail' => 'Adresse indéterminée',
    'form_googlemap_help' => 'Renseignez une adresse ou placez le marqueur de la carte à l\'endroit désiré. Si besoin, vous pouvez utiliser le zoom et faire glisser la carte',
    'form_googlemap_help2' => 'Renseignez une adresse',
    'form_quantity_label' => 'Quantité à vendre',
    'form_lot_mini_label' => 'Lot minimal de vente',
    'form_urgent' => 'Marquez cette annonce urgente pour seulement :price€ HT',
    'form_urgent_delegation' => 'Marquez cette annonce urgente',

    'modal_yes' => 'Oui',
    'modal_no' => 'Non',
    'modal_send'=> 'Envoyer',
    'modal_cancel'=> 'Annuler',

    'link_forgot_password' => 'Mot de passe oublié',
    'link_send_reset_link' => 'Envoyer un lien de renouvellement',

    'request_main_picture_require' => 'Il faut au moins une image à votre annonce',
    'request_input_generic_error' => 'Le champ ":name" est invalide',
    'request_input_require' => 'Le champ ":name" est obligatoire',
    'request_input_numeric' => 'Le champ ":name" ne doit contenir que des chiffres',
    'request_input_min_chars' => 'Le champ ":name" requiert au moins :min caractères',
    'request_input_max_chars' => 'Le champ ":name" requiert :max caractères maximum',
    'request_input_min_numeric' => 'La valeur du champ ":name" est au minimum :min',
    'request_input_max_numeric' => 'La valeur du champ ":name" est au maximum :max',
    'request_input_regex_name' => 'Le champ ":name" ne doit contenir que des lettres et des espaces',

    'request_vat_invalid' => 'Ce numéro de TVA est bien formé mais n\'existe pas',
    'request_vat_invalid_input' => 'Ce numéro de TVA est mal formé',
    'request_vat_service_unavailable' => 'Le service de vérification europeen des TVA n\'est pas disponible, réessayer plus tard svp',
    'request_vat_blocked' => 'Désolé, le service europeen de vérification des TVA nous indique que ce numéro de TVA est un numéro bloqué',

    'auth_register_success' => 'Votre compte est créé. Merci de le valider en cliquant le lien reçu à votre adresse email.',
    'auth_register_resend_token' => 'Le lien de confirmation de votre email vous a été renvoyé.',
    'auth_register_account_confirm' => 'Félicitation, votre compte est maintenant validé.',
    'auth_register_account_already_confirm' => 'Votre compte est déjà validé.',
    'auth_register_invalid_link' => 'Le lien de confirmation est invalide.',
    'auth_register_already_exist_account' => 'un email identique est enregistré sur un compte ouvert par ',
    'auth_register_register_error' => 'une erreur est survenue pendant votre inscription. Merci de contacter l\'administrateur du site',
    'auth_register_global_error' => 'une erreur est survenue pendant votre connexion/inscription. Merci de contacter l\'administrateur du site',
    'auth_register_provider_error' => 'Probleme de connexion avec ',
    'auth_register_oauth_success' => 'Bienvenu sur :appname :username. Merci de votre confiance. Vous pouvez desormais profiter de votre espace client',

    'divider_register' => 'ou utiliser',
    'divider_payment' => 'ou payer par carte',

    'middleware_admin' => 'Vous n\'avez pas les droits nécéssaires pour acceder à cette page...',
    'middleware_complete_account' => 'Merci de remplir tous les champs obligatoires (marqués d\'une astérisque rouge) avant validation',

    'option_payedPicture_name' => 'Photos supplémentaires',
    'option_isUrgent_name' => 'Annonce urgente',
    'option_isRenew_name' => 'Renouvellement de mon annonce',

    'view_all_error_load_message' => 'Ho non! Erreur de chargement :-( , essayez de rafraichir la page',
    'view_all_error_reload_message' => 'rafraichir la page SVP',
    'view_all_error_add_message' => 'Ho non! L\'ajout a échoué',
    'view_all_error_saving_message' => 'Ho non! L\'enregistrement a échoué :-(',
    'view_all_error_del_message' => 'Ho non! La suppression a échoué',
    'view_all_error_patch_message' => 'Ho non! La modification a échoué',
    'view_all_error_download_file' => 'Ho non! Un fichier demandé n\'a pas pu être téléchargé',
    'view_all_success_patch_message' => 'Modification enregistrée',
    'view_all_error_filesize_message' => 'Fichier trop gros!',
    'view_all_error_invalid_image_message' => 'Image invalide',
    'view_all_urgent' => 'urgent',
    'view_category_add_exist' => 'Cette catégorie existe déjà',
    'view_category_add_parent_not_exist' => 'La catégorie parente n\'existe pas',
    'view_category_patch_exist' => 'Cette catégorie existe déjà',
    'view_category_patch_not_exist' => 'Cette catégorie n\'existe pas',
    'view_category_patch_shift_impossible' => 'La catégorie n\'à pas pu être déplacée',
    'view_category_del_not_exist' => 'Cette catégorie n\'existe pas',
    'view_category_index_header' => 'Gestion des catégories',
    'view_category_index_modal_del_header' => 'Supprimer la Categorie',
    'view_category_index_modal_del_description' => 'ATTENTION!! La suppression de la catégorie entraine la suppression de toutes les annonces associées. Confirmez-vous cette suppression?',

    'view_manage_index_header' => 'Parametrage de l\'application',
    'view_manage_adverts_label' => 'Annonces',
    'view_manage_adverts_nb_free_pictures_label' => 'Nombre d\'images gratuites par annonce',
    'view_manage_adverts_nb_max_pictures_label' => 'Nombre maximal d\'images par annonce',
    'view_manage_adverts_urgent_cost_label' => 'Prix de l\'option URGENT (€ HT)',
    'view_manage_adverts_renew_cost_label' => 'Prix de renouvellement (€ HT)',
    'view_manage_adverts_per_page_label' => 'Nombre d\'annonces par page',
    'view_manage_resume_length_label' => 'Longeur du résumé (nb caractères)',
    'view_manage_search_label' => 'Filtre de recherche',
    'view_manage_min_length_search_label' => 'Minimun de caractère pour une recherche',
    'view_manage_max_result_search_label' => 'Maximum de resultats de recherche',
    'view_manage_ads_label' => 'Publicité',
    'view_manage_ads_frequency_label' => 'Fréquence des publicités',
    'view_manage_master_ads_activation_label' => 'Activer la publicité de page d\'accueil',
    'view_manage_master_ads_url_label' => 'URL de l\'image de la publicité',
    'view_manage_master_ads_url_link_label' => 'URL de redirection de la publicité',
    'view_manage_master_offset_y_label' => 'Décalage vertical de l\'annonce',
    'view_manage_appearance_label' => 'Apparence',
    'view_manage_welcome_type_label' => 'Page d\'accueil type n°',
    'view_manage_dashboard_label' => 'Etat de l\'application',

    'view_portal_list_header_1' => 'Fins de series',
    'view_portal_list_header_2' => 'Arrivages',
    'view_portal_list_header_3' => 'Destockages',
    'view_portal_list_header_4' => 'Faillites',
    'view_portal_list_header_5' => 'Redressements',
    'view_portal_list_header_6' => 'Fins de stocks',
    'view_portal_button_filter_label' => 'Voir les annonces de ce lieu',
    'view_portal_newsletter_title' => 'Newsletter',
    'view_portal_newsletter_description' => 'Soyez les premiers pour les bonnes affaires en remplissant le formulaire ci-dessous',
    'view_portal_newsletter_subscribe_success' => 'Votre demande est enregistrée',
    'view_portal_newsletter_subscribe_error' => 'Ho non! Votre demande n\'a pas pu aboutir \'-(',

    'view_advert_create_header' => 'Créer une annonce',
    'view_advert_create_header_delegation' => 'Créer une annonce (délégation de vente à ' . env('DEFAULT_DELEGATE') .')',
    'view_advert_form_title_label' => 'Titre de l\'annonce',
    'view_advert_form_description_label' => 'Description de l\'annonce',
    'view_advert_form_price_label' => 'Prix de vente à l\'unité',
    'view_advert_form_address_label' => 'Lieu de vente',
    'view_advert_list_type_dropdown_label' => 'Type d\'annonce',
    'view_advert_list_type_bid' => 'offre',
    'view_advert_list_type_request' => 'demande',
    'view_advert_list_price_info' => 'Tous les prix sont unitaires HT',
    'view_advert_list_no_result_header' => 'Aucun résultat',
    'view_advert_list_no_result_message' => 'Aucune annonce ne correspond aux critères de recherche. Soyez le 1er à publier la votre!!',
    'view_advert_steps_1_title' => 'Création de l\'annonce',
    'view_advert_steps_1_description' => 'décrire votre annonce',
    'view_advert_steps_2_title' => 'Vos informations',
    'view_advert_steps_2_description' => 'vérifier les informations de votre compte',
    'view_advert_steps_2_description_delegation' => env('DEFAULT_DELEGATE'),
    'view_advert_steps_3_title' => 'Paiement',
    'view_advert_steps_3_title_post' => '€ HT',
    'view_advert_steps_3_description' => 'si urgent, photos supplémentaires, ou renouvellement',
    'view_advert_steps_3_description_delegation' => '0€: inclus dans l\'offre ',
    'view_advert_show_contact_label' => 'Contacter le vendeur',
    'view_advert_show_bookmark_info' => 'Favoris',
    'view_advert_show_bookmark_label' => 'Mettre en favori',
    'view_advert_show_bookmark_success' => 'Votre annonce est sauvegardée dans vos favoris',
    'view_advert_show_unbookmark_label' => 'Supprimer des favoris',
    'view_advert_show_unbookmark_success' => 'Cette annonce est supprimée de vos favoris',
    'view_advert_show_bookmark_auth_required' => 'Identifiez-vous svp pour bénéficier du suivi des favoris',
    'view_advert_show_delete_label' => 'Supprimer mon annonce',
    'view_advert_show_views_info' => 'Vues',
    'view_advert_show_manage_label' => 'Gerer',
    'view_advert_show_renew_label' => 'Renouveler',
    'view_advert_show_delete2_label' => 'Supprimer',
    'view_advert_show_see_label' => 'Voir',
    'view_advert_show_update_label' => 'Mettre à jour',
    'view_advert_show_is_renew_label' => 'Est renouvellée',
    'view_advert_show_delete_success' => 'Votre annonce est bien supprimée. Merci de votre confiance',
    'view_advert_show_message_send' => 'Votre message est bien envoyé',
    'view_advert_report_label' => 'Signaler cette annonce',
    'view_advert_show_report_send' => 'Votre signalement est bien envoyé. Nous vérifions cette annonce dans les plus brefs délais',
    'view_advert_show_modal_delete_header' => 'Vous allez supprimer votre annonce',
    'view_advert_show_modal_delete_description' => 'En cliquant sur "oui", vous allez définitivement supprimer votre annonce. Cliquez sur "non" ou fermez cette fenetre si vous ne souhaitez pas supprimer votre annonce',
    'advert_create_success' => 'Votre annonce est créée. Elle sera validée très prochainement par nos équipes',
    'view_advert_validation_on_progress' => 'Annonce en attente de validation',

    'view_advert_approve_header' => 'Approuver les annonces',
    'view_advert_approve_toggle_label' => 'J\'approuve cette annonce',
    'view_advert_disapprove_toggle_label' => 'Je désapprouve cette annonce',
    'view_advert_priceCoefficient_label' => 'Coefficient de marge  en %',
    'view_advert_priceCoefficient_new_price' => 'Nouveau prix affiché',
    'view_advert_priceCoefficient_unit_margin' => 'Marge/unité',
    'view_advert_priceCoefficient_total_margin' => 'Marge totale',
    'view_advert_approve_modal_valid_header' => 'Valider toutes les actions',
    'view_advert_approve_modal_valid_description' => 'ATTENTION!! La validation de toutes les actions entraîne l\'envoi des mails d\'approbation ou de desapprobation aux clients.',
    'view_advert_approve_error' => 'Une erreur est survenue pendant l\'approbation d\'une annonce',
    'view_advert_auto_approve_error' => 'Une erreur est survenue pendant l\'approbation automatique de votre annonce renouvellée. Nos service sont avertis et traitent votre problème au plus vite',
    'view_advert_approve_success' => 'Approbations des annonces envoyées',

    'view_advert_by_link_label' => 'Voir l\'annonce',

    'view_pagination_page_label' => 'page',
    'view_pagination_prev_label' => 'précédente',
    'view_pagination_next_label' => 'suivante',

    'view_user_account_preferences_label' => 'Préférences',
    'view_user_account_patch_success' => 'Mise à jour OK',
    'view_user_account_currency_dropdown_label' => 'Monnaie',
    'view_user_account_currency_patch_error' => 'La monnaie choisie n\'est pas valide',
    'view_user_account_locale_dropdown_label' => 'Langage',
    'view_user_account_locale_patch_error' => 'La localisation choisie n\'est pas valide',
    'view_user_account_location_patch_error' => 'L\'adresse choisie n\'est pas valide',
    'view_user_account_name_patch_error' => 'Le nom choisie n\'est pas valide',
    'view_user_account_registration_patch_error' => 'Le numéro de TVA n\'a pas pu être sauvé',
    'view_user_account_compagny_divider_label' => 'Votre société',
    'view_user_account_compagny_name_label' => 'Nom de votre société',
    'view_user_account_compagny_number_label' => 'Numero de TVA intracommunautaire',
    'view_user_account_compagny_number_warning_label' => 'Fournissez un bon numéro de TVA pour un calcul correct des tarifs',
    'view_user_account_compagny_number_check_progress' => 'N° en cours de vérification...',
    'view_user_account_compagny_number_identifier' => 'vérification n°',
    'view_filter_ribbon' => 'Filtres',
    'view_filter_price_title' => 'Tranche de Prix',

    'view_reviewForPayment_header' => 'Recapitulatif de votre commande',
    'view_reviewForPayment_table_option_name' => 'Options',
    'view_reviewForPayment_table_option_quantity' => 'Quantité',
    'view_reviewForPayment_table_option_cost' => 'Prix',
    'view_reviewForPayment_table_total_excl_vat' => 'Total HT',
    'view_reviewForPayment_table_vat' => 'TVA',
    'view_reviewForPayment_table_total_incl_vat' => 'Total TTC',
    'view_reviewForPayment_cgv' => 'J\'accepte les <a href=":link">conditions générales de vente</a>',
    'view_reviewForPayment_lock_info_header' => 'Cette page est entierement sécurisée',
    'view_reviewForPayment_lock_info_content' => 'Cette page utilise le protocol https:// et vous garantie une totale sécurité des échanges',
    'view_reviewForPayment_paypal_title' => 'PayPal, le réflexe sécurité pour payer en ligne',

    'payment_card_type_label' => 'Type de carte',
    'payment_card_name_label' => 'Nom sur la carte',
    'payment_card_number_label' => 'Numero de carte',
    'payment_card_number_error' => 'Entrez un numéro de carte valide (sans espace)',
    'payment_card_number_placeholder' => 'Carte n°...',
    'payment_card_cvc_label' => 'CVC',
    'payment_card_expiration_label' => 'Expiration',
    'payment_card_expiration_month_placeholder' => 'Mois',
    'payment_card_expiration_year_placeholder' => 'Année AAAA',
    'payment_paypal_generic_product_name' => 'Une annonce avec :nb option:|Une annonce avec :nb options:',
    'payment_paypal_invoice_description' => 'Votre achat sur :name',
    'payment_all_error' => 'Ho non! Le paiement a échoué :-(',
    'payment_paypal_success' => 'Merci pour votre paiement. Celui-ci sera définitif quand votre annonce sera validé par nos service.',
    'payment_renew_success' => 'Merci pour votre paiement. Votre annonce est renouvellée à partir du :date pour une durée de '. env('ADVERT_LIFE_TIME') . ' jours.',

    'footer_list_header_1' => 'MAISON',
    'footer_list_1_1' => 'Ameublement',
    'footer_list_1_2' => 'Electroménager',
    'footer_list_1_3' => 'Arts de la table',
    'footer_list_1_4' => 'Décoration',
    'footer_list_1_5' => 'Linge de maison',
    'footer_list_1_6' => 'Bricolage',
    'footer_list_1_7' => 'Jardinage',
    'footer_list_1_8' => 'Vêtements',
    'footer_list_1_9' => 'Chaussures',
    'footer_list_1_10' => 'Accessoires & Bagagerie',
    'footer_list_1_11' => 'Montres & Bijoux',
    'footer_list_1_12' => 'Equipement bébé',
    'footer_list_1_13' => 'Vêtements bébé',

    'footer_list_header_2' => 'MULTIMEDIA',
    'footer_list_2_1' => 'Informatique',
    'footer_list_2_2' => 'Consoles & Jeux vidéo',
    'footer_list_2_3' => 'Image & Son',
    'footer_list_2_4' => 'Téléphonie',

    'footer_list_header_3' => 'LOISIRS',
    'footer_list_3_1' => 'DVD / Films',
    'footer_list_3_2' => 'CD / Musique',
    'footer_list_3_3' => 'Livres',
    'footer_list_3_4' => 'Vélos',
    'footer_list_3_5' => 'Sports & Hobbies',
    'footer_list_3_6' => 'Instruments de musique',
    'footer_list_3_7' => 'Collection',
    'footer_list_3_8' => 'Jeux & Jouets',
    'footer_list_3_9' => 'Vins & Gastronomie',

    'footer_list_header_4' => 'MATERIEL PROFESSIONNEL',
    'footer_list_4_1' => 'Matériel Agricole',
    'footer_list_4_2' => 'Transport - Manutention',
    'footer_list_4_3' => 'BTP - Chantier Gros-oeuvre',
    'footer_list_4_4' => 'Outillage - Matériaux 2nd-oeuvre',
    'footer_list_4_5' => 'Équipements Industriels',
    'footer_list_4_6' => 'Restauration - Hôtellerie',
    'footer_list_4_7' => 'Fournitures de Bureau',
    'footer_list_4_8' => 'Commerces & Marchés',
    'footer_list_4_9' => 'Matériel Médical',

    'footer_list_header_5' => 'À PROPOS DE DESTOCKEUROPE',
    'footer_list_5_1' => 'Qui sommes-nous ?',
    'footer_list_5_2' => 'Nous rejoindre',
    'footer_list_5_3' => 'Impact environnemental',
    'footer_list_5_4' => 'Publicité',

    'footer_list_header_6' => 'INFORMATIONS LÉGALES',
    'footer_list_6_1' => 'Conditions générales d\'utilisation',
    'footer_list_6_2' => 'Règles de diffusion',
    'footer_list_6_3' => 'Conditions Générales de Vente',
    'footer_list_6_4' => 'Vie privée / cookies',
    'footer_list_6_5' => 'Vos droits et obligations',

    'footer_list_header_7' => 'DES QUESTIONS ?',
    'footer_list_7_1' => 'Aide',
    'footer_list_7_2' => 'Nous contacter',
    'footer_list_7_3' => 'Vous êtes à l\'étranger ?',

    'january' => 'janvier',
    'february' => 'fevrier',
    'march' => 'mars',
    'april' => 'avril',
    'may' => 'mai',
    'june' => 'juin',
    'july'=> 'juillet',
    'august' => 'août',
    'september' => 'septembre',
    'october' => 'octobre',
    'november' => 'novembre',
    'december' => 'decembre',

    'admin_purge_response' => ':nbadvert annonces supprimées et :nbimg images éffacées',
    'admin_purge_error' => 'erreur pendant la purge',
    'admin_transfert_image_response' => ':nb Mb en cours de transfert vers le disque :disk',
    'admin_transfert_image_exist' => 'L\'application signale qu\'un transfert est déjà en cours',
    'admin_transfert_size_null' => 'Taille du transfert trop petite',

    'dashboard_size_local_files_label' => 'Charge disque local',
    'dashboard_size_distant_files_label' => 'Charge disque ' . \App\Common\PicturesManager::DISK_DISTANT,
    'dashboard_count_files_title' => 'Nombre de fichiers',
    'dashboard_count_local_files_label' => 'Local',
    'dashboard_count_distant_files_label' => \App\Common\PicturesManager::DISK_DISTANT,
    'dashboard_megaBytes_label' => 'Mb',
    'dashboard_stats_label' => 'Statistiques',
    'dashboard_graph_adverts_title' => 'Suivi des annonces',
    'dashboard_graph_valid_adverts_label' => 'Annonces Valides',
    'dashboard_graph_invalid_adverts_label' => 'Annonces Refusées',
    'dashboard_graph_waiting_adverts_label' => 'Annonces en attente',
    'dashboard_graph_costs_title' => 'Suivi des ventes',
    'dashboard_graph_sum_costs_label' => 'Ventes total',
    'dashboard_graph_avg_costs_label' => 'Vente moyenne',
    'dashboard_graph_views_title' => 'Total des Vues',

    'country_italy' => 'italie',
    'country_germany' => 'allemagne',
    'country_france' => 'france',
    'country_swiss' => 'suisse',
    'country_belgium' => 'belgique',
    'country_denmark' => 'danemark',
    'country_netherlands' => 'pays bas',
    'country_ireland' => 'irlande',
    'country_finland' => 'finlande',
    'country_austria' => 'autriche',
    'country_sweden' => 'suède',
    'country_norway' => 'norvège',
    'country_iceland' => 'islande',
    'country_england' => 'angleterre',
    'country_scotland' => 'ecosse',
    'country_united_kingdom' => 'royaumes unis',
    'country_ukraine' => 'ukraine',
    'country_poland' => 'pologne',
    'country_romania' => 'roumanie',
    'country_spain' => 'espagne',
    'country_greece' => 'grèce',
    'country_portugal' => 'portugal',
    'country_czech_republic' => 'république tchèque',
    'country_turkey' => 'turquie',
    'country_hungary' => 'hongrie',
    'country_russia' => 'russie',
    'country_armenia' => 'arménie',
    'country_bulgaria' => 'bulgarie',
    'country_estonia' => 'estonie',
    'country_europe' => 'europe',
];