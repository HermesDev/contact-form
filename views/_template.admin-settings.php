
<div class="hermes-contact-form-admin-ui-wrap">
  <h1>Contact form options</h1>
  <form name="hermesContactFormAdminUi" autocomplete="off" novalidate>
    <div class="recipient-group">
      <label for="recipient">Recipient</label>
      <input name="recipient" id="recipient" type="text" value="<?php echo get_option('email_recipient'); ?>">
    </div>
    <div class="subject-group">
      <label for="subject">Subject</label>
      <input name="subject" id="subject" type="text" value="<?php echo get_option('email_subject'); ?>">
    </div>
    <div class="message-length-group">
      <label for="message-length">Message max length</label>
      <input id="message-length" name="message_length" type="number" value="<?php echo get_option('message_length'); ?>">
    </div>
    <hr>
    <div class="success-class-group">
      <label for="success-class">css class if success</label>
      <input id="success-class" name="success_class" type="text" value="<?php echo get_option('success_class'); ?>"></input>
    </div>
    <div class="error-class-group">
      <label for="error-class">css class if errors</label>
      <input id="error-class" name="error_class" type="text" value="<?php echo get_option('error_class'); ?>"></input>
    </div>
    <hr>
    <div class="form-incomplete-message-group">
      <label for="form-incomplete-message">Form incomplete message</label>
      <input id="form-incomplete-message" name="form_incomplete_message" type="text" value="<?php echo get_option('form_incomplete_message'); ?>"></input>
    </div>
    <div class="send-email-success-message-group">
      <label for="send-email-success-message">Send email success message</label>
      <input id="send-email-success-message" name="send_email_success_message" type="text" value="<?php echo get_option('send_email_success_message'); ?>"></input>
    </div>
    <div class="send-email-error-message-group">
      <label for="send-email-error-message">Send email error message</label>
      <input id="send-email-error-message" name="send_email_error_message" type="text" value="<?php echo get_option('send_email_error_message'); ?>"></input>
    </div>
    <div class="submit">
      <button type="submit">Save</button>
      <?php wp_nonce_field('contact_form_settings_token', 'csrf_token'); ?>
    </div>
    <div class="output"></div>
  </form>
</div>