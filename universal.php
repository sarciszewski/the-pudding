<?php
/* --------------------------------------------------------------------------- *
// In a word, if you want to add a script and not have to worry about library
\\ dependencies and whatnot, just include this script and everything will be 
// loaded: settings, DBs, functions, classes, etc.
* --------------------------------------------------------------------------- */

// PEAR: HTMLPurifier (see install.sh)
require_once "HTMLPurifier.auto.php";
$purifier = new HTMLPurifier();

// Let's load all of the libraries:
foreach(glob(__DIR__."/libraries/*.php") as $f) {
  require_once $f;
}
// Let's load twig
require_once __DIR__."/libraries/Twig/Autoloader.php";
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader, [
  // 'cache' => __DIR__.'/cache'
]);

$twig->addFilter(new Twig_SimpleFilter('cachebust', function($path) {
  if(!preg_match('/^(https?|ftp)?:\/\//', $path)) {
    if($path[0] == '/') {
      $full = $_SERVER['DOCUMENT_ROOT'].$path;
    } else {
      $full = $path;
      $path = '/'.$path;
    }
    if(file_exists($full)) {
      return $path.'?'.hash_hmac('sha1', file_get_contents($full), filemtime($full));
    }
  }
  return $path.'?'.sha1(date('Ymd').$GLOBALS['ip']);
}));

// Load all of the settings:
$settings = json_decode(file_get_contents(__DIR__.'/config/application.json'), true);

// Setup databases
$DB = new DB($settings['database']);

// Include the session fixation prevention script
require_once __DIR__."/session.php";