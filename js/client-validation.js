/**
 * isEmail validate an email address
 * @param   string   email the email to validate
 * @return  boolean  return true if the email is valid or false otherwise
 */
function isEmail(email) {
  var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  return re.test(email);
}

(function() {
	var form = document.getElementById('hermes-contact-form');
	var errorOutput = form.querySelectorAll('.output.error');

	/**
	 * showError show error message
	 * @param  string  message  the error message to display
	 */
	function showError(element, message) {
		errorOutput.innerHTML = message;
	  errorOutput.style.display = 'block';
	  element.setAttribute('class', 'error');
	}

	/**
	 * isFormValid form validation
	 * @param  string  name     name
	 * @param  string  email    email
	 * @param  string  message 	message
	 * @return Boolean          true if valid
	 */
	function isFormValid(name, email, message) {
		if(name.value.length === 0) {
      showError(data.name, 'Name is required.');
      return false;
    }
    if(email.value.length === 0) {
      showError('Email is required.');
      return false;
    }
    if(message.value.length === 0) {
      showError('Message is required.');
      return false;
    }
    if(name.value.length > 200) {
      showError('Name is too long.');
      return false;
    }
    if(email.value.length > 200) {
      showError('Email is too long.');
      return false;
    }
    if(message.value.length > 5000) { // a full page
      showError('Message is too long. Limited to 5000 characters.');
      return false;
    }
    if(!isEmail(email.value)) {
    	showError('Email is invalid.');
      return false;
    }
    return true;
	}

	/**
	 * onsubmit form submission
	 */
	form.onsubmit = function() {
		var name = this.getElementById('visitor-name'),
		    email = this.getElementById('visitor-email'),
        message = this.getElementById('visitor-message'),

    // client side form validation
		if(!isFormValid(name, email, message)) {
			return;
		}

		// xhr request
    var xhr = new XMLHttpRequest(),
        url = 'admin-ajax.php',
        params = '';

    params += 'name=' + name.value;
    params += '&email=' + email.value;
    params += '&message=' + message.value;
    params += '&token=' + this.getElementById('_wpnonce').value;
    params += '&action=' + 'get_contact_form_data';

		xhr.open("POST", url, true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function() { 
	    if(xhr.readyState == 4 && xhr.status == 200) {
	    	console.log(this.responseText);
	    }
		}
		xhr.send(params);

	};
	
})();