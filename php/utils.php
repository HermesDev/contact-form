<?php

/**
 * send_user_feedback send a feedback array to the end user and exit the program
 * @param  array  $feedback  the feedback array
 */
function send_user_feedback($feedback) {
	die(json_encode($feedback));
}

/**
 * debug gives feedback to the programmer
 * @param  array  $message  the feedback array
 */
function debug($message) {
	$feedback = array(
		'status' => 'debug',
		'message' => $message
	);

	die(json_encode($feedback));
}