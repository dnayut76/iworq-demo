<?php

// ─── Bootstrap ───────────────────────────────────────────────────────────────

$webroot = $_SERVER['DOCUMENT_ROOT'];
$phproot = realpath($webroot . "/../php");

require $phproot . "/vendor/autoload.php";

$config = new App\Config();

// Dev helpers (never loaded in production)
if ($config->get('development')) {
    require $phproot . '/dev.php';
}

// ─── Request ─────────────────────────────────────────────────────────────────

// Strip leading slash and normalise to lowercase
$request = ltrim(strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), "/");

if (empty($request)) {
    $request = "register";
}

// ─── Routes ──────────────────────────────────────────────────────────────────

$routes = [
    'register'        => 'components/register_form.php',
    'register2'       => 'components/register_form2.php',
    'register_submit' => 'components/register_form_submit.php',
    'login'           => 'components/login_form.php',
];

// Dev-only routes — expose diagnostics tools locally
$devRoutes = [
    'phpinfo'      => '../php/tools/phpinfo.php',
    'print_config' => '../php/tools/print_config.php',
    'gen_pepper'   => '../php/tools/gen_pepper.php',
    'passwd_test'  => '../php/tools/passwd_test.php',
];

if ($config->get('development')) {
    $routes = array_merge($routes, $devRoutes);
}

// ─── Dispatch ─────────────────────────────────────────────────────────────────

$routeFile = $routes[$request] ?? null;

if ($routeFile && file_exists($webroot . "/" . $routeFile)) {
    require $routeFile;
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}

