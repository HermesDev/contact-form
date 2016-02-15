<?php
/*
Plugin Name: Hermes Contact Form
Plugin URI: http://hermesdevelopment.com/
Description: Very simple contact form for our needs
Version: 1.0
Author: Hermes Development
Author URI: http://hermesdevelopment.com
License: GPLv2
*/

define('PLUGIN_NAME', 'Contact Form');
define('PLUGIN_PREFIX', 'hermes_cf_');
define('WEBSITE_SNAPSHOT__PLUGIN_URL', plugin_dir_url(__FILE__));

// Settings
define('EMAIL_RECIPIENT_OPTION', 'email_recipient');
define('EMAIL_SUBJECT_OPTION', 'email_subject');
define('MESSAGE_LENGTH_OPTION', 'message_length');
define('SUCCESS_CLASS_OPTION', 'success_class');
define('ERROR_CLASS_OPTION', 'error_class');
define('FORM_INCOMPLETE_MESSAGE_OPTION', 'form_incomplete_message');
define('SEND_EMAIL_SUCCESS_MESSAGE_OPTION', 'send_email_success_message');
define('SEND_EMAIL_ERROR_MESSAGE_OPTION', 'send_email_error_message');

include "send-email.php";
include "update-settings.php";


// src css front and back ends
add_action('init', 'hermes_contact_form_styles_both');
function hermes_contact_form_styles_both() {
	wp_register_style('hermes_contact_form_css', WEBSITE_SNAPSHOT__PLUGIN_URL.'css/style.css');
	wp_enqueue_style('hermes_contact_form_css');
}

// src js front and back ends
add_action('init', 'hermes_contact_form_enqueue_scripts_both');
function hermes_contact_form_enqueue_scripts_both() {
	wp_register_script('validation_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'node_modules/validate-js/validate.min.js', null, null, false);
  wp_enqueue_script('validation_js');
}

// src js and css files for the front end side
add_action('wp_enqueue_scripts', 'hermes_contact_form_enqueue_scripts');
function hermes_contact_form_enqueue_scripts() {
	wp_register_script('hermes_contact_form_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'js/contact-form.js', null, null, true);
  wp_enqueue_script('hermes_contact_form_js');
}

// src js and css files for the wp-admin side (settings)
add_action('admin_init', 'hermes_contact_form_settings_enqueue_scripts');
function hermes_contact_form_settings_enqueue_scripts() {
  wp_register_script('hermes_contact_form_admin_ui_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'js/admin-ui.js', null, null, true);
  wp_enqueue_script('hermes_contact_form_admin_ui_js');
}

// Settings page
add_action('admin_menu', function() {
	add_options_page(PLUGIN_NAME, PLUGIN_NAME, 'manage_options', __FILE__, 'hermes_contact_form_settings_view');
});

function hermes_contact_form_settings_view() {
	include 'views/_admin-ui.php';
}

// Shortcodes handler
add_action('init', function() {
	add_shortcode('hermes_contact_form', 'hermes_contact_form_main_view');
});

function hermes_contact_form_main_view() {
	include 'views/_contact-form.php';
}

?>