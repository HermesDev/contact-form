<?php


add_filter('wp_mail_content_type', function(){
    return "text/html";
});

add_action('wp_ajax_get_contact_form_data', 'hermes_contact_form_process_form');
add_action('wp_ajax_nopriv_get_contact_form_data', 'hermes_contact_form_process_form');

/**
 * hermes_contact_form_process_form  called by ajax from client-validation.js
 * @return array the feedback array with the keys 
 *     - status: error|success
 *     - message: string
 */
function hermes_contact_form_process_form() {
	$feedback = array(
		'status' => 'error',
		'message' => '',
		'messages' => array()
	);

	if(!hermes_is_csrf_token_valid($_POST['csrf_token'])) {
		$feedback['message'] = 'Wrong CSRF token.';
		send_user_feedback($feedback);
	}

	if(!hermes_are_all_input_set($_POST)) {
		$feedback['message'] = 'The form is incomplete.';
		send_user_feedback($feedback);
	}

	$data = hermes_sanitize_form($_POST); // Sanitize and escape HTML

	if(!hermes_contact_is_form_valid($data, $feedback)) {
		send_user_feedback($feedback);
	}

	hermes_contact_form_send_email($data, $feedback); // SEND EMAIL

	die(json_encode($feedback));
}

/**
 * hermes_is_csrf_token_valid check if the CSRF token is valid
 * @param   string $csrf_token the CSRF token
 * @return  boolean            true if the CSRF is valid
 */
function hermes_is_csrf_token_valid($csrf_token) {
	if(!@isset($csrf_token) || !wp_verify_nonce($csrf_token, 'contact_form_settings_token')) {
	  return false;
	}

	return true;
}

/**
 * hermes_are_all_input_set check for unset inputs
 * @param  array $input_data the $_POST array
 */
function hermes_are_all_input_set($input_data) {
	if(!@isset($input_data['name']) || 
	!@isset($input_data['email']) || 
	!@isset($input_data['message'])) {
		return false;
	}

	return true;
}

/**
 * hermes_contact_form_sanitize_form return a clean array with the inputs sanitized and properly escaped HTML
 * @param   array  $input_data the $_POST array to sanitize
 * @return  array              the sanitize array
 */
function hermes_contact_form_sanitize_form($input_data) {
	$data = array();

	$data['name'] = sanitize_email(esc_html($input_data['name']));
	$data['email'] = sanitize_email(esc_html($input_data['email']));
	$data['message'] = sanitize_email(esc_html($input_data['message']));

	return $data;
}

/**
 * hermes_contact_form_is_form_valid form validation
 * @param  string   $input_data  the input_data
 * @param  array    $&feedback   a reference to the feedback object
 * @return Boolean               true if valid
 */
function hermes_contact_form_is_form_valid($input_data, &$feedback) {
	$result = true;
	$feedback['message'] = '';

	if(!is_email($input_data['email'])) {
		$feedback['messages'][] = 'Email address is invalid.';
		$result = false;
	}

	if(strlen($input_data['name']) > TEXT_FIELD__LEN_MAX) {
		$feedback['messages'][] = 'Name is too long.';
		$result = false;
	}

	if(strlen($input_data['email']) > TEXT_FIELD__LEN_MAX) {
		$feedback['messages'][] = 'Email is too long.';
		$result = false;
	}

	if(strlen($input_data['message']) > MESSAGE__LEN_MAX) {
		$feedback['messages'][] = 'Message is too long.';
		$result = false;
	}

	return $result;
}

/**
 * hermes_contact_form_send_email Send the email
 * @param  array    $data       the form data
 * @param  array    $&feedback  a reference to the feedback object
 * @return boolean              true if the email has been sent
 */
function hermes_contact_form_send_email($data, &$feedback) {
	$headers = 'From: ' . $data['name'] . ' <' . $data['email'] . '>' . "\r\n";
	$to = "florian.goussin@gmail.com"; 
	$subject = 'EML foundation website message'; 
	$data['message'] = '<p>' . $data['message'] . '</p><p>' . $data['name'] . '</p>';

	// Send the email
	if(wp_mail($to, $subject, $data['message'], $headers)) {
		$feedback['status'] = 'success';
		$feedback['message'] = 'Message has been sent succesfully!';
	} else {
		$feedback['message'] = 'Impossible to send the message';
	}
}

?>