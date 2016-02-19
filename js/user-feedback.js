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
		console.log('error debug');
		if(message.indexOf('<div') === -1) {
			console.log('error debug2');
			message = '<div class="error">' + message + '</div>';
		}

		this.output.innerHTML += message;
	  this.output.style.display = 'block';

	  if(typeof element !== 'undefined') {
	  	element.setAttribute('class', 'error');
	  }
	};

	/**
	 * showSuccess show success message
	 * @param  string  message  the error message to display
	 */
	UserFeedback.prototype.showSuccess = function(message) {
		if(message.indexOf('<div') === -1) {
			message = '<div class="' + this.successClassName + '">' + message + '</div>';
		}

		this.output.innerHTML += message;
	  this.output.style.display = 'block';
	};

	if(!window.hasOwnProperty('hermesdev')) {
		var hermesdev = {};
		window.hermesdev = hermesdev;
	}

	window.hermesdev.userFeedback = function(output, side) { // Factory
		return new UserFeedback(output, side);
	};

})(window);

/*
 * Export as a CommonJS module
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UserFeedback;
}