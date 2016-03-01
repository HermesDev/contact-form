<?php

$success_class = get_option(DB__SUCCESS_CLASS_OPTION);
$success_class = $success_class !== '' ? $success_class : 'success';
$error_class = get_option(DB__ERROR_CLASS_OPTION);
$error_class = $error_class !== '' ? $error_class : 'error';

?>

<script> 
(function(window) { 
  var userDefinedVariables = {
    successClass: '<?php echo $success_class ?>',
    errorClass: '<?php echo $error_class ?>'
  };

  // namespaces
  if(!window.hasOwnProperty('hermesdev')) {
    window.hermesdev = {};
  }

  if(!window.hermesdev.hasOwnProperty('contactForm')) {
    window.hermesdev.contactForm = {};
  }

  window.hermesdev.contactForm.userDefinedVariables = userDefinedVariables;

})(window);
</script>