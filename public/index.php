<?php
require_once "../universal.php";


$router = new CPHPRouter($settings['module_dir']);
$router->allow_slash = true;
$router->ignore_query = true;

$router->routes = [[
  "^/$" => [
      'target' => "index.php",
			'_padded' => false
  ],
	"^/sign-up$" => "signup.php"
]];

try {
	$router->RouteRequest();
}
catch (RouterException $e) {
  // 404
}
