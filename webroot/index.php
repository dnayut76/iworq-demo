<?php

// ─── Bootstrap ───────────────────────────────────────────────────────────────

$approot = dirname($_SERVER['DOCUMENT_ROOT']);
$phproot = realpath($approot . "/php");

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
    'register'        => $approot.'/pages/register_form.php',
    'register2'       => $approot.'/pages/register_form2.php',
    'register_submit' => $approot.'/pages/register_form_submit.php',
    'login'           => $approot.'/pages/login_form.php',
];

// Dev-only routes — expose diagnostics tools locally
$devRoutes = [
    'phpinfo'      => $phproot.'/tools/phpinfo.php',
    'print_config' => $phproot.'/tools/print_config.php',
    'gen_pepper'   => $phproot.'/tools/gen_pepper.php',
    'passwd_test'  => $phproot.'/tools/passwd_test.php',
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

