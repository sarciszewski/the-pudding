<?php
require_once "../universal.php";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(!CSRF::post()) {
    $_POST = []; // Empty array to you!
  }
}

$router = new CPHPRouter($settings['module_dir']);
$router->allow_slash = true;
$router->ignore_query = true;

$router->routes = [[
  "^/$" => [
      'target' => "index.php",
			'_padded' => false
  ],
	//"^/sign-up$" => "signup.php",
  "^/sign-up" => [
    'target' => "error/guest.php",
    'authenticator' => "authenticators/user.php",
    'auth_error' => "signup.php"
  ],
	"^/about" => "about.php"
]];

try {
	$router->RouteRequest();
}
catch (RouterException $e) {
  // 404
  var_dump($e);
}
