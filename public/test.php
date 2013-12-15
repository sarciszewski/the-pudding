<?php
require_once "../universal.php";
if(!empty($_POST)) {
  if(CSRF::post()) {
    echo "VALID";
  } else {
    echo "INVALID";
  }
}
  ?>
<form method="post">
  <?php CSRF::insert(); ?>
  <button>Submit</button>
</form>