<?php
namespace app\Core;

class Router {
    // On définit le contrôleur et la méthode par défaut
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // 1. On récupère et on découpe l'URL
        $url = $this->parseUrl();

        // 2. On vérifie si le contrôleur demandé existe dans le dossier Controllers
        if (isset($url[0])) {
            $nomController = ucfirst(strtolower($url[0]));
            if (file_exists(__DIR__ . '/../../app/controllers/' . $nomController . '.php')) {
                $this->controller = $nomController;
            }
            unset($url[0]); // On retire le contrôleur du tableau de l'URL
        }

        // 3. On instancie le contrôleur
        $classeController = '\\app\\controllers\\' . $this->controller;
        $this->controller = new $classeController();

                // 4. On vérifie si une méthode est demandée dans l'URL
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
            }
            unset($url[1]);
        }

        // 5. Les éléments restants deviennent les paramètres (ex: $url[2] devient l'ID de la ville)
        $this->params = $url ? array_values($url) : [];
    }

    public function run() {
        // On exécute la méthode du contrôleur en lui passant les paramètres éventuels
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        // Si une URL est passée, on la nettoie et on la découpe par les "/"
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}