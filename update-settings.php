<?php

add_action('wp_ajax_update_contact_form_settings', 'hermes_contact_form_update_settings');
add_action('wp_ajax_nopriv_update_contact_form_settings', 'hermes_contact_form_update_settings');

/**
 * hermes_contact_form_update_settings  called by ajax from admin-settings.js
 * @echo array the feedback array with the keys 
 *     - status: error|success
 *     - message: string
 */
function hermes_contact_form_update_settings() {
	$feedback = array(
		'status' => 'error',
		'message' => '',
		'messages' => array()
	);

	if(!hermes_is_csrf_token_valid($_POST['csrf_token']) {
		$feedback['message'] = 'Wrong CSRF token.';
		die(json_encode($feedback));
	}

	if(!hermes_are_all_input_set($_POST) {
		$feedback['message'] = 'The form is incomplete.';
		die(json_encode($feedback));
	}
	
	$data = hermes_sanitize_form($_POST); // Sanitize and escape HTML

	if(!hermes_contact_is_form_valid($data)) {
		die(json_encode($feedback));
	}

	if(!hermes_contact_save_settings_to_db($data)) {
		die(json_encode($feedback));	
	}

	$feedback['status'] = 'success';
	$feedback['message'] = 'Settings have been updated successfully.'
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
	if(!@isset($input_data[EMAIL_RECIPIENT_OPTION]) || 
	!@isset($input_data[EMAIL_SUBJECT_OPTION]) || 
	!@isset($input_data[MESSAGE_LENGTH_OPTION])) ||
	!@isset($input_data[SUCCESS_CLASS_OPTION])) ||
	!@isset($input_data[ERROR_CLASS_OPTION])) ||
	!@isset($input_data[FORM_INCOMPLETE_MESSAGE_OPTION])) ||
	!@isset($input_data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION])) ||
	!@isset($input_data[SEND_EMAIL_ERROR_MESSAGE_OPTION])) {
		return false;
	}

	return true;
}


/**
 * hermes_sanitize_form return a clean array with the inputs sanitized and HTML escapted
 * @param   array  $input_data the $_POST array to sanitize
 * @return  array              the sanitize array
 */
function hermes_sanitize_form($input_data) {
	$data = array();

	$data[EMAIL_RECIPIENT_OPTION] = sanitize_email(esc_html($input_data[EMAIL_RECIPIENT_OPTION]));
	$data[EMAIL_SUBJECT_OPTION] = sanitize_text_field(esc_html($input_data[EMAIL_SUBJECT_OPTION]));
	$data[MESSAGE_LENGTH_OPTION] = filter_var(esc_html($input_data[MESSAGE_LENGTH_OPTION]), FILTER_SANITIZE_NUMBER_INT);
	$data[SUCCESS_CLASS_OPTION] = sanitize_text_field(esc_html($input_data[SUCCESS_CLASS_OPTION]));
	$data[ERROR_CLASS_OPTION] = sanitize_text_field(esc_html($input_data[ERROR_CLASS_OPTION]));
	$data[FORM_INCOMPLETE_MESSAGE_OPTION] = sanitize_text_field(esc_html($input_data[FORM_INCOMPLETE_MESSAGE_OPTION]));
	$data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION] = sanitize_text_field(esc_html($input_data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION]));
	$data[SEND_EMAIL_ERROR_MESSAGE_OPTION] = sanitize_text_field(esc_html($input_data[SEND_EMAIL_ERROR_MESSAGE_OPTION]));

	return $data;
}


/**
 * hermes_contact_form_validate_form form validation
 * @param  string  name     the name
 * @param  string  email    the email
 * @param  string  message 	the message
 * @return Boolean          true if valid
 */
function hermes_contact_is_form_valid($data) {
	$result = true;
	define('TEXT_FIELD_LEN_MAX', 200);

	if(strlen($data['emailSubject']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Email Subject is too long.';
		$result = false;
	}

	if(strlen($data['successClass']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Success Class is too long.';
		$result = false;
	}

	if(strlen($data['errorClass']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Error Class is too long.';
		$result = false;
	}

	if(strlen($data['errorClass']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Error Class is too long.';
		$result = false;
	}

	if(strlen($data['formIncompleteMessage']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Form Incomplete Message is too long.';
		$result = false;
	}

	if(strlen($data['sendEmailSuccessMessage']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Send Email Success Message is invalid.';
		$result = false;
	}

	if(strlen($data['sendEmailErrorMessage']) > TEXT_FIELD_LEN_MAX) {
		$feedback['messages'][] = 'Send Email Error Message is invalid.';
		$result = false;
	}

	if(!is_email($data['emailRecipient'])) {
		$feedback['messages'][] = 'Email Recipient is invalid.';
		$result = false;
	}

	return $result;
}


/**
 * hermes_contact_save_settings_to_db save all the settings to the database
 * @param   array    $data  the data array with the input values
 * @return  boolean         return true if the database has been successfully updated
 */
function hermes_contact_save_settings_to_db($data) {
	$option_group = 'contatct_form_settings';
	register_setting($option_group, EMAIL_RECIPIENT_OPTION);
	register_setting($option_group, EMAIL_SUBJECT_OPTION);
	register_setting($option_group, MESSAGE_LENGTH_OPTION);
	register_setting($option_group, SUCCESS_CLASS_OPTION);
	register_setting($option_group, ERROR_CLASS_OPTION);
	register_setting($option_group, FORM_INCOMPLETE_MESSAGE_OPTION);
	register_setting($option_group, SEND_EMAIL_SUCCESS_MESSAGE_OPTION);
	register_setting($option_group, SEND_EMAIL_ERROR_MESSAGE_OPTION);

	if(!update_option(EMAIL_RECIPIENT_OPTION, $data[EMAIL_RECIPIENT_OPTION])) {
		return false;
	}

	if(!update_option(EMAIL_SUBJECT_OPTION, $data[EMAIL_SUBJECT_OPTION])) {
		return false;
	}

	if(!update_option(MESSAGE_LENGTH_OPTION, $data[MESSAGE_LENGTH_OPTION])) {
		return false;
	}

	if(!update_option(SUCCESS_CLASS_OPTION, $data[SUCCESS_CLASS_OPTION])) {
		return false;
	}

	if(!update_option(ERROR_CLASS_OPTION, $data[ERROR_CLASS_OPTION])) {
		return false;
	}

	if(!update_option(FORM_INCOMPLETE_MESSAGE_OPTION, $data[FORM_INCOMPLETE_MESSAGE_OPTION])) {
		return false;
	}

	if(!update_option(SEND_EMAIL_SUCCESS_MESSAGE_OPTION, $data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION])) {
		return false;
	}

	if(!update_option(SEND_EMAIL_ERROR_MESSAGE_OPTION, $data[SEND_EMAIL_ERROR_MESSAGE_OPTION])) {
		return false;
	}

	return true;
}

?>