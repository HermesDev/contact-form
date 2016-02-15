
(function() {
	/**
	 * Validate the form inputs
	 * @callback calls sendData callback on success
	 */
  new FormValidator('hermesContactFormAdminUi', [{
    name: 'email_recipient',
    display: 'Email Recipient',
    rules: 'valid_email|max_length[200]'
	}, {
    name: 'email_subject',
    display: 'Email Subject',
    rules: 'alpha_numeric|max_length[1000]'
	}, {
    name: 'message_length',
    display: 'Message Length',
    rules: 'integer'
	}, {
    name: 'success_class',
    display: 'Success Class',
    rules: 'alpha_numeric|max_length[200]'
	}, {
    name: 'error_class',
    display: 'Error Class',
    rules: 'alpha_numeric|max_length[200]'
	}, {
    name: 'form_incomplete_message',
    display: 'Form Incomplete Message',
    rules: 'alpha_numeric|max_length[1000]'
	}, {
    name: 'send_email_success_message',
    display: 'Send Email Success Message',
    rules: 'alpha_numeric|max_length[1000]'
	}, {
    name: 'send_email_error_message',
    display: 'Send Email Error Message',
    rules: 'alpha_numeric|max_length[1000]'
	}], function(errors, event) {
		event.preventDefault();
		
		var form = event.srcElement;
    var output = form.querySelector('.output');

    output.innerHTML = '';
    if (errors.length > 0) {
      for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
      	output.innerHTML += '<div>' + errors[i].message + '<div/>'
      }
      output.className += ' error';
      console.log('errors');
    } else {
      output.className += ' success';

      sendData(event.path[0]);
    }
	});

	/**
	 * sendData XML Http Request that sends data to PHP
	 * @param  element form the form element
	 */
	function sendData(form) {
		console.log(form);
	  var xhr = new XMLHttpRequest(),
	      url = 'admin-ajax.php',
	      params = '';

	  params += 'email_recipient=' + form.querySelector('.email-recipient-group > input').value;
	  params += '&email_subject=' + form.querySelector('.email-subject-group > input').value;
	  params += '&message_length=' + form.querySelector('.message-length-group > input').value;
	  params += '&success_class=' + form.querySelector('.success-class-group > input').value;
	  params += '&error_class=' + form.querySelector('.error-class-group > input').value;
	  params += '&form_incomplete_message=' + form.querySelector('.form-incomplete-message-group > input').value;
	  params += '&send_email_success_message=' + form.querySelector('.send-email-success-message-group > input').value;
	  params += '&send_email_error_message=' + form.querySelector('.send-email-error-message-group > input').value;
	  params += '&csrf_token=' + form.querySelector('#csrf_token').value;
	  params += '&action=' + 'update_contact_form_settings';

		xhr.open("POST", url, true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function() { 
	    if(xhr.readyState == 4 && xhr.status == 200) {
	    	console.log(this.responseText);
	    	// showSuccess(this.responseText);
	    }
		};
		
		xhr.send(params);
	}
	
})();