<?php
function cleanOut($string, $allow_html = false) {
  if($allow_html) {
    // Pass to HTML Purifier
  }
  return htmlspecalchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// https://defuse.ca/php-pbkdf2.htm
// https://twitter.com/DefuseSec
function slow_equals($a, $b) {
    $diff = strlen($a) ^ strlen($b);
    for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
    {
        $diff |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $diff === 0; 
}