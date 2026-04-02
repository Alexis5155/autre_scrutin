<?php
namespace app\models;

class VilleResultats {

    // Chemins vers les CSV placés dans app/data/
    private string $csvT1         = __DIR__ . '/../data/municipales_2026_t1_resultats.csv';
    private string $csvT2         = __DIR__ . '/../data/municipales_2026_t2_resultats.csv';
    private string $csvCandidats  = __DIR__ . '/../data/municipales_2026_t1_candidatures.csv';

    /**
     * Point d'entrée principal.
     */
    public function fetchParCodeInsee(string $codeInsee): array
    {
        $codeInsee = str_pad($codeInsee, 5, '0', STR_PAD_LEFT);

        $rowT1         = $this->lire1erTour($codeInsee);
        $totalExprimes = (int)($rowT1['Exprimés'] ?? 0);

        if ($totalExprimes === 0) {
            throw new \RuntimeException("Aucun suffrage exprimé trouvé pour la commune $codeInsee.");
        }

        $tetesDeListe = $this->lireTetesDeListe($codeInsee);
        $listes       = $this->extraireListes1erTour($rowT1, $totalExprimes, $tetesDeListe);

        $nomCommune  = $rowT1['Libellé commune'] ?? 'Commune inconnue';
        $totalSieges = array_sum(array_column($listes, 'sieges_reel'));
        $elu1erTour  = ($totalSieges > 0);

        $vainqueurId = null;
        $perdantId   = null;

        if ($elu1erTour) {
            foreach ($listes as $id => $liste) {
                if ($liste['score_1er_tour'] > 50) {
                    $vainqueurId = $id;
                    break;
                }
            }
        } else {
            [$listes, $vainqueurId, $perdantId, $totalSieges] =
                $this->fusionner2ndTour($codeInsee, $listes);
        }

        if ($totalSieges === 0) {
            throw new \RuntimeException("Impossible de déterminer le nombre de sièges pour $codeInsee.");
        }

        return [
            'commune'     => $nomCommune,
            'code_insee'  => $codeInsee,
            'sieges'      => $totalSieges,
            'elu1erTour'  => $elu1erTour,
            'isPLM'       => in_array($codeInsee, ['75056', '69123', '13055']),
            'listes'      => array_values($listes),
            'vainqueurId' => $vainqueurId,
            'perdantId'   => $perdantId,
        ];
    }

    // -------------------------------------------------------------------------
    // Lecture CSV
    // -------------------------------------------------------------------------

    private function lire1erTour(string $codeInsee): array
    {
        $row = $this->rechercherDansCSV($this->csvT1, 'Code commune', $codeInsee);

        if ($row === null) {
            throw new \RuntimeException(
                "Résultats du 1er tour introuvables pour $codeInsee. " .
                "Il s'agit peut-être d'une commune de moins de 1000 habitants."
            );
        }
        return $row;
    }

    private function lireTetesDeListe(string $codeInsee): array
    {
        $tetes = [];

        $fh = fopen($this->csvCandidats, 'r');
        if (!$fh) {
            throw new \RuntimeException("Impossible d'ouvrir le fichier candidatures CSV.");
        }

        // Lire l'en-tête
        $headers = fgetcsv($fh, 0, ';');
        $headers = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);

        $colCirco  = array_search('Code circonscription', $headers);
        $colOrdre  = array_search('Ordre', $headers);
        $colNum    = array_search('Numéro de panneau', $headers);
        $colPrenom = array_search('Prénom sur le bulletin de vote', $headers);
        $colNom    = array_search('Nom sur le bulletin de vote', $headers);

        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            if (!isset($row[$colCirco])) continue;
            $circo = str_pad(trim($row[$colCirco], " \""), 5, '0', STR_PAD_LEFT);
            if ($circo !== $codeInsee) continue;

            // Tête de liste = Ordre 1
            if (trim($row[$colOrdre] ?? '') !== '1') continue;

            $num = trim($row[$colNum] ?? '');
            if ($num === '') continue;

