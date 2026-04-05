<div class="container mt-5 pt-5">

    <!-- === HERO === -->
    <div class="row align-items-center mb-5 pb-4 fade-in-up" style="min-height:70vh;">
        <div class="col-lg-6 pe-lg-5">
            <h1 class="display-3 fw-bold mb-4 lh-sm">
                Et si un autre <span style="color:var(--secondary)">scrutin</span> était possible ?
            </h1>
            <p class="lead mb-2 text-muted">
                Le scrutin municipal actuel génère des majorités écrasantes et relègue l'opposition à l'insignifiance.
                Cette proposition permettrait de <strong>réconcilier stabilité municipale et pluralisme politique</strong> au sein des conseils municipaux.
            </p>
            <p class="mb-4 text-muted">
                Grâce à un mécanisme en deux temps — proportionnelle dès 1<sup>er</sup> tour et distribution des primes au 2<sup>nd</sup> tour — avec une nouveauté majeure : la prime minoritaire —
                chaque courant politique est enfin représenté et participe à la vie démocratique locale.
                La réforme consacre pleinement l'adage : <strong>« Au 1<sup>er</sup> tour on choisit, au 2<sup>nd</sup> on élimine. »</strong>
            </p>
            <div class="glass-card p-2 d-flex mb-3" onclick="openSearch()" style="cursor:text;">
                <div class="form-control border-0 bg-transparent shadow-none text-muted d-flex align-items-center">
                    <i class="bi bi-search me-2"></i> Rechercher ma commune (ex : Douai)…
                </div>
                <button class="btn btn-custom m-0">Projeter</button>
            </div>
            <p class="small text-muted">
                <i class="bi bi-sliders me-1"></i>
                <a href="<?= BASE_URL ?>simulateur/manuel", class="text-decoration-none">Utiliser le simulateur manuel →</a>
            </p>
        </div>

        <!-- Hémicycles -->
        <div class="col-lg-6 mt-5 mt-lg-0" id="hemi-zone">
            <div id="hemicycle-carousel">
                <div class="d-flex justify-content-center gap-2 mb-3" id="hemi-dots"></div>
                <div id="hemi-slides"></div>
            </div>
        </div>
    </div>

    <!-- === PROBLÈME ACTUEL === -->
    <div class="py-5 mb-4 fade-in-up" style="animation-delay:0.1s;">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-exclamation-triangle me-1"></i> Le problème actuel
                </span>
                <h2 class="fw-bold mb-3" style="font-size:clamp(1.5rem,3vw,2.2rem);">Un scrutin conçu pour écraser</h2>
                <p class="text-muted mb-3">
                    Depuis 1982, le scrutin de liste majoritaire à deux tours favorise les grandes majorités au détriment de la représentation pluraliste.
                    Une liste obtenant <strong>35 % des voix</strong> peut remporter <strong>70 % des sièges</strong> grâce à la prime majoritaire,
                    laissant l'opposition sans poids réel.
                </p>
                <p class="text-muted mb-0">
                    Résultat : des élus minoritaires structurellement réduits à une présence symbolique, sans moyens d'animation d'une opposition constructive,
                    et des électeurs de petites listes sans représentant au conseil.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="glass-card p-4 h-100 border-start border-warning border-3">
                            <div class="display-6 fw-bold text-warning mb-1">75 %</div>
                            <div class="small text-muted">des sièges peuvent revenir à une liste ayant fait 35 % des voix</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="glass-card p-4 h-100 border-start border-danger border-3">
                            <div class="display-6 fw-bold text-danger mb-1">0</div>
                            <div class="small text-muted">siège pour les listes obligées de se désister, malgré leur score du 1<sup>er</sup> tour</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="glass-card p-4 h-100 border-start border-secondary border-3">
                            <div class="display-6 fw-bold mb-1" style="color:var(--secondary)">« Vote utile »</div>
                            <div class="small text-muted">phénomène qui pousse les électeurs à trahir leurs convictions dès le 1<sup>er</sup> tour</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="glass-card p-4 h-100 border-start border-primary border-3">
                            <div class="display-6 fw-bold text-primary mb-1">+9 000</div>
                            <div class="small text-muted">communes de plus de 1 000 habitants concernées par ce scrutin en France</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5 opacity-25">

    <!-- === LES 3 PILIERS === -->
    <div class="py-4 fade-in-up" style="animation-delay:0.15s;">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-columns-gap me-1"></i> Les trois piliers de la réforme
            </span>
            <h2 class="fw-bold" style="font-size:clamp(1.6rem,3vw,2.4rem);">Un mécanisme en trois actes</h2>
            <p class="text-muted mx-auto" style="max-width:60ch;">
                Chaque pilier répond à une défaillance identifiée du système actuel. Ensemble, ils forment un équilibre cohérent
                entre efficacité gouvernementale et représentation démocratique.
            </p>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="glass-card p-4 p-lg-5 h-100 d-flex flex-column justify-content-center"
                     style="background: linear-gradient(135deg, rgba(13,110,253,0.07) 0%, transparent 100%);">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:52px;height:52px;">
                        <i class="bi bi-1-circle-fill text-primary fs-4"></i>
                    </div>
                    <h3 class="fs-3 fw-bold mb-3">La fin du vote utile</h3>
                    <p class="text-muted mb-3">
                        Dès le premier tour, les sièges de la proportionnelle sont figés définitivement.
                        Chaque liste ayant franchi le seuil de <strong>5 % des inscrits</strong> se voit attribuer ses sièges proportionnels —
                        et les conserve quel que soit le résultat du second tour.
                    </p>
                    <p class="text-muted mb-3">
                        Un électeur peut voter pour la liste qui correspond à ses convictions sans craindre de « perdre » son vote.
                        La représentation des petites listes est <strong>garantie dès le premier tour</strong>.
                    </p>
                    <p class="text-muted mb-0 fst-italic border-start border-primary border-2 ps-3">
                        La réforme consacre l'adage : <strong>« Au 1<sup>er</sup> tour on choisit, au 2<sup>nd</sup> on élimine. »</strong>
                        Le second tour retrouve sa vocation première : désigner la direction exécutive de la commune, sans écraser la représentation pluraliste déjà acquise.
                    </p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="glass-card p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:48px;height:48px;">
                                    <i class="bi bi-2-circle-fill text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h3 class="fs-5 fw-bold mb-2">Prime majoritaire et prime minoritaire</h3>
                                    <p class="text-muted mb-0">
                                        Le vainqueur du second tour obtient une <strong>prime majoritaire de 40 %</strong> des sièges
                                        (30 % dans les communes PLM), garantissant une majorité stable et opérationnelle.
                                        En contrepartie, le finaliste reçoit une <strong>prime minoritaire de 10 %</strong> des sièges,
                                        lui conférant naturellement une posture de leader de l'opposition avec un poids politique réel dans les débats.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="glass-card p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:48px;height:48px;">
                                    <i class="bi bi-3-circle-fill text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h3 class="fs-5 fw-bold mb-2">Des petites listes qui comptent vraiment</h3>
                                    <p class="text-muted mb-0">
                                        Toute liste ayant franchi le seuil de 5 % des inscrits entre au conseil avec ses sièges proportionnels acquis au 1<sup>er</sup> tour.
                                        Ces élus participent pleinement aux délibérations, représentant leurs électeurs indépendamment du résultat du second tour.
                                        Le conseil municipal reflète ainsi fidèlement la diversité des sensibilités politiques.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- === RÉPARTITION CONCRÈTE === -->
    <div class="py-5 my-2 fade-in-up" style="animation-delay:0.2s;">
        <div class="glass-card p-4 p-lg-5"
             style="background: linear-gradient(135deg, rgba(108,117,125,0.06) 0%, transparent 80%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-diagram-3 me-1"></i> Fonctionnement
                    </span>
                    <h2 class="fw-bold mb-3" style="font-size:clamp(1.4rem,2.5vw,2rem);">
                        La répartition des sièges en pratique
                    </h2>
                    <p class="text-muted">
                        Sur un conseil municipal de 39 sièges (commune de 20 000 à 29 999 habitants),
                        la répartition s'organise comme suit :
                    </p>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-3 h-100" style="background:rgba(13,110,253,0.10);">
                                <div class="fw-bold fs-2 text-primary">16</div>
                                <div class="small text-muted mt-1">sièges proportionnels<br><span class="fw-semibold">1<sup>er</sup> tour</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-3 h-100" style="background:rgba(108,117,125,0.10);">
                                <div class="fw-bold fs-2" style="color:var(--secondary)">+16</div>
                                <div class="small text-muted mt-1">prime majorité<br><span class="fw-semibold">vainqueur 2<sup>nd</sup> tour</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-3 h-100" style="background:rgba(16,185,129,0.10);">
                                <div class="fw-bold fs-2 text-success">+4</div>
                                <div class="small text-muted mt-1">prime minorité<br><span class="fw-semibold">finaliste 2<sup>nd</sup> tour</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-3 h-100" style="background:rgba(245,158,11,0.10);">
                                <div class="fw-bold fs-2 text-warning">+3</div>
                                <div class="small text-muted mt-1">sièges restants<br><span class="fw-semibold">à la proportionnelle</span></div>
                            </div>
                        </div>
                    </div>
                    <p class="small text-muted mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Dans les communes soumises au régime PLM (Paris, Lyon, Marseille), la prime majorité est ramenée à
                        <strong>30 %</strong> pour renforcer la représentation proportionnelle.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- === OBJECTIONS / FAQ === -->
    <div class="py-5 fade-in-up" style="animation-delay:0.25s;">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-chat-square-quote me-1"></i> Questions fréquentes
            </span>
            <h2 class="fw-bold" style="font-size:clamp(1.6rem,3vw,2.4rem);">Ce que soulève la réforme</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Les coalitions seront instables »</h4>
                    <p class="text-muted mb-0">La prime majoritaire garantit au vainqueur du second tour une majorité absolue confortable — 55 % des sièges minimum. La stabilité gouvernementale est pleinement préservée, tout en rendant l'opposition davantage visible et structurée au sein du conseil.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Les petites listes seront ingérables »</h4>
                    <p class="text-muted mb-0">Les petites listes n'entrent au conseil que si elles franchissent le seuil de <strong>5 % des inscrits</strong>. Ce filtre naturel évite l'émiettement tout en garantissant une représentation réelle aux forces politiques ancrées localement.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Le deuxième tour perd de son sens »</h4>
                    <p class="text-muted mb-0">Au contraire : le second tour retrouve sa vocation originelle, concentrée sur le duel pour la <em>direction exécutive</em> de la commune. Les électeurs choisissent clairement leur Maire, tandis que la pluralité du conseil a déjà été actée au 1<sup>er</sup> tour.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Ce système est trop complexe »</h4>
                    <p class="text-muted mb-0">Pour l'électeur, rien ne change : il vote deux fois, comme aujourd'hui. La complexité est dans la répartition des sièges, une opération transparente et vérifiable via le simulateur.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Et les communes à liste unique ? »</h4>
                    <p class="text-muted mb-0">La question des communes où une seule liste se présente est réelle et mérite réflexion. Des pistes existent — scrutin d'approbation, seuil minimal de participation — mais elles dépassent le cadre de cette réforme, qui se concentre sur les communes où la compétition électorale est effective.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h4 class="fw-bold mb-2 fs-5"><i class="bi bi-question-circle text-primary me-2"></i>« Quid du vote blanc ? »</h4>
                    <p class="text-muted mb-0">La prise en compte du vote blanc est une question légitime et importante — mais elle appelle une réflexion en profondeur sur l'ensemble de notre système électoral, bien au-delà du seul scrutin municipal. Cette réforme a vocation à s'intégrer dans le droit existant tel qu'il est, sans ouvrir ce chantier distinct.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- === CTA FINAL === -->
    <div class="py-5 mb-5 text-center fade-in-up" style="animation-delay:0.3s;">
        <div class="glass-card p-5"
             style="background: linear-gradient(135deg, rgba(13,110,253,0.08) 0%, rgba(108,117,125,0.06) 100%);">
            <h2 class="fw-bold mb-3" style="font-size:clamp(1.4rem,3vw,2rem);">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>Simulez la réforme dans votre commune
            </h2>
            <p class="text-muted mb-4 mx-auto" style="max-width:55ch;">
                Entrez les résultats réels ou hypothétiques de votre commune et visualisez instantanément
                la répartition des sièges selon la nouvelle règle.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <button class="btn btn-custom btn-lg px-4" onclick="openSearch()">
                    <i class="bi bi-search me-2"></i>Chercher ma commune
                </button>
                <a href="<?= BASE_URL ?>simulateur/manuel" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="bi bi-sliders me-2"></i>Simulateur manuel
                </a>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════════════
     CHART.JS + HÉMICYCLES
