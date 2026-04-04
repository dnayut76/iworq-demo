<?php
// Simple PHP Router

// Set up some global variables
$webroot = $_SERVER['DOCUMENT_ROOT'];
$phproot = realpath($webroot."/../php");

// Include composer autoloader for 3rd party libraries
require $phproot."/vendor/autoload.php";

// Capture the current URL path
$request = trim( strtolower( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH )));

// Default route
if (empty($request) || $request == "/") $request = "register_form1";

// Define your routes mapping: 'URL' => 'file'
$routes = [
    'register_form1' => 'components/register_form1.php',
];

// Load the page
if (
     // Route exists ?
     array_key_exists($request, $routes)
     &&
     // File exists on the server
     file_exists($webroot."/".$routes[$request])
    ) {

    // Load and parse PHP file
    require $routes[$request];

// Otherwise, return an error to the browser
} else {

    // Handle 404 - Not Found
    http_response_code(404);
    echo "<h1>404 Page Not Found</h1>";

}

?>

