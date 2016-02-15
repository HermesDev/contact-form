<div class="hermes-contact-form-wrap">
	<form id="hermes-contact-form" novalidate>
		<div class="name input-group">
			<label for="visitor-name">Name</label>
			<input id="visitor-name" type="text">
		</div>
		<div class="email input-group">
			<label for="visitor-email">Email</label>
			<input id="visitor-email" type="text">
		</div>
		<div class="message input-group">
			<label for="visitor-message">Message</label>
			<textarea id="visitor-message" cols="30" rows="10"></textarea>
		</div>
		<div class="submit input-group">
			<button type="submit">Submit</button>
			<?php wp_nonce_field('contact_form_token', 'csrf_token'); ?>
		</div>
		<div class="output input-group"></div>
	</form>
</div>