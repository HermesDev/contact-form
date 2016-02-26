(function(window) {

  /**
   * UserFeedback give basic user feedbacks
   * @param object inputs The inputs settings
   * {
   *  output: output,                  The ouput where to display error|success
   *  successClass: 'my_sucess_class'  The success class to add
   *  errorClass: 'my_error_class',    The error class to add
   * }
   */
  var UserFeedback = function(inputs) {
    if(!inputs.hasOwnProperty('output')) {
      console.error('inputs object requires the output attribute');
    }

    this.output = inputs.output;
    this.successClassName = typeof inputs.successClass === 'undefined' ? 'success' : inputs.successClass;
    this.errorClassName = typeof inputs.errorClass === 'undefined' ? 'error' : inputs.errorClass;

    // if(typeof side !== 'undefined' && side === 'admin') {
    //   this.successClassName = 'updated';
    // } 
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
    if(message.indexOf('<div') === -1) {
      message = '<div class="' + this.errorClassName + '">' + message + '</div>';
    }

    this.output.innerHTML += message;
    this.output.style.display = 'block';

    if(typeof element !== 'undefined') {
      element.setAttribute('class', this.errorClassName);
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