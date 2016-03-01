
<div class="hermes-contact-form-admin-ui-wrap">
  <h1>Contact form options</h1>
  <form name="hermes_contact_form_admin_content_ui" autocomplete="off" novalidate>
    <div class="recipient-group">
      <label for="recipient">Recipient</label>
      <input name="recipient" id="recipient" type="text" value="<?php echo get_option(DB__EMAIL_RECIPIENT_OPTION); ?>">
    </div>
    <div class="subject-group">
      <label for="subject">Subject</label>
      <input name="subject" id="subject" type="text" value="<?php echo get_option(DB__EMAIL_SUBJECT_OPTION); ?>">
    </div>
    <hr>
    <div class="text-before-group">
      <label for="text-before">Text before form</label>
      <input name="text_before" id="text-before" type="text" value="<?php echo get_option(DB__TEXT_BEFORE_CONTACT_FORM_OPTION); ?>">
    </div>
    <div class="text-after-group">
      <label for="text-after">Text after form</label>
      <input name="text_after" id="text-after" type="text" value="<?php echo get_option(DB__TEXT_AFTER_CONTACT_FORM_OPTION); ?>">
    </div>
    <div class="submit">
      <button type="submit">Save</button>
      <?php wp_nonce_field('contact_form_content_token', 'csrf_token'); ?>
    </div>
    <div class="output"></div>
  </form>
</div>