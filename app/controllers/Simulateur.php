<?php
namespace app\controllers;
use app\models\Algorithme;

class Simulateur {
    
    // Affiche la page du simulateur (l'interface)
    public function manuel() {
        ob_start();
        require_once __DIR__ . '/../views/simulateur.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }

    // Méthode appelée en AJAX pour faire le calcul
    public function calculer() {
        // IMPORTANT : Désactiver l'affichage HTML des erreurs PHP pour ne pas casser le JSON
        ini_set('display_errors', 0);
        error_reporting(0);
        
        header('Content-Type: application/json; charset=utf-8');

        try {
            // On récupère les données envoyées en POST (depuis le JS)
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (!$data) {
                echo json_encode(['error' => 'Aucune donnée reçue', 'raw' => $input]);
                exit;
            }

            $totalSeats = (int)($data['sieges'] ?? 0);
            $lists = $data['listes'] ?? [];
            $isPLM = isset($data['isPLM']) ? filter_var($data['isPLM'], FILTER_VALIDATE_BOOLEAN) : false;
            
            // Validation basique
            if ($totalSeats === 0 || empty($lists)) {
                echo json_encode(['error' => 'Paramètres manquants (sièges ou listes)']);
                exit;
            }

            $winnerId = null;
            $runnerUpId = null;
            $isFirstRoundWin = false;

            // On cherche le vainqueur du 1er tour (s'il y en a un à > 50%)
            foreach($lists as $id => $list) {
                if(isset($list['score_1er_tour']) && (float)$list['score_1er_tour'] > 50.0) {
                    $winnerId = $id;
                    $isFirstRoundWin = true;
                    break;
                }
            }

            // S'il n'y a pas de victoire au 1er tour, on prend les infos du 2nd tour fournies par le JS
            if (!$isFirstRoundWin) {
                $winnerId = $data['winner_2nd_tour'] ?? null;
                $runnerUpId = $data['runner_up_2nd_tour'] ?? null;
            }

            $algorithme = new Algorithme();
            $calcul = $algorithme->calculateReformSeats($totalSeats, $lists, $winnerId, $runnerUpId, $isPLM, $isFirstRoundWin);

            // On renvoie le résultat au Javascript en JSON propre
            echo json_encode($calcul);
            exit;

        } catch (\Exception $e) {
            echo json_encode(['error' => 'Erreur serveur PHP: ' . $e->getMessage()]);
            exit;
        }
    }

    /**
     * Effectue une requête HTTP GET robuste avec cURL vers Data.gouv.fr
     */
    private function fetchApi($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SimulateurReformeMunicipale/1.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return false;
        }

        return $response;
    }

