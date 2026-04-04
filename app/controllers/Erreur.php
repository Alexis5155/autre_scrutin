<?php
namespace app\controllers;

class Erreur {

    public function index(): void {
        $code    = (int)($_GET['code'] ?? 404);
        $message = $_GET['msg'] ?? null;

        http_response_code($code);

        $configs = [
            400 => ['icon' => 'bi-slash-circle',        'titre' => 'Requête invalide',         'couleur' => 'warning'],
            404 => ['icon' => 'bi-geo-alt',              'titre' => 'Page introuvable',      'couleur' => 'primary'],
            500 => ['icon' => 'bi-exclamation-triangle', 'titre' => 'Erreur serveur',           'couleur' => 'danger'],
        ];

        $config = $configs[$code] ?? ['icon' => 'bi-question-circle', 'titre' => 'Erreur inattendue', 'couleur' => 'secondary'];

        $messages_defaut = [
            400 => 'Le code INSEE fourni est invalide ou mal formé.',
            404 => 'La page demandée n\'existe pas ou a été supprimée.',
            500 => 'Une erreur interne s\'est produite. Veuillez réessayer.',
        ];

        $message = $message ?: ($messages_defaut[$code] ?? 'Une erreur s\'est produite.');

        ob_start();
        require_once __DIR__ . '/../views/erreur.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
}