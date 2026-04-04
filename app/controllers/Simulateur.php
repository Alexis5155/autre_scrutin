<?php
namespace app\controllers;

use app\models\Algorithme;
use app\models\VilleResultats;
use app\models\AnalyseService;

class Simulateur {

    public function manuel(): void {
        ob_start();
        require_once __DIR__ . '/../views/simulateur.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }

    /**
     * Endpoint AJAX — calcul de la réforme à partir de données POST (JSON).
     */
    public function calculer(): void {
        ini_set('display_errors', 0);
        error_reporting(0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = file_get_contents('php://input');
            $data  = json_decode($input, true);

            if (empty($data)) {
                echo json_encode(['error' => 'Aucune donnée reçue.']);
                exit;
            }

            $totalSeats = (int)($data['sieges'] ?? 0);
            $lists      = $data['listes'] ?? [];
            $isPLM      = filter_var($data['isPLM'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($totalSeats === 0 || empty($lists)) {
                echo json_encode(['error' => 'Paramètres manquants (sièges ou listes).']);
                exit;
            }

            $winnerId        = null;
            $runnerUpId      = null;
            $isFirstRoundWin = false;

            foreach ($lists as $id => $list) {
                if (isset($list['score_1er_tour']) && (float)$list['score_1er_tour'] > 50.0) {
                    $winnerId        = $id;
                    $isFirstRoundWin = true;
                    break;
                }
            }

            if (!$isFirstRoundWin) {
                $winnerId   = $data['winner_2nd_tour']    ?? null;
                $runnerUpId = $data['runner_up_2nd_tour'] ?? null;
            }

            $calcul = (new Algorithme())->calculateReformSeats(
                $totalSeats, $lists, $winnerId, $runnerUpId, $isPLM, $isFirstRoundWin
            );

            echo json_encode($calcul);
            exit;

        } catch (\Exception $e) {
            echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
            exit;
        }
    }

    /**
     * Endpoint AJAX — données complètes d'une ville par code INSEE (GET ?insee=XXXXX).
     * Appelé par Vue.js au montage de la page simulateur_ville.
     */
    public function data(): void {
        ini_set('display_errors', 0);
        error_reporting(0);
        header('Content-Type: application/json; charset=utf-8');

        $codeInsee = trim($_GET['insee'] ?? '');

        if (empty($codeInsee) || strlen($codeInsee) < 4) {
            http_response_code(400);
            echo json_encode(['error' => 'Code INSEE invalide.']);
            exit;
        }

        try {
            // session_start() sécurisé : ne redémarre pas si déjà active
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $cacheKey = 'ville_' . $codeInsee;

            if (isset($_SESSION[$cacheKey])) {
                $donneesVille = $_SESSION[$cacheKey];
            } else {
                $donneesVille = (new VilleResultats())->fetchParCodeInsee($codeInsee);
                $_SESSION[$cacheKey] = $donneesVille;
            }

            $calcul = (new Algorithme())->calculateReformSeats(
                $donneesVille['sieges'],
                array_column($donneesVille['listes'], null, 'id'),
                $donneesVille['vainqueurId'],
                $donneesVille['perdantId'],
                $donneesVille['isPLM'],
                $donneesVille['elu1erTour']
            );

            $messagesAnalyse = (new AnalyseService())->analyser(
                $donneesVille['listes'],
                array_values($calcul['resultats']),
                $donneesVille['sieges'],
                $donneesVille['elu1erTour']
            );

            echo json_encode([
                'commune'          => $donneesVille['commune'],
                'code_insee'       => $donneesVille['code_insee'],
                'sieges'           => $donneesVille['sieges'],
                'isPLM'            => $donneesVille['isPLM'],
                'elu1erTour'       => $donneesVille['elu1erTour'],
                'listesInitiales'  => $donneesVille['listes'],
                'resultatsReforme' => array_values($calcul['resultats']),
                'explications'     => $calcul['explications'],
                'messagesAnalyse'  => $messagesAnalyse,
            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
            exit;

        } catch (\RuntimeException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Page squelette pour une ville — rendu immédiat, sans données.
     * Les données sont chargées côté client via fetch() vers data().
     */
    public function ville(string $codeInsee): void {
        if (empty($codeInsee) || strlen($codeInsee) < 4) {
            die("Code INSEE invalide.");
        }

        // Sans cette ligne, $codeInsee n'est pas dans la portée du require
        // et la vue reçoit une chaîne vide → fetch() part avec insee='' → rien ne s'affiche
        $codeInsee = strtoupper(trim($codeInsee)); // normalise + reste disponible dans require

        ob_start();
        require __DIR__ . '/../views/simulateur_ville.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }
}