<?php
/* --------------------------------------------------------------------------- *
// In a word, if you want to add a script and not have to worry about library
\\ dependencies and whatnot, just include this script and everything will be 
// loaded: settings, DBs, functions, classes, etc.
* --------------------------------------------------------------------------- */
// Let's load all of the libraries:
foreach(glob(__DIR__."/libraries/*") as $f) {
  require_once $f;
}

// Load all of the settings:
$settings = json_decode(file_get_contents(__DIR__.'/config/application.json'), true);

$DB = new DB($settings['database']);