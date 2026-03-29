<?php
namespace app\models;

class VilleResultats {

    private string $urlApi = "https://tabular-api.data.gouv.fr/api/resources/";
    private string $rid1erTour  = "4feeef01-24f7-4d5a-914f-8aa806f31ec2";
    private string $rid2ndTour  = "6ff67a28-01bf-459e-beca-dd7aa8132dc1";
    private string $ridCandidats = "b929c2a4-18ec-4e8b-bc37-2ff346a867cd";

    /**
     * Point d'entrée principal. Retourne toutes les données normalisées
     * pour une commune donnée, ou lève une \RuntimeException en cas d'erreur.
     */
    public function fetchParCodeInsee(string $codeInsee): array {
        $codeInsee = str_pad($codeInsee, 5, "0", STR_PAD_LEFT);

        $rowT1           = $this->fetch1erTour($codeInsee);
        $totalExprimes   = (int)($rowT1['Exprimés'] ?? 0);

        if ($totalExprimes === 0) {
            throw new \RuntimeException("Aucun suffrage exprimé trouvé pour la commune $codeInsee.");
        }

        $tetesDeListe = $this->fetchTetesDeListe($codeInsee);
        $listes       = $this->extraireListes1erTour($rowT1, $totalExprimes, $tetesDeListe);

        $nomCommune  = $rowT1['Libellé commune'] ?? 'Commune inconnue';
        $totalSieges = array_sum(array_column($listes, 'sieges_reel'));
        $elu1erTour  = ($totalSieges > 0);

        $vainqueurId  = null;
        $perdantId    = null;

        if ($elu1erTour) {
            // Victoire au 1er tour : le vainqueur est celui qui a > 50%
            foreach ($listes as $id => $liste) {
                if ($liste['score_1er_tour'] > 50) {
                    $vainqueurId = $id;
                    break;
                }
            }
        } else {
            // Besoin du 2nd tour
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
    // Méthodes privées
    // -------------------------------------------------------------------------

    private function fetch1erTour(string $codeInsee): array {
        $url  = $this->urlApi . $this->rid1erTour . "/data/?Code%20commune__exact=" . urlencode($codeInsee);
        $json = $this->httpGet($url);
        $data = json_decode($json, true);

        if (empty($data['data'])) {
            throw new \RuntimeException(
                "Résultats du 1er tour introuvables pour $codeInsee. " .
                "Il s'agit peut-être d'une commune de moins de 1000 habitants."
            );
        }
        return $data['data'][0];
    }

    private function fetchTetesDeListe(string $codeInsee): array {
        $url  = $this->urlApi . $this->ridCandidats .
                "/data/?Code%20circonscription__exact=" . urlencode($codeInsee) .
                "&T%C3%AAte%20de%20liste__exact=true";
        $json = $this->httpGet($url);
        $data = json_decode($json, true);

        $tetes = [];
        if (!empty($data['data'])) {
            foreach ($data['data'] as $cand) {
                $num = $cand['Numéro de panneau'] ?? null;
                if ($num) {
                    $tetes[$num] = trim(
                        ($cand['Prénom sur le bulletin de vote'] ?? '') . ' ' .
                        ($cand['Nom sur le bulletin de vote']    ?? '')
                    );
                }
            }
        }
        return $tetes;
    }

    private function extraireListes1erTour(array $row, int $totalExprimes, array $tetes): array {
        $listes = [];
        for ($i = 1; $i <= 15; $i++) {
            $voixBrut = $row["Voix $i"] ?? null;
            if ($voixBrut === null || trim((string)$voixBrut) === '') break;

            $voix       = (int)$voixBrut;
            $num        = $row["Numéro de panneau $i"] ?? null;
            $teteListe  = ($num && isset($tetes[$num])) ? $tetes[$num] : 'Candidat non renseigné';
            $nomListe   = trim($row["Libellé de liste $i"] ?? '') ?: $teteListe;
            $score      = round(($voix / $totalExprimes) * 100, 2);

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

    /**
     * Fusionne les données du 2nd tour dans le tableau des listes.
     * Retourne [$listes, $vainqueurId, $perdantId, $totalSieges].
     */
    private function fusionner2ndTour(string $codeInsee, array $listes): array {
        $url  = $this->urlApi . $this->rid2ndTour . "/data/?Code%20commune__exact=" . urlencode($codeInsee);
        $json = $this->httpGet($url);
        $data = json_decode($json, true);

        if (empty($data['data'])) {
            throw new \RuntimeException("Résultats du 2nd tour introuvables pour $codeInsee.");
        }

        $row         = $data['data'][0];
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

        // Mise à jour des sièges réels dans les listes du 1er tour
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

            // Liste de fusion inconnue au 1er tour
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

    private function nomCorrespond(string $a, string $b): bool {
        $a = trim($a); $b = trim($b);
        return strcasecmp($a, $b) === 0 || stripos($b, $a) !== false;
    }

    private function trouverIdParNom(array $listes, string $nom): ?string {
        foreach ($listes as $id => $liste) {
            if ($this->nomCorrespond($liste['nom'], $nom)) return $id;
        }
        return null;
    }

    /**
     * Requête HTTP GET robuste via cURL.
     * Lève une \RuntimeException en cas d'échec réseau.
     */
    private function httpGet(string $url): string {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'SimulateurReformeMunicipale/1.0',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,   // ← CORRECTION SÉCURITÉ
            CURLOPT_TIMEOUT        => 15,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            throw new \RuntimeException("Erreur HTTP $httpCode lors de l'appel à : $url");
        }
        return $response;
    }
}