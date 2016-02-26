<?php

define('PLUGIN_NAME', 'Contact Form');
define('PLUGIN_PREFIX', 'hermes_cf_');
define('WEBSITE_SNAPSHOT__PLUGIN_URL', plugin_dir_url(dirname(__FILE__)));
define('WEBSITE_SNAPSHOT__PLUGIN_PATH', dirname(__FILE__));
define('DB_PREFIX', 'HERMES__CONTACT_FORM');

// Settings
define('EMAIL_RECIPIENT_OPTION', 'recipient');
define('EMAIL_SUBJECT_OPTION', 'subject');
define('MESSAGE_LENGTH_OPTION', 'message_length');
define('SUCCESS_CLASS_OPTION', 'success_class');
define('ERROR_CLASS_OPTION', 'error_class');
define('FORM_INCOMPLETE_MESSAGE_OPTION', 'form_incomplete_message');
define('SEND_EMAIL_SUCCESS_MESSAGE_OPTION', 'send_email_success_message');
define('SEND_EMAIL_ERROR_MESSAGE_OPTION', 'send_email_error_message');
define('TEXT_BEFORE_CONTACT_FORM_OPTION', 'text_before_contact_form');
define('TEXT_AFTER_CONTACT_FORM_OPTION', 'text_after_contact_form');

define('DB__EMAIL_RECIPIENT_OPTION', DB_PREFIX.'recipient');
define('DB__EMAIL_SUBJECT_OPTION', DB_PREFIX.'subject');
define('DB__MESSAGE_LENGTH_OPTION', DB_PREFIX.'message_length');
define('DB__SUCCESS_CLASS_OPTION', DB_PREFIX.'success_class');
define('DB__ERROR_CLASS_OPTION', DB_PREFIX.'error_class');
define('DB__FORM_INCOMPLETE_MESSAGE_OPTION', DB_PREFIX.'form_incomplete_message');
define('DB__SEND_EMAIL_SUCCESS_MESSAGE_OPTION', DB_PREFIX.'send_email_success_message');
define('DB__SEND_EMAIL_ERROR_MESSAGE_OPTION', DB_PREFIX.'send_email_error_message');
define('DB__TEXT_BEFORE_CONTACT_FORM_OPTION', DB_PREFIX.'text_before_contact_form');
define('DB__TEXT_AFTER_CONTACT_FORM_OPTION', DB_PREFIX.'text_after_contact_form');

define('TEXT_FIELD__LEN_MAX', 200);
define('MESSAGE__LEN_MAX', 5000);