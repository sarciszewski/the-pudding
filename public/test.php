<?php
require_once "/var/www/universal.php";
if(!empty($_POST)) {
  if(CSRF::post('formIDgoeshere', 'csrf')) {
    echo "VALID";
  } else {
    echo "INVALID";
  }
}
  ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?=CSRF::generate('formIDgoeshere'); ?>" />
  <button>Submit</button>
</form>