
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
	 * showError show error message
	 * @param  string  message  the error message to display
	 */
	var showError = function(message, element) {
		output.innerHTML = message;
	  output.style.display = 'block';
	  output.className += ' error';
	  element.setAttribute('class', 'error');
	};

	/**
	 * showError show error message
	 * @param  string  message  the error message to display
	 */
	var showSuccess = function(message) {
		output.innerHTML = message;
	  output.style.display = 'block';
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
		if(name.value.length === 0) {
      showError('Name is required.', name);
      return false;
    }
    if(email.value.length === 0) {
      showError('Email is required.', email);
      return false;
    }
    if(message.value.length === 0) {
      showError('Message is required.', message);
      return false;
    }
    if(!isEmail(email.value)) {
    	showError('Email is invalid.', email);
      return false;
    }
    if(name.value.length > 200) {
      showError('Name is too long.', name);
      return false;
    }
    if(email.value.length > 200) {
      showError('Email is too long.', email);
      return false;
    }
    if(message.value.length > 5000) { // a full page
      showError('Message is too long. Limited to 5000 characters.', message);
      return false;
    }
    return true;
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
	    	console.log(this.responseText);
	    	showSuccess(this.responseText);
	    }
		};
		xhr.send(params);

	};
	
})();