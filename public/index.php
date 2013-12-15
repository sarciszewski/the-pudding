<?php
require_once "../universal.php";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(!CSRF::post()) {
    // CSRF detected
    header("Location: /"); exit;
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
	"^/sign-up$" => "signup.php",
	"^/about" => "about.php"
]];

try {
	$router->RouteRequest();
}
catch (RouterException $e) {
  // 404
  var_dump($e);
}
