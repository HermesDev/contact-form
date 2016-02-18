<?php

define('PLUGIN_NAME', 'Contact Form');
define('PLUGIN_PREFIX', 'hermes_cf_');
define('WEBSITE_SNAPSHOT__PLUGIN_URL', plugin_dir_url(dirname(__FILE__)));

// Settings
define('EMAIL_RECIPIENT_OPTION', 'recipient');
define('EMAIL_SUBJECT_OPTION', 'subject');
define('MESSAGE_LENGTH_OPTION', 'message_length');
define('SUCCESS_CLASS_OPTION', 'success_class');
define('ERROR_CLASS_OPTION', 'error_class');
define('FORM_INCOMPLETE_MESSAGE_OPTION', 'form_incomplete_message');
define('SEND_EMAIL_SUCCESS_MESSAGE_OPTION', 'send_email_success_message');
define('SEND_EMAIL_ERROR_MESSAGE_OPTION', 'send_email_error_message');

define('TEXT_FIELD__LEN_MAX', 200);
define('MESSAGE__LEN_MAX', 5000);