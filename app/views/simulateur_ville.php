<style>
@keyframes shimmer {
    0%   { background-position: -600px 0; }
    100% { background-position:  600px 0; }
}
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
    background-size: 600px 100%;
    animation: shimmer 1.4s infinite linear;
    border-radius: 6px;
    display: block;
}
</style>

<div class="container mt-5 pt-4" id="app-ville">

    <!-- ══════════════════════════════════════════════
         EN-TÊTE
    ══════════════════════════════════════════════ -->
    <div class="row fade-in-up mb-4">
        <div class="col-12 text-center">

            <!-- Squelette -->
            <template v-if="chargement">
                <div class="skeleton mx-auto mb-2" style="height:28px;width:160px;border-radius:20px;"></div>
                <div class="skeleton mx-auto mb-2" style="height:52px;width:340px;border-radius:12px;"></div>
                <div class="skeleton mx-auto" style="height:22px;width:220px;"></div>
            </template>

            <!-- Erreur -->
            <template v-else-if="erreur">
                <div class="alert alert-danger mt-4 rounded-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ erreur }}
                </div>
            </template>

            <!-- Données réelles -->
            <template v-else>
                <span class="badge bg-primary bg-opacity-10 text-primary mb-2 rounded-pill px-3 py-2 fw-semibold">
                    <i class="bi bi-geo-alt-fill me-1"></i> Code INSEE : {{ donnees.code_insee }}
                </span>
                <h1 class="display-4 fw-bold mb-2">{{ donnees.commune }}</h1>
                <p class="lead text-muted">
                    {{ donnees.sieges }} sièges au conseil municipal
                    <span v-if="donnees.isPLM" class="badge bg-secondary ms-2">Mode Métropole (PLM)</span>
                </p>
            </template>

        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         RÉCAPITULATIF 1ER TOUR
    ══════════════════════════════════════════════ -->
    <div class="row fade-in-up mb-5">
        <div class="col-12">
            <div class="glass-card p-4 shadow-sm border-0 bg-white bg-opacity-50">
                <h5 class="mb-4 fw-bold text-center"><i class="bi bi-card-list me-2"></i>Résultats du 1er tour</h5>

                <!-- Squelettes -->
                <div v-if="chargement" class="d-flex flex-wrap justify-content-center gap-3">
                    <div v-for="n in 3" :key="'sk1t_'+n"
                         class="bg-white p-3 rounded-4 shadow-sm border"
                         style="min-width:300px;flex:1;max-width:350px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="skeleton flex-shrink-0" style="width:45px;height:45px;border-radius:50%;"></div>
                            <div class="flex-grow-1">
                                <div class="skeleton mb-2" style="height:16px;width:75%;"></div>
                                <div class="skeleton mb-2" style="height:12px;width:55%;"></div>
                                <div class="d-flex justify-content-between">
                                    <div class="skeleton" style="height:20px;width:50px;border-radius:20px;"></div>
                                    <div class="skeleton" style="height:16px;width:60px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Données réelles -->
                <div v-else-if="!erreur" class="d-flex flex-wrap justify-content-center gap-3">
                    <div v-for="(liste, index) in listesInitialesFiltrees" :key="liste.id"
                         class="d-flex align-items-center bg-white p-3 rounded-4 shadow-sm border border-light"
                         style="min-width: 300px; flex: 1; max-width: 350px;">
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
                                <span v-if="liste.nuance" class="badge px-2 py-1 border" :style="getNuanceStyle(liste.nuance)">
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

    <!-- ══════════════════════════════════════════════
         HÉMICYCLES
    ══════════════════════════════════════════════ -->
    <div class="row g-4 fade-in-up mb-5">

        <!-- SYSTÈME ACTUEL -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100 border-0 shadow-sm d-flex flex-column" style="background: rgba(255,255,255,0.6);">
                <div class="text-center mb-3">
                    <h5 class="mb-1 fw-bold text-dark">Répartition actuelle</h5>
                    <p class="small text-muted mb-0">Selon le système en vigueur</p>
                </div>

                <!-- Canvas / squelette -->
                <div class="position-relative mx-auto mb-3" style="height: 180px; width: 100%; max-width: 250px;">
                    <div v-if="chargement" class="skeleton w-100 h-100" style="border-radius:50%;"></div>
                    <canvas v-else id="chartActuel"></canvas>
                </div>

                <!-- Légende -->
                <div class="flex-grow-1">
                    <template v-if="chargement">
                        <div v-for="n in 3" :key="'skla_'+n" class="d-flex align-items-center mb-2 p-2">
                            <div class="skeleton me-3 flex-shrink-0" style="width:14px;height:14px;border-radius:50%;"></div>
                            <div class="skeleton flex-grow-1" style="height:14px;"></div>
                            <div class="skeleton ms-2 flex-shrink-0" style="width:30px;height:22px;border-radius:12px;"></div>
                        </div>
                    </template>
                    <template v-else-if="!erreur">
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
                    </template>
                </div>
            </div>
        </div>

        <!-- AVEC LA RÉFORME -->
        <div class="col-lg-8">
            <div class="glass-card p-4 h-100 border-primary bg-white shadow-sm" style="border-width: 2px;">
                <h4 class="mb-1 text-primary text-center fw-bold"><i class="bi bi-magic me-2"></i>Avec la proposition de réforme</h4>

                <!-- Squelette -->
                <template v-if="chargement">
                    <div class="skeleton mx-auto mt-3 mb-4" style="height:16px;width:70%;"></div>
                    <div class="row align-items-center mt-3">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <div class="skeleton mx-auto" style="height:220px;max-width:320px;border-radius:50%;"></div>
                        </div>
                        <div class="col-md-7">
                            <div v-for="n in 3" :key="'sklr_'+n" class="skeleton mb-2" style="height:56px;border-radius:10px;"></div>
                        </div>
                    </div>
                </template>

                <!-- Données réelles -->
                <template v-else-if="!erreur">
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
                </template>

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         TOUT LE RESTE : masqué pendant le chargement
    ══════════════════════════════════════════════ -->
    <template v-if="!chargement && !erreur">

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
                                                 :title="liste.nom" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
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

        <!-- Explication pédagogique — Timeline verticale -->
        <div class="row fade-in-up mt-2 mb-5">
            <div class="col-12">

                <div class="text-center mb-5">
                    <h4 class="fw-bold mb-1"><i class="bi bi-mortarboard text-primary me-2"></i>Comment les sièges ont-ils été attribués ?</h4>
                    <p class="text-muted small mb-0">Trois mécanismes distincts, appliqués successivement sur des assiettes différentes.</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <!-- ÉTAPE 1 : PROPORTIONNELLE -->
                        <div class="d-flex gap-4 mb-0">
                            <div class="d-flex flex-column align-items-center flex-shrink-0" style="width:48px;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold shadow" style="width:48px;height:48px;font-size:1rem;z-index:1;">1</div>
                                <div style="width:2px;flex-grow:1;background:linear-gradient(to bottom,#6366f1,#6366f155);min-height:20px;"></div>
                            </div>
                            <div class="flex-grow-1 pb-4">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h5 class="fw-bold text-primary mb-0">La répartition proportionnelle</h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ explicationsSimules.primes.part_prop }} sièges — {{ donnees.isPLM && estEluDesLe1erTour ? '70' : (donnees.isPLM ? '60' : (estEluDesLe1erTour ? '60' : '50')) }}% du conseil</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-database-fill text-primary opacity-50" style="font-size:0.8rem;"></i>
                                    <span class="text-muted" style="font-size:0.82rem;"><strong class="text-dark">Assiette :</strong> résultats du 1<sup>er</sup> tour — toutes les listes ayant obtenu ≥ 5%
                                        <span v-if="explicationsSimules.seuil.eliminees.length > 0" class="text-danger ms-1">
                                            ({{ explicationsSimules.seuil.eliminees.map(n => 'Liste '+getLettreByNom(n)).join(', ') }} éliminée(s))
                                        </span>
                                    </span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 h-100" style="background:#f0f4ff; border:1px solid #c7d2fe;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="badge bg-primary rounded-pill" style="font-size:0.7rem;">A</span>
                                                <span class="fw-bold text-dark" style="font-size:0.88rem;"><i class="bi bi-calculator me-1 text-primary"></i>Quotient Électoral</span>
                                            </div>
                                            <p class="text-muted mb-3" style="font-size:0.78rem;"><em>Suffrages utiles ÷ sièges à répartir.</em> Chaque liste reçoit autant de sièges qu'elle a atteint ce quotient.</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div v-for="(sieges, nom) in explicationsSimules.quotient.attributions" :key="'qa_'+nom"
                                                    class="d-flex align-items-center gap-2 px-2 py-1 rounded-3 bg-white shadow-sm">
                                                    <span class="badge rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                                        :style="{backgroundColor: getCouleurByNom(nom), width:'24px', height:'24px', fontSize:'0.7rem'}">{{ getLettreByNom(nom) }}</span>
                                                    <span style="font-size:0.82rem;">→ <strong>{{ sieges }}</strong> siège(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 h-100" :style="explicationsSimules.restes.sieges_restants > 0 ? 'background:#f0fff4;border:1px solid #bbf7d0;' : 'background:#f8fafc;border:1px solid #e2e8f0;'">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="badge rounded-pill" :class="explicationsSimules.restes.sieges_restants > 0 ? 'bg-success' : 'bg-secondary'" style="font-size:0.7rem;">B</span>
                                                <span class="fw-bold text-dark" style="font-size:0.88rem;">
                                                    <i class="bi me-1" :class="explicationsSimules.restes.sieges_restants > 0 ? 'bi-bar-chart-steps text-success' : 'bi-check-circle text-secondary'"></i>
                                                    Plus Forte Moyenne
                                                </span>
                                            </div>
                                            <div v-if="explicationsSimules.restes.sieges_restants > 0">
                                                <p class="text-muted mb-3" style="font-size:0.78rem;"><strong>{{ explicationsSimules.restes.sieges_restants }} siège(s)</strong> restant(s), attribués un par un à la liste avec la moyenne la plus haute <em>(Score ÷ (Sièges+1))</em>.</p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <div v-for="(sieges, nom) in explicationsSimules.restes.attributions" :key="'ra_'+nom"
                                                        class="d-flex align-items-center gap-2 px-2 py-1 rounded-3 bg-white shadow-sm">
                                                        <span class="badge rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                                            :style="{backgroundColor: getCouleurByNom(nom), width:'24px', height:'24px', fontSize:'0.7rem'}">{{ getLettreByNom(nom) }}</span>
                                                        <span class="text-success fw-bold" style="font-size:0.82rem;">+{{ sieges }} siège(s)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p v-else class="text-muted mb-0" style="font-size:0.78rem;">Le quotient est tombé juste — aucun reste à répartir.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ÉTAPE 2 : PRIME MAJORITAIRE -->
                        <div class="d-flex gap-4 mb-0">
                            <div class="d-flex flex-column align-items-center flex-shrink-0" style="width:48px;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white fw-bold shadow" style="width:48px;height:48px;font-size:1rem;z-index:1;">2</div>
                                <div style="width:2px;flex-grow:1;background:linear-gradient(to bottom,#22c55e,#22c55e55);min-height:20px;"></div>
                            </div>
                            <div class="flex-grow-1 pb-4">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-trophy-fill me-2"></i>Prime Majoritaire</h5>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ explicationsSimules.primes.txt_maj }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-database-fill text-success opacity-50" style="font-size:0.8rem;"></i>
                                    <span class="text-muted" style="font-size:0.82rem;">
                                        <strong class="text-dark">Assiette :</strong>
                                        <span v-if="estEluDesLe1erTour"> vainqueur du 1<sup>er</sup> tour (majorité absolue obtenue)</span>
                                        <span v-else> vainqueur du duel au 2<sup>nd</sup> tour</span>
                                    </span>
                                </div>
                                <div class="p-3 rounded-4 d-inline-flex align-items-center gap-3 shadow-sm"
                                    :style="{background: getCouleurByNom(explicationsSimules.distribution_primes.vainqueur.nom)+'18', border: '1.5px solid '+getCouleurByNom(explicationsSimules.distribution_primes.vainqueur.nom)+'55'}">
                                    <span class="badge rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                        :style="{backgroundColor: getCouleurByNom(explicationsSimules.distribution_primes.vainqueur.nom), width:'38px', height:'38px', fontSize:'0.9rem'}">
                                        {{ getLettreByNom(explicationsSimules.distribution_primes.vainqueur.nom) }}
                                    </span>
                                    <div class="lh-sm">
                                        <div class="fw-bold text-dark" style="font-size:0.88rem;">Liste {{ getLettreByNom(explicationsSimules.distribution_primes.vainqueur.nom) }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;" :title="explicationsSimules.distribution_primes.vainqueur.nom">{{ explicationsSimules.distribution_primes.vainqueur.nom }}</div>
                                    </div>
                                    <span class="badge bg-success text-white fs-5 ms-2 shadow-sm">+{{ explicationsSimules.distribution_primes.vainqueur.sieges }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ÉTAPE 3 : PRIME MINORITAIRE -->
                        <div class="d-flex gap-4 mb-0">
                            <div class="d-flex flex-column align-items-center flex-shrink-0" style="width:48px;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow"
                                    :class="explicationsSimules.distribution_primes.perdant ? 'bg-info' : 'bg-secondary bg-opacity-50'"
                                    style="width:48px;height:48px;font-size:1rem;z-index:1;">3</div>
                                <div style="width:2px;flex-grow:1;min-height:20px;"
                                    :style="explicationsSimules.distribution_primes.perdant ? 'background:linear-gradient(to bottom,#06b6d4,#06b6d455)' : 'background:#e2e8f0'"></div>
                            </div>
                            <div class="flex-grow-1 pb-4">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h5 class="fw-bold mb-0" :class="explicationsSimules.distribution_primes.perdant ? 'text-info-emphasis' : 'text-muted'">
                                        <i class="bi bi-shield-fill-check me-2"></i>Prime Minoritaire
                                    </h5>
                                    <span class="badge border" :class="explicationsSimules.distribution_primes.perdant ? 'bg-info bg-opacity-10 text-info-emphasis border-info border-opacity-25' : 'bg-light text-muted border-secondary border-opacity-25'">
                                        {{ explicationsSimules.primes.txt_min }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-database-fill opacity-50" :class="explicationsSimules.distribution_primes.perdant ? 'text-info' : 'text-secondary'" style="font-size:0.8rem;"></i>
                                    <span class="text-muted" style="font-size:0.82rem;">
                                        <strong class="text-dark">Assiette :</strong>
                                        <span v-if="explicationsSimules.distribution_primes.perdant"> perdant du duel au 2<sup>nd</sup> tour</span>
                                        <span v-else class="text-danger"> aucune — élection au 1<sup>er</sup> tour, pas de duel, pas de perdant à récompenser</span>
                                    </span>
                                </div>
                                <div v-if="explicationsSimules.distribution_primes.perdant"
                                    class="p-3 rounded-4 d-inline-flex align-items-center gap-3 shadow-sm"
                                    :style="{background: getCouleurByNom(explicationsSimules.distribution_primes.perdant.nom)+'18', border: '1.5px solid '+getCouleurByNom(explicationsSimules.distribution_primes.perdant.nom)+'55'}">
                                    <span class="badge rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                        :style="{backgroundColor: getCouleurByNom(explicationsSimules.distribution_primes.perdant.nom), width:'38px', height:'38px', fontSize:'0.9rem'}">
                                        {{ getLettreByNom(explicationsSimules.distribution_primes.perdant.nom) }}
                                    </span>
                                    <div class="lh-sm">
                                        <div class="fw-bold text-dark" style="font-size:0.88rem;">Liste {{ getLettreByNom(explicationsSimules.distribution_primes.perdant.nom) }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;" :title="explicationsSimules.distribution_primes.perdant.nom">{{ explicationsSimules.distribution_primes.perdant.nom }}</div>
                                    </div>
                                    <span class="badge bg-info text-white fs-5 ms-2 shadow-sm">+{{ explicationsSimules.distribution_primes.perdant.sieges }}</span>
                                </div>
                                <div v-else class="p-3 rounded-4 d-inline-flex align-items-center gap-3" style="background:#f8fafc;border:1.5px dashed #94a3b8;">
                                    <i class="bi bi-arrow-left-right text-primary opacity-50 fs-4"></i>
                                    <p class="text-muted mb-0" style="font-size:0.82rem;">Ses <strong>{{ explicationsSimules.primes.part_prop - (donnees.isPLM ? Math.round(donnees.sieges*0.6) : Math.round(donnees.sieges*0.5)) }}</strong> sièges ont été reversés dans la part proportionnelle (étape 1), augmentant la représentativité globale.</p>
                                </div>
                            </div>
                        </div>

                        <!-- RÉSULTAT FINAL -->
                        <div class="d-flex gap-4">
                            <div class="d-flex flex-column align-items-center flex-shrink-0" style="width:48px;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white shadow" style="width:48px;height:48px;font-size:1.1rem;z-index:1;">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-success mb-3">Résultat final</h5>
                                <div class="d-flex flex-wrap gap-3">
                                    <div v-for="liste in listesReformeLegende" :key="'fin_'+liste.nom"
                                        class="d-flex align-items-center gap-3 p-3 rounded-4 flex-fill"
                                        style="min-width:200px; max-width:300px;"
                                        :style="{background: getCouleurByNom(liste.nom)+'15', border: '1.5px solid '+getCouleurByNom(liste.nom)+'55'}">
                                        <span class="badge rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                            :style="{backgroundColor: getCouleurByNom(liste.nom), width:'36px', height:'36px', fontSize:'0.9rem'}">
                                            {{ getLettreByNom(liste.nom) }}
                                        </span>
                                        <div class="flex-grow-1 lh-sm overflow-hidden">
                                            <div class="fw-bold text-truncate text-dark" style="font-size:0.85rem;" :title="liste.nom">{{ liste.nom }}</div>
                                            <div class="mt-1 d-flex flex-wrap gap-1" style="font-size:0.72rem;">
                                                <span v-if="liste.siegesprop > 0" class="text-muted"><i class="bi bi-pie-chart me-1"></i>{{ liste.siegesprop }}</span>
                                                <span v-if="liste.siegesprime > 0" class="text-success fw-bold"><i class="bi bi-trophy-fill me-1"></i>+{{ liste.siegesprime }}</span>
                                                <span v-if="liste.siegesmin > 0" class="text-info-emphasis fw-bold"><i class="bi bi-shield-fill-check me-1"></i>+{{ liste.siegesmin }}</span>
                                            </div>
                                        </div>
                                        <span class="badge fs-5 shadow-sm text-white flex-shrink-0"
                                            :style="{backgroundColor: getCouleurByNom(liste.nom)}">
                                            {{ liste.totalsieges }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </template>
    <!-- fin v-if="!chargement && !erreur" -->

</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const BASE_URL   = <?= json_encode(BASE_URL,   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const CODE_INSEE = <?= json_encode($codeInsee ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= BASE_URL ?>app/views/simulateur_ville.js"></script>