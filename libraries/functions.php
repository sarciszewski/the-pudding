<?php
function cleanOut($string, $allow_html = false) {
  if($allow_html) {
    // Pass to HTML Purifier
  }
  return htmlspecalchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}