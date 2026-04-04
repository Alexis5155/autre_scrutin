<?php
namespace app\models;

class VilleResultats {

    private string $csvT1          = __DIR__ . '/../data/municipales_2026_t1_resultats.csv';
    private string $csvT2          = __DIR__ . '/../data/municipales_2026_t2_resultats.csv';
    private string $csvCandidatsT1 = __DIR__ . '/../data/municipales_2026_t1_candidatures.csv';
    private string $csvCandidatsT2 = __DIR__ . '/../data/municipales_2026_t2_candidatures.csv';

    public function fetchParCodeInsee(string $codeInsee): array
    {
        $codeInsee = str_pad($codeInsee, 5, '0', STR_PAD_LEFT);

        $rowT1         = $this->lire1erTour($codeInsee);
        $totalExprimes = (int)($rowT1['Exprimés'] ?? 0);

        if ($totalExprimes === 0) {
            throw new \RuntimeException("Aucun suffrage exprimé trouvé pour la commune $codeInsee.");
        }

        $candidatsT1 = $this->lireCandidaturesT1($codeInsee);
        $listes       = $this->extraireListes1erTour($rowT1, $totalExprimes, $candidatsT1);

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
            $candidatsT2 = $this->lireCandidaturesT2($codeInsee);
            [$listes, $vainqueurId, $perdantId, $totalSieges] =
                $this->fusionner2ndTour($codeInsee, $listes, $candidatsT1, $candidatsT2);
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
                "Résultats du 1er tour introuvables pour $codeInsee. Le code INSEE est peut-être incorrect ou il n'y a pas eu d'élections municipales dans cette commune en mars 2026."          
                );
        }
        return $row;
    }

    /**
     * Lit le CSV T1 candidatures pour une commune.
     * Retourne un tableau indexé par numéro de panneau :
     * [
     *   "1" => [
     *     'tete'      => 'Pierre DUPONT',       // casse originale pour affichage
     *     'candidats' => ['DUPONT PIERRE', ...]  // normalisés pour comparaison
     *   ],
     * ]
     */
    private function lireCandidaturesT1(string $codeInsee): array
    {
        $result = [];

        $fh = fopen($this->csvCandidatsT1, 'r');
        if (!$fh) throw new \RuntimeException("Impossible d'ouvrir le fichier candidatures T1.");

        $headers   = fgetcsv($fh, 0, ';');
        $headers   = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);

        $colCirco  = array_search('Code circonscription', $headers);
        $colOrdre  = array_search('Ordre', $headers);
        $colNum    = array_search('Numéro de panneau', $headers);
        $colPrenom = array_search('Prénom sur le bulletin de vote', $headers);
        $colNom    = array_search('Nom sur le bulletin de vote', $headers);

        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            if (!isset($row[$colCirco])) continue;
            $circo = str_pad(trim($row[$colCirco], " \""), 5, '0', STR_PAD_LEFT);
            if ($circo !== $codeInsee) continue;

            $num = trim($row[$colNum] ?? '');
            if ($num === '') continue;

            $nomComplet = trim(($row[$colPrenom] ?? '') . ' ' . ($row[$colNom] ?? ''));
            if ($nomComplet === '') continue;

            if (!isset($result[$num])) {
                $result[$num] = ['tete' => '', 'candidats' => []];
            }

            $result[$num]['candidats'][] = $this->normaliserNom($nomComplet);

            if (trim($row[$colOrdre] ?? '') === '1') {
                $result[$num]['tete'] = $nomComplet; // casse originale
            }
        }
        fclose($fh);

        return $result;
    }

    /**
     * Lit le CSV T2 candidatures pour une commune.
     * Retourne un tableau indexé par numéro de panneau T2 :
     * [
     *   "1" => [
     *     'nom_liste' => 'Fusion gauche unie',
     *     'candidats' => ['DUPONT PIERRE', 'MARTIN SOPHIE', ...]  // normalisés
     *   ],
     * ]
     */
    private function lireCandidaturesT2(string $codeInsee): array
    {
        $result = [];

        if (!file_exists($this->csvCandidatsT2)) return $result;

        $fh = fopen($this->csvCandidatsT2, 'r');
        if (!$fh) return $result;

        $headers   = fgetcsv($fh, 0, ';');
        $headers   = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);

        $colCirco  = array_search('Code circonscription', $headers);
        $colNum    = array_search('Numéro de panneau', $headers);
        $colListe  = array_search('Libellé de la liste', $headers);
        $colPrenom = array_search('Prénom sur le bulletin de vote', $headers);
        $colNom    = array_search('Nom sur le bulletin de vote', $headers);

        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            if (!isset($row[$colCirco])) continue;
            $circo = str_pad(trim($row[$colCirco], " \""), 5, '0', STR_PAD_LEFT);
            if ($circo !== $codeInsee) continue;

            $num = trim($row[$colNum] ?? '');
            if ($num === '') continue;

            $nomComplet = trim(($row[$colPrenom] ?? '') . ' ' . ($row[$colNom] ?? ''));

            if (!isset($result[$num])) {
                $result[$num] = [
                    'nom_liste' => trim($row[$colListe] ?? ''),
                    'candidats' => [],
                ];
            }

            $normalise = $this->normaliserNom($nomComplet);
            if ($normalise !== '') {
                $result[$num]['candidats'][] = $normalise;
            }
        }
        fclose($fh);

        return $result;
    }

    private function rechercherDansCSV(string $chemin, string $colonneFiltre, string $valeur): ?array
    {
        $fh = fopen($chemin, 'r');
        if (!$fh) throw new \RuntimeException("Impossible d'ouvrir le fichier CSV : $chemin");

        $headers  = fgetcsv($fh, 0, ';');
        $headers  = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);
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
                return array_combine($headers, array_pad($row, count($headers), ''));
            }
        }

        fclose($fh);
        return null;
    }

    // -------------------------------------------------------------------------
    // Extraction
    // -------------------------------------------------------------------------

    private function extraireListes1erTour(array $row, int $totalExprimes, array $candidatsT1): array
    {
        $listes = [];
        for ($i = 1; $i <= 15; $i++) {
            $voixBrut = $row["Voix $i"] ?? null;
            if ($voixBrut === null || trim((string)$voixBrut) === '') break;

            $voix = (int)$voixBrut;
            $num  = trim($row["Numéro de panneau $i"] ?? '');

            $teteListe = '';
            if ($num !== '' && isset($candidatsT1[$num]['tete']) && $candidatsT1[$num]['tete'] !== '') {
                $teteListe = $candidatsT1[$num]['tete'];
            } else {
                $teteListe = trim(($row["Prénom candidat $i"] ?? '') . ' ' . ($row["Nom candidat $i"] ?? ''));
            }
            if ($teteListe === '') $teteListe = 'Candidat non renseigné';

            $nomListe = trim($row["Libellé de liste $i"] ?? '') ?: $teteListe;
            $score    = $totalExprimes > 0 ? round(($voix / $totalExprimes) * 100, 2) : 0;

            $listes["L$i"] = [
                'id'             => "L$i",
                'nom'            => $nomListe,
                'candidat'       => $teteListe,
                'nuance'         => trim($row["Nuance liste $i"] ?? 'LDIV'),
                'score_1er_tour' => $score,
                'voix'           => $voix,
                'sieges_reel'    => (int)($row["Sièges au CM $i"] ?? 0),
                'num_panneau'    => $num, // conservé pour détection de fusion
            ];
        }
        return $listes;
    }

    private function fusionner2ndTour(string $codeInsee, array $listes, array $candidatsT1, array $candidatsT2): array
    {
        $row = $this->rechercherDansCSV($this->csvT2, 'Code commune', $codeInsee);
        if ($row === null) {
            throw new \RuntimeException("Résultats du 2nd tour introuvables pour $codeInsee.");
        }

        // --- Étape 1 : lire les listes T2 indexées par numéro de panneau (string) ---
        $listesT2    = [];
        $totalSieges = 0;

        for ($i = 1; $i <= 15; $i++) {
            $voixBrut = $row["Voix $i"] ?? null;
            if ($voixBrut === null || trim((string)$voixBrut) === '') break;

            $panneauT2 = (string)trim($row["Numéro de panneau $i"] ?? '');
            $nom       = trim($row["Libellé de liste $i"] ?? $row["Nom candidat $i"] ?? "Liste $i");
            $sieges    = (int)($row["Sièges au CM $i"] ?? 0);
            $totalSieges += $sieges;

            $listesT2[$panneauT2] = [
                'nom'         => $nom,
                'voix'        => (int)$voixBrut,
                'sieges'      => $sieges,
                'num_panneau' => $panneauT2,
            ];
        }

        $panneauxT2 = array_keys($listesT2);

        // Étape 2 : forcer le cast en string pour la comparaison
        $panneauxT2 = array_map('strval', array_keys($listesT2));

        $disparues = [];
        foreach ($listes as $id => $liste) {
            $panneau = (string)($liste['num_panneau'] ?? '');
            if ($liste['score_1er_tour'] < 5) continue;
            if ($panneau === '' || in_array($panneau, $panneauxT2, true)) continue;
            $disparues[$id] = $liste;
        }

        // --- Étape 3 : détecter dans quelle liste T2 chaque disparue a fusionné ---
        $fusionMap = [];

        // Panneaux T1 des listes non-disparues (= listes maintenues au T2 avec même panneau)
        $panneauxMaintenuusT1 = [];
        foreach ($listes as $id => $liste) {
            if (isset($disparues[$id])) continue; // exclure les disparues
            $panneauxMaintenuusT1[] = (string)($liste['num_panneau'] ?? '');
        }

        foreach ($disparues as $id => $disparue) {
            $panneau    = (string)$disparue['num_panneau'];
            $candidats3 = array_slice($candidatsT1[$panneau]['candidats'] ?? [], 0, 3);
            if (empty($candidats3)) continue;

            foreach ($listesT2 as $panneauT2 => $resT2) {
                // Une liste hôte de fusion ne peut pas avoir le même panneau
                // qu'une liste T1 maintenue (sinon c'est juste la même liste)
                if (in_array($panneauT2, $panneauxMaintenuusT1, true)) continue;

                $candidatsFusion = $candidatsT2[$panneauT2]['candidats'] ?? [];
                if (empty($candidatsFusion)) continue;

                error_log("  Test disparue=$id (panneau=$panneau) vs T2 panneau=$panneauT2");
                error_log("    candidats3: " . implode(', ', $candidats3));
                error_log("    candidatsFusion: " . implode(', ', $candidatsFusion));
                
                if (count(array_intersect($candidats3, $candidatsFusion)) > 0) {
                    $fusionMap[$id] = $panneauT2;
                    break;
                }
            }
        }

        // --- Étape 4 : mettre à jour les listes T1 ---
        foreach ($listes as &$liste) {
            $panneau = (string)($liste['num_panneau'] ?? '');
            if ($panneau === '' || !isset($listesT2[$panneau])) continue;

            $resT2 = $listesT2[$panneau];

            // Sièges réels T2
            $liste['sieges_reel'] = $resT2['sieges'];

            // Nom T2 si différent
            if ($resT2['nom'] !== $liste['nom']) {
                $liste['nom_T2'] = $resT2['nom'];
            }
        }
        unset($liste);

        // Ajouter fusionnees_avec sur les listes disparues qui ont fusionné
        foreach ($fusionMap as $id => $panneauHote) {
            $listes[$id]['fusionnees_avec'] = $panneauHote;
        }

        // --- Étape 5 : vainqueur / perdant par panneau ---
        $scoresT2tries = array_values($listesT2);
        usort($scoresT2tries, fn($a, $b) => $b['voix'] <=> $a['voix']);

        $vainqueurId = null;
        $perdantId   = null;

        foreach ($listes as $id => $liste) {
            $panneau = (string)($liste['num_panneau'] ?? '');
            if ($panneau === ($scoresT2tries[0]['num_panneau'] ?? '')) $vainqueurId = $id;
            if (isset($scoresT2tries[1]) && $panneau === $scoresT2tries[1]['num_panneau']) $perdantId = $id;
        }

        return [$listes, $vainqueurId, $perdantId, $totalSieges];
    }

    // -------------------------------------------------------------------------
    // Utilitaires
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

    /**
     * Normalise un nom complet en majuscules sans accents pour comparaison robuste.
     * "Pierre DUPONT" et "DUPONT Pierre" → "PIERRE DUPONT" / "DUPONT PIERRE"
     * (on ne réordonne pas, mais la casse et les accents sont harmonisés)
     */
    private function normaliserNom(string $nom): string
    {
        $nom = mb_strtoupper(trim($nom), 'UTF-8');
        $nom = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom);
        return preg_replace('/\s+/', ' ', $nom);
    }
}