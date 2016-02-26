
(function() {
  /**
   * Validate the form inputs
   * @callback calls sendData callback on success
   */
  new FormValidator('hermesContactFormAdminUi', [{
    name: 'recipient',
    display: 'Recipient',
    rules: 'valid_email|max_length[200]'
  }, {
    name: 'subject',
    display: 'Subject',
    rules: 'max_length[1000]'
  }, {
    name: 'message_length',
    display: 'Message Length',
    rules: 'integer'
  }, {
    name: 'success_class',
    display: 'Success Class',
    rules: 'max_length[200]'
  }, {
    name: 'error_class',
    display: 'Error Class',
    rules: 'max_length[200]'
  }, {
    name: 'form_incomplete_message',
    display: 'Form Incomplete Message',
    rules: 'max_length[1000]'
  }, {
    name: 'send_email_success_message',
    display: 'Send Email Success Message',
    rules: 'max_length[1000]'
  }, {
    name: 'send_email_error_message',
    display: 'Send Email Error Message',
    rules: 'alpha_numeric|max_length[1000]'
  }], function(errors, event) {
    event.preventDefault();
    
    var form = event.srcElement;
        output = form.querySelector('.output'),
        feedback = hermesdev.userFeedback({
          output: output,
          successClass: 'updated',
          errorClass: 'error'
        });

    console.log('debug start');
    console.log(errors);
    console.log(errors.length);

    feedback.clearOuput();

    if(errors.length > 0) {
      var errorLen = errors.length,
          errorMessage = '';
          
      for(var i = 0; i < errorLen; i++) {
        errorMessage += '<div class="error">' + errors[i].message + '</div>';
      }
      
      feedback.showError(errorMessage);

    } else {
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

    params += 'recipient=' + form.querySelector('.recipient-group > input').value;
    params += '&subject=' + form.querySelector('.subject-group > input').value;
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
      if(xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(this.responseText);

        if(response.status === 'success') {
          ajax_success(response);

        } else if(response.status === 'error') {
          ajax_error(response);
          
        } else if(response.status === 'debug') { 
          console.log(response);
        }
      }
    };
    
    xhr.send(params);
  }

  /**
   * ajax_success response status: success
   * @param  object response the response object
   */
  function ajax_success(response) {
    feedback.clearOuput(); 

    if(response.message.length !== 0) {
      feedback.showSuccess(response.message);

    } else if(response.hasOwnProperty('messages') && response.messages.length > 0) {
      var errorLen = response.messages.length,
          errorMessage = '';
          
      for(var i = 0; i < errorLen; i++) {
        errorMessage += '<div class="updated">' + response.messages[i] + '</div>';
      }

      feedback.showSuccess(errorMessage);
    } else {
      console.error('Something wrong on the php side.');
    }
  }

  /**
   * ajax_error response status: error
   * @param  object response the response object
   */
  function ajax_error(response) {
    feedback.clearOuput();

    if(response.message.length !== 0) {
      feedback.showError(response.message);

    } else if(response.hasOwnProperty('messages') && response.messages.length > 0) {
      var errorLen = response.messages.length,
          errorMessage = '';
          
      for(var i = 0; i < errorLen; i++) {
        errorMessage += '<div class="error">' + response.messages[i] + '</div>';
      }

      feedback.showError(errorMessage);
    } else {
      console.error('Something wrong on the php side.');
    }
  }
  
})();