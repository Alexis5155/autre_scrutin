<?php
namespace app\models;

class AnalyseService {

    /**
     * Génère un tableau de messages d'analyse de l'impact de la réforme.
     * Retourne un tableau de ['type' => 'success|info|warning', 'icone' => '...', 'texte' => '...']
     * La vue se charge uniquement de les afficher.
     */
    public function analyser(
        array $listesInitiales,
        array $resultatsReforme,
        int   $totalSieges,
        bool  $elu1erTour
    ): array {
        $messages = [];

        if (empty($resultatsReforme) || empty($listesInitiales)) {
            return $messages;
        }

        // Indexation par nom pour accès rapide
        $siegesReelsParNom = [];
        foreach ($listesInitiales as $l) {
            $siegesReelsParNom[$l['nom']] = $l['sieges_reel'];
        }

        // Tri des résultats de la réforme par sièges décroissants
        usort($resultatsReforme, fn($a, $b) => $b['total_sieges'] <=> $a['total_sieges']);

        $maire          = $resultatsReforme[0];
        $majoriteAbs    = (int)floor($totalSieges / 2) + 1;
        $aMajoriteAbs   = $maire['total_sieges'] >= $majoriteAbs;
        $siegesReelMaire = $siegesReelsParNom[$maire['nom']] ?? 0;

        // --- Message principal : le vainqueur ---
        if (count($listesInitiales) === 1) {
            $messages[] = [
                'type'  => 'info',
                'icone' => 'bi-dash-circle',
                'texte' => "La liste gagnante était la seule candidate. Cette réforme n'aurait <strong>aucun impact</strong>."
            ];
            return $messages;
        }

        if (!$aMajoriteAbs) {
            $messages[] = [
                'type'  => 'warning',
                'icone' => 'bi-exclamation-triangle',
                'texte' => "La liste <strong>{$maire['nom']}</strong> remporte les élections mais ne dispose pas de la "
                         . "<strong>majorité absolue</strong> au conseil municipal. Elle devra former une coalition."
            ];
        } else {
            $diff = $maire['total_sieges'] - $siegesReelMaire;
            if ($diff < 0) {
                $messages[] = [
                    'type'  => 'success',
                    'icone' => 'bi-trophy-fill',
                    'texte' => "La liste <strong>{$maire['nom']}</strong> remporte les élections avec une majorité absolue, "
                             . "mais moins hégémonique que le système actuel (<strong>" . abs($diff) . " sièges de moins</strong>), "
                             . "renforçant le débat démocratique."
                ];
            } else {
                $messages[] = [
                    'type'  => 'success',
                    'icone' => 'bi-trophy-fill',
                    'texte' => "La liste <strong>{$maire['nom']}</strong> remporte les élections et dispose "
                             . "d'une <strong>majorité absolue</strong> au conseil municipal."
                ];
            }
        }

        // --- Message : l'opposition ---
        if (isset($resultatsReforme[1])) {
            $perdant = $resultatsReforme[1];
            if ($elu1erTour) {
                $messages[] = [
                    'type'  => 'info',
                    'icone' => 'bi-shield',
                    'texte' => "La liste <strong>{$perdant['nom']}</strong>, arrivée 2ème, ne bénéficie pas "
                             . "de la prime minoritaire, l'élection ayant été remportée dès le 1er tour."
                ];
            } else {
                $messages[] = [
                    'type'  => 'info',
                    'icone' => 'bi-shield-fill-check',
                    'texte' => "La liste <strong>{$perdant['nom']}</strong> devient le groupe d'opposition principal "
                             . "grâce à la <strong>prime minoritaire de 10%</strong>."
                ];
            }
        }

        // --- Message : fin du vote utile (listes qui gagnent des sièges) ---
        if (!$elu1erTour) {
            $repechees = [];
            $gagnantes = [];

            foreach ($resultatsReforme as $res) {
                if ($res['nom'] === $maire['nom']) continue;
                $siegesReel = $siegesReelsParNom[$res['nom']] ?? 0;

                if ($siegesReel === 0 && $res['total_sieges'] > 0) {
                    $repechees[] = "<strong>{$res['nom']}</strong>";
                } elseif ($siegesReel > 0 && $res['total_sieges'] > $siegesReel) {
                    $gagnantes[] = "<strong>{$res['nom']}</strong>";
                }
            }

            if (!empty($gagnantes)) {
                $messages[] = [
                    'type'  => 'success',
                    'icone' => 'bi-arrow-up-circle-fill',
                    'texte' => "Fin du vote utile : " . implode(' et ', $gagnantes)
                             . " bénéficient de sièges supplémentaires grâce à la proportionnelle du 1er tour."
                ];
            }

            if (!empty($repechees)) {
                $messages[] = [
                    'type'  => 'primary',
                    'icone' => 'bi-door-open-fill',
                    'texte' => implode(' et ', $repechees)
                             . " entre(nt) au conseil municipal grâce au figement de la proportionnelle au 1er tour."
                ];
            }
        }

        return $messages;
    }
}