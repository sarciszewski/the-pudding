<?php
if(!empty($_POST)) {
  $error = [];
  if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2'])) {
    $error[] = "Please fill out the form completely";   
  } elseif($_POST['password'] !== $_POST['password2']) {
    $error[] = "The passwords you entered did not match.";
  } elseif($DB->single("SELECT count(id) FROM accounts WHERE username = ?", [$_POST['username']] )) {
    $error[] = cleanOut($_POST['username'])." is already in use!";
  }
  if(strlen($_POST['password']) < 16 || strlen($_POST['password']) > 32768) {
    $error[] = "Your password must be a reasonable length. (At least 16 characters!)";
  }
  if(count($error) < 1) {
    if($DB->insert("accounts", ['username' => $_POST['username'], 'passwordhash' => Password::hash($_POST['password'])])) {
    header("Location: /");
    exit;
    } else {
      var_dump($_POST);
    }
  }
}
if(!empty($error)) {
  render('signup.twig', ['message' => $error]);
} else {
  render('signup.twig');
}
