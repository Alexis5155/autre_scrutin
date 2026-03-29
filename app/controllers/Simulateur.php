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
                $winnerId   = $data['winner_2nd_tour']   ?? null;
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
     * Page résultat pour une ville donnée par son code INSEE.
     */
    public function ville(string $codeInsee): void {
        if (empty($codeInsee) || strlen($codeInsee) < 4) {
            die("Code INSEE invalide.");
        }

        try {
            // 1. Récupération et normalisation des données (Model)
            $donneesVille = (new VilleResultats())->fetchParCodeInsee($codeInsee);

            // 2. Calcul de la réforme (Model)
            $calcul = (new Algorithme())->calculateReformSeats(
                $donneesVille['sieges'],
                array_column($donneesVille['listes'], null, 'id'),
                $donneesVille['vainqueurId'],
                $donneesVille['perdantId'],
                $donneesVille['isPLM'],
                $donneesVille['elu1erTour']
            );

            // 3. Génération des messages d'analyse (Service)
            $messagesAnalyse = (new AnalyseService())->analyser(
                $donneesVille['listes'],
                array_values($calcul['resultats']),
                $donneesVille['sieges'],
                $donneesVille['elu1erTour']
            );

            // 4. Préparation du tableau de données pour la vue
            // Clés normalisées, stables, documentées — la vue ne normalise plus rien.
            $donneesVue = [
                'commune'          => $donneesVille['commune'],
                'code_insee'       => $donneesVille['code_insee'],
                'sieges'           => $donneesVille['sieges'],
                'isPLM'            => $donneesVille['isPLM'],
                'elu1erTour'       => $donneesVille['elu1erTour'],
                'listesInitiales'  => $donneesVille['listes'],
                'resultatsReforme' => array_values($calcul['resultats']),
                'explications'     => $calcul['explications'],
                'messagesAnalyse'  => $messagesAnalyse,   // ← généré en PHP, affiché tel quel
            ];

            // 5. Rendu de la vue
            ob_start();
            require_once __DIR__ . '/../views/simulateur_ville.php';
            $content = ob_get_clean();
            require_once __DIR__ . '/../views/layout.php';

        } catch (\RuntimeException $e) {
            die("<p class='container mt-5 alert alert-danger'><strong>Erreur :</strong> "
                . htmlspecialchars($e->getMessage()) . "</p>");
        }
    }
}