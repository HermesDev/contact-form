
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

	  if(typeof element !== 'undefined') {
	  	element.setAttribute('class', 'error');
	  }
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
	function isFormValid(data) {
		var result = true;
		clearOuput();

		if(data.name.value.length === 0) {
      showError('Name is required.', data.name);
      result = false;
    }

    if(data.email.value.length === 0) {
      showError('Email is required.', data.email);
      result = false;
    }

    if(data.email.value.length !== 0 && !isEmail(data.email.value)) {
    	showError('Email is invalid.', data.email);
      result = false;
    }

    if(data.message.value.length === 0) {
      showError('Message is required.', data.message);
      result = false;
    }

    if(result == false) { 
    	return false;
    }
    // 2nd step

    if(data.name.value.length > 200) {
      showError('Name is too long.', data.name);
      result = false;
    }

    if(data.email.value.length > 200) {
      showError('Email is too long.', data.email);
      result = false;
    }

    if(data.message.value.length > 5000) { // a full page
      showError('Message is too long. Limited to 5000 characters.', data.message);
      result = false;
    }

    return result;
	}

	/**
	 * onsubmit form submission
	 */
	form.onsubmit = function(event) {
		event.preventDefault();

		var data = {};

		data.name = document.getElementById('visitor-name');
		data.email = document.getElementById('visitor-email');
		data.message = document.getElementById('visitor-message');

    // client side form validation
		// if(!isFormValid(data)) {
		// 	return;
		// }

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
	    	clearOuput(); 

	    	if(response.status === 'success') {
	    		showSuccess(response.message);

	    	} else if(response.status === 'error') {

	    		if(response.message.length !== 0) {
	    			showError(response.message);

	    		} else if(response.hasOwnProperty('messages') && response.messages.length > 0) {
	    			var len = response.messages.length,
	    			    errorMessage = '';
	    			    
	    			for(var i = 0; i < len; i++) {
	    				errorMessage += '<div>' + response.messages[i] + '</div>';
	    			}

	    			showError(errorMessage);
	    		} else {
	    			console.error('Something wrong on the php side.');
	    		}
	    	} else if(response.status === 'debug') { // response.status === 'debug'
	    		console.log(response);
	    	}
	    }
		};

		xhr.send(params);

	};
	
})();