    public function ville($codeInsee) {
        if (empty($codeInsee) || strlen($codeInsee) < 4) {
            die("Code INSEE invalide.");
        }

        // On s'assure que le code INSEE fait 5 caractères (ex: 01004)
        $codeInsee = str_pad($codeInsee, 5, "0", STR_PAD_LEFT);

        $rid_1er_tour = "4feeef01-24f7-4d5a-914f-8aa806f31ec2";
        $rid_2nd_tour = "6ff67a28-01bf-459e-beca-dd7aa8132dc1";
        $urlApi = "https://tabular-api.data.gouv.fr/api/resources/";

        // 1. Récupération du 1er Tour avec la syntaxe exacte de l'API tabulaire
        // L'espace dans le nom de la colonne doit être encodé en %20
        $url1erTour = $urlApi . $rid_1er_tour . "/data/?Code%20commune__exact=" . urlencode($codeInsee);
        $json_t1 = $this->fetchApi($url1erTour);
        
        if (!$json_t1) {
            die("Erreur de connexion à l'API Data.gouv.fr pour le 1er tour.");
        }
        
        $dataApi_t1 = json_decode($json_t1, true);
        
        if (empty($dataApi_t1['data'])) {
            die("Les résultats du 1er tour pour cette commune ($codeInsee) sont introuvables ou il s'agit d'une commune de moins de 1000 habitants (scrutin non proportionnel).");
        }

        // Il n'y a qu'une seule ligne retournée par l'API pour un code INSEE exact
        $rowT1 = $dataApi_t1['data'][0];

        // 2. Extraction des infos générales
        $nomCommune = $rowT1['Libellé commune'] ?? 'Commune Inconnue';
        $totalExprimesT1 = (int)($rowT1['Exprimés'] ?? 0);

        if ($totalExprimesT1 === 0) die("Aucun suffrage exprimé trouvé pour cette commune.");

        $elu1erTour = true;
        $totalSieges = 0;
        $listes = [];

        // Récupération des Têtes de liste depuis la 2ème API
        // On filtre par Code Insee (Code circonscription) et on ne prend que la tête de liste
        $urlCandidats = "https://tabular-api.data.gouv.fr/api/resources/b929c2a4-18ec-4e8b-bc37-2ff346a867cd/data/?Code%20circonscription__exact=" . urlencode($codeInsee) . "&T%C3%AAte%20de%20liste__exact=true";
        
        $chCand = curl_init();
        curl_setopt($chCand, CURLOPT_URL, $urlCandidats);
        curl_setopt($chCand, CURLOPT_RETURNTRANSFER, 1);
        // Ajout d'un User-Agent si Data.gouv l'exige
        curl_setopt($chCand, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']); 
        $responseCand = curl_exec($chCand);
        curl_close($chCand);

        $tetesDeListe = [];
        if ($responseCand) {
            $dataCand = json_decode($responseCand, true);
            if (isset($dataCand['data'])) {
                foreach ($dataCand['data'] as $cand) {
                    $numPanneau = $cand['Numéro de panneau'] ?? null;
                    if ($numPanneau) {
                        // Concaténation Prénom + Nom
                        $nomComplet = trim(($cand['Prénom sur le bulletin de vote'] ?? '') . ' ' . ($cand['Nom sur le bulletin de vote'] ?? ''));
                        $tetesDeListe[$numPanneau] = $nomComplet;
                    }
                }
            }
        }

        // 3. Extraction dynamique des listes du 1er tour
        for ($i = 1; $i <= 15; $i++) {
            $colNom = "Libellé de liste " . $i;
            $colNumPanneau = "Numéro de panneau " . $i; // <-- Nouvel identifiant de liaison
            $colNuance = "Nuance liste " . $i;
            $colVoix = "Voix " . $i;
            $colSieges = "Sièges au CM " . $i;

            if (!isset($rowT1[$colVoix]) || empty(trim($rowT1[$colVoix] ?? ''))) {
                break;
            }

            // Croisement des données : on cherche le nom du candidat via le numéro de panneau
            $numPanneau = $rowT1[$colNumPanneau] ?? null;
            $teteListe = ($numPanneau && isset($tetesDeListe[$numPanneau])) ? $tetesDeListe[$numPanneau] : "Candidat non renseigné";

            // Si le libellé de la liste est vide, on utilise le nom du candidat, sinon "Liste X"
            $nomListe = !empty($rowT1[$colNom]) ? trim($rowT1[$colNom]) : ($teteListe !== "Candidat non renseigné" ? $teteListe : "Liste $i");
            $nuance = trim($rowT1[$colNuance] ?? "");
            $voix = (int)$rowT1[$colVoix];
            $sieges = (int)($rowT1[$colSieges] ?? 0);
            
            $score = ($voix / $totalExprimesT1) * 100;
            $totalSieges += $sieges;

            $listes['L'.$i] = [
                'id' => 'L'.$i,
                'nom' => $nomListe,
                'candidat' => $teteListe, // <-- La valeur est désormais récupérée de la 2ème API
                'nuance' => $nuance,
                'score_1er_tour' => round($score, 2),
                'voix' => $voix,
                'sieges_reel' => $sieges
            ];
        }

        $vainqueurReel2ndTourId = null;
        $perdantReel2ndTourId = null;

        // 4. Gestion du 2nd Tour
        if ($totalSieges === 0) {
            $elu1erTour = false;
            
            $url2ndTour = $urlApi . $rid_2nd_tour . "/data/?Code%20commune__exact=" . urlencode($codeInsee);
            $json_t2 = $this->fetchApi($url2ndTour);
            $dataApi_t2 = $json_t2 ? json_decode($json_t2, true) : ['data' => []];

            if (!empty($dataApi_t2['data'])) {
                $rowT2 = $dataApi_t2['data'][0];
                $scoresT2 = [];

                for ($i = 1; $i <= 15; $i++) {
                    $colNom = "Libellé de liste " . $i;
                    $colCandidat = "Nom candidat " . $i;
                    $colVoix = "Voix " . $i;
                    $colSieges = "Sièges au CM " . $i;

                    if (!isset($rowT2[$colVoix]) || empty(trim($rowT2[$colVoix] ?? ''))) break;

                    $nomListeT2 = !empty($rowT2[$colNom]) ? trim($rowT2[$colNom]) : trim($rowT2[$colCandidat] ?? "Liste $i");
                    $voixT2 = (int)$rowT2[$colVoix];
                    $siegesT2 = (int)($rowT2[$colSieges] ?? 0);
                    $totalSieges += $siegesT2;

                    $scoresT2[] = ['nom' => $nomListeT2, 'voix' => $voixT2, 'sieges' => $siegesT2];
                }

                usort($scoresT2, function($a, $b) { return $b['voix'] <=> $a['voix']; });

                // Mise à jour des sièges réels pour le tableau comparatif
                foreach ($scoresT2 as $resT2) {
                    $found = false;
                    foreach ($listes as &$listeInitiale) {
                        if (strcasecmp(trim($listeInitiale['nom']), trim($resT2['nom'])) === 0 || 
                            stripos(trim($resT2['nom']), trim($listeInitiale['nom'])) !== false) {
                            $listeInitiale['sieges_reel'] = $resT2['sieges'];
                            $found = true;
                        }
                    }
                    unset($listeInitiale);

                    if (!$found) {
                        $newId = 'L' . (count($listes) + 1);
                        $listes[$newId] = [
                            'id' => $newId,
                            'nom' => $resT2['nom'] . ' (Fusion)',
                            'score_1er_tour' => 0,
                            'voix' => 0,
                            'sieges_reel' => $resT2['sieges']
                        ];
                    }
                }

                if (count($scoresT2) > 0) {
                    $vainqueurReel2ndTourId = $this->findListIdByName($listes, $scoresT2[0]['nom']);
                    if (isset($scoresT2[1])) {
                        $perdantReel2ndTourId = $this->findListIdByName($listes, $scoresT2[1]['nom']);
                    }
                }
            } else {
                die("Résultats du 2nd tour introuvables pour cette commune.");
            }
        } else {
            foreach($listes as $id => $liste) {
                if ($liste['score_1er_tour'] > 50) {
                    $vainqueurReel2ndTourId = $id;
                    break;
                }
            }
        }

        if ($totalSieges === 0) {
            die("Impossible de déterminer le nombre de sièges du conseil municipal. (Score: $totalSieges)");
        }

        // 5. Calcul avec votre Algorithme
        $algorithme = new Algorithme();
        
        $isPLM = in_array($codeInsee, ['75056', '69123', '13055']);
        
        $calculReforme = $algorithme->calculateReformSeats(
            $totalSieges, $listes, $vainqueurReel2ndTourId, $perdantReel2ndTourId, $isPLM, $elu1erTour
        );

        $donneesVue = [
            'commune' => $nomCommune,
            'code_insee' => $codeInsee,
            'sieges' => $totalSieges,
            'isPLM' => $isPLM,
            'listesInitiales' => array_values($listes),
            'resultatsReforme' => array_values($calculReforme['resultats']), // On extrait les résultats
            'explications' => $calculReforme['explications'] // On passe le journal à la vue
        ];


        ob_start();
        require_once __DIR__ . '/../views/simulateur_ville.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }

    private function findListIdByName($listes, $nomRecherche) {
        foreach($listes as $id => $liste) {
            if (strcasecmp(trim($liste['nom']), trim($nomRecherche)) === 0 || 
                stripos(trim($nomRecherche), trim($liste['nom'])) !== false) {
                return $id;
            }
        }
        return null;
    }
}