<?php

/**
 * send_user_feedback send a feedback array to the end user and exit the program
 * @param  array  $feedback  the feedback array
 */
function send_user_feedback($feedback) {
	die(json_encode($feedback));
}