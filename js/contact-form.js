(function() {
  var userDefinedVariables = hermesdev.contactForm.userDefinedVariables;

  /**
   * isEmail validate an email address
   * @param   string   email the email to validate
   * @return  boolean  return true if the email is valid or false otherwise
   */
  function isEmail(email) {
    var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(email);
  }

  var formWrapper = document.getElementsByClassName('hermes-contact-form-wrap');

  // onsubmit
  // loop for misuse of the plugin (e.g. bootstrap hidden)
  for (var i = 0; i < formWrapper.length; i++) {
    var form = formWrapper[i].querySelector('#hermes-contact-form');
    form.onsubmit = submitForm;
  }

  /**
   * isFormValid form validation
   * @param  string  name     the name
   * @param  string  email    the email
   * @param  string  message  the message
   * @return Boolean          true if valid
   */
  function isFormValid(data, feedback) {
    var result = true;
    feedback.clearOuput();

    if(data.name.value.length === 0) {
      feedback.showError('Name is required.', data.name);
      result = false;
    }

    if(data.email.value.length === 0) {
      feedback.showError('Email is required.', data.email);
      result = false;
    }

    if(data.email.value.length !== 0 && !isEmail(data.email.value)) {
      feedback.showError('Email is invalid.', data.email);
      result = false;
    }

    if(data.message.value.length === 0) {
      feedback.showError('Message is required.', data.message);
      result = false;
    }

    if(result == false) { 
      return false;
    }
    // 2nd step

    if(data.name.value.length > 200) {
      feedback.showError('Name is too long.', data.name);
      result = false;
    }

    if(data.email.value.length > 200) {
      feedback.showError('Email is too long.', data.email);
      result = false;
    }

    if(data.message.value.length > 5000) { // a full page
      feedback.showError('Message is too long. Limited to 5000 characters.', data.message);
      result = false;
    }

    return result;
  }


  /**
   * submitForm Form submission
   * @param  object event The event object
   */
  function submitForm(event) {
    event.preventDefault();
    var output = this.querySelector('.output'), 
        feedback = hermesdev.userFeedback({
          output: output,
          successClass: userDefinedVariables.successClass,
          errorClass: userDefinedVariables.errorClass
        });

    var data = {};

    data.name = this.querySelector('#visitor-name');
    data.email = this.querySelector('#visitor-email');
    data.message = this.querySelector('#visitor-message');

    // client side form validation
    if(!isFormValid(data, feedback)) {
      return;
    }
    
    // xhr request
    var xhr = new XMLHttpRequest(),
        url = '../wp-admin/admin-ajax.php',
        params = '';

    params += 'name=' + data.name.value;
    params += '&email=' + data.email.value;
    params += '&message=' + data.message.value;
    params += '&csrf_token=' + document.getElementById('csrf_token').value;
    params += '&action=' + 'get_contact_form_data';

    xhr.open("POST", url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() { 
      if(xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(this.responseText);

        if(response.status === 'success') {
          ajax_success(response, feedback);

        } else if(response.status === 'error') {
          ajax_error(response, feedback);
          
        } else if(response.status === 'debug') { 
          console.log(response);
        }
      }
    };

    xhr.send(params);
  };

  /**
   * ajax_success response status: success
   * @param  object response the response object
   */
  function ajax_success(response, feedback) {
    feedback.clearOuput(); 
    feedback.showSuccess(response.message);
  }

  /**
   * ajax_error response status: error
   * @param  object response the response object
   */
  function ajax_error(response, feedback) {
    feedback.clearOuput();

    if(response.message.length !== 0) {
      feedback.showError(response.message);

    } else if(response.hasOwnProperty('messages') && response.messages.length > 0) {
      var errorLen = response.messages.length,
          errorMessage = '';
          
      for(var i = 0; i < errorLen; i++) {
        errorMessage += '<div class="' + userDefinedVariables.errorClass + '">' + response.messages[i] + '</div>';
      }

      feedback.showError(errorMessage);
    } else {
      console.error('Something wrong on the php side.');
    }
  }
  
})();