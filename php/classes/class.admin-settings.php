<?php

new Admin_Settings;

/**
 * Settings Process the data from the admin settings
 */
class Admin_Settings {
	function __construct() {
		add_action('wp_ajax_update_contact_form_settings', array($this, 'ajax_form_data'));
		add_action('wp_ajax_nopriv_update_contact_form_settings', array($this, 'ajax_form_data'));
	}

	/**
	 * ajax_form_data Get the data from the ajax call
	 * @echo array the feedback array with the keys 
	 *  - status: error|success
	 *  - message: string
	 *  - messages: array
	 */
	function ajax_form_data() {
		$feedback = array(
			'status' => 'error',
			'message' => '',
			'messages' => array()
		);

		if(!$this->is_csrf_token_valid($_POST['csrf_token'])) {
			$feedback['message'] = 'Wrong CSRF token.';
			send_user_feedback($feedback);
		}

		if(!$this->are_all_input_set($_POST)) {
			$feedback['message'] = 'The form is incomplete.';
			send_user_feedback($feedback);
		}
		$data = $this->sanitize_form($_POST); // Sanitize and escape HTML

		if(!$this->is_form_valid($data, $feedback)) {
			send_user_feedback($feedback);
		}

		$this->register_settings(); 
		if(!$this->save_settings($data, $feedback)) {
			$feedback['message'] = 'No changes detected.';
			send_user_feedback($feedback);	
		}

		send_user_feedback($feedback);
	}

	/**
	 * is_csrf_token_valid check if the CSRF token is valid
	 * @param   string $csrf_token the CSRF token
	 * @return  boolean            true if the CSRF is valid
	 */
	function is_csrf_token_valid($csrf_token) {
		if(!@isset($csrf_token) || !wp_verify_nonce($csrf_token, 'contact_form_settings_token')) {
		  return false;
		}

		return true;
	}


	/**
	 * are_all_input_set check for unset inputs
	 * @param  array $input_data the $_POST array
	 */
	function are_all_input_set($input_data) {
		if(!@isset($input_data[EMAIL_RECIPIENT_OPTION]) || 
		!@isset($input_data[EMAIL_SUBJECT_OPTION]) || 
		!@isset($input_data[MESSAGE_LENGTH_OPTION]) ||
		!@isset($input_data[SUCCESS_CLASS_OPTION]) ||
		!@isset($input_data[ERROR_CLASS_OPTION]) ||
		!@isset($input_data[FORM_INCOMPLETE_MESSAGE_OPTION]) ||
		!@isset($input_data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION]) ||
		!@isset($input_data[SEND_EMAIL_ERROR_MESSAGE_OPTION])) {
			return false;
		}

