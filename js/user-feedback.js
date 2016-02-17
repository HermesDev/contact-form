(function(window) {

	/**
	 * UserFeedback give basic user feedbacks
	 * @param object output the ouput dom element
	 */
	var UserFeedback = function(output) {
		this.output = output;
	}


	/**
	 * clearOutput clear the output DOM
	 */
	UserFeedback.prototype.clearOuput = function() {
		this.output.innerHTML = '';
	}

	/**
	 * showError show error message
	 * @param  string  message  the error message to display
	 */
	UserFeedback.prototype.showError = function(message, element) {
		this.output.innerHTML += '<div>' + message + '</div>';
	  this.output.style.display = 'block';

	  if(this.output.className.indexOf('success') != -1) { // remove success class if present
	  	this.output.className.replace('success', '');
	  }

	  if(this.output.className.indexOf('error') == -1) { // add error class if not present
	  	this.output.className += ' error';
	  }

	  if(typeof element !== 'undefined') {
	  	element.setAttribute('class', 'error');
	  }
	};

	/**
	 * showSuccess show success message
	 * @param  string  message  the error message to display
	 */
	UserFeedback.prototype.showSuccess = function(message) {
		this.output.innerHTML += '<div>' + message + '</div>';
	  this.output.style.display = 'block';

	  if(this.output.className.indexOf('error') != -1) { // remove error class if present
	  	this.output.className.replace('error', '');
	  }

	  if(this.output.className.indexOf('success') == -1) { // add success class if not present
	  	this.output.className += ' success';
	  }

	  this.output.setAttribute('class', 'success');
	};

	if(!window.hasOwnProperty('hermesdev')) {
		var hermesdev = {};
		window.hermesdev = hermesdev;
	}

	window.hermesdev.userFeedback = function(output) { // Factory
		return new UserFeedback(output);
	};

})(window);

/*
 * Export as a CommonJS module
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UserFeedback;
}