<?php
namespace app\Controllers;

class Home {
    public function index() {
        // Chemin absolu vers le dossier Views basé sur la position de ce contrôleur
        $viewPath = __DIR__ . '/../views/';

        // On vérifie que les fichiers existent bien avant de les inclure
        if (!file_exists($viewPath . 'home.php') || !file_exists($viewPath . 'layout.php')) {
            die("Erreur fatale : Fichiers de vue introuvables dans " . $viewPath);
        }

        // On capture le contenu de la vue "home.php"
        ob_start();
        require_once $viewPath . 'home.php';
        $content = ob_get_clean();

        // On injecte ce contenu dans le layout global
        require_once $viewPath . 'layout.php';
    }
}