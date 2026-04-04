<!-- app/views/simulateur.php -->
<div class="container-fluid px-0" id="app-simulateur">

    <!-- ══ PAGE PRINCIPALE — Hémicycle pleine largeur ══ -->
    <div class="container mt-5 pt-4">

        <!-- En-tête -->
        <div class="row fade-in-up mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 rounded-pill px-3 py-2 fw-semibold">
                    <i class="bi bi-sliders me-1"></i> Simulation manuelle
                </span>
                <h1 class="display-4 fw-bold mb-2">
                    Simulateur <span style="background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Manuel</span>
                </h1>
                <p class="lead text-muted">Composez librement une élection et projetez la réforme</p>
            </div>
        </div>

        <!-- Zone hémicycle -->
        <div class="glass-card p-4 p-lg-5 fade-in-up mb-5" style="background: rgba(255,255,255,0.75); animation-delay:.1s;">

            <div v-if="!resultats" class="text-center py-5">
                <div class="mb-4" style="opacity:0.1;">
                    <i class="bi bi-pie-chart" style="font-size:7rem; background: linear-gradient(135deg,var(--primary),var(--secondary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></i>
                </div>
                <p class="fw-semibold text-muted fs-5 mb-2">Aucune projection encore</p>
                <p class="text-muted mb-4">Ouvrez les paramètres et lancez la simulation.</p>
                <button class="btn btn-custom px-4" @click="drawerOpen = true">
                    <i class="bi bi-sliders me-2"></i>Ouvrir les paramètres
                </button>
            </div>

            <template v-else>
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 d-flex" style="background: linear-gradient(135deg,var(--primary),var(--secondary)); color:white;">
                            <i class="bi bi-bar-chart-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Hémicycle projeté</h5>
                            <p class="text-muted mb-0" style="font-size:0.75rem;">
                                {{ parametres.sieges }} sièges &mdash; majorité absolue :
                                <strong class="text-dark">{{ Math.floor(parametres.sieges / 2) + 1 }}</strong>
                                <span v-if="parametres.isPLM" class="badge rounded-pill ms-2"
                                      style="background:linear-gradient(135deg,var(--primary),var(--secondary)); color:white; font-size:0.68rem;">
                                    Mode PLM
                                </span>
                            </p>
                            <!-- Badge ville importée sous le sous-titre -->
                            <p v-if="nomImporte" class="mb-0 mt-1">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill"
                                      style="font-size:0.68rem;">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ nomImporte }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <!-- Bouton modifier -->
                    <button class="btn btn-light border fw-semibold" style="border-radius:12px;" @click="drawerOpen = true">
                        <i class="bi bi-pencil-square me-2"></i>Modifier
                    </button>
                </div>

                <div class="row g-5 align-items-center">
                    <!-- Graphique -->
                    <div class="col-lg-5">
                        <div class="position-relative mx-auto" style="height:360px; max-width:560px;">
                            <canvas id="hemicycleChart"></canvas>
                        </div>
                    </div>
                    <!-- Légende -->
                    <div class="col-lg-7">
                        <div class="d-flex flex-column gap-2">
                            <div v-for="(res, index) in resultats" :key="res.nom">
                                <div v-if="res.total_sieges > 0"
                                     class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white border shadow-sm"
                                     :style="{'border-left': '4px solid ' + res.couleur}">
                                    <!-- Avatar : toujours la lettre -->
                                    <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle flex-shrink-0"
                                          :style="{background: res.couleur, width:'38px', height:'38px', fontSize:'0.9rem'}">
                                        {{ getLettre(getIndexByNom(res.nom)) }}
                                    </span>
                                    <div class="flex-grow-1 lh-sm overflow-hidden">
                                        <div class="d-flex align-items-baseline gap-2">
                                            <!-- Nom : vrai nom si importé, sinon "Liste X" -->
                                            <span class="fw-bold text-dark text-truncate" style="font-size:0.9rem; max-width:180px;">{{ res.nom }}</span>
                                            <span class="text-muted flex-shrink-0" style="font-size:0.78rem;">{{ res.score }}%</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            <span v-if="res.sieges_prop > 0" class="text-muted" style="font-size:0.72rem;">
                                                <i class="bi bi-pie-chart-fill me-1 opacity-75"></i>{{ res.sieges_prop }} prop.
                                            </span>
                                            <span v-if="res.sieges_prime > 0" class="text-success fw-semibold" style="font-size:0.72rem;">
                                                <i class="bi bi-trophy-fill me-1"></i>+{{ res.sieges_prime }} prime maj.
                                            </span>
                                            <span v-if="res.sieges_min > 0" class="fw-semibold" style="font-size:0.72rem; color:var(--primary);">
                                                <i class="bi bi-shield-fill-plus me-1"></i>+{{ res.sieges_min }} prime min.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-center">
                                        <div class="fw-bold text-white rounded-3 px-2 py-1"
                                             :style="{background: res.couleur, fontSize:'1.2rem', minWidth:'44px'}">
                                            {{ res.total_sieges }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size:0.65rem;">sièges</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ══ BOUTON FLOTTANT ══ -->
    <button class="drawer-fab" @click="drawerOpen = true" :class="{ 'drawer-fab--hidden': drawerOpen }">
        <i class="bi bi-sliders"></i>
        <span>Paramètres</span>
    </button>

    <!-- ══ OVERLAY ══ -->
    <div class="drawer-overlay" :class="{ 'drawer-overlay--visible': drawerOpen }" @click="drawerOpen = false"></div>

    <!-- ══ DRAWER ══ -->
    <div class="drawer" :class="{ 'drawer--open': drawerOpen }">

        <!-- Header drawer -->
        <div class="drawer-header">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2 d-flex" style="background:linear-gradient(135deg,var(--primary),var(--secondary)); color:white;">
                    <i class="bi bi-gear-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Paramètres</h5>
                    <p class="text-muted mb-0" style="font-size:0.75rem;">Conseil municipal &amp; mode de calcul</p>
                </div>
            </div>
            <button class="btn-close-drawer" @click="drawerOpen = false">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Contenu scrollable -->
        <div class="drawer-body">

            <!-- Bandeau ville importée -->
            <div v-if="villeImportee"
                 class="d-flex align-items-center justify-content-between p-2 px-3 rounded-3 mb-4"
                 style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #86efac;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-success"></i>
                    <div>
                        <div class="fw-semibold text-success" style="font-size:0.82rem; line-height:1.2;">{{ villeImportee }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">Données importées</div>
                    </div>
                </div>
                <button @click="reinitialiser"
                        class="btn btn-sm fw-semibold"
                        style="border-radius:8px; font-size:0.75rem; background:#fee2e2; color:#dc2626; border:1px solid #fca5a5;">
                    <i class="bi bi-x-circle me-1"></i>Effacer
                </button>
            </div>

            <!-- Sièges + Switch -->
            <div class="d-flex align-items-end justify-content-between gap-3 mb-4">
                <div style="flex: 0 0 auto;">
                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1" style="letter-spacing:.04em;">Sièges à pourvoir</label>
                    <input type="number" class="form-control fw-bold text-center"
                           style="font-size:1.1rem; border-radius:12px; width:110px;"
                           v-model="parametres.sieges" min="9" max="163">
                </div>
                <div class="d-flex align-items-center gap-2 pb-1">
                    <span style="font-size:0.78rem; cursor:pointer; line-height:1;"
                          :class="modeSaisie==='pourcentage' ? 'fw-bold text-dark' : 'text-muted'"
                          @click="modeSaisie='pourcentage'">%</span>
                    <div style="position:relative; width:36px; height:20px; flex-shrink:0;">
                        <input type="checkbox" :checked="modeSaisie === 'voix'"
                               @change="modeSaisie = ($event.target.checked ? 'voix' : 'pourcentage')"
                               style="position:absolute; opacity:0; width:100%; height:100%; margin:0; cursor:pointer; z-index:1;">
                        <span :style="{position:'absolute',inset:'0',borderRadius:'20px',transition:'background 0.2s',background:modeSaisie==='voix'?'var(--primary)':'#cbd5e1'}"></span>
                        <span :style="{position:'absolute',top:'3px',left:'3px',width:'14px',height:'14px',background:'white',borderRadius:'50%',boxShadow:'0 1px 3px rgba(0,0,0,0.2)',transition:'transform 0.2s ease',transform:modeSaisie==='voix'?'translateX(16px)':'translateX(0)'}"></span>
                    </div>
                    <span style="font-size:0.78rem; cursor:pointer; line-height:1;"
                          :class="modeSaisie==='voix' ? 'fw-bold text-dark' : 'text-muted'"
                          @click="modeSaisie='voix'">voix</span>
                </div>
            </div>

            <!-- 1er tour -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="fw-bold text-dark" style="font-size:0.85rem; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="bi bi-1-circle-fill text-primary me-1"></i>1<sup>er</sup> tour
                </span>
                <span v-if="modeSaisie === 'pourcentage'"
                      class="badge rounded-pill fw-semibold"
                      :class="totalScores === 100 ? 'bg-success text-white' : 'bg-danger text-white'">
                    {{ totalScores }}%
                </span>
                <span v-else class="badge rounded-pill fw-semibold bg-dark text-white">
                    {{ totalVoix }} voix
                </span>
            </div>

            <!-- Listes -->
            <div class="mb-3" style="padding-right: 2px;">
                <transition-group name="liste-item" tag="div">
                    <div v-for="(liste, index) in listes" :key="liste.id"
                         class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-white border shadow-sm">
                        <!-- Avatar lettre -->
                        <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle flex-shrink-0"
                              :style="{background: COLORS[index % COLORS.length], width:'32px', height:'32px', fontSize:'0.85rem'}">
                            {{ getLettre(index) }}
                        </span>
                        <div class="flex-grow-1">
                            <!-- Nom de la liste si importé -->
                            <div v-if="liste.nom" class="text-truncate fw-semibold text-dark mb-1"
                                 style="font-size:0.78rem; max-width:200px;">
                                {{ liste.nom }}
                            </div>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control text-end fw-bold"
                                       style="border-radius: 8px 0 0 8px;"
                                       v-model.number="liste.valeurSaisie"
                                       :step="modeSaisie === 'pourcentage' ? '0.1' : '1'" min="0">
                                <span class="input-group-text" style="font-size:0.8rem; border-radius:0 8px 8px 0;">
                                    {{ modeSaisie === 'pourcentage' ? '%' : 'voix' }}
                                </span>
                            </div>
                        </div>
                        <button v-if="listes.length > 2" @click="supprimerListe(index)"
                                class="btn btn-sm p-1 border-0 text-muted"
                                style="background:none; opacity:0.4; transition:opacity .2s;"
                                @mouseenter="$event.currentTarget.style.opacity=1"
                                @mouseleave="$event.currentTarget.style.opacity=0.4">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </transition-group>
            </div>

            <!-- Ajouter liste -->
            <button @click="ajouterListe"
                    class="btn btn-light border w-100 mb-4 fw-semibold"
                    style="border-radius:12px; border-style:dashed !important;"
                    :class="listes.length >= 20 ? 'text-muted opacity-50' : 'text-primary'"
                    :disabled="listes.length >= 20">
                <i class="bi bi-plus-lg me-1"></i>
                {{ listes.length >= 20 ? 'Maximum 20 listes atteint' : 'Ajouter une liste' }}
            </button>

            <!-- Duel 2nd tour -->
            <div class="duel-wrapper" :class="{ 'duel-visible': !victoirePremierTour && isCalculable }">
                <div class="duel-inner rounded-3 border border-primary bg-primary bg-opacity-10 p-3 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-2 p-1 d-flex" style="background:var(--primary); color:white; font-size:0.75rem;">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <span class="fw-bold text-primary" style="font-size:0.85rem; text-transform:uppercase; letter-spacing:.05em;">Duel du 2nd tour</span>
                    </div>
                    <p class="small text-muted mb-2">Sélectionnez le vainqueur :</p>
                    <div class="d-flex flex-column gap-2">
                        <label v-for="finaliste in [finaliste1, finaliste2]"
                               :key="finaliste.id"
                               class="d-flex align-items-center gap-2 p-2 rounded-3 border"
                               style="cursor:pointer; transition: all .2s;"
                               :style="vainqueur2ndTour === finaliste.id
                                   ? 'background:white; border-color:var(--primary) !important; box-shadow: 0 0 0 2px var(--primary);'
                                   : 'background:rgba(255,255,255,0.5);'">
                            <input type="radio" :value="finaliste.id" v-model="vainqueur2ndTour" class="form-check-input mt-0">
                            <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle flex-shrink-0"
                                  :style="{background: getCouleurById(finaliste.id), width:'26px', height:'26px', fontSize:'0.75rem'}">
                                {{ getLettreById(finaliste.id) }}
                            </span>
                            <!-- Nom réel ou "Liste X" -->
                            <span class="fw-semibold small flex-grow-1 text-truncate" style="max-width:160px;">
                                {{ getNomById(finaliste.id) }}
                            </span>
                            <span class="badge bg-white text-dark border fw-normal flex-shrink-0" style="font-size:0.75rem;">{{ finaliste.scoreReel }}%</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Mode PLM -->
            <div class="d-flex align-items-center justify-content-between p-2 px-3 rounded-3 bg-light border mb-4">
                <div class="d-flex align-items-center gap-1">
                    <span class="small fw-semibold text-muted">Mode Métropole (PLM)</span>
                    <button type="button" id="plmInfoBtn" class="btn btn-link btn-sm p-0 text-muted"
                            style="font-size:0.78rem; line-height:1; text-decoration:none;">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small" :class="parametres.isPLM ? 'text-primary fw-bold' : 'text-muted'">
                        {{ parametres.isPLM ? 'Prime 30%' : 'Prime 40%' }}
                    </span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" v-model="parametres.isPLM"
                               style="cursor:pointer; width:2.4rem; height:1.2rem; margin-top:0;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer drawer -->
        <div class="drawer-footer">
            <!-- Bouton Réinitialiser (toujours visible) -->
            <button @click="reinitialiser"
                    class="btn btn-light border w-100 fw-semibold mb-2"
                    style="border-radius:12px; font-size:0.9rem; color:#64748b;">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser les paramètres
            </button>
            <button @click="lancerSimulation"
                    class="btn btn-custom w-100 fw-semibold fs-5"
                    :disabled="!isCalculable || (!victoirePremierTour && !vainqueur2ndTour)">
                <i class="bi bi-calculator me-2"></i>Projeter le conseil
            </button>
            <p v-if="modeSaisie === 'pourcentage' && totalScores !== 100"
               class="text-danger small text-center mt-2 mb-0">
                <i class="bi bi-exclamation-triangle me-1"></i>Le total doit être exactement 100%
            </p>
        </div>
    </div>

</div>

<style>
html { overflow-y: scroll; }

/* ══ Drawer ══ */
.drawer {
    position: fixed;
    top: 0; right: 0;
    width: 420px;
    max-width: 100vw;
    height: 100vh;
    background: #fff;
    box-shadow: -8px 0 40px rgba(0,0,0,0.12);
    z-index: 1050;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 24px 0 0 24px;
}
.drawer--open { transform: translateX(0); }

.drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem 1.5rem;
}
.drawer-footer {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
    background: #fff;
}

.btn-close-drawer {
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #64748b;
    transition: background 0.2s, color 0.2s;
}
.btn-close-drawer:hover { background: #e2e8f0; color: #0f172a; }

/* ══ Overlay ══ */
.drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.35);
    backdrop-filter: blur(4px);
    z-index: 1049;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease;
}
.drawer-overlay--visible { opacity: 1; pointer-events: auto; }

