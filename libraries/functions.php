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

// https://github.com/joepie91/cphp/blob/feature/orm-uuid/include.misc.php
// https://twitter.com/joepie91
// http://cryto.net
function extract_globals()
{
    $vars = array();
    
    foreach($GLOBALS as $key => $value){
        $vars[] = "$".$key;
    }
    
    return "global " . join(",", $vars) . ";";
}