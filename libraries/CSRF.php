<?php
/* 
 * to verify:
 * if(CSRF::verify('unique_form_id', $_POST['csrf']))
 * or
 * if(CSRF::post('unique_form_id', 'csrf'))
 * or
 * if(CSRF::get('unique_form_id', 'csrf'))
 * 
 * to generate:
 * echo CSRF::generate("unique_form_id");
 */
abstract class CSRF {
  public function verify($index, $hash) {
    if(empty($_SESSION['csrfTokens'][$index])) {
      // They never loaded the page before!
      return slow_equals('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaab',
        hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], 
          'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa') );
        // Constant time failure
    } else {
      return slow_equals($hash, hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], 
        $_SESSION['csrfTokens'][$index]) );
      // Constant time failure
    }
  }
  
  public function post($post_index, $session_index) {
    if(empty($_POST[$post_index]) || is_array($_POST[$post_index])) {
      $_POST[$post_index] = bin2hex(mcrypt_create_iv(32)); // Random garbage
    }
    return self::verify($session_index, $_POST[$post_index]);
  }
  
  public function get($post_index, $session_index) {
    if(empty($_GET[$post_index]) || is_array($_GET[$post_index])) {
      $_GET[$post_index] = bin2hex(mcrypt_create_iv(32)); // Random garbage
    }
    return self::verify($session_index, $_GET[$post_index]);
  }
  
  public function generate($index) {
    $strong = false;
    $_SESSION['csrfTokens'][$index] = openssl_random_pseudo_bytes(32, $strong);
    if(!$strong) {
      // FUCK! Let's get inventive then...
      $fp = fopen("/dev/urandom", "rb");
      $_SESSION['csrfTokens'][$index] = hash_hmac('sha256',
           fread($fp, 32), // 
           microtime(true).$_SERVER['REMOTE_ADDR'].mt_rand(0, PHP_INT_MAX),
           true); // Raw binary
      fclose($fp);
    }
    return hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], $_SESSION['csrfTokens'][$index]);
  }
}