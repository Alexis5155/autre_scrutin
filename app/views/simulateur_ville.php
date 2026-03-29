<div class="container mt-5 pt-4" id="app-ville">

    <!-- En-tête -->
    <div class="row fade-in-up mb-4">
        <div class="col-12 text-center">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-2 rounded-pill px-3 py-2 fw-semibold">
                <i class="bi bi-geo-alt-fill me-1"></i> Code INSEE : {{ donnees.code_insee }}
            </span>
            <h1 class="display-4 fw-bold mb-2">{{ donnees.commune }}</h1>
            <p class="lead text-muted">{{ donnees.sieges }} sièges au conseil municipal <span v-if="donnees.isPLM" class="badge bg-secondary ms-2">Mode Métropole (PLM)</span></p>
        </div>
    </div>

    <!-- Récapitulatif 1er Tour -->
    <div class="row fade-in-up mb-5">
        <div class="col-12">
            <div class="glass-card p-4 shadow-sm border-0 bg-white bg-opacity-50">
                <h5 class="mb-4 fw-bold text-center"><i class="bi bi-card-list me-2"></i>Résultats du 1er tour</h5>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <div v-for="(liste, index) in listesInitialesFiltrees" :key="liste.id" class="d-flex align-items-center bg-white p-3 rounded-4 shadow-sm border border-light" style="min-width: 300px; flex: 1; max-width: 350px;">
                        <span class="badge rounded-circle d-flex align-items-center justify-content-center fs-5 me-3 shadow-sm text-white" 
                              :style="{backgroundColor: getCouleurByNom(liste.nom), width: '45px', height: '45px', flexShrink: 0}">
                            {{ getLettreByNom(liste.nom) }}
                        </span>
                        <div class="lh-sm flex-grow-1 overflow-hidden">
                            <div class="fw-bold fs-6 text-truncate mb-1 text-dark" :title="liste.candidat">
                                {{ liste.candidat || 'Candidat inconnu' }}
                            </div>
                            <div class="text-muted small text-truncate mb-2" :title="liste.nom" style="font-size: 0.8rem;">
                                {{ liste.nom }}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span v-if="liste.nuance" class="badge px-2 py-1 border" 
                                      :style="getNuanceStyle(liste.nuance)">
                                    {{ liste.nuance }}
                                </span>
                                <span v-else class="badge bg-light text-dark border">N/C</span>
                                <div class="text-primary fw-bold" style="font-size: 0.95rem;">
                                    {{ liste.score_1er_tour }}% 
                                    <span class="text-muted fw-normal" style="font-size: 0.75rem;">({{ liste.voix }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualisation des Hémicycles -->
    <div class="row g-4 fade-in-up mb-5">
        
        <!-- SYSTÈME ACTUEL -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100 border-0 shadow-sm d-flex flex-column" style="background: rgba(255,255,255,0.6);">
                <div class="text-center mb-3">
                    <h5 class="mb-1 fw-bold text-dark">Répartition actuelle</h5>
                    <p class="small text-muted mb-0">Selon le système en vigueur</p>
                </div>
                <div class="position-relative mx-auto mb-3" style="height: 180px; width: 100%; max-width: 250px;">
                    <canvas id="chartActuel"></canvas>
                </div>
                <div class="flex-grow-1">
                    <div v-for="(liste, index) in listesActuellesLegende" :key="'leg_'+index" 
                         class="d-flex align-items-center mb-2 p-2 rounded-3 shadow-sm transition-all"
                         :class="{
                            'bg-white border border-warning border-2': index === 0 && liste.sieges_reel > 0, 
                            'bg-white': index !== 0 && liste.id !== 'autres',
                            'bg-light opacity-75': liste.id === 'autres'
                         }">
                        <span class="d-inline-block rounded-circle me-3 flex-shrink-0" 
                              :style="{width:'14px', height:'14px', backgroundColor: liste.couleur}"></span>
                        <div class="flex-grow-1 text-truncate lh-sm">
                            <span class="fw-bold fs-6" :class="liste.id === 'autres' ? 'text-muted' : 'text-dark'" :title="liste.nom">{{ liste.nom }}</span>
                            <div v-if="liste.candidat && liste.id !== 'autres'" class="text-muted" style="font-size: 0.75rem;">
                                {{ liste.candidat }}
                            </div>
                        </div>
                        <div class="ms-2 flex-shrink-0 text-end">
                            <span class="badge rounded-pill fs-6" 
                                  :class="index === 0 && liste.sieges_reel > 0 ? 'bg-warning text-dark' : 'bg-secondary'">
                                {{ liste.sieges_reel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AVEC LA RÉFORME -->
        <div class="col-lg-8">
            <div class="glass-card p-4 h-100 border-primary bg-white shadow-sm" style="border-width: 2px;">
                <h4 class="mb-1 text-primary text-center fw-bold"><i class="bi bi-magic me-2"></i>Avec la proposition de réforme</h4>
                <p class="small text-muted mb-4 text-center px-2">
                    <span v-if="estEluDesLe1erTour">
                        Proportionnelle ({{ donnees.isPLM ? 70 : 60 }}%) et prime majoritaire ({{ donnees.isPLM ? 30 : 40 }}%) au vainqueur.
                    </span>
                    <span v-else>
                        Proportionnelle ({{ donnees.isPLM ? 60 : 50 }}%) sur la base des résultats du 1er tour, primes majoritaire ({{ donnees.isPLM ? 30 : 40 }}%) et minoritaire (10%).
                    </span>
                </p>
                <div class="row align-items-center mt-3">
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="position-relative mx-auto" style="height: 220px; width: 100%; max-width: 320px;">
                            <canvas id="chartReforme"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6 class="text-muted mb-3 fw-bold border-bottom pb-2">Répartition détaillée des sièges</h6>
                        <div class="row g-2">
                            <div v-for="(liste, index) in listesCompleteReforme" :key="'ref_dyn_'+index" class="col-12">
                                <div v-if="(liste.totalsieges > 0) || (liste.sieges > 0)" 
                                     class="d-flex align-items-center p-2 bg-light rounded-3 border-start border-4 shadow-sm"
                                     :style="{borderLeftColor: getCouleurByNom(liste.nom) + ' !important'}">
                                    <div class="flex-grow-1 ps-2 lh-sm overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate" :title="liste.nom">{{ liste.nom }}</div>
                                        <div class="text-muted mt-1 d-flex flex-wrap gap-2" style="font-size: 0.75rem;">
                                            <span v-if="liste.siegesprop > 0 || liste.sieges_prop > 0">
                                                <i class="bi bi-pie-chart text-secondary"></i> {{ liste.siegesprop || liste.sieges_prop }} proportionnelle
                                            </span>
                                            <span v-if="liste.siegesprime > 0 || liste.sieges_prime > 0 || liste.sieges_majo > 0" class="text-success fw-bold">
                                                <i class="bi bi-trophy-fill"></i> +{{ liste.siegesprime || liste.sieges_prime || liste.sieges_majo }} prime
                                            </span>
                                            <span v-if="liste.siegesmin > 0" class="text-info fw-bold">
                                                <i class="bi bi-shield-fill-plus"></i> +{{ liste.siegesmin }} minorité
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ms-2 text-center flex-shrink-0 min-w-50px">
                                        <div class="badge fs-5 shadow-sm w-100" :style="{backgroundColor: getCouleurByNom(liste.nom)}">
                                            {{ liste.totalsieges || liste.sieges }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Encadré d'analyse dynamique -->
    <div v-if="analyseTexte" class="row fade-in-up mb-5">
        <div class="col-12">
            <div class="alert shadow-sm rounded-4 border border-warning p-4" style="background-color: #fffdf5;">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                    <h5 class="fw-bold text-dark mb-0">Impact de la réforme sur cette élection</h5>
                </div>
                <div class="mb-0 lh-base text-secondary fs-6" v-html="analyseTexte"></div>
            </div>
        </div>
    </div>

    <!-- Philosophie de la réforme & Scénario du 2nd Tour -->
    <div class="row mb-5 fade-in-up">
        <div class="col-12">
            <div class="glass-card p-0 overflow-hidden shadow-sm border-primary" style="border-width: 2px;">
                <div class="bg-primary bg-opacity-10 p-4 border-bottom border-primary border-opacity-25">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-book-half text-primary fs-3 me-3"></i>
                        <h4 class="fw-bold text-primary mb-0">
                            Comprendre la réforme : {{ estEluDesLe1erTour ? "L'élection au 1er tour" : "Le nouveau 2nd tour" }}
                        </h4>
                    </div>
                    <div v-if="estEluDesLe1erTour" class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-award-fill text-success fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark">La Prime Majoritaire octroyée</h6>
                                    <p class="text-muted small mb-0 lh-base">L'élection ayant été remportée avec la majorité absolue dès le premier tour, la prime majoritaire est logiquement attribuée à la liste gagnante pour lui assurer une majorité de gestion au conseil municipal.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-pie-chart-fill text-info fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark">Prime Minoritaire reversée</h6>
                                    <p class="text-muted small mb-0 lh-base">Puisqu'il n'y a pas de duel (pas de second tour), il n'y a pas de perdant à récompenser. La prime minoritaire n'a donc pas lieu d'être : <strong>ses 10% de sièges sont intégralement reversés dans la part proportionnelle</strong>, augmentant la représentativité globale.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-ui-radios text-primary fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark">Proportionnelle figée au 1er tour</h6>
                                    <p class="text-muted small mb-0 lh-base">La part proportionnelle est attribuée au 1er tour car c'est le moment le plus représentatif (toutes les listes sont en lice). Cela colle à l'adage : <em>« Au 1er tour on choisit, au 2nd on élimine »</em>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-people-fill text-success fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark">Un 2nd tour pour choisir le Maire</h6>
                                    <p class="text-muted small mb-0 lh-base">Le 2nd tour sert à octroyer la <strong>prime majoritaire</strong>. On ne garde que les deux meilleurs (les 3èmes ne gagnant statistiquement presque jamais) pour demander aux électeurs quelle équipe suscite le plus d'adhésion ou le moins de rejet.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-shield-shaded text-info fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark">Consécration de l'opposition</h6>
                                    <p class="text-muted small mb-0 lh-base">La <strong>prime minoritaire (10%)</strong> est une spécificité du duel : elle vient récompenser la participation de la liste perdante au second tour et la consacre comme opposition principale au sein du conseil.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ZONE INTERACTIVE -->
                <div class="p-4 bg-white">
                    <!-- CAS 1 : ÉLU AU 1ER TOUR -->
                    <div v-if="estEluDesLe1erTour" class="d-flex align-items-center justify-content-center text-start py-3">
                        <i class="bi bi-check-circle-fill text-success me-4" style="font-size: 2.5rem;"></i>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Élection remportée dès le 1er tour</h5>
                            <p class="text-muted mb-0">La liste gagnante ayant obtenu la majorité absolue, il n'y a pas de scénario de second tour à simuler.</p>
                        </div>
                    </div>

                    <!-- CAS 2 : DUEL AU 2ND TOUR -->
                    <div v-else>
                        <h6 class="fw-bold text-dark mb-3 text-center"><i class="bi bi-arrow-repeat text-primary me-2"></i>Simulez l'impact du duel : inversez le vainqueur de la mairie</h6>
                        <div class="row g-3">
                            <div v-for="liste in finalistes" :key="liste.id" class="col-sm-6">
                                <input type="radio" class="btn-check" name="vainqueur_duel" :id="'btn_duel_' + liste.id" :value="liste.id" v-model="vainqueurSimuleId" @change="changementVainqueurEtScroll">
                                <label class="btn btn-outline-primary w-100 h-100 d-flex align-items-center p-3 m-0 border-2 text-start" :for="'btn_duel_' + liste.id" style="border-radius: 12px; transition: all 0.2s;">
                                    <span class="badge rounded-circle me-3 shadow-sm flex-shrink-0 d-flex align-items-center justify-content-center" 
                                          :style="{backgroundColor: getCouleurByNom(liste.nom), width: '40px', height: '40px', fontSize: '1.2rem', color: '#fff', border: '2px solid rgba(255,255,255,0.8)'}">
                                        {{ getLettreByNom(liste.nom) }}
                                    </span>
                                    <div class="flex-grow-1 lh-sm">
                                        <div class="d-flex flex-wrap align-items-center mb-1 gap-2">
                                            <span class="fw-bold fs-6 text-truncate transition-colors"
                                                  :class="liste.id === vainqueurSimuleId ? 'text-white' : 'text-dark'" 
                                                  :title="liste.candidat" style="max-width: 60%;">
                                                {{ liste.candidat || 'Candidat' }}
                                            </span>
                                            <span v-if="liste.nuance" class="badge px-2 py-1 border" :style="getNuanceStyle(liste.nuance)">
                                                {{ liste.nuance }}
                                            </span>
                                            <span v-else class="badge bg-light text-dark border">NC</span>
                                            <span v-if="estVraiVainqueur2026(liste.nom)" class="badge bg-warning text-dark border border-warning ms-2 d-flex align-items-center" title="Cette liste a remporté les élections de 2026" style="font-size: 0.75rem;">
                                                <i class="bi bi-trophy-fill me-1"></i> Élu 2026
                                            </span>
                                        </div>
                                        <div class="small transition-colors"
                                             :class="liste.id === vainqueurSimuleId ? 'text-white-50' : 'text-muted'"
                                             :title="liste.nom" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ liste.nom }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Explication pédagogique étape par étape -->
    <div class="row fade-in-up mt-2">
        <div class="col-12">
            <h4 class="mb-4 text-center fw-bold"><i class="bi bi-mortarboard text-primary me-2"></i>Comment les sièges ont-ils été attribués ?</h4>
            <div class="row g-4">
                <!-- BLOC 1 : LA PROPORTIONNELLE -->
                <div class="col-md-6">
                    <div class="glass-card p-4 h-100 shadow-sm border-0 bg-white">
                        <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                            <div class="bg-primary text-white rounded px-3 py-1 fw-bold fs-5 shadow-sm me-3">Étape 1</div>
                            <h5 class="mb-0 fw-bold text-primary">La Proportionnelle</h5>
                        </div>
                        <p class="small text-muted mb-4">
                            Fixée sur les résultats du 1er tour, la part proportionnelle représente 
                            <strong>{{ explicationsSimules.primes.part_prop }} sièges</strong> 
                            ({{ donnees.isPLM && donnees.elu1erTour ? '70' : (donnees.isPLM ? '60' : (donnees.elu1erTour ? '60' : '50')) }}% du conseil).
                            <span v-if="explicationsSimules.seuil.eliminees.length > 0" class="text-danger d-block mt-1">
                                <i class="bi bi-x-circle me-1"></i> Seules les listes ayant franchi 5% y participent.
                            </span>
                        </p>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark"><i class="bi bi-calculator me-2"></i>A. Le Quotient Électoral</h6>
                            <p class="small text-muted mb-2">
                                <em>(Suffrages "utiles" / Nombre de sièges à répartir)</em><br>
                                Il fixe le coût d'un siège. Chaque liste reçoit un nombre de sièges égal au nombre de fois qu'elle a atteint ce quotient.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <span v-for="(sieges, nom) in explicationsSimules.quotient.attributions" :key="'quot_'+nom" class="badge bg-light text-dark border">
                                    Liste {{ getLettreByNom(nom) }} : <strong>{{ sieges }} siège(s)</strong>
                                </span>
                            </div>
                        </div>
                        <div v-if="explicationsSimules.restes.sieges_restants > 0">
                            <h6 class="fw-bold text-dark"><i class="bi bi-bar-chart-steps me-2"></i>B. La Plus Forte Moyenne</h6>
                            <p class="small text-muted mb-2">
                                Il restait <strong>{{ explicationsSimules.restes.sieges_restants }} siège(s)</strong> non pourvu(s). Ils sont attribués un par un à la liste ayant la moyenne la plus élevée <em>(Score / (Sièges déjà obtenus + 1))</em>.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <span v-for="(sieges, nom) in explicationsSimules.restes.attributions" :key="'reste_'+nom" class="badge bg-secondary text-white">
                                    Liste {{ getLettreByNom(nom) }} : <strong>+{{ sieges }}</strong>
                                </span>
                            </div>
                        </div>
                        <div v-else>
                            <h6 class="fw-bold text-dark"><i class="bi bi-check-circle me-2"></i>B. La Plus Forte Moyenne</h6>
                            <p class="small text-muted mb-0">Le quotient électoral est tombé juste, aucun reste à répartir.</p>
                        </div>
                    </div>
                </div>

                <!-- BLOC 2 : LES PRIMES -->
                <div class="col-md-6">
                    <div class="glass-card p-4 h-100 border-primary" style="border-width: 2px; background: rgba(248, 250, 252, 0.8);">
                        <div class="d-flex align-items-center mb-4 border-bottom pb-3 border-primary border-opacity-25">
                            <div class="bg-primary text-white rounded px-3 py-1 fw-bold fs-5 shadow-sm me-3">Étape 2</div>
                            <h5 class="mb-0 fw-bold text-primary">Les Primes de Gouvernabilité</h5>
                        </div>
                        <p class="small text-muted mb-4">
                            Une fois la proportionnelle distribuée, on attribue les sièges de prime selon l'issue de l'élection (au {{ donnees.elu1erTour ? '1er' : '2nd' }} tour).
                        </p>
                        <div class="mb-4 p-3 bg-white rounded border border-success border-opacity-25 shadow-sm">
                            <h6 class="fw-bold text-success"><i class="bi bi-award-fill me-2"></i>Prime Majoritaire ({{ explicationsSimules.primes.txt_maj }})</h6>
                            <p class="small text-muted border-start border-success ms-1 ps-2 mb-2">
                                Réservée au vainqueur de l'élection pour garantir une majorité stable permettant la gestion de la commune.
                            </p>
                            <div class="fs-6">
                                La <strong>Liste {{ getLettreByNom(explicationsSimules.distribution_primes.vainqueur.nom) }}</strong> 
                                reçoit d'office <strong class="text-success">+{{ explicationsSimules.distribution_primes.vainqueur.sieges }} sièges</strong>.
                            </div>
                        </div>
                        <div v-if="explicationsSimules.distribution_primes.perdant" class="p-3 bg-white rounded border border-info border-opacity-25 shadow-sm">
                            <h6 class="fw-bold text-info-emphasis"><i class="bi bi-shield-fill-check me-2"></i>Prime Minoritaire ({{ explicationsSimules.primes.txt_min }})</h6>
                            <p class="small text-muted border-start border-info ms-1 ps-2 mb-2">
                                Réservée au vaincu du duel final. Elle consacre le statut de Chef de l'Opposition.
                            </p>
                            <div class="fs-6">
                                La <strong>Liste {{ getLettreByNom(explicationsSimules.distribution_primes.perdant.nom) }}</strong> 
                                reçoit d'office <strong class="text-info-emphasis">+{{ explicationsSimules.distribution_primes.perdant.sieges }} sièges</strong>.
                            </div>
                        </div>
                        <div v-else class="p-3 bg-light rounded border border-secondary border-opacity-25" style="border-style: dashed !important;">
                            <h6 class="fw-bold text-muted"><i class="bi bi-dash-circle me-2"></i>Pas de Prime Minoritaire</h6>
                            <p class="small text-muted mb-0">
                                L'élection ayant été remportée dès le 1er tour avec plus de 50% des voix, il n'y a pas de duel ni de finaliste. La prime minoritaire n'est donc pas attribuée. 
                                <strong>Ses sièges ont été reversés d'office à la part proportionnelle.</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const BASE_URL = <?= json_encode(BASE_URL,    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const DATA_PHP = <?= json_encode($donneesVue, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= BASE_URL ?>app/views/simulateur_ville.js"></script>