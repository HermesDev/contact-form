<?php

$success_class = get_option(DB__SUCCESS_CLASS_OPTION);
$success_class = $success_class !== '' ? $success_class : 'success';
$error_class = get_option(DB__ERROR_CLASS_OPTION);
$error_class = $error_class !== '' ? $error_class : 'error';

?>

<script>  
  // define one global object for user defined variables
  var hermesContactForm__userDefinedVariables = {
    successClass: '<?php echo $success_class ?>',
    errorClass: '<?php echo $error_class ?>'
  };
</script>