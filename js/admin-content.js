
(function() {
  /**
   * Validate the form inputs
   * @callback calls sendData callback on success
   */
  new FormValidator('hermes_contact_form_admin_content_ui', [{
    name: 'recipient',
    display: 'Recipient',
    rules: 'valid_email|max_length[200]'
  }, {
    name: 'subject',
    display: 'Subject',
    rules: 'max_length[1000]'
  }, {
    name: 'text_before',
    display: 'Text before form',
    rules: ''
  }, {
    name: 'text_after',
    display: 'Text after form',
    rules: ''
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
    params += '&text_before=' + form.querySelector('.text-before-group > input').value;
    params += '&text_after=' + form.querySelector('.text-after-group > input').value;
    params += '&csrf_token=' + form.querySelector('#csrf_token').value;
    params += '&action=' + 'update_contact_form_content';

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