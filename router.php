<?php 

$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

$routes = [
    "/thesis/" => "controllers/guest/index.php",
    "/thesis/about" => "controllers/guest/about.php",
    "/thesis/contact" => "controllers/guest/contact.php",
];

function routeToController($uri, $routes) {
    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort(404);
    }
}

routeToController($uri, $routes);