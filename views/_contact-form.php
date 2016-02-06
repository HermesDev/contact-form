<div class="hermes-contact-form-wrap">
	<form id="hermes-contact-form" action="submit-contact-form" method="post" novalidate>
		<div class="name">
			<label for="visitor-name">Name</label>
			<input id="visitor-name" type="text">
		</div>
		<div class="email">
			<label for="visitor-email">Email</label>
			<input id="visitor-email" type="text">
		</div>
		<div class="message">
			<label for="visitor-message">Message</label>
			<textarea id="visitor-message" cols="30" rows="10"></textarea>
		</div>
		<div class="submit">
			<button type="submit">Submit</button>
			<?php wp_nonce_field( 'submit-contact-form' ); ?>
		</div>
	</form>
</div>