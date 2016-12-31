<?php

return [
    'meta_menu_common' => 'Adverts',
    'meta_menu_admin' => 'Admin',
    'meta_menu_user' => 'User',
    'menu_home' => 'Home',
    'menu_sells' => 'Sales',
    'menu_research' => 'Research',
    'menu_offer' => 'Offer an advert',
    'menu_mines' => 'My Adverts',
    'menu_bookmarks' => 'My Bookmarks',
    'menu_login' => 'Login',
    'menu_logout' => 'Logout',
    'menu_register' => 'Register',
    'menu_subscribe' => 'Subscribe',
    'menu_account' => 'My account',
    'menu_category' => 'Categories',
    'menu_parameters' => 'Application parameters',
    'menu_dashboard' => 'Dashboard',
    'menu_cleanApp' => 'Clean Application',
    'menu_transfertMedias' => 'Lighten Local Disk',
    'menu_advert_to_approve' => 'Advert to Validate',

    'title_reset_password' => 'Reset Password',
    'title_new_password' => 'New Password',

    'flash_header_success' => 'Success :)',
    'flash_header_info' => 'Information:',
    'flash_header_status' => 'Information:',
    'flash_header_error' => 'Error :(',

    'notification_action-text' => 'If you’re having trouble clicking the ":actiontext" button, copy and paste the URL below into your web browser:',
    'notification_regards1' => 'Regards,',
    'notification_regards2' => 'The :teamname team',
    'notification_rights' => 'All rights reserved',

    'mail_firstSendToken_subject' => 'DestockEurope Subscription',
    'mail_firstSendToken_greeting' => 'Welcome :username!',
    'mail_firstSendToken_line' => 'Your account :mail is being created',
    'mail_firstSendToken_action' => 'Confirm your email',
    'mail_firstSendToken_line2' => 'Thank you for using our application!',

    'mail_customerToSeller_subject' => 'Your advert ":title" have a response',
    'mail_customerToSeller_greeting' => 'Hello :sellername',
    'mail_customerToSeller_line' => ':customername answers you about your advert ":title"',
    'mail_customerToSeller_line2' => 'Contact informations:',
    'mail_customerToSeller_mail' => 'Mail: :mail',
    'mail_customerToSeller_phone' => 'Phone: :phone',
    'mail_customerToSeller_comapgnyName' => 'Compagny Name: :compagnyName',
    'mail_customerToSeller_line3' => 'Thank you for using our application!',
    'mail_customerToSeller_send_error' => 'Ho no! The email could not be sent \'-(',

    'mail_report_send_error' => 'Ho non! The report could not be sent \'-(',
    'mail_report_subject' => 'An advert has just been report by a user',
    'mail_report_greeting' => 'Hello dear admin',
    'mail_report_line' => ':customermail posted a report on an advert leaving the following message:',
    'mail_report_action' => 'Check Advert',

    'mail_advertApprove_subject' => 'Your advert is online!',
    'mail_advertApprove_greeting' => 'Congratulations :username !',
    'mail_advertApprove_line' => 'Your advert ":title" is approved and is now online',
    'mail_advertApprove_action' => 'See my advert',
    'mail_advertApprove_line2' => 'Thank you for using our application!',

    'mail_advertNotApprove_subject' => 'Your advert is not approve',
    'mail_advertNotApprove_greeting' => 'Sorry dear user :username !',
    'mail_advertNotApprove_line' => 'Your advert ":title" does not respect the rules and is not approved by our team \':(.',
    'mail_advertNotApprove_line_voidPayment' => 'Your Payment for this Advert is Canceled.',
    'mail_advertNotApprove_line2' => 'We hope to see you quickly for post a new valid advert. Thank you for using our application!',

    'mail_alertObsoleteAdvert_subject' => 'Your advert will soon expire. Renew it',
    'mail_alertObsoleteAdvert_subject_lastday' => 'Reminder: Your advert expires in less than 24h. Renew it there',
    'mail_alertObsoleteAdvert_subject_afterday' => 'Information: Your advert has expired. You can renew it again',
    'mail_alertObsoleteAdvert_greeting' => 'Hello :username !',
    'mail_alertObsoleteAdvert_line' => 'Your advert ":title" will expire in :days day. You can click on the following link to renew it.',
    'mail_alertObsoleteAdvert_line_lastday' => 'Your advert ":title" expires in less than 24 hours. You can click on the following link to renew it.',
    'mail_alertObsoleteAdvert_line_afterdays' => 'Your advert ":title" has expired. You can click on the following link to renew it in a delay of '. env('DELAY_DAYS_FOR_RENEW_ADVERT') .  'days.',
    'mail_alertObsoleteAdvert_action' => 'See my advert',
    'mail_alertObsoleteAdvert_line2' => 'Thank you for using our application!',

    'mail_advertRenew_subject' => 'Your advert is renewed!',
    'mail_advertRenew_greeting' => 'Congratulations :username !',
    'mail_advertRenew_line' => 'Your advert ":title" is renewed from :date for a period of :days days',
    'mail_advertRenew_line2' => 'Thank you for using our application!',

    'form_label_name' => 'Your name',
    'form_label_email' => 'Your E-mail',
    'form_label_phone' => 'Your phone number',
    'form_validation_email' => 'Please enter a valid e-mail',
    'form_label_password' => 'Password',
    'form_label_confimr_password' => 'Confirm Password',
    'form_label_remember_me' => 'Remember me',
    'form_label_message_input' => 'Your message',
    'form_button_new_password' => 'Valide new password',
    'form_button_validation' => 'Send',
    'form_pointing_minimum_chars' => ' characters minimum',
    'form_pointing_maximum_chars' => ' characters maximum',
    'form_input_search_label' => 'Search issues...',
    'form_input_search_label2' => 'Find in the title or in the description...',
    'form_input_search_view_all' => 'See the only :nb result|See :nb results',
    'form_input_location_label' => 'Country, region, department, city ...',
    'form_dropdown_all_item' => 'All',
    'form_dropdown_move_item' => 'Move to...',
    'form_dropdown_move_as_root' => 'Root catégory',
    'form_dropdown_move_already_child_of' => 'Category already child of choice',
    'form_file_add_photo_separator' => 'Add pictures',
    'form_file_add_photo_label' => 'File: mini :minwidth px * :minheight px, :maxsize Mb max',
    'form_file_add_free_photo_help_header' => 'remaining free picture|remaining free pictures',
    'form_file_add_pay_photo_help_header' => 'pay picture|pay pictures',
    'form_file_add_photo_help_content' => 'You can add up :nb free photos to your ad. You can add other photo following <a href=":link">fee schedule here</a>' ,
    'form_radio_main_photo_label' => 'Main Picture',
    'form_googlemap_label' => 'Location',
    'form_googlemap_marker' => 'The selling is here',
    'form_googlemap_geoloc_fail' => 'Adresse undefined',
    'form_googlemap_help' => 'Enter a location or place the marker of the map to the desired location. If necessary, you can use the zoom and drag the map',
    'form_googlemap_help2' => 'Enter a location',
    'form_quantity_label' => 'Quantity for sale',
    'form_lot_mini_label' => 'Minimal lot for sale',
    'form_urgent' => 'Mark this advert as urgent for only :price€',

    'modal_yes' => 'Yes',
    'modal_no' => 'No',
    'modal_send'=> 'Envoyer',
    'modal_cancel'=> 'Annuler',

    'link_forgot_password' => 'Forgot your password',
    'link_send_reset_link' => 'Send reset link',

    'request_main_picture_require' => 'At least one picture is required',
    'request_input_generic_error' => 'The ":name" field is not valid',
    'request_input_require' => 'The ":name" field is required',
    'request_input_numeric' => 'The ":name" field must contain only numbers',
    'request_input_min_chars' => 'The ":name" field required at least :min characters',
    'request_input_max_chars' => 'The ":name" field required :max characters maximum',
    'request_input_min_numeric' => 'The value of ":name" field is minimum :min',
    'request_input_max_numeric' => 'The value of ":name" field is maximum :max',
    'request_input_regex_name' => 'The ":name" field must contain only letters and spaces',

    'auth_register_success' => 'Your account is create. Thank you validate it by clicking the link received in your email address',
    'auth_register_resend_token' => 'Confirmation link send to your e-mail.',
    'auth_register_account_confirm' => 'Congratulations, your account has been validated.',
    'auth_register_account_already_confirm' => 'Your account is already validated.',
    'auth_register_invalid_link' => 'The confirmation link is invalid.',
    'auth_register_already_exist_account' => 'an identical email is registered to an account opened by ',
    'auth_register_register_error' => 'an error occurred during registration. Thank you to contact the site administrator',
    'auth_register_global_error' => 'an error occurred during your login / registration. Thank you to contact the site administrator',
    'auth_register_provider_error' => 'Connection problem with ',
    'auth_register_oauth_success' => 'Welcome on :appname :username. Thank you for your trust. You can henceforth enjoy your customer area',

    'divider_register' => 'or use',
    'divider_payment' => 'or use card',

    'middleware_admin' => 'You do not have the correct security credentials to access this page...',
    'middleware_complete_account' => 'Thank you to fill in all fields before validation of your account',

    'option_payedPicture_name' => 'Extra Photos',
    'option_isUrgent_name' => 'Urgent advert',
    'option_isRenew_name' => 'Renewal of my advert',

    'view_all_error_load_message' => 'Ho no! Load error :-( , try to refresh the page',
    'view_all_error_reload_message' => 'refresh the page Please',
    'view_all_error_add_message' => 'Ho no! Adding Fails error :-(',
    'view_all_error_saving_message' => 'Ho no! Save Fails error :-(',
    'view_all_error_del_message' => 'Ho no! Delete Fails error :-(',
    'view_all_error_patch_message' => 'Ho non! The update fail',
    'view_all_success_patch_message' => 'Modification save',
    'view_all_error_download_file' => 'Ho no! A requested file could not be downloaded',
    'view_all_error_filesize_message' => 'File to Big!',
    'view_all_error_invalid_image_message' => 'Unvaillable Image',
    'view_all_urgent' => 'urgent',
    'view_category_add_exist' => 'This category is already exist',
    'view_category_add_parent_not_exist' => 'The parent category is not exist',
    'view_category_patch_exist' => 'This category is already exist',
    'view_category_patch_not_exist' => 'This category is not exist',
    'view_category_patch_shift_impossible' => 'It does not reach the category could be moved',
    'view_category_del_not_exist' => 'This category is not exist',
    'view_category_index_header' => 'Categories settings',
    'view_category_index_modal_del_header' => 'Delete Category',
    'view_category_index_modal_del_description' => 'WARNING!! The deletion of the category entails the removal of all associated ads. Can you confirm this deletion?',

    'view_manage_index_header' => 'Application Parameter',
    'view_manage_adverts_label' => 'Offers',
    'view_manage_adverts_nb_free_pictures_label' => 'Number of free advert pictures',
    'view_manage_adverts_nb_max_pictures_label' => 'Number of max advert pictures',
    'view_manage_adverts_per_page_label' => 'Number adverts per page',
    'view_manage_resume_length_label' => 'Advert resume length',
    'view_manage_search_label' => 'Search Filter',
    'view_manage_min_length_search_label' => 'Minimun characters for search',
    'view_manage_max_result_search_label' => 'Max results on search',
    'view_manage_adverts_urgent_cost_label' => 'Cost of URGENT option (€)',
    'view_manage_adverts_renew_cost_label' => 'Price for renew (€)',
    'view_manage_ads_label' => 'Advertisement',
    'view_manage_ads_frequency_label' => 'Advert Frequency',
    'view_manage_master_ads_activation_label' => 'Activate home page advert',
    'view_manage_master_ads_url_label' => 'Master advertisement URL',
    'view_manage_master_ads_url_link_label' => 'Advertissement Redirection URL',
    'view_manage_master_offset_y_label' => 'Vertical offset of the advert',
    'view_manage_appearance_label' => 'Appearance',
    'view_manage_welcome_type_label' => 'Home Page type n°',
    'view_manage_dashboard_label' => 'Application states',

    'view_portal_list_header_1' => 'End of series',
    'view_portal_list_header_2' => 'News',
    'view_portal_list_header_3' => 'For Sale',
    'view_portal_list_header_4' => 'Bankruptcy',
    'view_portal_list_header_5' => 'Restructuring',
    'view_portal_list_header_6' => 'End of stocks',
    'view_portal_button_filter_label' => 'View ads of this place',
    'view_portal_newsletter_title' => 'Newsletter',
    'view_portal_newsletter_description' => 'Be the first for bargains by filling out the form below',
    'view_portal_newsletter_subscribe_success' => 'Your subscription is save',
    'view_portal_newsletter_subscribe_error' => 'Ho no! Votre request fail \'-(',

    'view_advert_create_header' => 'Create new advert',
    'view_advert_form_title_label' => 'Advert Title',
    'view_advert_form_description_label' => 'Advert description',
    'view_advert_form_price_label' => 'Unitary Sell Price',
    'view_advert_form_address_label' => 'Location selling',
    'view_advert_list_type_dropdown_label' => 'Advert type',
    'view_advert_list_type_bid' => 'bid',
    'view_advert_list_type_request' => 'request',
    'view_advert_list_price_info' => 'All prices are unitaries',
    'view_advert_list_no_result_header' => 'No result',
    'view_advert_list_no_result_message' => 'No ads found matching the search criteria. Be the 1st to post your own!!',
    'view_advert_steps_1_title' => 'Create Advert',
    'view_advert_steps_1_description' => 'describe your advert',
    'view_advert_steps_2_title' => 'Account info',
    'view_advert_steps_2_description' => 'check the information in your account',
    'view_advert_steps_3_title' => 'Payment',
    'view_advert_steps_3_description' => 'if urgent, extra photos or renew',
    'view_advert_show_contact_label' => 'Contact the seller',
    'view_advert_show_bookmark_info' => 'Bookmarks',
    'view_advert_show_bookmark_label' => 'Bookmark',
    'view_advert_show_bookmark_success' => 'This advert is now in your bookmark',
    'view_advert_show_unbookmark_label' => 'Remove Bookmark',
    'view_advert_show_unbookmark_success' => 'This advert is now remove of your bookmark',
    'view_advert_show_bookmark_auth_required' => 'Please log in to view favorite bookmarks',
    'view_advert_show_delete_label' => 'Delete my Advert',
    'view_advert_show_views_info' => 'Views',
    'view_advert_show_manage_label' => 'Manage',
    'view_advert_show_renew_label' => 'Renew it',
    'view_advert_show_is_renew_label' => 'Is Renewed',
    'view_advert_show_delete_success' => 'Your ad is deleted. Thank you for your confidence',
    'view_advert_show_message_send' => 'Your message is being send',
    'view_advert_report_label' => 'Report this advert',
    'view_advert_show_report_send' => 'Your report is being send. We will verify this ad as soon as possible.',
    'view_advert_show_modal_delete_header' => 'You will delete your advert',
    'view_advert_show_modal_delete_description' => 'By clicking "Yes", you will definitely delete your advert. Click "no" or close this window if you do not want to delete your advert',
    'advert_create_success' => 'Your advert is being created. It will be validated by our teams soon',

    'view_advert_approve_header' => 'Approve this adverts',
    'view_advert_approve_toggle_label' => 'I approve this advert',
    'view_advert_disapprove_toggle_label' => 'I disapprove this advert',
    'view_advert_approve_modal_valid_header' => 'Approve all actions',
    'view_advert_approve_modal_valid_description' => 'WARNING!! The validation of all actions resulting sending mails approval or disapproval to customers.',
    'view_advert_approve_error' => 'Advert approve error',
    'view_advert_auto_approve_error' => 'Error while automatic advert approve process. Our services are warned and deal with your problem as soon as possible',
    'view_advert_approve_success' => 'Advert approve send',

    'view_advert_by_link_label' => 'See Advert',

    'view_pagination_page_label' => 'page',
    'view_pagination_prev_label' => 'previous',
    'view_pagination_next_label' => 'next',

    'view_user_account_preferences_label' => 'Preferences',
    'view_user_account_patch_success' => 'Update OK',
    'view_user_account_currency_dropdown_label' => 'Currency',
    'view_user_account_currency_patch_error' => 'The select currency is not available',
    'view_user_account_locale_dropdown_label' => 'Language',
    'view_user_account_locale_patch_error' => 'The select localization is not available',
    'view_user_account_location_patch_error' => 'The select location is not available',
    'view_user_account_name_patch_error' => 'The name is not available',
    'view_user_account_registration_patch_error' => 'The registration number is not available',
    'view_user_account_compagny_divider_label' => 'Your Compagny',
    'view_user_account_compagny_name_label' => 'Compagny Name',
    'view_user_account_compagny_number_label' => 'Compagny registration number',
    'view_filter_ribbon' => 'Filters',
    'view_filter_price_title' => 'Range Price',

    'view_reviewForPayment_header' => 'Summary of your order',
    'view_reviewForPayment_table_option_name' => 'Options',
    'view_reviewForPayment_table_option_quantity' => 'Quantity',
    'view_reviewForPayment_table_option_cost' => 'Cost',
    'view_reviewForPayment_table_total_excl_vat' => 'Total excl. VAT',
    'view_reviewForPayment_table_vat' => 'VAT',
    'view_reviewForPayment_table_total_incl_vat' => 'Total incl VAT',
    'view_reviewForPayment_cgv' => 'I agree with <a href=":link">Terms of service</a>',
    'view_reviewForPayment_lock_info_header' => 'This page is fully secure',
    'view_reviewForPayment_lock_info_content' => 'This page uses the https: // protocol and guarantees a total security of the exchanges',
    'view_reviewForPayment_paypal_title' => 'PayPal, the reflex security to pay online',

    'payment_card_type_label' => 'Card type',
    'payment_card_name_label' => 'Card owner name',
    'payment_card_number_label' => 'Card number',
    'payment_card_number_error' => 'Please enter a valid credit card number (whitout spacings)',
    'payment_card_number_placeholder' => 'Card #',
    'payment_card_cvc_label' => 'CVC',
    'payment_card_expiration_label' => 'Expiration',
    'payment_card_expiration_month_placeholder' => 'Month',
    'payment_card_expiration_year_placeholder' => 'Year YYYY',
    'payment_paypal_generic_product_name' => 'One Advert with :nb option:|One Advert with :nb options:',
    'payment_paypal_invoice_description' => 'Votre achat sur :name',
    'payment_all_error' => 'Ho no! Payment fails :-(',
    'payment_paypal_success' => 'Thank you for your payment. This will be final when your advert is approved by our service.',
    'payment_renew_success' => 'Thank you for your payment. Your advert is renew from :date for a period of :days days.',

    'footer_list_header_1' => 'HOUSE',
    'footer_list_1_1' => 'Furnishings',
    'footer_list_1_2' => 'Home appliance',
    'footer_list_1_3' => 'Table Arts',
    'footer_list_1_4' => 'Decoration',
    'footer_list_1_5' => 'Household linen',
    'footer_list_1_6' => 'Do-it-yourself',
    'footer_list_1_7' => 'Gardening',
    'footer_list_1_8' => 'Clothing',
    'footer_list_1_9' => 'Shoes',
    'footer_list_1_10' => 'Accessories & Luggage',
    'footer_list_1_11' => 'Watches & Jewelry',
    'footer_list_1_12' => 'Baby equipment',
    'footer_list_1_13' => 'Baby clothes',

    'footer_list_header_2' => 'MULTIMEDIA',
    'footer_list_2_1' => 'Computing',
    'footer_list_2_2' => 'Consoles & Video Games',
    'footer_list_2_3' => 'Image & Sound',
    'footer_list_2_4' => 'Telephony',

    'footer_list_header_3' => 'HOBBIES',
    'footer_list_3_1' => 'DVD / Movies',
    'footer_list_3_2' => 'CD / Music',
    'footer_list_3_3' => 'Books',
    'footer_list_3_4' => 'Bicycles',
    'footer_list_3_5' => 'Sports & Hobbies',
    'footer_list_3_6' => 'Musical instruments',
    'footer_list_3_7' => 'Collection',
    'footer_list_3_8' => 'Toys & Games',
    'footer_list_3_9' => 'Wine & Gastronomy',

    'footer_list_header_4' => 'PROFESSIONAL MATERIAL',
    'footer_list_4_1' => 'Agricultural material',
    'footer_list_4_2' => 'Transport - Handling',
    'footer_list_4_3' => 'Buildings - Construction - Big work',
    'footer_list_4_4' => 'Tools - Materials 2nd work',
    'footer_list_4_5' => 'Industrial Equipment',
    'footer_list_4_6' => 'Catering - Hotels',
    'footer_list_4_7' => 'Office supplies',
    'footer_list_4_8' => 'Shopping & Markets',
    'footer_list_4_9' => 'Medical material',

    'footer_list_header_5' => 'ABOUT OF DESTOCKEUROPE',
    'footer_list_5_1' => 'Who are we ?',
    'footer_list_5_2' => 'Join us',
    'footer_list_5_3' => 'Environmental impact',
    'footer_list_5_4' => 'Advertising',

    'footer_list_header_6' => 'LEGAL INFORMATIONS',
    'footer_list_6_1' => 'Terms of Service',
    'footer_list_6_2' => 'Dissemination Rules',
    'footer_list_6_3' => 'Terms of Sales',
    'footer_list_6_4' => 'Privacy Policy / Cookies',
    'footer_list_6_5' => 'Your rights and obligations',

    'footer_list_header_7' => 'SOME QUESTIONS ?',
    'footer_list_7_1' => 'Help',
    'footer_list_7_2' => 'Contact Us',
    'footer_list_7_3' => 'You are abroad ?',

    'january' => 'january',
    'february' => 'february',
    'march' => 'march',
    'april' => 'april',
    'may' => 'may',
    'june' => 'june',
    'july'=> 'july',
    'august' => 'august',
    'september' => 'september',
    'october' => 'october',
    'november' => 'november',
    'december' => 'december',

    'admin_purge_response' => ':nbadvert adverts deleted and :nbimg pictures deleted',
    'admin_purge_error' => 'purge error',
    'admin_transfert_image_response' => ':nb Mb moving to disk :disk',
    'admin_transfert_image_exist' => 'Application indicates that a transfer is already in progress',
    'admin_transfert_size_null' => 'Transfert size too small',

    'dashboard_size_local_files_label' => 'local disk charge',
    'dashboard_size_distant_files_label' => \App\Common\PicturesManager::DISK_DISTANT . 'disk charge',
    'dashboard_count_files_title' => 'File count',
    'dashboard_count_local_files_label' => 'Local',
    'dashboard_count_distant_files_label' => \App\Common\PicturesManager::DISK_DISTANT,
    'dashboard_megaBytes_label' => 'Mb',
    'dashboard_stats_label' => 'Statistics',
    'dashboard_graph_adverts_title' => 'Advert Tracking',
    'dashboard_graph_valid_adverts_label' => 'Valid adverts',
    'dashboard_graph_invalid_adverts_label' => 'Refused adverts',
    'dashboard_graph_waiting_adverts_label' => 'Pending adverts',
    'dashboard_graph_costs_title' => 'Sell Tracking',
    'dashboard_graph_sum_costs_label' => 'Sum Sells',
    'dashboard_graph_avg_costs_label' => 'Average Sells',
    'dashboard_graph_views_title' => 'Totla Views advert',

    'country_italy' => 'italy',
    'country_germany' => 'germany',
    'country_france' => 'france',
    'country_swiss' => 'swiss',
    'country_belgium' => 'belgium',
    'country_denmark' => 'denmark',
    'country_netherlands' => 'netherlands',
    'country_ireland' => 'ireland',
    'country_finland' => 'finland',
    'country_austria' => 'austria',
    'country_sweden' => 'sweden',
    'country_norway' => 'norway',
    'country_iceland' => 'iceland',
    'country_england' => 'england',
    'country_scotland' => 'scotland',
    'country_united_kingdom' => 'united Kingdom',
    'country_ukraine' => 'ukraine',
    'country_poland' => 'poland',
    'country_romania' => 'romania',
    'country_spain' => 'spain',
    'country_greece' => 'greece',
    'country_portugal' => 'portugal',
    'country_czech_republic' => 'czech republic',
    'country_turkey' => 'turkey',
    'country_hungary' => 'hungary',
    'country_russia' => 'russia',
    'country_armenia' => 'armenia',
    'country_bulgaria' => 'bulgaria',
    'country_estonia' => 'estonia',
    'country_europe' => 'europe',
];