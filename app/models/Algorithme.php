<?php
namespace app\models;

class Algorithme {
    
    public function calculateReformSeats($totalSeats, $lists, $winnerId, $runnerUpId, $isPLM = false, $isFirstRoundWin = false) {
        
        $log = []; // Journal explicatif des étapes
        
        // 1. Définition des pourcentages des primes
        $pctMaj = $isPLM ? 0.30 : 0.40;
        $pctMin = $isFirstRoundWin ? 0 : 0.10;

        $primeMajSeats = ceil($totalSeats * $pctMaj);
        $primeMinSeats = $pctMin > 0 ? ceil($totalSeats * $pctMin) : 0;
        $propSeats = $totalSeats - $primeMajSeats - $primeMinSeats;

        $log['primes'] = [
            'total_sieges' => $totalSeats,
            'part_prop' => $propSeats,
            'prime_maj' => $primeMajSeats,
            'prime_min' => $primeMinSeats,
            'txt_maj' => ($pctMaj * 100) . '%',
            'txt_min' => ($pctMin * 100) . '%'
        ];

        // 2. Filtrer les listes ayant passé le seuil de 5%
        $validLists = [];
        $eliminatedLists = [];
        $totalValidScore = 0;
        
        foreach($lists as $id => $list) {
            if($list['score_1er_tour'] >= 5.0) {
                $validLists[$id] = [
                    'nom' => $list['nom'],
                    'score' => $list['score_1er_tour'],
                    'sieges_prop' => 0,
                    'sieges_prime' => 0,
                    'total_sieges' => 0
                ];
                $totalValidScore += $list['score_1er_tour'];
            } else {
                $eliminatedLists[] = $list['nom'];
            }
        }

        $log['seuil'] = [
            'score_utile' => round($totalValidScore, 2),
            'eliminees' => $eliminatedLists
        ];

        // 3. Répartition proportionnelle (Quotient Electoral)
        $quotient = $totalValidScore / $propSeats;
        $remainingSeats = $propSeats;
        
        $log['quotient'] = [
            'valeur' => round($quotient, 2),
            'attributions' => []
        ];

        foreach($validLists as $id => &$data) {
            $seats = floor($data['score'] / $quotient);
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
            'attributions' => []
        ];

        while($remainingSeats > 0) {
            $maxAvg = -1;
            $winnerAvgId = null;
            
            foreach($validLists as $id => $data) {
                $avg = $data['score'] / ($data['sieges_prop'] + 1);
                if($avg > $maxAvg) {
                    $maxAvg = $avg;
                    $winnerAvgId = $id;
                }
            }
            $validLists[$winnerAvgId]['sieges_prop']++;
            
            if (!isset($log['restes']['attributions'][$validLists[$winnerAvgId]['nom']])) {
                $log['restes']['attributions'][$validLists[$winnerAvgId]['nom']] = 0;
            }
            $log['restes']['attributions'][$validLists[$winnerAvgId]['nom']]++;
            
            $remainingSeats--;
        }

        // 5. Attribution des primes
        $log['distribution_primes'] = [];
        if(isset($validLists[$winnerId])) {
            $validLists[$winnerId]['sieges_prime'] += $primeMajSeats;
            $log['distribution_primes']['vainqueur'] = ['nom' => $validLists[$winnerId]['nom'], 'sieges' => $primeMajSeats];
        }
        
        if(!$isFirstRoundWin && $runnerUpId && isset($validLists[$runnerUpId])) {
            $validLists[$runnerUpId]['sieges_prime'] += $primeMinSeats;
            $log['distribution_primes']['perdant'] = ['nom' => $validLists[$runnerUpId]['nom'], 'sieges' => $primeMinSeats];
        }

        // 6. Calcul des totaux finaux
        foreach($validLists as &$data) {
            $data['total_sieges'] = $data['sieges_prop'] + $data['sieges_prime'];
        }

        uasort($validLists, function($a, $b) {
            return $b['total_sieges'] <=> $a['total_sieges'];
        });

        // On retourne un tableau contenant les résultats ET le journal explicatif
        return [
            'resultats' => $validLists,
            'explications' => $log
        ];
    }
}