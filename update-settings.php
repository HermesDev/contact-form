<?php

add_action('wp_ajax_update_contact_form_settings', 'hermes_contact_form_update_settings');
add_action('wp_ajax_nopriv_update_contact_form_settings', 'hermes_contact_form_update_settings');

/**
 * hermes_contact_form_update_settings  called by ajax from admin-settings.js
 * @return array the feedback array with the keys 
 *     - status: error|success
 *     - message: string
 */
function hermes_contact_form_update_settings() {
	$feedback = array('status' => 'error');

	// check if the CSRF token is valid
	if(!@isset($_POST['csrf_token']) || !wp_verify_nonce($_POST['csrf_token'], 'contact_form_settings_token')) {
	  $feedback['message'] = 'Wrong CSRF token.';
		die(json_encode($feedback));
	}

	// if(!@isset($_POST['name']) || !@isset($_POST['email']) || !@isset($_POST['message'])) {
	// 	$feedback['message'] = 'The form is incomplete.';
	// 	die(json_encode($feedback));
	// }

	// Sanitize
	$name = sanitize_text_field($_POST['name']);
	$email = sanitize_email($_POST['email']);
	$message = sanitize_text_field($_POST['message']);

	// Validations
	hermes_contact_form_validate_form($name, $email, $message);

	$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
	$to = "florian.goussin@gmail.com"; 
	$subject = 'EML foundation website message'; 
	$message = '<p>' . $message . '</p><p>' . $name . '</p>';

	// Send the email
	if(wp_mail($to, $subject, $message, $headers)) {
		$feedback['status'] = 'success';
		$feedback['message'] = 'Message has been sent succesfully!';
	} else {
		$feedback['message'] = 'Impossible to send the message';
	}

	die(json_encode($feedback));
}

/**
 * hermes_contact_form_validate_form form validation
 * @param  string  name     the name
 * @param  string  email    the email
 * @param  string  message 	the message
 * @return Boolean          true if valid
 */
function hermes_contact_form_validate_form($name, $email, $message) {
	if(!is_email($email)) {
		$feedback['message'] = 'Email address is invalid.';
		die(json_encode($feedback));
	}

	if(strlen($name) > 200) {
		$feedback['message'] = 'Name is too long.';
		die(json_encode($feedback));
	}

	if(strlen($email) > 200) {
		$feedback['message'] = 'Email is too long.';
		die(json_encode($feedback));
	}

	if(strlen($message) > 5000) {
		$feedback['message'] = 'Message is too long.';
		die(json_encode($feedback));
	}
}

?>