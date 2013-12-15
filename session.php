<?php
if($_SERVER['SERVER_PORT'] === 443) {
  ini_set('session.cookie_secure', true); // Just in case HSTS fails somehow
}
$newSession = false;
if(isset($_COOKIE[ini_get('session.name')])) {
  $newSession = true;
}
session_start();
if($newSession) {
  $_SESSION['canary'] = [
    'birth' => time()
  ];
  if($settings['session']['bind_to_ip']) {
    // Cryptolog
    if(!file_exists('/tmp/ip_hash_key.key')) {
      file_put_contents('/tmp/ip_hash_key.key',
        substr(base64_encode(openssl_random_pseudo_bytes( 48 )), 0, 63)
      );
    }
    $_SESSION['canary']['ip'] = hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], 
      file_get_contents('/tmp/ip_hash_key.key'));
    // TODO: Use shm to keep the key in memory rather than on disk
  }
} else {
  if(empty($_SESSION['canary'])) {
    // Session fixation; just regenerate
    foreach(array_keys($_SESSION) as $i) {
      unset($_SESSION[$i]); // NO U
    }
    session_regenerate_id(true);
    header("Location: /"); exit;
  } else {
    // Let's verify our canary values!
    if($settings['session']['bind_to_ip']) {
      // If the hashed IP doesn't match our records, log everyone out.
      if(!slow_equals(
        $_SESSION['canary']['ip'],
        hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], file_get_contents('/tmp/ip_hash_key.key')))
      ) {
        foreach(array_keys($_SESSION) as $i) {
          unset($_SESSION[$i]); // NO U
        }
        session_regenerate_id(true);
        header("Location: /"); exit;
      } // end if(!slow_equals())
    } // <- end if($settings['session']['bind_to_ip'])
    if($settings['session']['regen_time'] > 0) {
      // Let's rotate our session ID every ___ seconds
      if(time() - $_SESSION['canary']['birth'] >= $settings['session']['regen_time']) {
        $_SESSION['canary']['birth'] = time();
        session_regenerate_id(true);
      }
    } // <- end if($settings['session']['regen_time'])
  } // <- end else for if(empty($_SESSION['canary']))
} // end else for if($newSession)