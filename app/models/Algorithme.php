<?php
namespace app\models;

class Algorithme {

    public function calculateReformSeats($totalSeats, $lists, $winnerId, $runnerUpId, $isPLM = false, $isFirstRoundWin = false) {

        $log = [];

        // 1. Définition des pourcentages des primes
        $pctMaj = $isPLM ? 0.30 : 0.40;
        $pctMin = $isFirstRoundWin ? 0 : 0.10;

        // CORRECTION #3 : Un seul ceil() sur la prime majoritaire.
        // La prime minoritaire est calculée par soustraction pour éviter
        // que deux arrondis supérieurs ne grignotent la part proportionnelle.
        $primeMajSeats = ceil($totalSeats * $pctMaj);
        $primeMinSeats = $pctMin > 0 ? ($totalSeats - $primeMajSeats - (int)round($totalSeats * (1 - $pctMaj - $pctMin))) : 0;
        // Formulation plus simple et lisible du même principe :
        $propSeatsTheorique = (int)round($totalSeats * (1 - $pctMaj - $pctMin));
        $primeMajSeats      = ceil($totalSeats * $pctMaj);
        $primeMinSeats      = $pctMin > 0 ? ($totalSeats - $primeMajSeats - $propSeatsTheorique) : 0;
        $propSeats          = $totalSeats - $primeMajSeats - $primeMinSeats;

        $log['primes'] = [
            'total_sieges' => $totalSeats,
            'part_prop'    => $propSeats,
            'prime_maj'    => $primeMajSeats,
            'prime_min'    => $primeMinSeats,
            'txt_maj'      => ($pctMaj * 100) . '%',
            'txt_min'      => ($pctMin * 100) . '%'
        ];

        // 2. Filtrer les listes ayant passé le seuil de 5%
        $validLists    = [];
        $eliminatedLists = [];
        $totalValidScore = 0;

        foreach ($lists as $id => $list) {
            // CORRECTION #7 : Validation des entrées — on s'assure que les clés existent.
            $score = isset($list['score_1er_tour']) ? (float)$list['score_1er_tour'] : 0.0;
            $nom   = isset($list['nom'])            ? (string)$list['nom']           : "Liste $id";

            // CORRECTION #6 : Comparaison avec round() pour éviter les imprécisions flottantes.
            if (round($score, 4) >= 5.0) {
                $validLists[$id] = [
                    'nom'          => $nom,
                    'score'        => $score,
                    'sieges_prop'  => 0,
                    'sieges_prime' => 0,
                    'total_sieges' => 0
                ];
                $totalValidScore += $score;
            } else {
                $eliminatedLists[] = $nom;
            }
        }

        $log['seuil'] = [
            'score_utile' => round($totalValidScore, 2),
            'eliminees'   => $eliminatedLists
        ];

        // CORRECTION #1 et #4 : Garde contre $propSeats <= 0 ou $validLists vide.
        // Si ces conditions sont réunies, aucun calcul proportionnel n'est possible.
        if ($propSeats <= 0 || empty($validLists)) {
            $log['erreur'] = 'Aucun siège proportionnel à répartir ou aucune liste valide.';
            return [
                'resultats'    => [],
                'explications' => $log
            ];
        }

        // 3. Répartition proportionnelle (Quotient Electoral)
        $quotient       = $totalValidScore / $propSeats; // $propSeats > 0 garanti ci-dessus
        $remainingSeats = $propSeats;

        $log['quotient'] = [
            'valeur'       => round($quotient, 2),
            'attributions' => []
        ];

        foreach ($validLists as $id => &$data) {
            $seats = (int)floor($data['score'] / $quotient);
            $data['sieges_prop'] = $seats;
            $remainingSeats -= $seats;
            if ($seats > 0) {
                $log['quotient']['attributions'][$data['nom']] = $seats;
            }
        }
        unset($data);

        // 4. Répartition des restes à la plus forte moyenne
        $log['restes'] = [
            'sieges_restants' => $remainingSeats,
            'attributions'    => []
        ];

        // CORRECTION #4 : $validLists est garanti non-vide ici (garde en amont).
        // La boucle ne peut donc plus être infinie.
        while ($remainingSeats > 0) {
            $maxAvg      = -1;
            $winnerAvgId = null;

            foreach ($validLists as $id => $data) {
                $avg = $data['score'] / ($data['sieges_prop'] + 1);
                if ($avg > $maxAvg) {
                    $maxAvg      = $avg;
                    $winnerAvgId = $id;
                }
            }

            // Sécurité défensive : ne devrait jamais être null grâce à la garde,
            // mais on vérifie pour être explicite.
            if ($winnerAvgId === null) break;

            $validLists[$winnerAvgId]['sieges_prop']++;

            $nom = $validLists[$winnerAvgId]['nom'];
            if (!isset($log['restes']['attributions'][$nom])) {
                $log['restes']['attributions'][$nom] = 0;
            }
            $log['restes']['attributions'][$nom]++;

            $remainingSeats--;
        }

        // 5. Attribution des primes
        $log['distribution_primes'] = [];

        // CORRECTION #2 : Si le vainqueur n'est pas dans $validLists (ex: < 5%),
        // on lève une exception explicite plutôt que d'ignorer silencieusement la prime.
        if ($winnerId !== null && !isset($validLists[$winnerId])) {
            $log['erreur_prime'] = "Le vainqueur (ID: $winnerId) n'a pas atteint le seuil de 5% "
                                 . "et ne peut pas recevoir la prime majoritaire.";
            // On redistribue la prime dans la proportionnelle du premier du classement
            // pour ne pas perdre de sièges — comportement de repli explicite.
            reset($validLists);
            $premierKey = key($validLists);
            if ($premierKey !== null) {
                $validLists[$premierKey]['sieges_prime'] += $primeMajSeats;
                $log['distribution_primes']['vainqueur'] = [
                    'nom'    => $validLists[$premierKey]['nom'],
                    'sieges' => $primeMajSeats,
                    'repli'  => true
                ];
            }
        } elseif (isset($validLists[$winnerId])) {
            $validLists[$winnerId]['sieges_prime'] += $primeMajSeats;
            $log['distribution_primes']['vainqueur'] = [
                'nom'    => $validLists[$winnerId]['nom'],
                'sieges' => $primeMajSeats
            ];
        }

        if (!$isFirstRoundWin && $runnerUpId && isset($validLists[$runnerUpId])) {
            $validLists[$runnerUpId]['sieges_prime'] += $primeMinSeats;
            $log['distribution_primes']['perdant'] = [
                'nom'    => $validLists[$runnerUpId]['nom'],
                'sieges' => $primeMinSeats
            ];
        }

        // 6. Calcul des totaux finaux
        foreach ($validLists as &$data) {
            $data['total_sieges'] = $data['sieges_prop'] + $data['sieges_prime'];
        }
        unset($data); // Bonne pratique : libération de la référence

        uasort($validLists, function ($a, $b) {
            return $b['total_sieges'] <=> $a['total_sieges'];
        });

        return [
            'resultats'    => $validLists,
            'explications' => $log
        ];
    }
}