/* ══ Bouton flottant ══ */
.drawer-fab {
    position: fixed;
    bottom: 2rem; right: 2rem;
    z-index: 1040;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 22px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.95rem;
    font-family: 'Outfit', sans-serif;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
    cursor: pointer;
    transition: transform 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
}
.drawer-fab:hover { transform: translateY(-3px) scale(1.03); box-shadow: 0 12px 30px rgba(79, 70, 229, 0.5); }
.drawer-fab--hidden { opacity: 0; pointer-events: none; transform: translateY(10px); }

/* ── Animation listes ── */
.liste-item-enter-active { transition: opacity 0.25s ease, max-height 0.25s ease; max-height: 60px; overflow: hidden; }
.liste-item-leave-active { transition: opacity 0.2s ease, max-height 0.22s ease; max-height: 60px; overflow: hidden; }
.liste-item-enter-from { opacity: 0; }
.liste-item-leave-to { opacity: 0; max-height: 0 !important; margin-bottom: 0 !important; }
.liste-item-move { transition: transform 0.25s ease; }

/* ── Duel 2nd tour ── */
.duel-wrapper {
    display: grid;
    grid-template-rows: 0fr;
    opacity: 0;
    transition: grid-template-rows 0.35s ease, opacity 0.3s ease;
}
.duel-wrapper.duel-visible { grid-template-rows: 1fr; opacity: 1; }
.duel-inner { overflow: hidden; min-height: 0; }

@media (max-width: 576px) {
    .drawer { width: 100vw; border-radius: 0; }
    .drawer-fab span { display: none; }
    .drawer-fab { padding: 14px 16px; border-radius: 50%; }
}
</style>

<script>const BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>app/views/simulateur.js"></script>