            $tetes[$num] = trim(
                ($row[$colPrenom] ?? '') . ' ' . ($row[$colNom] ?? '')
            );
        }
        fclose($fh);

        return $tetes;
    }

    /**
     * Recherche générique dans un CSV séparé par ";" — retourne la première ligne
     * dont la colonne $colonneFiltre vaut $valeur.
     */
    private function rechercherDansCSV(string $chemin, string $colonneFiltre, string $valeur): ?array
    {
        $fh = fopen($chemin, 'r');
        if (!$fh) {
            throw new \RuntimeException("Impossible d'ouvrir le fichier CSV : $chemin");
        }

        $headers = fgetcsv($fh, 0, ';');
        $headers = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);

        $colIndex = array_search($colonneFiltre, $headers);
        if ($colIndex === false) {
            fclose($fh);
            throw new \RuntimeException("Colonne '$colonneFiltre' introuvable dans $chemin.");
        }

        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            if (!isset($row[$colIndex])) continue;
            $cellule = str_pad(trim($row[$colIndex], " \""), 5, '0', STR_PAD_LEFT);
            if ($cellule === $valeur) {
                fclose($fh);
                // Retourner un tableau associatif header => valeur
                return array_combine($headers, array_pad($row, count($headers), ''));
            }
        }

        fclose($fh);
        return null;
    }

    // -------------------------------------------------------------------------
    // Extraction — logique identique à l'ancienne version
    // -------------------------------------------------------------------------

    private function extraireListes1erTour(array $row, int $totalExprimes, array $tetes): array
    {
        $listes = [];
        for ($i = 1; $i <= 15; $i++) {
            $voixBrut = $row["Voix $i"] ?? null;
            if ($voixBrut === null || trim((string)$voixBrut) === '') break;

            $voix      = (int)$voixBrut;
            $num       = trim($row["Numéro de panneau $i"] ?? '');
            $teteListe = ($num !== '' && isset($tetes[$num]))
                ? $tetes[$num]
                : trim(($row["Prénom candidat $i"] ?? '') . ' ' . ($row["Nom candidat $i"] ?? ''));
            if ($teteListe === '') $teteListe = 'Candidat non renseigné';

            $nomListe = trim($row["Libellé de liste $i"] ?? '') ?: $teteListe;
            $score    = $totalExprimes > 0 ? round(($voix / $totalExprimes) * 100, 2) : 0;

            $listes["L$i"] = [
                'id'             => "L$i",
                'nom'            => $nomListe,
                'candidat'       => $teteListe,
                'nuance'         => trim($row["Nuance liste $i"] ?? ''),
                'score_1er_tour' => $score,
                'voix'           => $voix,
                'sieges_reel'    => (int)($row["Sièges au CM $i"] ?? 0),
            ];
        }
        return $listes;
    }

    private function fusionner2ndTour(string $codeInsee, array $listes): array
    {
        $row = $this->rechercherDansCSV($this->csvT2, 'Code commune', $codeInsee);

        if ($row === null) {
            throw new \RuntimeException("Résultats du 2nd tour introuvables pour $codeInsee.");
        }

        $scoresT2    = [];
        $totalSieges = 0;

        for ($i = 1; $i <= 15; $i++) {
            $voixBrut = $row["Voix $i"] ?? null;
            if ($voixBrut === null || trim((string)$voixBrut) === '') break;

            $nom    = trim($row["Libellé de liste $i"] ?? $row["Nom candidat $i"] ?? "Liste $i");
            $sieges = (int)($row["Sièges au CM $i"] ?? 0);
            $totalSieges += $sieges;
            $scoresT2[] = ['nom' => $nom, 'voix' => (int)$voixBrut, 'sieges' => $sieges];
        }

        usort($scoresT2, fn($a, $b) => $b['voix'] <=> $a['voix']);

        foreach ($scoresT2 as $resT2) {
            $found = false;
            foreach ($listes as &$liste) {
                if ($this->nomCorrespond($liste['nom'], $resT2['nom'])) {
                    $liste['sieges_reel'] = $resT2['sieges'];
                    $found = true;
                    break;
                }
            }
            unset($liste);

            if (!$found) {
                $newId = 'L' . (count($listes) + 1);
                $listes[$newId] = [
                    'id'             => $newId,
                    'nom'            => $resT2['nom'] . ' (Fusion)',
                    'candidat'       => '',
                    'nuance'         => '',
                    'score_1er_tour' => 0,
                    'voix'           => 0,
                    'sieges_reel'    => $resT2['sieges'],
                ];
            }
        }

        $vainqueurId = isset($scoresT2[0]) ? $this->trouverIdParNom($listes, $scoresT2[0]['nom']) : null;
        $perdantId   = isset($scoresT2[1]) ? $this->trouverIdParNom($listes, $scoresT2[1]['nom']) : null;

        return [$listes, $vainqueurId, $perdantId, $totalSieges];
    }

    // -------------------------------------------------------------------------
    // Utilitaires — inchangés
    // -------------------------------------------------------------------------

    private function nomCorrespond(string $a, string $b): bool
    {
        $a = trim($a); $b = trim($b);
        return strcasecmp($a, $b) === 0 || stripos($b, $a) !== false;
    }

    private function trouverIdParNom(array $listes, string $nom): ?string
    {
        foreach ($listes as $id => $liste) {
            if ($this->nomCorrespond($liste['nom'], $nom)) return $id;
        }
        return null;
    }
}