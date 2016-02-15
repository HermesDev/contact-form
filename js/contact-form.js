
(function() {
	/**
	 * isEmail validate an email address
	 * @param   string   email the email to validate
	 * @return  boolean  return true if the email is valid or false otherwise
	 */
	function isEmail(email) {
	  var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
	  return re.test(email);
	}

	var form = document.getElementById('hermes-contact-form');
	var output = form.querySelector('.output');

	/**
	 * clearOutput clear the output
	 */
	var clearOuput = function() {
		output.innerHTML = '';
	}

	/**
	 * showError show error message
	 * @param  string  message  the error message to display
	 */
	var showError = function(message, element) {
		output.innerHTML += '<div>' + message + '</div>';
	  output.style.display = 'block';

	  if(output.className.indexOf('success') != -1) { // remove success class if present
	  	output.className.replace('success', '');
	  }

	  if(output.className.indexOf('error') == -1) { // add error class if not present
	  	output.className += ' error';
	  }

	  element.setAttribute('class', 'error');
	};

	/**
	 * showSuccess show success message
	 * @param  string  message  the error message to display
	 */
	var showSuccess = function(message) {
		output.innerHTML += '<div>' + message + '</div>';
	  output.style.display = 'block';

	  if(output.className.indexOf('error') != -1) { // remove error class if present
	  	output.className.replace('error', '');
	  }

	  if(output.className.indexOf('success') == -1) { // add success class if not present
	  	output.className += ' success';
	  }

	  output.setAttribute('class', 'success');
	};

	/**
	 * isFormValid form validation
	 * @param  string  name     the name
	 * @param  string  email    the email
	 * @param  string  message 	the message
	 * @return Boolean          true if valid
	 */
	function isFormValid(name, email, message) {
		var result = true;
		clearOuput();

		if(name.value.length === 0) {
      showError('Name is required.', name);
      result = false;
    }

    if(email.value.length === 0) {
      showError('Email is required.', email);
      result = false;
    }

    if(message.value.length === 0) {
      showError('Message is required.', message);
      result = false;
    }

    if(result == false) { // validation step1 over
    	return false;
    }

    if(!isEmail(email.value)) {
    	showError('Email is invalid.', email);
      result = false;
    }

    if(result == false) { // validation step2 over
    	return false;
    }

    if(name.value.length > 200) {
      showError('Name is too long.', name);
      result = false;
    }

    if(email.value.length > 200) {
      showError('Email is too long.', email);
      result = false;
    }

    if(message.value.length > 5000) { // a full page
      showError('Message is too long. Limited to 5000 characters.', message);
      result = false;
    }

    return result;
	}

	/**
	 * onsubmit form submission
	 */
	form.onsubmit = function(event) {
		event.preventDefault();

		var name = document.getElementById('visitor-name'),
		    email = document.getElementById('visitor-email'),
        message = document.getElementById('visitor-message');

    // client side form validation
		if(!isFormValid(name, email, message)) {
			return;
		}

		// xhr request
    var xhr = new XMLHttpRequest(),
        url = '../wp-admin/admin-ajax.php',
        params = '';

    params += 'name=' + name.value;
    params += '&email=' + email.value;
    params += '&message=' + message.value;
    params += '&csrf_token=' + document.getElementById('csrf_token').value;
    params += '&action=' + 'get_contact_form_data';

		xhr.open("POST", url, true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function() { 
	    if(xhr.readyState == 4 && xhr.status == 200) {
	    	var response = this.responseText.message;
	    	clearOuput(); 

	    	var len = response.messages.length;
	    	if(this.responseText.status === 'success') {
	    		showSuccess(response.message);
	    	} else {
	    		if(!response.message == '') {
	    			showError(response.message);
	    		} else if(len > 0) {
	    			for(var i = 0; i < len; i++) {
	    				showError(response.messages[i]);
	    			}
	    		} else {
	    			console.error('Something wrong on the php side.');
	    		}
	    	}
	    }
		};

		xhr.send(params);

	};
	
})();