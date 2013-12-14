<?php
header("Content-Type: text/plain;charset=UTF-8");
include_once "../libraries/scrypt.php";

$p = Password::hash('dsfargeg');
echo "{$p}\n";
echo "Length: ".strlen($p)."\n";

if(Password::check('dsfargeg', $p)) {
  echo 'scrypt works!';
}