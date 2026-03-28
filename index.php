<?php
// /index.php (à la racine)
session_start();
ini_set('display_errors', 1);

// Définition de l'URL de base dynamique (totalement portable)
// $_SERVER['SCRIPT_NAME'] vaut par ex: /municipales/index.php
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
// Si on est à la racine absolue du domaine, dirname renvoie '\' ou '/'
$baseDir = ($baseDir === '\\' || $baseDir === '/') ? '' : $baseDir;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];

define('BASE_URL', $protocol . $domainName . $baseDir . '/');

// Autoloader ajusté (index.php et app/ sont maintenant au même niveau)
spl_autoload_register(function ($class) {
    // Remplacement des backslashs des namespaces en slashs de répertoires
    $path = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Lancement du Routeur
$router = new \app\core\Router();
$router->run();