		return true;
	}


	/**
	 * sanitize_form return a clean array with the inputs sanitized and HTML escapted
	 * @param   array  $input_data the $_POST array to sanitize
	 * @return  array              the sanitize array
	 */
	function sanitize_form($input_data) {
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
	 * is_form_valid form validation
	 * @param  array   $data      the array of data to validate
	 * @param  array   $feedback  the feedback array reference
	 * @return Boolean            true if valid
	 */
	function is_form_valid($data, &$feedback) {
		$result = true;

		if(!strlen($data[EMAIL_RECIPIENT_OPTION]) === 0 && !is_email($data['emailRecipient'])) {
			$feedback['messages'][] = 'Email Recipient is invalid.';
			$result = false;
		}

		if(strlen($data[EMAIL_SUBJECT_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Email Subject is too long.';
			$result = false;
		}

		if(strlen($data[MESSAGE_LENGTH_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Success Class is too long.';
			$result = false;
		}

		if(strlen($data[SUCCESS_CLASS_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Error Class is too long.';
			$result = false;
		}

		if(strlen($data[ERROR_CLASS_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Error Class is too long.';
			$result = false;
		}

		if(strlen($data[FORM_INCOMPLETE_MESSAGE_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Form Incomplete Message is too long.';
			$result = false;
		}

		if(strlen($data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Send Email Success Message is invalid.';
			$result = false;
		}

		if(strlen($data[SEND_EMAIL_ERROR_MESSAGE_OPTION]) > TEXT_FIELD__LEN_MAX) {
			$feedback['messages'][] = 'Send Email Error Message is invalid.';
			$result = false;
		}

		return $result;
	}


	/**
	 * register_settings create the the table fields if needed
	 */
	function register_settings() {
		if(!get_option(EMAIL_RECIPIENT_OPTION)) {
			register_setting(PLUGIN_PREFIX, EMAIL_RECIPIENT_OPTION);
		}

		if(!get_option(EMAIL_SUBJECT_OPTION)) {
			register_setting(PLUGIN_PREFIX, EMAIL_SUBJECT_OPTION);
		}

		if(!get_option(MESSAGE_LENGTH_OPTION)) {
			register_setting(PLUGIN_PREFIX, MESSAGE_LENGTH_OPTION);
		}

		if(!get_option(SUCCESS_CLASS_OPTION)) {
			register_setting(PLUGIN_PREFIX, SUCCESS_CLASS_OPTION);
		}

		if(!get_option(ERROR_CLASS_OPTION)) {
			register_setting(PLUGIN_PREFIX, ERROR_CLASS_OPTION);
		}

		if(!get_option(FORM_INCOMPLETE_MESSAGE_OPTION)) {
			register_setting(PLUGIN_PREFIX, FORM_INCOMPLETE_MESSAGE_OPTION);
		}

		if(!get_option(SEND_EMAIL_SUCCESS_MESSAGE_OPTION)) {
			register_setting(PLUGIN_PREFIX, SEND_EMAIL_SUCCESS_MESSAGE_OPTION);
		}

		if(!get_option(SEND_EMAIL_ERROR_MESSAGE_OPTION)) {
			register_setting(PLUGIN_PREFIX, SEND_EMAIL_ERROR_MESSAGE_OPTION);
		}
	}

	/**
	 * save_settings save all the settings to the database
	 * @param   array    $data      the data array with the input values
	 * @param   array    $feedback  the feedback array reference
	 * @return  boolean             return true if the database has been successfully updated
	 */
	function save_settings($data, &$feedback) {
		$feedback['status'] = 'success'; // always a success from here
		$result = false;

		if(update_option(EMAIL_RECIPIENT_OPTION, $data[EMAIL_RECIPIENT_OPTION])) {
			$feedback['messages'][] = EMAIL_RECIPIENT_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(EMAIL_SUBJECT_OPTION, $data[EMAIL_SUBJECT_OPTION])) {
			$feedback['messages'][] = EMAIL_SUBJECT_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(MESSAGE_LENGTH_OPTION, $data[MESSAGE_LENGTH_OPTION])) {
			$feedback['messages'][] = MESSAGE_LENGTH_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(SUCCESS_CLASS_OPTION, $data[SUCCESS_CLASS_OPTION])) {
			$feedback['messages'][] = SUCCESS_CLASS_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(ERROR_CLASS_OPTION, $data[ERROR_CLASS_OPTION])) {
			$feedback['messages'][] = ERROR_CLASS_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(FORM_INCOMPLETE_MESSAGE_OPTION, $data[FORM_INCOMPLETE_MESSAGE_OPTION])) {
			$feedback['messages'][] = FORM_INCOMPLETE_MESSAGE_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(SEND_EMAIL_SUCCESS_MESSAGE_OPTION, $data[SEND_EMAIL_SUCCESS_MESSAGE_OPTION])) {
			$feedback['messages'][] = SEND_EMAIL_SUCCESS_MESSAGE_OPTION.' has been successfully updated.';
			$result = true;
		}

		if(update_option(SEND_EMAIL_ERROR_MESSAGE_OPTION, $data[SEND_EMAIL_ERROR_MESSAGE_OPTION])) {
			$feedback['messages'][] = SEND_EMAIL_ERROR_MESSAGE_OPTION.' has been successfully updated.';
			$result = true;
		}

		return $result;
	}

}
