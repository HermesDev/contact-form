<!-- 
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
 -->
<div class="hermes-contact-form-admin-ui-wrap">
	<h1>Contact form options</h1>
	<form name="hermesContactFormAdminUi" novalidate>
		<div class="output"></div>
		<div class="email-recipient-group">
			<label for="email-recipient">Email recipient</label>
			<input name="emailRecipient" type="text">
		</div>
		<div class="email-subject-group">
			<label for="email-subject">Email subject</label>
			<input name="emailSubject" type="text">
		</div>
		<div class="message-length-group">
			<label for="message-length">Message max length</label>
			<input name="messageLength" type="number" placeholder="<?php  ?>">
		</div>
		<hr>
		<div class="success-class-group">
			<label for="success-class">css class if success</label>
			<input name="successClass" type="text"></input>
		</div>
		<div class="error-class-group">
			<label for="error-class">css class if errors</label>
			<input name="errorClass" type="text"></input>
		</div>
		<hr>
		<div class="form-incomplete-message-group">
			<label for="form-incomplete-message">Form incomplete message</label>
			<input name="formIncompleteMessage" type="text"></input>
		</div>
		<div class="send-email-success-message-group">
			<label for="send-email-success-message">Send email success message</label>
			<input name="sendEmailSuccessMessage" type="text"></input>
		</div>
		<div class="send-email-error-message-group">
			<label for="send-email-error-message">Send email error message</label>
			<input name="sendEmailErrorMessage" type="text"></input>
		</div>
		<div class="submit">
			<button type="submit">Save</button>
			<?php wp_nonce_field('contact_form_settings_token', 'csrf_token'); ?>
		</div>
		<div class="output"></div>
	</form>
</div>