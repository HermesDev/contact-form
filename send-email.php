<?php

// TODO: fields to be added the wp-admin UI plugin options
// 1. $to
// 2. $subject
// 3. success class
// 4. error class
// 5. form incomplete message
// 6. invalid email message
// 7. send email error message
// 8. send email success message

add_action('wp_ajax_get_contact_form_data', 'hermes_contact_form_send_email');
add_action('wp_ajax_nopriv_get_contact_form_data', 'hermes_contact_form_send_email');

function hermes_contact_form_send_email() {

	$feedback = array('status' => 'error');

	if(!@isset($_POST['name']) || !@isset($_POST['email']) || !@isset($_POST['message'])) {
		$feedback['message'] = 'The form is incomplete.';
		die(json_encode($feedback));
	}

	// Sanitize
	$name = sanitize_text_field($_POST['name']);
	$email = sanitize_email($_POST['email']);
	$message = sanitize_text_field($_POST['message']);

	// Email Validation
	if(!is_email($email)) {
		$feedback['message'] = 'Email address is invalid.';
		die(json_encode($feedback));
	}

	// Email setup
	$headers = 'From: My Name <' . $email . '>;' . '\r\n';
	$to = "florian.goussin@gmail.com"; 
	$subject = 'EML foundation website message'; 
	$message = <<< EOF
	<html>
		<body>
		<p>{$message}</p>
		<br>
		<p>{$name}</p>
		</body>
	</html>
EOF;

	// Send the email
	if(wp_mail($to, $subject, $message, $headers)) {
		$feedback['status'] = 'success';
		$feedback['message'] = 'Message has been sent succesfully!';
	} else {
		$feedback['message'] = 'Impossible to send the message';
	}

	die(json_encode($feedback));
}

?>