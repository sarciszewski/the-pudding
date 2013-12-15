<?php
if(!empty($_POST)) {
  $error = [];
  if($DB->single("SELECT count(id) FROM accounts WHERE username = ?", [$_POST['username']] )) {
    $error[] = cleanOut($_POST['username'])." is already in use!";
  }
}
if(!empty($error)) {
  render('signup.twig', ['message' => $error]);
} else {
  render('signup.twig');
}
