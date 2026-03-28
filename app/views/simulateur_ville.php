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
                        
                        <!-- Pastille de la liste -->
                        <span class="badge rounded-circle d-flex align-items-center justify-content-center fs-5 me-3 shadow-sm text-white" 
                              :style="{backgroundColor: getCouleurByNom(liste.nom), width: '45px', height: '45px', flexShrink: 0}">
                            {{ getLettreByNom(liste.nom) }}
                        </span>
                        
                        <div class="lh-sm flex-grow-1 overflow-hidden">
                            <!-- Nom du Candidat (Tête de liste) en grand -->
                            <div class="fw-bold fs-6 text-truncate mb-1 text-dark" :title="liste.candidat">
                                {{ liste.candidat || 'Candidat inconnu' }}
                            </div>
                            
                            <!-- Nom de la liste en plus petit -->
                            <div class="text-muted small text-truncate mb-2" :title="liste.nom" style="font-size: 0.8rem;">
                                {{ liste.nom }}
                            </div>
                            
                            <!-- Bloc du bas avec Nuance (colorée) et Score -->
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
                
                <!-- Légende Actuelle : Noms, Vainqueur et Regroupement -->
                <div class="flex-grow-1">
                    <div v-for="(liste, index) in listesActuellesLegende" :key="'leg_'+index" 
                         class="d-flex align-items-center mb-2 p-2 rounded-3 shadow-sm transition-all"
                         :class="{
                            'bg-white border border-warning border-2': index === 0 && liste.sieges_reel > 0, 
                            'bg-white': index !== 0 && liste.id !== 'autres',
                            'bg-light opacity-75': liste.id === 'autres'
                         }">
                        
                        <!-- Couleur de la liste -->
                        <span class="d-inline-block rounded-circle me-3 flex-shrink-0" 
                              :style="{width:'14px', height:'14px', backgroundColor: liste.couleur}"></span>
                        
                        <!-- Nom et Candidat -->
                        <div class="flex-grow-1 text-truncate lh-sm">
                            <span class="fw-bold fs-6" :class="liste.id === 'autres' ? 'text-muted' : 'text-dark'" :title="liste.nom">{{ liste.nom }}</span>
                            <!-- Optionnel : Afficher le nom du candidat si ce n'est pas le groupe "Autres" -->
                            <div v-if="liste.candidat && liste.id !== 'autres'" class="text-muted" style="font-size: 0.75rem;">
                                {{ liste.candidat }}
                            </div>
                        </div>
                        
                        <!-- Nombre de sièges -->
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
                
                <!-- Sous-titre dynamique : s'adapte via la computed estEluDesLe1erTour -->
                <p class="small text-muted mb-4 text-center px-2">
                    <span v-if="estEluDesLe1erTour">
                        Proportionnelle ({{ donnees.isPLM ? 70 : 60 }}%) et prime majoritaire ({{ donnees.isPLM ? 30 : 40 }}%) au vainqueur.
                    </span>
                    <span v-else>
                        Proportionnelle ({{ donnees.isPLM ? 60 : 50 }}%) sur la base des résultats du 1er tour, primes majoritaire ({{ donnees.isPLM ? 30 : 40 }}%) et minoritaire (10%).
                    </span>
                </p>
                
                <div class="row align-items-center mt-3">
                    <!-- Hémicycle à gauche -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="position-relative mx-auto" style="height: 220px; width: 100%; max-width: 320px;">
                            <canvas id="chartReforme"></canvas>
                        </div>
                    </div>
                    
                    <!-- Détail des sièges à droite -->
                    <div class="col-md-7">
                        <h6 class="text-muted mb-3 fw-bold border-bottom pb-2">Répartition détaillée des sièges</h6>
                        
                        <div class="row g-2">
                            <div v-for="(liste, index) in listesCompleteReforme" :key="'ref_dyn_'+index" class="col-12">
                                
                                <div v-if="(liste.totalsieges > 0) || (liste.sieges > 0)" 
                                     class="d-flex align-items-center p-2 bg-light rounded-3 border-start border-4 shadow-sm"
                                     :style="{borderLeftColor: getCouleurByNom(liste.nom) + ' !important'}">
                                    
                                    <div class="flex-grow-1 ps-2 lh-sm overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate" :title="liste.nom">{{ liste.nom }}</div>
                                        
                                        <!-- Décomposition -->
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
                    
                    <!-- EN-TÊTE PÉDAGOGIQUE (S'adapte selon le 1er ou 2nd tour) -->
                    <div class="bg-primary bg-opacity-10 p-4 border-bottom border-primary border-opacity-25">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-book-half text-primary fs-3 me-3"></i>
                            <h4 class="fw-bold text-primary mb-0">
                                Comprendre la réforme : {{ estEluDesLe1erTour ? "L'élection au 1er tour" : "Le nouveau 2nd tour" }}
                            </h4>
                        </div>
                        
                        <!-- Explications si victoire au 1er tour -->
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

                        <!-- Explications si duel au 2nd tour -->
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
                    
                    <!-- ZONE INTERACTIVE (Boutons ou validation) -->
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
                                    <!-- Input caché -->
                                    <input type="radio" class="btn-check" name="vainqueur_duel" :id="'btn_duel_' + liste.id" :value="liste.id" v-model="vainqueurSimuleId" @change="changementVainqueurEtScroll">
                                    
                                    <!-- Label stylisé en bouton -->
                                    <label class="btn btn-outline-primary w-100 h-100 d-flex align-items-center p-3 m-0 border-2 text-start" :for="'btn_duel_' + liste.id" style="border-radius: 12px; transition: all 0.2s;">
                                        <!-- Pastille lettre (gauche) -->
                                        <span class="badge rounded-circle me-3 shadow-sm flex-shrink-0 d-flex align-items-center justify-content-center" 
                                              :style="{backgroundColor: getCouleurByNom(liste.nom), width: '40px', height: '40px', fontSize: '1.2rem', color: '#fff', border: '2px solid rgba(255,255,255,0.8)'}">
                                            {{ getLettreByNom(liste.nom) }}
                                        </span>
                                        
                                        <!-- Contenu texte (droite) sans overflow-hidden pour ne pas couper les badges -->
                                        <div class="flex-grow-1 lh-sm">
                                            <div class="d-flex flex-wrap align-items-center mb-1 gap-2">
                                                <!-- Candidat Tête de liste avec changement de couleur dynamique -->
                                                <span class="fw-bold fs-6 text-truncate transition-colors"
                                                      :class="liste.id === vainqueurSimuleId ? 'text-white' : 'text-dark'" 
                                                      :title="liste.candidat" style="max-width: 60%;">
                                                    {{ liste.candidat || 'Candidat' }}
                                                </span>
                                                
                                                <!-- Nuance identique au 1er tour -->
                                                <span v-if="liste.nuance" class="badge px-2 py-1 border" :style="getNuanceStyle(liste.nuance)">
                                                    {{ liste.nuance }}
                                                </span>
                                                <span v-else class="badge bg-light text-dark border">NC</span>
                                                
                                                <!-- Trophée si vrai vainqueur -->
                                                <span v-if="estVraiVainqueur2026(liste.nom)" class="badge bg-warning text-dark border border-warning ms-2 d-flex align-items-center" title="Cette liste a remporté les élections de 2026" style="font-size: 0.75rem;">
                                                    <i class="bi bi-trophy-fill me-1"></i> Élu 2026
                                                </span>
                                            </div>
                                            <!-- Nom de la liste avec changement de couleur dynamique -->
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

                        <!-- Sous-étape A : Quotient Electoral -->
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

                        <!-- Sous-étape B : Plus Forte Moyenne -->
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

                        <!-- Prime Majoritaire (Toujours présente) -->
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

                        <!-- Prime Minoritaire (Présente uniquement s'il y a un duel au 2nd tour) -->
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
                        
                        <!-- Cas de victoire absolue au 1er tour (Pas de duel = Pas de prime minoritaire) -->
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
    // INJECTION DES DONNÉES PHP
    const BASE_URL = '<?= BASE_URL ?>';
    const DATA_PHP = <?= json_encode($donneesVue) ?>;
    
    // Configuration des couleurs
    const COLORS = ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16'];
    
    let chartActuel = null;
    let chartReforme = null;

    // Fonction utilitaire externe pour chercher l'ID avant l'initialisation de Vue
    function extractVainqueurId(donnees) {
        if (!donnees.explications || !donnees.explications.distribution_primes || !donnees.explications.distribution_primes.vainqueur) {
            return null;
        }
        const nomVainqueur = donnees.explications.distribution_primes.vainqueur.nom;
        const liste = donnees.listesInitiales.find(l => l.nom === nomVainqueur);
        return liste ? liste.id : null;
    }

    const { createApp } = Vue;

    createApp({
        data() {
            return {
                donnees: DATA_PHP,
                COLORS: COLORS,
                // Initialisation sécurisée 
                vainqueurSimuleId: extractVainqueurId(DATA_PHP),
                resultatsReformeSimules: DATA_PHP.resultatsReforme,
                explicationsSimules: DATA_PHP.explications
            }
        },
        computed: {
            listesInitialesFiltrees() {
                return this.donnees.listesInitiales.filter(l => l.nom && l.nom.trim() !== '').sort((a, b) => b.score_1er_tour - a.score_1er_tour);
            },
            listesInitialesTrieesActuel() {
                return [...this.listesInitialesFiltrees].sort((a, b) => {
                    if (b.sieges_reel !== a.sieges_reel) return b.sieges_reel - a.sieges_reel;
                    return b.score_1er_tour - a.score_1er_tour;
                });
            },
            finalistes() {
                // S'il y a eu élection au 1er tour, pas de finalistes (tableau vide)
                if (this.donnees.elu1erTour) return [];
                
                // Sinon, on cherche les deux finalistes (le vainqueur et le perdant)
                if (this.explicationsSimules.distribution_primes && this.explicationsSimules.distribution_primes.perdant) {
                    const idVainqueur = this.trouverIdParNom(this.donnees.listesInitiales, this.explicationsSimules.distribution_primes.vainqueur.nom);
                    const idPerdant = this.trouverIdParNom(this.donnees.listesInitiales, this.explicationsSimules.distribution_primes.perdant.nom);
                    return this.listesInitialesFiltrees.filter(l => l.id === idVainqueur || l.id === idPerdant);
                }
                
                // Fallback de sécurité : on prend les deux premiers
                return this.listesInitialesFiltrees.slice(0, 2);
            },
            listesCompleteReforme() {
                if (!this.listesInitialesFiltrees) return [];
                
                let completeList = [];
                
                // 1. Assure qu'on a bien un tableau (évite le crash si PHP renvoie un objet JSON)
                let resultatsReforme = Array.isArray(this.resultatsReformeSimules) 
                    ? this.resultatsReformeSimules 
                    : Object.values(this.resultatsReformeSimules || {});

                this.listesInitialesFiltrees.forEach(liste => {
                    let resultReforme = resultatsReforme.find(r => r.nom === liste.nom);
                    
                    if (resultReforme) {
                        // 2. Normalisation de toutes les orthographes possibles du PHP
                        let total = resultReforme.totalsieges || resultReforme.sieges || resultReforme.total_sieges || 0;
                        let prop = resultReforme.siegesprop || resultReforme.sieges_prop || 0;
                        let prime = resultReforme.siegesprime || resultReforme.sieges_prime || resultReforme.sieges_majo || 0;
                        let min = resultReforme.siegesmin || resultReforme.sieges_min || 0;
                        
                        completeList.push({
                            ...resultReforme,
                            nom: liste.nom,
                            candidat: liste.candidat,
                            nuance: liste.nuance,
                            totalsieges: total,
                            siegesprop: prop,
                            siegesprime: prime,
                            siegesmin: min
                        });
                    } else {
                        completeList.push({
                            nom: liste.nom, candidat: liste.candidat, nuance: liste.nuance,
                            siegesprop: 0, siegesprime: 0, siegesmin: 0, totalsieges: 0
                        });
                    }
                });
                
                // On trie du plus grand nombre de sièges au plus petit
                return completeList.sort((a, b) => b.totalsieges - a.totalsieges);
            },
            analyseTexte() {
                if (!this.listesCompleteReforme || this.listesCompleteReforme.length === 0) return '';
                
                let html = '';
                
                // 1. Détection de l'élection au 1er tour : on se base sur la présence d'une prime minoritaire
                let aEuPrimeMinoritaire = this.explicationsSimules && 
                                        this.explicationsSimules.distribution_primes && 
                                        this.explicationsSimules.distribution_primes.perdant;
                let elu1erTourActif = !aEuPrimeMinoritaire;

                // 2. Identification des acteurs
                let maire = this.listesCompleteReforme[0]; // Vainqueur final
                let totalSiegesConseil = this.donnees.sieges;
                let majoriteAbsolue = Math.floor(totalSiegesConseil / 2) + 1;
                let aMajoriteAbsolue = maire.totalsieges >= majoriteAbsolue;
                
                // 3. Le VRAI premier du 1er tour. Puisque listesInitialesFiltrees est toujours trié par score décroissant.
                let premierDu1erTour = this.listesInitialesFiltrees[0];
                
                // -----------------------------------------------------
                // CAT 1 : LA LISTE GAGNANTE
                // -----------------------------------------------------
                html += `<div class="mb-3">`;
                
                if (this.listesInitialesFiltrees.length === 1) {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> bénéficie de l'intégralité des sièges, puisqu'elle était la seule liste candidate lors de ces élections. Cette réforme n'aurait ici <strong>aucun impact</strong>.`;
                } 
                else if (!aMajoriteAbsolue) {
                    if (this.donnees.isPLM) {
                        html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, la prime n'étant que de 30% dans la commune, son faible score au premier tour ne lui permet pas de disposer de la <strong>majorité absolue</strong> des sièges à la mairie centrale, l'obligeant à former une coalition.`;
                    } else {
                        html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, compte tenu de son faible score au 1er tour, elle ne disposera pas de la <strong>majorité absolue</strong> des sièges au conseil municipal, l'obligeant à former une coalition.`;
                    }
                }
                else {
                    let diffMaire = maire.totalsieges - this.getSiegesReelsByNom(maire.nom);
                    
                    // C'est ICI qu'était l'erreur. On compare strictement le NOM du maire avec le NOM du 1er du T1.
                    if (!elu1erTourActif && premierDu1erTour && premierDu1erTour.nom !== maire.nom) {
                        html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Si elle n'était pas la liste en tête au 1er tour, il semble que les électeurs des listes disqualifiées ont préféré lui confier la gestion de la collectivité, lui permettant de disposer d'une <strong>majorité absolue</strong> de sièges.`;
                    } 
                    else if (diffMaire < 0) {
                        html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, la majorité devient plus juste et moins hégémonique (<strong>perte de ${Math.abs(diffMaire)} sièges</strong> par rapport au système actuel), forçant le débat démocratique.`;
                    } 
                    else {
                        html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Elle dispose d'une <strong>majorité absolue</strong> de sièges.`;
                    }
                }
                html += `</div>`;

                // -----------------------------------------------------
                // CAT 2 : LA LISTE MINORITAIRE
                // -----------------------------------------------------
                if (this.listesInitialesFiltrees.length > 1) {
                    let perdant = this.listesCompleteReforme[1]; 
                    
                    if (perdant) {
                        html += `<div class="mb-3">`;
                        
                        if (elu1erTourActif) {
                            html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong>, bien qu'arrivée en 2ème position, ne bénéficie pas de la prime minoritaire de 10% compte tenu de l'élection dès le 1er tour de la liste gagnante.`;
                        } 
                        else {
                            // Duel au 2nd tour
                            let etaitPremier = premierDu1erTour && (premierDu1erTour.nom === perdant.nom);
                            
                            if (etaitPremier) {
                                html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong>, qui était en tête au 1er tour, n'est pas parvenue à fédérer une majorité d'électeurs. Son score du 1er tour et la <strong>prime minoritaire de 10%</strong> lui permettent de disposer d'un important groupe au conseil municipal.`;
                            } else {
                                html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong> obtient le groupe d'opposition le plus important au conseil municipal grâce à la <strong>prime minoritaire de 10%</strong>.`;
                            }
                        }
                        html += `</div>`;
                    }
                }

                // -----------------------------------------------------
                // CAT 3 : AUTRES ANALYSES
                // -----------------------------------------------------
                let blocAutreHTML = '';

                // Cas de l'élection au 1er tour
                if (elu1erTourActif) {
                    // S'il y avait d'autres listes que le maire
                    if (this.listesInitialesFiltrees.length > 1) {
                        let pourcentagePrime = this.donnees.isPLM ? "30%" : "40%";
                        blocAutreHTML += `<div class="mb-2"><i class="bi bi-people-fill text-info me-2"></i><strong>Meilleure représentativité :</strong> Grâce à l'abaissement de la prime majoritaire à ${pourcentagePrime}, les listes minoritaires sont mieux représentées au conseil municipal.</div>`;
                    }
                } 
                // Cas d'élection au 2nd tour (Duel)
                else {
                    // On identifie le perdant du duel (le 2ème en sièges de la simulation)
                    let perdant = this.listesCompleteReforme[1];
                    let nomPerdant = perdant ? perdant.nom : "";

                    // PHRASE A : Fin du vote utile (concerne le 3ème et suivants qui gagnent des sièges)
                    let listesGagnantes = this.listesCompleteReforme.filter(l => 
                        l.nom !== maire.nom && 
                        l.nom !== nomPerdant && // EXCLUSION DU 2ème
                        this.getSiegesReelsByNom(l.nom) > 0 && 
                        l.totalsieges > this.getSiegesReelsByNom(l.nom)
                    );

                    if (listesGagnantes.length > 0) {
                        let nomsGagnants = listesGagnantes.map(s => `<strong>${this.getLettreByNom(s.nom)} (${s.nom})</strong>`).join(" et ");
                        let textePluriel = listesGagnantes.length > 1 ? "Les listes" : "La liste";
                        let verbePluriel = listesGagnantes.length > 1 ? "bénéficient" : "bénéficie";
                        
                        blocAutreHTML += `<div class="mb-2"><i class="bi bi-arrow-up-circle-fill text-success me-2"></i><strong>Fin du vote utile :</strong> ${textePluriel} ${nomsGagnants} ${verbePluriel} de sièges supplémentaires grâce à la prise en compte du score réalisé au 1er tour pour l'attribution de la part proportionnelle.</div>`;
                    }

                    // PHRASE B : Listes repêchées (0 siège en réel, >0 en simulé)
                    let sauvees = this.listesCompleteReforme.filter(l => 
                        l.nom !== maire.nom &&
                        l.totalsieges > 0 && 
                        this.getSiegesReelsByNom(l.nom) === 0
                    );

                    if (sauvees.length > 0) {
                        let nomsSauves = sauvees.map(s => `<strong>${this.getLettreByNom(s.nom)} (${s.nom})</strong>`).join(" et ");
                        let textePluriel = sauvees.length > 1 ? "Les listes" : "La liste";
                        let verbeEtre = sauvees.length > 1 ? "étaient totalement effacées" : "était totalement effacée";
                        let verbeEntrer = sauvees.length > 1 ? "entrent" : "entre";
                        
                        blocAutreHTML += `<div><i class="bi bi-door-open-fill text-primary me-2"></i>${textePluriel} ${nomsSauves} qui ${verbeEtre} dans le système actuel, soit par retrait, fusion, ou non qualification au 2nd tour, ${verbeEntrer} au conseil municipal grâce au figeage de la proportionnelle au 1er tour.</div>`;
                    }
                }

                // S'il n'y a aucune analyse supplémentaire, ce bloc reste vide et ne s'affiche pas
                if (blocAutreHTML !== '') {
                    html += `<div class="mt-3 p-3 bg-light border rounded shadow-sm" style="font-size: 0.9rem;">${blocAutreHTML}</div>`;
                }

                return html;
            },
            estEluDesLe1erTour() {
                // Si la clé "elu1erTour" est explicitement à true ou 1 depuis le PHP
                if (this.donnees.elu1erTour === true || this.donnees.elu1erTour === 1 || this.donnees.elu1erTour === "1") {
                    return true;
                }
                
                // Si l'algorithme PHP a distribué les primes mais qu'il n'y a PAS de perdant (prime minoritaire absente)
                // Cela signifie de façon absolue que l'élection a été gagnée au 1er tour
                if (this.explicationsSimules && 
                    this.explicationsSimules.distribution_primes && 
                    !this.explicationsSimules.distribution_primes.perdant) {
                    return true;
                }
                
                return false;
            },

            // 2. Légende du système actuel (Regroupe les perdants)
            listesActuellesLegende() {
                if (!this.listesInitialesTrieesActuel) return [];
                
                let listesAffichees = [];
                let listesSansElu = 0;
                
                this.listesInitialesTrieesActuel.forEach(liste => {
                    if (liste.sieges_reel > 0) {
                        // On garde les listes ayant des élus, en ajoutant la couleur précalculée
                        listesAffichees.push({
                            ...liste,
                            couleur: this.getCouleurByNom(liste.nom)
                        });
                    } else {
                        listesSansElu++;
                    }
                });
                
                // On trie du plus grand nombre de sièges au plus petit
                listesAffichees.sort((a, b) => b.sieges_reel - a.sieges_reel);
                
                // S'il y a des listes à 0 siège, on les groupe
                if (listesSansElu > 0) {
                    listesAffichees.push({
                        id: 'autres',
                        nom: listesSansElu > 1 ? `${listesSansElu} listes n'ayant obtenu aucun siège` : `1 liste n'ayant obtenu aucun siège`,
                        candidat: '',
                        sieges_reel: 0,
                        couleur: '#e9ecef' // Gris neutre
                    });
                }
                
                return listesAffichees;
            },

            // 3. Légende du système réformé (Garde uniquement les listes ayant des sièges dans la simulation)
            listesReformeLegende() {
                if (!this.listesCompleteReforme) return [];
                
                // Filtre pour ne garder que ceux qui ont au moins 1 siège dans la simulation
                let listesAvecSieges = this.listesCompleteReforme.filter(liste => liste.totalsieges > 0);
                
                // Tri décroissant par nombre de sièges (le vainqueur en premier)
                return listesAvecSieges.sort((a, b) => b.totalsieges - a.totalsieges);
            },
        },
        methods: {
            trouverIdParNom(listes, nom) {
                const liste = listes.find(l => l.nom === nom);
                return liste ? liste.id : null;
            },
            getLettre(index) { return String.fromCharCode(65 + index); },
            getLettreByNom(nom) {
                const index = this.listesInitialesFiltrees.findIndex(l => l.nom === nom);
                return index !== -1 ? this.getLettre(index) : '?';
            },
            getLettreById(id) {
                const index = this.listesInitialesFiltrees.findIndex(l => l.id === id);
                return index !== -1 ? this.getLettre(index) : '?';
            },
            getCouleurByNom(nom) {
                const index = this.listesInitialesFiltrees.findIndex(l => l.nom === nom);
                return index !== -1 ? COLORS[index % COLORS.length] : '#ccc';
            },
            getSiegesReelsByNom(nom) {
                const liste = this.donnees.listesInitiales.find(l => l.nom === nom);
                return liste ? liste.sieges_reel : 0;
            },
            getCandidatByNom(nom) {
                const liste = this.donnees.listesInitiales.find(l => l.nom === nom);
                return liste && liste.candidat ? liste.candidat : '-';
            },
            getNuanceByNom(nom) {
                const liste = this.donnees.listesInitiales.find(l => l.nom === nom);
                return liste && liste.nuance ? liste.nuance : 'N/C';
            },
            formatDiff(diff) {
                if (diff > 0) return '+' + diff;
                if (diff < 0) return diff;
                return '=';
            },
            getDiffColorClass(diff) {
                if (diff > 0) return 'text-success bg-success bg-opacity-10';
                if (diff < 0) return 'text-danger bg-danger bg-opacity-10';
                return 'text-muted';
            },
            async recalculerReforme() {
                let finalisteMedaileArgent = this.finalistes.find(f => f.id !== this.vainqueurSimuleId);
                let runnerUpId = finalisteMedaileArgent ? finalisteMedaileArgent.id : null;
                
                let listesPourPhp = {};
                this.donnees.listesInitiales.forEach(l => { 
                    listesPourPhp[l.id] = {
                        id: l.id,
                        nom: l.nom,
                        score_1er_tour: l.score_1er_tour,
                        voix: l.voix
                    }; 
                });

                const payload = {
                    sieges: this.donnees.sieges,
                    isPLM: this.donnees.isPLM,
                    listes: listesPourPhp,
                    winner_2nd_tour: this.vainqueurSimuleId,
                    runner_up_2nd_tour: runnerUpId
                };

                try {
                    const response = await fetch(BASE_URL + 'simulateur/calculer', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    
                    const textData = await response.text();
                    
                    if (!response.ok) {
                        console.error("Erreur serveur : ", textData);
                        throw new Error("Erreur serveur " + response.status);
                    }
                    
                    const data = JSON.parse(textData);
                    
                    if (data.error) {
                        console.error("Erreur PHP :", data.error);
                        alert("Erreur PHP : " + data.error);
                        return;
                    }
                    
                    this.resultatsReformeSimules = [...Object.values(data.resultats)];
                    this.explicationsSimules = JSON.parse(JSON.stringify(data.explications)); 
                    
                    this.$nextTick(() => {
                        const labelsReforme = this.listesCompleteReforme.map(r => r.nom);
                        const dataReforme = this.listesCompleteReforme.map(r => r.total_sieges);
                        const colorsReforme = this.listesCompleteReforme.map(r => this.getCouleurByNom(r.nom));
                        this.dessinerHemicycleReforme(labelsReforme, dataReforme, colorsReforme);
                    });
                } catch (e) {
                    console.error('Erreur Javascript de recalcul:', e);
                }
            },
            dessinerHemicycleActuel(labels, dataSieges, bgColors) {
                const ctx = document.getElementById('chartActuel');
                if (!ctx) return;
                const filtered = this.filtrerZero(labels, dataSieges, bgColors);
                if(chartActuel) chartActuel.destroy();
                chartActuel = new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: filtered.labels, datasets: [{ data: filtered.data, backgroundColor: filtered.colors, borderWidth: 2 }] },
                    options: { responsive: true, maintainAspectRatio: false, rotation: -90, circumference: 180, cutout: '60%', plugins: { legend: { display: false } } }
                });
            },
            dessinerHemicycleReforme(labels, dataSieges, bgColors) {
                const ctx = document.getElementById('chartReforme');
                if (!ctx) return;
                const filtered = this.filtrerZero(labels, dataSieges, bgColors);
                if(chartReforme) chartReforme.destroy();
                chartReforme = new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: filtered.labels, datasets: [{ data: filtered.data, backgroundColor: filtered.colors, borderWidth: 2 }] },
                    options: { responsive: true, maintainAspectRatio: false, rotation: -90, circumference: 180, cutout: '55%', plugins: { legend: { display: false } } }
                });
            },
            filtrerZero(labels, data, colors) {
                let fData=[], fLabels=[], fColors=[];
                for(let i=0; i<data.length; i++) {
                    if(data[i] > 0) { fData.push(data[i]); fLabels.push(labels[i]); fColors.push(colors[i]); }
                }
                return {data: fData, labels: fLabels, colors: fColors};
            },
            // Vérifie si une liste est le vrai vainqueur de 2026
            estVraiVainqueur2026(nomListe) {
                // 1. S'assurer qu'on a les données de la légende actuelle
                if (!this.listesActuellesLegende || this.listesActuellesLegende.length === 0) return false;
                
                // 2. Prendre le premier (qui est toujours le gagnant trié par sièges)
                const vraiGagnant = this.listesActuellesLegende[0];
                
                // 3. Vérifier qu'il n'est pas "autres" et qu'il a au moins 1 siège
                if (vraiGagnant.id === 'autres' || !vraiGagnant.siegesreel || vraiGagnant.siegesreel <= 0) return false;
                
                // 4. Comparaison stricte mais sûre des noms (en enlevant les espaces et en minuscules)
                if (!nomListe || !vraiGagnant.nom) return false;
                
                const nomA = String(nomListe).trim().toLowerCase();
                const nomB = String(vraiGagnant.nom).trim().toLowerCase();
                
                return nomA === nomB;
            },
            getNuanceStyle(nuance) {
                // Les nuances du Ministère commencent souvent par L (LFI, LSOC, LUD, LDVD...)
                // On nettoie la chaîne pour la comparaison
                const n = nuance ? nuance.trim().toUpperCase() : '';
                
                let bgColor = '#f8f9fa'; // Défaut (gris très clair)
                let textColor = '#212529';
                let borderColor = '#dee2e6';

                // Extrême-Gauche & Gauche Radicale
                if (n.includes('EXG') || n === 'LCOM' || n === 'LFI') {
                    bgColor = '#ffe4e6'; // Rouge pastel
                    textColor = '#be123c';
                    borderColor = '#fda4af';
                }
                // Gauche (PS, DVG, Union Gauche)
                else if (n === 'LSOC' || n === 'LDVG' || n === 'LUG') {
                    bgColor = '#fce7f3'; // Rose
                    textColor = '#be185d';
                    borderColor = '#f9a8d4';
                }
                // Écologistes
                else if (n.includes('ECO') || n === 'LVEC') {
                    bgColor = '#dcfce7'; // Vert
                    textColor = '#15803d';
                    borderColor = '#86efac';
                }
                // Centre / Majorité Présidentielle (RE, MoDem, Horizons, DVC)
                else if (n === 'LREM' || n === 'LMDM' || n === 'LDVC' || n === 'LUC' || n === 'LHOR') {
                    bgColor = '#fef08a'; // Jaune / Orangé clair
                    textColor = '#a16207';
                    borderColor = '#fde047';
                }
                // Droite (LR, DVD, UDI, Union Droite)
                else if (n === 'LLR' || n === 'LDVD' || n === 'LUD' || n === 'LUDI') {
                    bgColor = '#e0f2fe'; // Bleu clair
                    textColor = '#0369a1';
                    borderColor = '#7dd3fc';
                }
                // Extrême-Droite / Droite Souverainiste (RN, Reconquête, EXD)
                else if (n === 'LRN' || n === 'LEXD' || n === 'LREC' || n === 'LUXD') {
                    bgColor = '#eed3c8'; // Marron très clair
                    textColor = '#7c2d12'; // Marron foncé
                    borderColor = '#a48460'; // Marron clair pour la bordure
                }
                // Divers / Régionalistes / Sans étiquette
                else if (n.includes('DIV') || n.includes('REG')) {
                    bgColor = '#f3f4f6';
                    textColor = '#4b5563';
                    borderColor = '#d1d5db';
                }

                return {
                    backgroundColor: bgColor,
                    color: textColor,
                    borderColor: borderColor + ' !important'
                };
            },
            // Gère le scroll fluide avant de lancer le recalcul PHP
            changementVainqueurEtScroll() {
                // 1. On lance le recalcul PHP de la réforme
                this.recalculerReforme();
                
                // 2. On fait scroller la page de manière plus précise
                setTimeout(() => {
                    // On cible le canvas de l'hémicycle
                    const chartReforme = document.getElementById('chartReforme');
                    if (chartReforme) {
                        // On remonte jusqu'à la ligne qui contient l'hémicycle (.closest('.row'))
                        // On laisse 80px de marge pour que le titre "Avec la proposition de réforme" soit bien visible
                        const conteneur = chartReforme.closest('.row');
                        if (conteneur) {
                            const y = conteneur.getBoundingClientRect().top + window.pageYOffset - 120;
                            window.scrollTo({ top: y, behavior: 'smooth' });
                        }
                    }
                }, 150);
            },
        },
        mounted() {
            // Dessin initial
            const labelsActuel = this.listesInitialesTrieesActuel.map(l => l.nom);
            const dataActuel = this.listesInitialesTrieesActuel.map(l => l.sieges_reel);
            const colorsActuel = this.listesInitialesTrieesActuel.map(l => this.getCouleurByNom(l.nom));
            this.dessinerHemicycleActuel(labelsActuel, dataActuel, colorsActuel);

            const labelsReforme = this.listesCompleteReforme.map(r => r.nom);
            const dataReforme = this.listesCompleteReforme.map(r => r.total_sieges);
            const colorsReforme = this.listesCompleteReforme.map(r => this.getCouleurByNom(r.nom));
            this.dessinerHemicycleReforme(labelsReforme, dataReforme, colorsReforme);
        }
    }).mount('#app-ville');
</script>