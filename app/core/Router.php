<?php
namespace app\Core;

class Router {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Aucune URL → accueil
        if (empty($url) || $url === ['']) {
            $this->controller = new \app\controllers\Home();
            return;
        }

        // 2. Vérification du contrôleur
        $nomController = ucfirst(strtolower($url[0]));
        if (file_exists(__DIR__ . '/../../app/controllers/' . $nomController . '.php')) {
            $this->controller = $nomController;
            unset($url[0]);
        } else {
            // Contrôleur inconnu → 404 immédiat
            $this->afficher404();
            return;
        }

        // 3. Instanciation du contrôleur
        $classeController = '\\app\\controllers\\' . $this->controller;
        $this->controller = new $classeController();

        // 4. Vérification de la méthode
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Méthode inconnue → 404
                $this->afficher404();
                return;
            }
        }

        // 5. Paramètres restants
        $this->params = $url ? array_values($url) : [];
    }

    public function run() {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    private function afficher404(): void {
        http_response_code(404);
        $_GET['code'] = '404';
        $this->controller = new \app\controllers\Erreur();
        $this->method     = 'index';
        $this->params     = [];
    }
}