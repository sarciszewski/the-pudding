<?php
/* OUTPUT CLEANING */
function cleanOut($string, $allow_html = false) {
  if($allow_html) {
    // Pass to HTML Purifier
    if(empty($GLOBALS['purifier']) || !($GLOBALS['purifier'] instanceof HTMLPurifier )) {
      require_once "HTMLPurifier.auto.php";
      $GLOBALS['purifier'] = new HTMLPurifier();
    }
    return $GLOBALS['purifier']->purify($string);
  }
  // No HTML allowed:
  return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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

function forceASCII($in, $allow_linebreaks=false) {
  // Remove non-ASCII characters
  if($allow_linebreaks) {
    $a = explode("\n", $in);
    foreach($a as $i => $n) {
      $a[$i] = forceASCII($n);
    }
    return trim(implode("\n", $a));
  } else {
    // Return all printable ASCII characters 32 to 126
    // Not compatible with other languages (Chinese, Russian, etc.)
    return preg_replace('/([^\x20-\x7e]+)/', '', $in);
  }
}