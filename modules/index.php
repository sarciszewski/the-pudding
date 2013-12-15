<?php
//echo $twig->render('index.twig');
header("Content-Type: text/plain");
$i = $DB->insert('accounts', [
  'username' => 'user_'.  mt_rand(),
  'passwordhash' => Password::hash('password'),
  'personas' => '1'
]);
var_dump($i);
var_dump($settings['database']);