<?php
if($_SERVER['SERVER_PORT'] === 443) {
  ini_set('session.cookie_secure', true); // Just in case HSTS fails somehow
}
// Inspired by cryptolog; don't store real IP addresses anywhere if possible!
if(!file_exists('/tmp/ip_hash_key.key')) {
  file_put_contents('/tmp/ip_hash_key.key',
    substr(base64_encode(openssl_random_pseudo_bytes( 48 )), 0, 63)
  );
  if(!file_exists('/tmp/ip_hash_key.key')) {
    die("Website offline. Please make daily IP key writeable.");
  }
} elseif(time() - filemtime('/tmp/ip_hash_key.key') > 86400) {
  // This shit needs to be rotated daily
  file_put_contents('/tmp/ip_hash_key.key',
    substr(base64_encode(openssl_random_pseudo_bytes( 48 )), 0, 63)
  );
  if(time() - filemtime('/tmp/ip_hash_key.key') > 86400) {
    die("Website offline. Please make daily IP key overwriteable.");
  }
}

$ip = hash_hmac('sha256', $_SERVER['REMOTE_ADDR'],
        file_get_contents('/tmp/ip_hash_key.key'));
$newSession = !isset($_COOKIE[ini_get('session.name')]);
$sid = null;
if(!$newSession) {
  $sid = $_COOKIE[ini_get('session.name')];
}
// It's kind of important to know if it's a new session or not
/******************************************************************************/
session_start();

if($newSession || session_id() !== $sid) {
  $_SESSION['canary'] = [
    'birth' => time()
  ];
  if($settings['session']['bind_to_ip']) {
    $_SESSION['canary']['ip'] = $ip;
    // TODO: Use shm to keep the key in memory rather than on disk
  }
} else {
  if(empty($_SESSION['canary'])) {
    // Session fixation; just regenerate
    foreach(array_keys($_SESSION) as $i) {
      unset($_SESSION[$i]); // NO U
    }
    session_regenerate_id(true);
    $_SESSION['canary'] = [
      'birth' => time()
    ];
    if($settings['session']['bind_to_ip']) {
      $_SESSION['canary']['ip'] = $ip;
    }
    header("Location: /"); exit;
  } else {
    // Let's verify our canary values!
    if($settings['session']['bind_to_ip']) {
      // If the hashed IP doesn't match our records, log everyone out.
      if(!slow_equals($_SESSION['canary']['ip'], $ip)) {
        // You are the weakest link. Good-bye!
        foreach(array_keys($_SESSION) as $i) {
          unset($_SESSION[$i]); // NO U
        }
        session_regenerate_id(true);
        header("Location: /"); exit;
      } // end if(!slow_equals($_SESSION['canary']['ip'], $ip))
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
if(empty($_SESSION['csrfTokens'])) {
  $_SESSION['csrfTokens'] = [];
}