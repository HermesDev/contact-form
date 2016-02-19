<?php

new Contact_Form;

/**
 * Contact_Form Process the data from the contact form
 */
class Contact_Form {
  function __construct() {
    add_filter('wp_mail_content_type', function() {
        return "text/html";
    });
    add_action('wp_ajax_get_contact_form_data', array($this, 'ajax_form_data'));
    add_action('wp_ajax_nopriv_get_contact_form_data', array($this, 'ajax_form_data'));
  }

  /**
   * ajax_form_data Get the data from ajax call
   * @echo array the feedback array with the keys 
   *  - status: error|success
   *  - message: string
   *  - messages: array()
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

    $data = $this->sanitize_form($_POST); 

    if(!$this->is_form_valid($data, $feedback)) {
      send_user_feedback($feedback);
    }

    $this->send_email($data, $feedback); 

    die(json_encode($feedback));
  }

  /**
   * is_csrf_token_valid check if the CSRF token is valid
   * @param   string $csrf_token the CSRF token
   * @return  boolean            true if the CSRF is valid
   */
  function is_csrf_token_valid($csrf_token) {
    if(!@isset($csrf_token) || !wp_verify_nonce($csrf_token, 'contact_form_token')) {
      return false;
    }

    return true;
  }

  /**
   * are_all_input_set check for unset inputs
   * @param  array $input_data the $_POST array
   */
  function are_all_input_set($input_data) {
    if(!@isset($input_data['name']) || 
    !@isset($input_data['email']) || 
    !@isset($input_data['message'])) {
      return false;
    }

    return true;
  }

  /**
   * sanitize_form return a clean array with the inputs sanitized and properly escaped HTML
   * @param   array  $input_data the $_POST array to sanitize
   * @return  array              the sanitize array
   */
  function sanitize_form($input_data) {
    $data = array();

    $data['name'] = sanitize_text_field(esc_html($input_data['name']));
    $data['email'] = sanitize_email(esc_html($input_data['email']));
    $data['message'] = sanitize_text_field(esc_html($input_data['message']));

    return $data;
  }

  /**
   * is_form_valid form validation
   * @param  string   $input_data  the input_data
   * @param  array    $&feedback   a reference to the feedback object
   * @return Boolean               true if valid
   */
  function is_form_valid($input_data, &$feedback) {
    $result = true;
    $feedback['message'] = '';

    if(strlen($input_data['name']) === 0) {
      $feedback['messages'][] = 'Name is required.';
      $result = false;
    }

    if(strlen($input_data['email']) === 0) {
      $feedback['messages'][] = 'Email is required.';
      $result = false;
    }

    if(strlen($input_data['message']) === 0) { // TODO: len?????
      $feedback['messages'][] = 'Message is required.';
      $result = false;
    }

    if(strlen($input_data['email']) !== 0 && !is_email($input_data['email'])) {
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
   * send_email Send the email
   * @param  array    $data       the form data
   * @param  array    $&feedback  a reference to the feedback object
   * @return boolean              true if the email has been sent
   */
  function send_email($data, &$feedback) {
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
}
