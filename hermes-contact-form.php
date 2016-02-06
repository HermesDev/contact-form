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

// TODO: fields to be added the wp-admin UI plugin options
// 1. $to
// 2. $subject
// 3. success class
// 4. error class
// 5. form incomplete message
// 6. invalid email message
// 7. send email error message
// 8. send email success message
// 9. length max of the visitor's message
// 
define( 'PLUGIN_DIR', dirname(__FILE__).'/' ); 

include "send-email.php";

// src js and css files
add_action('wp_enqueue_scripts', 'hermes_contact_form_enqueue_scripts');
add_action('wp_enqueue_styles', 'hermes_contact_form_enqueue_styles');


function hermes_contact_form_enqueue_scripts() {
  wp_register_script('hermes_contact_js', plugins_url('hermes-contact-form/plugin.js'), array('jquery'), '2.1', true);
  wp_enqueue_script('hermes_contact_js');
}

function hermes_contact_form_enqueue_styles() {}

// Shortcodes handler
add_action('init', 'hermes_contact_form_register_shortcodes');

function hermes_contact_form_register_shortcodes() {
	add_shortcode('hermes_contact_form', 'hermes_contact_form_main_view');
}

function hermes_contact_form_main_view() {
	include 'views/_contact-form.php';
}

?>