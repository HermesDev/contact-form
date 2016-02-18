(function(window) {

	/**
	 * UserFeedback give basic user feedbacks
	 * @param object output the ouput dom element
	 */
	var UserFeedback = function(output, side) {
		this.output = output;
		this.successClassName = 'success';

		if(typeof side !== 'undefined' && side === 'admin') {
			this.successClassName = 'updated';
		} 
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
		if(message.indexOf('<div>') !== -1) {
			message += '<div>' + message + '</div>';

			if(this.output.className.indexOf('error') == -1) { // add error class if not present
		  	this.output.className += ' error';
		  }
		}

		this.output.innerHTML += '<div>' + message + '</div>';
	  this.output.style.display = 'block';

	  if(this.output.className.indexOf(this.successClassName) != -1) { // remove success class if present
	  	this.output.className.replace(this.successClassName, '');
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
		if(message.indexOf('<div>') !== -1) {
			message += '<div>' + message + '</div>';

			if(this.output.className.indexOf(this.successClassName) == -1) { // add success class if not present
		  	this.output.className += ' ' + this.successClassName;
		  }
		}

		this.output.innerHTML += '<div>' + message + '</div>';

	  this.output.style.display = 'block';

	  if(this.output.className.indexOf('error') != -1) { // remove error class if present
	  	this.output.className.replace('error', '');
	  }
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