═══════════════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<style>
#hemi-slides { position:relative; min-height:360px; }
.hemi-slide  { display:none; }
.hemi-slide.active { display:block; animation: hemi-in .4s ease; }
@keyframes hemi-in { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

.hemi-dot {
    width:8px; height:8px; border-radius:50%; border:none; padding:0;
    background:#6c757d; opacity:.3;
    transition: opacity .25s, transform .25s, background .25s;
    cursor:pointer;
}
.hemi-dot.active { opacity:1; transform:scale(1.4); background:var(--primary,#0d6efd); }

.hemi-toggle {
    display:inline-flex; border-radius:999px; overflow:hidden;
    border:1px solid rgba(0,0,0,.13); font-size:.74rem;
}
.hemi-toggle button {
    padding:.22rem .8rem; border:none; background:transparent;
    color:#6c757d; cursor:pointer;
    transition:background .2s, color .2s; line-height:1.6;
}
.hemi-toggle button.active { background:var(--primary,#0d6efd); color:#fff; }

.hemi-analyse {
    font-size:.78rem; line-height:1.55; color:#6c757d;
    border-left:3px solid; padding-left:.6rem; margin-top:.5rem;
}
.hemi-analyse.avant { border-color:#dc3545; }
.hemi-analyse.apres { border-color:#198754; }

.hemi-legend { display:flex; flex-wrap:wrap; justify-content:center; gap:.35rem .7rem; margin-top:.4rem; }
.hemi-legend-item { display:flex; align-items:center; gap:.3rem; font-size:.75rem; color:#6c757d; }
.hemi-legend-dot  { width:9px; height:9px; border-radius:2px; flex-shrink:0; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<style>
#hemi-slides { position:relative; min-height:360px; }
.hemi-slide  { display:none; }
.hemi-slide.active { display:block; } /* ← animation d'arrivée supprimée */

.hemi-dot {
    width:8px; height:8px; border-radius:50%; border:none; padding:0;
    background:#6c757d; opacity:.3;
    transition: opacity .25s, transform .25s, background .25s;
    cursor:pointer;
}
.hemi-dot.active { opacity:1; transform:scale(1.4); background:var(--primary,#0d6efd); }

.hemi-toggle {
    display:inline-flex; border-radius:999px; overflow:hidden;
    border:1px solid rgba(0,0,0,.13); font-size:.74rem;
}
.hemi-toggle button {
    padding:.22rem .8rem; border:none; background:transparent;
    color:#6c757d; cursor:pointer;
    transition:background .2s, color .2s; line-height:1.6;
}
.hemi-toggle button.active { background:var(--primary,#0d6efd); color:#fff; }

.hemi-analyse {
    font-size:.78rem; line-height:1.55; color:#6c757d;
    border-left:3px solid; padding-left:.6rem; margin-top:.5rem;
}
.hemi-analyse.avant { border-color:#dc3545; }
.hemi-analyse.apres { border-color:#198754; }

.hemi-legend { display:flex; flex-wrap:wrap; justify-content:center; gap:.35rem .7rem; margin-top:.4rem; }
.hemi-legend-item { display:flex; align-items:center; gap:.3rem; font-size:.75rem; color:#6c757d; }
.hemi-legend-dot  { width:9px; height:9px; border-radius:2px; flex-shrink:0; }
</style>

<script>
(function(){

/* ═══════════════════════════════
   RANG POLITIQUE — gauche (1) → droite (9)
   Détermine l'ordre des segments dans l'hémicycle
═══════════════════════════════ */
const RANG = {
  LEXG: 1,
  LFI:  2,
  LVEC: 3, 
  LUG:  4, LDVG: 4, LPS:  3,
  LDVC: 5, LUC:  5,
  LLR:  6, LDVD: 6, LUD:  6,
  LRN:  7, 
  LUXD: 8, LEXD: 8
};

function rangOf(et) { return RANG[et] ?? 5; }

/* ═══════════════════════════════
   DONNÉES
═══════════════════════════════ */
const VILLES = [
  {
    id:'paris', label:'Paris', slug:'75056',
    avant:[
      {nom:'Grégoire',      et:'LUG',  sieges:103, c:'#e05252'},
      {nom:'Dati+Bournazel',et:'LUD',  sieges: 51, c:'#3b82f6'},
      {nom:'Chikirou',      et:'LFI',  sieges:  9, c:'#8b5cf6'},
      {nom:'Knafo',         et:'LEXD', sieges:  0, c:'#92400e'},
    ],
    apres:[
      {nom:'Grégoire',  et:'LUG',  sieges: 88, c:'#e05252'},
      {nom:'Dati',      et:'LUD',  sieges: 42, c:'#3b82f6'},
      {nom:'Chikirou',  et:'LFI',  sieges: 12, c:'#8b5cf6'},
      {nom:'Bournazel', et:'LUC',  sieges: 11, c:'#f59e0b'},
      {nom:'Knafo',     et:'LEXD', sieges: 10, c:'#92400e'},
    ],
    analyseAvant:"Grégoire remporte 63 % des sièges avec 38 % des voix. Knafo, à 10 %, n'entre pas au conseil — ses 85 000 électeurs sont sans représentant.",
    analyseApres:"La majorité reste solide (54 %). Dati devient une opposition pesante. Knafo entre au conseil : 85 000 électeurs enfin représentés.",
  },
  {
    id:'lyon', label:'Lyon', slug:'69123',
    avant:[
      {nom:'Doucet+Belouassa', et:'LUG',  sieges:46, c:'#e05252'},
      {nom:'Aulas',            et:'LDVC', sieges:27, c:'#3b82f6'},
      {nom:'Dupalais',         et:'LUXD', sieges: 0, c:'#92400e'},
    ],
    apres:[
      {nom:'Doucet',       et:'LUG',  sieges:40, c:'#e05252'},
      {nom:'Aulas',        et:'LDVC', sieges:25, c:'#3b82f6'},
      {nom:'Belouassa-C.', et:'LFI',  sieges: 5, c:'#8b5cf6'},
      {nom:'Dupalais',     et:'LUXD', sieges: 3, c:'#92400e'},
    ],
    analyseAvant:"Duel à 0,6 point d'écart, mais le vainqueur obtient 63 % des sièges. Belouassa-Cherifi (10 %) n'est représentée qu'en fusionnant avec Doucet au 2nd tour.",
    analyseApres:"Aulas obtient 25 sièges d'opposition réelle malgré sa défaite. LFI et la 4e liste entrent au conseil, reflétant la diversité lyonnaise.",
  },
  {
    id:'toulouse', label:'Toulouse', slug:'31555',
    avant:[
      {nom:'Moudenc',           et:'LDVD', sieges:53, c:'#3b82f6'},
      {nom:'Piquemal+Briançon', et:'LFI',  sieges:16, c:'#8b5cf6'},
      {nom:'Leonardelli',       et:'LRN',  sieges: 0, c:'#92400e'},
    ],
    apres:[
      {nom:'Moudenc',     et:'LDVD', sieges:42, c:'#3b82f6'},
      {nom:'Piquemal',    et:'LFI',  sieges:16, c:'#8b5cf6'},
      {nom:'Briançon',    et:'LUG',  sieges: 9, c:'#e05252'},
      {nom:'Leonardelli', et:'LRN',  sieges: 2, c:'#92400e'},
    ],
    analyseAvant:"Moudenc rafle 77 % des sièges avec 37 % des voix. Briançon (25 %) perd son identité propre dans la fusion. Leonardelli et ses 5 % sont effacés.",
    analyseApres:"Moudenc garde une majorité absolue (61 %). PS et LFI siègent séparément. Le RN entre pour la première fois, reflet de la réalité électorale.",
  },
  {
    id:'bordeaux', label:'Bordeaux', slug:'33063',
    avant:[
      {nom:'Cazenave',   et:'LUC',  sieges:49, c:'#f59e0b'},
      {nom:'Hurmic',     et:'LUG',  sieges:16, c:'#e05252'},
      {nom:'Dessertine', et:'LDVC', sieges: 0, c:'#fbd797'},
      {nom:'Raymond',    et:'LFI',  sieges: 0, c:'#8b5cf6'},
      {nom:'Rechagneux', et:'LRN',  sieges: 0, c:'#92400e'},
      {nom:'Poutou',     et:'LEXG', sieges: 0, c:'#ff0000'},
    ],
    apres:[
      {nom:'Cazenave',   et:'LUC',  sieges:35, c:'#f59e0b'},
      {nom:'Hurmic',     et:'LUG',  sieges:16, c:'#e05252'},
      {nom:'Dessertine', et:'LDVC', sieges: 7, c:'#fbd797'},
      {nom:'Raymond',    et:'LFI',  sieges: 3, c:'#8b5cf6'},
      {nom:'Rechagneux', et:'LRN',  sieges: 2, c:'#92400e'},
      {nom:'Poutou',     et:'LEXG', sieges: 2, c:'#ff0000'},
    ],
    analyseAvant:"Cazenave remporte 75 % des sièges avec seulement 25,6 % des voix. Quatre listes totalisant 41 % des suffrages n'ont aucun élu — un effacement massif.",
    analyseApres:"Cazenave conserve la majorité (54 %). Six sensibilités politiques représentées au lieu de deux — un conseil à l'image de Bordeaux.",
  },
  {
    id:'lille', label:'Lille', slug:'59350',
    avant:[
      {nom:'Deslandes+Baly', et:'LUG', sieges:47, c:'#e05252'},
      {nom:'Addouche',       et:'LFI', sieges:10, c:'#8b5cf6'},
      {nom:'Spillebout',     et:'LUC', sieges: 2, c:'#f59e0b'},
      {nom:'Valet',          et:'LRN', sieges: 2, c:'#92400e'},
      {nom:'Delemer',        et:'LLR', sieges: 0, c:'#3b82f6'},
    ],
    apres:[
      {nom:'Deslandes',  et:'LUG',  sieges:34, c:'#e05252'},
      {nom:'Addouche',   et:'LFI',  sieges:13, c:'#8b5cf6'},
      {nom:'Baly',       et:'LVEC', sieges: 6, c:'#10b981'},
      {nom:'Spillebout', et:'LUC',  sieges: 3, c:'#f59e0b'},
      {nom:'Valet',      et:'LRN',  sieges: 3, c:'#92400e'},
      {nom:'Delemer',    et:'LLR',  sieges: 2, c:'#3b82f6'},
    ],
    analyseAvant:"Deslandes, fusionnée avec Baly au 2nd tour, obtient 77 % des sièges. Delemer, présente au 1er tour, disparaît complètement du conseil lillois.",
    analyseApres:"Deslandes garde la majorité absolue (56 %) sans coalition obligatoire. Six listes représentées — un conseil qui reflète la réalité du vote lillois.",
  },
];

/* ═══════════════════════════════
   ÉTAT GLOBAL
═══════════════════════════════ */
const charts = {};
let current = 0;
let autoTimer, flipTimer;
let paused = false;

/* ═══════════════════════════════
   CONSTRUCTION DOM
═══════════════════════════════ */
const dotsEl   = document.getElementById('hemi-dots');
const slidesEl = document.getElementById('hemi-slides');

VILLES.forEach((v, i) => {
  const dot = document.createElement('button');
  dot.className = 'hemi-dot' + (i === 0 ? ' active' : '');
  dot.dataset.index = i;
  dot.setAttribute('aria-label', v.label);
  dotsEl.appendChild(dot);

  const slide = document.createElement('div');
  slide.className = 'hemi-slide' + (i === 0 ? ' active' : '');
  slide.dataset.city = v.id;
  slide.innerHTML = `
    <p class="text-center small text-muted mb-2 fw-semibold text-uppercase" style="letter-spacing:.07em;">
      ${v.label}
    </p>
    <div class="d-flex justify-content-center mb-2">
      <div class="hemi-toggle" id="toggle-${v.id}">
        <button class="active" data-mode="avant">Système actuel</button>
        <button data-mode="apres">Avec la réforme</button>
      </div>
    </div>
    <div style="position:relative;height:190px;">
      <canvas id="canvas-${v.id}"></canvas>
    </div>
    <div class="hemi-legend" id="leg-${v.id}"></div>
    <p class="hemi-analyse avant mx-auto" id="analyse-${v.id}" style="max-width:390px;">${v.analyseAvant}</p>
    <div class="text-center mt-2">
      <a href="<?= BASE_URL ?>simulateur/ville/${v.slug}" class="btn btn-sm btn-outline-primary px-3" style="font-size:.78rem;">
        <i class="bi bi-bar-chart me-1"></i> Détail pour cette commune →
      </a>
    </div>`;
  slidesEl.appendChild(slide);
});

/* ═══════════════════════════════
   TRI GAUCHE→DROITE
   On trie par rang politique croissant.
   Les listes à 0 siège sont exclues avant
   le tri (elles n'apparaissent pas dans
   l'hémicycle de toute façon).
═══════════════════════════════ */
function sortedByAxis(listes) {
  return [...listes]
    .filter(l => l.sieges > 0)
    .sort((a, b) => rangOf(a.et) - rangOf(b.et));
}

/* ═══════════════════════════════
   CHART.JS
═══════════════════════════════ */
function buildChart(v, mode, isInit) {
  const sorted = sortedByAxis(mode === 'avant' ? v.avant : v.apres);
  const labels = sorted.map(l => `${l.nom} (${l.et}) — ${l.sieges}`);
  const data   = sorted.map(l => l.sieges);
  const colors = sorted.map(l => l.c);
  const ctx    = document.getElementById('canvas-' + v.id).getContext('2d');

  if (charts[v.id]) {
    const ch = charts[v.id];
    ch.data.labels                      = labels;
    ch.data.datasets[0].data            = data;
    ch.data.datasets[0].backgroundColor = colors;
    /* animation uniquement pour les transitions manuelles/auto,
       pas pour l'initialisation */
    ch.options.animation.duration = isInit ? 0 : 700;
    ch.update();
  } else {
    charts[v.id] = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: colors,
          borderWidth: 2,
          borderColor: 'transparent',
          hoverOffset: 4,
        }]
      },
      options: {
        rotation: -90,       /* gauche = extrême gauche politique */
        circumference: 180,
        cutout: '52%',
        animation: { duration: 0, easing: 'easeInOutQuart' }, /* pas d'anim au 1er rendu */
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: ctx => ` ${ctx.raw} siège${ctx.raw > 1 ? 's' : ''}`
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false,
      }
    });
  }

  buildLegend(v.id, sorted);
  updateAnalyse(v, mode);
  updateToggle(v.id, mode);
}

function buildLegend(id, sorted) {
  const legEl = document.getElementById('leg-' + id);
  legEl.innerHTML = '';
  sorted.forEach(l => {
    const item = document.createElement('span');
    item.className = 'hemi-legend-item';
    item.innerHTML = `<span class="hemi-legend-dot" style="background:${l.c}"></span>
      <span>${l.nom} <strong class="text-body">${l.sieges}</strong> <em class="opacity-50">${l.et}</em></span>`;
    legEl.appendChild(item);
  });
}

function updateAnalyse(v, mode) {
  const el = document.getElementById('analyse-' + v.id);
  el.textContent = mode === 'avant' ? v.analyseAvant : v.analyseApres;
  el.className   = 'hemi-analyse ' + mode + ' mx-auto';
}

function updateToggle(id, mode) {
  document.getElementById('toggle-' + id)
    .querySelectorAll('button')
    .forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
}

/* ═══════════════════════════════
   INIT — isInit=true → duration:0
═══════════════════════════════ */
VILLES.forEach(v => {
  buildChart(v, 'avant', true);
  document.getElementById('toggle-' + v.id).addEventListener('click', e => {
    const btn = e.target.closest('button[data-mode]');
    if (!btn) return;
    clearTimeout(flipTimer);
    buildChart(v, btn.dataset.mode, false);
  });
});

/* ═══════════════════════════════
   CAROUSEL
═══════════════════════════════ */
const hemiZone = document.getElementById('hemi-zone');
hemiZone.addEventListener('mouseenter', () => { paused = true; });
hemiZone.addEventListener('mouseleave', () => { paused = false; });

function goTo(idx) {
  const slides = document.querySelectorAll('.hemi-slide');
  const dots   = document.querySelectorAll('.hemi-dot');
  slides[current].classList.remove('active');
  dots[current].classList.remove('active');
  current = idx;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
  buildChart(VILLES[current], 'avant', true); /* changement de ville = réinit sans anim */
  scheduleFlip();
}

function scheduleFlip() {
  clearTimeout(flipTimer);
  flipTimer = setTimeout(() => {
    if (!paused) buildChart(VILLES[current], 'apres', false);
    else scheduleFlip();
  }, 3000);
}

function autoNext() {
  if (!paused) goTo((current + 1) % VILLES.length);
}

autoTimer = setInterval(autoNext, 10000);
scheduleFlip();

dotsEl.querySelectorAll('.hemi-dot').forEach(d =>
  d.addEventListener('click', () => {
    clearInterval(autoTimer);
    goTo(+d.dataset.index);
    autoTimer = setInterval(autoNext, 10000);
  })
);

})();
</script>