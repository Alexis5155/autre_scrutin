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

    <!-- === CE QUE CHANGE CONCRÈTEMENT === -->
    <div class="py-4 fade-in-up" style="animation-delay:0.15s;">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-check2-circle me-1"></i> Les apports de la réforme
            </span>
            <h2 class="fw-bold" style="font-size:clamp(1.6rem,3vw,2.4rem);">Ce que change concrètement cette réforme</h2>
            <p class="text-muted mx-auto" style="max-width:60ch;">
                Chaque avantage découle directement du mécanisme. Ensemble, ils forment un équilibre inédit
                entre efficacité gouvernementale et représentation démocratique.
            </p>
        </div>

        <div class="row g-4">

            <!-- 01 -->
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100 border-start border-primary border-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size:.7rem;letter-spacing:.06em;">01</span>
                        <h3 class="fs-5 fw-bold mb-0">Atténuation du vote utile</h3>
                    </div>
                    <p class="text-muted mb-2">
                        Aujourd'hui, voter pour une petite liste au 1<sup>er</sup> tour équivaut souvent à « perdre » son vote :
                        si la liste ne se maintient pas ou fusionne, ses électeurs n'ont aucun représentant.
                        Ce mécanisme contraint des millions de citoyens à voter stratégiquement plutôt que sincèrement.
                    </p>
                    <p class="text-muted mb-0">
                        Avec la réforme, <strong>les sièges proportionnels sont figés dès le 1<sup>er</sup> tour</strong>, quoi qu'il arrive au second.
                        Un électeur peut voter pour la liste qui correspond à ses convictions sans craindre de « perdre » son vote —
                        sa liste sera représentée si elle franchit le seuil de 5 % des suffrages exprimés.
                    </p>
                </div>
            </div>

            <!-- 02 -->
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100 border-start border-warning border-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:.7rem;letter-spacing:.06em;">02</span>
                        <h3 class="fs-5 fw-bold mb-0">Fin des fusions contre-nature</h3>
                    </div>
                    <p class="text-muted mb-2">
                        Sous le régime actuel, des listes aux programmes antagonistes se fusionnent entre les deux tours pour constituer
                        un « front républicain » ou simplement espérer accéder aux primes. Ces alliances trahissent les électeurs
                        du 1<sup>er</sup> tour et brouillent la lisibilité politique du conseil.
                    </p>
                    <p class="text-muted mb-0">
                        Avec la réforme, chaque liste présente ses propres élus. Les fusions ne sont plus nécessaires
                        pour exister au conseil — elles restent possibles pour le 2<sup>nd</sup> tour, mais sans pression existentielle.
                        <strong>Chaque liste assume ses couleurs jusqu'au bout.</strong>
                    </p>
                </div>
            </div>

            <!-- 03 -->
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100 border-start border-success border-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size:.7rem;letter-spacing:.06em;">03</span>
                        <h3 class="fs-5 fw-bold mb-0">Second tour : un duel lisible</h3>
                    </div>
                    <p class="text-muted mb-2">
                        Le second tour retrouve sa vocation première : désigner l'équipe qui gouvernera la commune.
                        Dans l'immense majorité des situations, seules les deux listes les mieux placées ont un intérêt
                        à se maintenir — les autres ont déjà sécurisé leurs sièges proportionnels au 1<sup>er</sup> tour
                        et n'ont aucune raison de participer à un duel dont elles ne peuvent espérer emporter les primes.
                    </p>
                    <p class="text-muted mb-0">
                        Sans règle formelle d'exclusion, le système crée naturellement ce duel. Le second tour devient
                        un scrutin majoritaire pur, <strong>clair pour l'électeur</strong> : à qui confie-t-on les clés de la mairie ?
                    </p>
                </div>
            </div>

            <!-- 04 -->
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100 border-start border-info border-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge text-white fw-bold px-2 py-1" style="font-size:.7rem;letter-spacing:.06em;background:var(--bs-info);">04</span>
                        <h3 class="fs-5 fw-bold mb-0">Maintien de la stabilité municipale</h3>
                    </div>
                    <p class="text-muted mb-2">
                        La prime majoritaire garantit au vainqueur une <strong>majorité absolue confortable</strong>, lui permettant de
                        gouverner sans négociation permanente. La stabilité de l'exécutif municipal est préservée,
                        voire renforcée par rapport au système actuel.
                    </p>
                    <p class="text-muted mb-0">
                        La prime minoritaire, en consacrant un leader de l'opposition identifié et disposant d'un poids réel,
                        structure le débat démocratique au lieu de le disperser. Majorité qui gouverne, opposition qui contrôle :
                        les deux piliers d'une démocratie locale saine coexistent enfin.
                    </p>
                </div>
            </div>

            <!-- 05 -->
            <div class="col-12">
                <div class="glass-card p-4 border-start border-3" style="border-color:var(--secondary)!important;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge text-white fw-bold px-2 py-1" style="font-size:.7rem;letter-spacing:.06em;background:var(--secondary);">05</span>
                        <h3 class="fs-5 fw-bold mb-0">Meilleure représentativité des petites listes</h3>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-7">
                            <p class="text-muted mb-2">
                                Sous le régime actuel, une liste obtenant entre 5 et 10 % des voix peut être contrainte de se désister
                                ou de fusionner entre les deux tours pour ne pas « faire perdre » un camp. Elle disparaît alors du conseil,
                                et ses électeurs se retrouvent sans représentant, malgré un score significatif.
                            </p>
                            <p class="text-muted mb-0">
                                Avec la réforme, <strong>toute liste franchissant 5 % des suffrages exprimés</strong> obtient ses sièges
                                proportionnels au 1<sup>er</sup> tour et les conserve définitivement. Mieux : une liste à 8 % qui se maintient
                                au 2<sup>nd</sup> tour — même en arrivant troisième — <strong>bénéficie à la fois de ses sièges proportionnels
                                et de la répartition du solde</strong>. Elle siège, elle débat, elle représente.
                            </p>
                        </div>
                        <div class="col-md-5">
                            <div class="rounded-3 p-3 h-100 d-flex flex-column justify-content-center gap-3"
                                style="background:rgba(108,117,125,0.07);">
                                <span><strong>La fin des injustices électorales</strong></span>
                                <span class="small text-muted"><strong>Avec le système actuel :</strong> Une liste à 8% au 1<sup>er</sup> tour ne fusionnant pas obtient 0 siège ; alors qu'une liste à 8 % au 2<sup>nd</sup> tour obtiendra des sièges</span>
                                <span class="small text-muted"><strong>Avec la proposition de réforme :</strong> Une liste qui obtient 8 % au 1<sup>er</sup> tour sera représentée au conseil municipal</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <hr class="my-5 opacity-25">


    <!-- === ATTRIBUTION DES SIÈGES — STEPPER === -->
    <div class="py-5 my-2 fade-in-up" style="animation-delay:0.2s;">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-diagram-3 me-1"></i> Fonctionnement
            </span>
            <h2 class="fw-bold" style="font-size:clamp(1.6rem,3vw,2.4rem);">Comment les sièges sont attribués</h2>
            <p class="text-muted mx-auto mb-3" style="max-width:58ch;">
                Un processus en trois étapes, lisible et transparent.
                Les chiffres ci-dessous correspondent à un conseil municipal de <strong>39 sièges</strong>
                (commune de 20 000 à 29 999 habitants).
            </p>
            <button class="btn btn-sm btn-outline-secondary px-3" id="btn-systeme-actuel"
                    onclick="toggleSystemeActuel()" style="font-size:.8rem;">
                <i class="bi bi-eye me-1"></i> Voir comment fonctionne le système actuel
            </button>
        </div>

        <!-- Panneau système actuel -->
        <div id="panneau-systeme-actuel"
            style="display:none; overflow:hidden;"
            aria-hidden="true">
            <div class="glass-card p-4 mb-5" style="border-left:4px solid #dc3545;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                    <h4 class="fw-bold mb-0 fs-5">Le système actuel (depuis 1982)</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-1-circle me-1 text-muted"></i> Premier tour</h6>
                        <p class="text-muted small mb-0">
                            Si une liste obtient la <strong>majorité absolue</strong> des suffrages exprimés, elle remporte
                            immédiatement la <strong>prime majoritaire</strong> (la moitié des sièges, arrondie à l'entier supérieur),
                            puis les sièges restants sont répartis à la proportionnelle à la plus forte moyenne entre toutes
                            les listes ayant obtenu au moins <strong>5 % des suffrages exprimés</strong> — y compris la liste primée.
                            <br>Sinon, on passe au second tour.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-2-circle me-1 text-muted"></i> Second tour</h6>
                        <p class="text-muted small mb-0">
                            Seules les listes ayant obtenu au moins <strong>12,5 % des inscrits</strong> au 1<sup>er</sup> tour
                            peuvent se maintenir (les listes à 5 % des suffrages exprimés peuvent fusionner).
                            La liste arrivée en tête — même avec une <strong>simple majorité relative</strong> — remporte
                            la prime majoritaire (moitié des sièges). Les sièges restants sont répartis à la proportionnelle
                            entre toutes les listes présentes ayant obtenu au moins 5 % des suffrages exprimés.
                            <br><strong class="text-danger">Conséquence :</strong> une liste fusionnée ou désistée disparaît
                            totalement du conseil, quelle que soit son influence au 1<sup>er</sup> tour.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stepper -->
        <div class="position-relative" id="stepper">

            <!-- Ligne verticale desktop -->
            <div class="d-none d-lg-block position-absolute top-0 bottom-0 start-50 translate-middle-x"
                style="width:2px;background:var(--bs-border-color);opacity:.3;z-index:0;"></div>

            <!-- ÉTAPE 1 -->
            <div class="row g-0 align-items-center mb-4 position-relative" style="z-index:1;">
                <div class="col-lg-5 text-lg-end pe-lg-5">
                    <div class="glass-card p-4" style="border-top:3px solid #0d6efd;">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2 mb-2">
                            <span class="badge bg-primary fw-bold px-2">Étape 1</span>
                            <h3 class="fs-5 fw-bold mb-0">1<sup>er</sup> tour — proportionnelle</h3>
                        </div>
                        <p class="text-muted small mb-2">
                            <strong>50 % des sièges</strong> sont répartis à la proportionnelle entre toutes les listes
                            ayant obtenu au moins <strong>5 % des suffrages exprimés</strong>.
                        </p>
                        <p class="text-muted small mb-2">
                            La méthode utilisée est celle du <strong>quotient électoral puis de la plus forte moyenne</strong> :
                            on divise le nombre de suffrages exprimés par le nombre de sièges à pourvoir pour obtenir
                            le quotient ; chaque liste reçoit autant de sièges entiers que son score le permet,
                            puis les sièges restants sont attribués un à un aux listes ayant la plus forte moyenne
                            (voix ÷ sièges déjà obtenus + 1).
                        </p>
                        <p class="text-muted small mb-0 fst-italic border-start border-primary border-2 ps-2">
                            Ces sièges sont <strong>définitivement acquis</strong>, indépendamment du résultat du second tour.
                        </p>
                    </div>
                </div>
                <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:42px;height:42px;z-index:2;flex-shrink:0;">1</div>
                </div>
                <div class="col-lg-5 ps-lg-5 d-none d-lg-flex align-items-center">
                    <div class="p-4 rounded-3 w-100 text-center" style="background:rgba(13,110,253,0.08);">
                        <div class="fw-bold" style="font-size:2.5rem;color:#0d6efd;line-height:1;">20</div>
                        <div class="small text-muted mt-1">sièges attribués à la proportionnelle</div>
                        <div class="small text-muted opacity-75 mt-1">= 50 % de 39 sièges</div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 -->
            <div class="row g-0 align-items-center mb-4 position-relative" style="z-index:1;">
                <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-end pe-lg-5">
                    <div class="p-4 rounded-3 text-center" style="background:rgba(245,158,11,0.08);">
                        <div class="fw-bold text-warning mb-1" style="font-size:1.1rem;">Sauf si…</div>
                        <div class="small text-muted">une liste obtient la majorité absolue<br>dès le 1<sup>er</sup> tour → on passe<br>directement aux primes</div>
                    </div>
                </div>
                <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center text-dark fw-bold"
                        style="width:42px;height:42px;z-index:2;flex-shrink:0;">2</div>
                </div>
                <div class="col-lg-5 ps-lg-5">
                    <div class="glass-card p-4" style="border-top:3px solid #f59e0b;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark fw-bold px-2">Étape 2</span>
                            <h3 class="fs-5 fw-bold mb-0">2<sup>nd</sup> tour — le duel</h3>
                        </div>
                        <p class="text-muted small mb-2">
                            Si aucune liste n'a obtenu la <strong>majorité absolue</strong> au 1<sup>er</sup> tour,
                            un second tour est organisé. Les deux listes les mieux placées s'affrontent pour désigner
                            l'équipe qui gouvernera la commune.
                        </p>
                        <p class="text-muted small mb-2">
                            Les autres listes n'ont aucun intérêt stratégique à se maintenir :
                            <strong>leurs sièges proportionnels sont déjà acquis</strong> et les primes
                            ne peuvent revenir qu'aux deux finalistes. Naturellement, le second tour devient un duel,
                            sans règle formelle d'exclusion.
                        </p>
                        <p class="text-muted small mb-0 fst-italic border-start border-warning border-2 ps-2">
                            Nouveauté : le vainqueur doit obtenir la <strong>majorité absolue</strong> des suffrages exprimés —
                            contrairement au système actuel qui se contente d'une majorité relative.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 3 -->
            <div class="row g-0 align-items-center position-relative" style="z-index:1;">
                <div class="col-lg-5 text-lg-end pe-lg-5">
                    <div class="glass-card p-4" style="border-top:3px solid #10b981;">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2 mb-2">
                            <span class="badge bg-success fw-bold px-2">Étape 3</span>
                            <h3 class="fs-5 fw-bold mb-0">Attribution des primes</h3>
                        </div>
                        <p class="text-muted small mb-2">
                            <strong class="text-success">Prime majoritaire (40 % des sièges)</strong> → attribuée à la liste
                            ayant obtenu la <strong>majorité absolue</strong>, que ce soit dès le 1<sup>er</sup> tour ou au 2<sup>nd</sup>.
                            Si la liste a déjà obtenu ces sièges via la proportionnelle, la prime est considérée comme atteinte
                            et ne génère pas de sièges supplémentaires.
                        </p>
                        <p class="text-muted small mb-3">
                            <strong style="color:var(--secondary)">Prime minoritaire (10 % des sièges)</strong> → attribuée
                            à la liste <em>perdante</em> du second tour. Elle compense sa participation au duel
                            et la consacre comme <strong>leader de l'opposition</strong> avec un poids politique réel,
                            indépendamment de l'écart de voix au 1<sup>er</sup> tour.
                        </p>
                        <div class="p-2 rounded-2 small text-muted" style="background:rgba(245,158,11,0.1);">
                            <i class="bi bi-arrow-right-circle text-warning me-1"></i>
                            <strong>Si victoire dès le 1<sup>er</sup> tour</strong> : pas de perdant au 2<sup>nd</sup> tour —
                            les sièges de la prime minoritaire sont reversés dans la répartition proportionnelle.
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:42px;height:42px;z-index:2;flex-shrink:0;">3</div>
                </div>
                <div class="col-lg-5 ps-lg-5 d-none d-lg-flex align-items-center">
                    <div class="p-4 rounded-3 w-100" style="background:rgba(16,185,129,0.08);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small text-muted">Prime majoritaire</span>
                            <span class="fw-bold text-success fs-5">16 sièges</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Prime minoritaire</span>
                            <span class="fw-bold fs-5" style="color:var(--secondary)">4 sièges</span>
                        </div>
                        <hr class="my-3 opacity-25">
                        <div class="small text-muted fst-italic text-center">40 % et 10 % de 39 sièges<br>(arrondis à l'entier supérieur)</div>
                    </div>
                </div>
            </div>

        </div><!-- /stepper -->

        <!-- Récapitulatif -->
        <div class="mt-5 p-4 rounded-3" style="background:rgba(13,110,253,0.05);">
            <p class="small text-muted text-center mb-3 fw-semibold text-uppercase" style="letter-spacing:.07em;">
                Récapitulatif — conseil de 39 sièges
            </p>
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-3">
                <span class="badge bg-primary px-3 py-2" style="font-size:.85rem;">20 sièges — proportionnelle 1<sup>er</sup> tour</span>
                <span class="text-muted fw-bold">+</span>
                <span class="badge bg-success px-3 py-2" style="font-size:.85rem;">16 sièges — prime majoritaire</span>
                <span class="text-muted fw-bold">+</span>
                <span class="badge px-3 py-2" style="font-size:.85rem;background:var(--secondary);color:#fff;">4 sièges — prime minoritaire</span>
                <span class="text-muted fw-bold fs-5">=</span>
                <span class="badge bg-dark px-3 py-2" style="font-size:.85rem;">39 sièges</span>
            </div>
            <p class="small text-muted text-center mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Dans les communes PLM (Paris, Lyon, Marseille), la prime majoritaire est ramenée à <strong>30 %</strong>. Une prime à 25 %, comme c'est le cas aujourd'hui, ferait peser un risque d'instabilité avec un système basé sur les résultats du 1<sup>er</sup> tour.
            </p>
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
    analyseAvant:"Grégoire remporte 63 % des sièges avec 38 % des voix. Knafo, à 10 %, n'entre pas au conseil à cause de son désistement. Ses électeurs sont privés de représentation.",
    analyseApres:"La majorité reste solide (54 %). Dati reste l'opposition principale, mais d'autres courants politiques sont représentés au conseil de Paris.",
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
    analyseApres:"Doucet conserve une majorité de sièges. Aulas dirige l'opposition avec 25 sièges. LFI n'a pas besoin de \"fusion technique\" pour faire être au conseil sans faire perdre la gauche et RN entre au conseil.",
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
    analyseAvant:"Moudenc rafle 77 % des sièges. Briançon doit se diluer dans la liste de Piquemal pour faire gagner la gauche. Leonardelli et ses 5 % sont effacés car n'a pas pu fusionner.",
    analyseApres:"Moudenc garde une majorité absolue (61 %) mais réduite. PS et LFI sont élus séparément, et le RN entre au conseil. L'opposition passe de 16 à 27, avec trois sensibilités politiques différentes.",
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
    analyseAvant:"Cazenave remporte 75 % des sièges avec à peine 50 % des voix. Quatre listes totalisant 41 % des suffrages n'ont aucun élu — un effacement massif.",
    analyseApres:"Cazenave conserve la majorité (54 %). Six sensibilités politiques sont représentées au lieu de deux — un conseil plus représentatif.",
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
    analyseAvant:"Deslandes, fusionnée avec Baly au 2nd tour, obtient 77 % des sièges. Delemer, présent au 1er tour, disparaît complètement du conseil lillois.",
    analyseApres:"Deslandes garde la majorité absolue (56 %) sans avoir à former d'alliance. Six listes représentées — un conseil qui reflète la réalité du vote lillois.",
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

(function () {
    let isOpen = false;

    window.toggleSystemeActuel = function () {
        const panneau = document.getElementById('panneau-systeme-actuel');
        const btn     = document.getElementById('btn-systeme-actuel');

        if (!isOpen) {
            panneau.style.overflow   = 'hidden';
            panneau.style.maxHeight  = '0px';
            panneau.style.opacity    = '0';
            panneau.style.display    = 'block';

            panneau.offsetHeight;

            panneau.style.transition = 'max-height .45s cubic-bezier(0.16,1,0.3,1), opacity .35s ease';
            panneau.style.maxHeight  = panneau.scrollHeight + 'px';
            panneau.style.opacity    = '1';

            panneau.addEventListener('transitionend', function done(e) {
                if (e.propertyName !== 'max-height') return;
                panneau.style.maxHeight  = 'none';
                panneau.style.overflow   = '';
                panneau.style.transition = '';
                panneau.removeEventListener('transitionend', done);
            });

            btn.innerHTML = '<i class="bi bi-eye-slash me-1"></i> Masquer le système actuel';
            isOpen = true;

        } else {
            panneau.style.overflow   = 'hidden';
            panneau.style.maxHeight  = panneau.scrollHeight + 'px';
            panneau.offsetHeight;

            panneau.style.transition = 'max-height .35s cubic-bezier(0.4,0,1,1), opacity .25s ease';
            panneau.style.maxHeight  = '0px';
            panneau.style.opacity    = '0';

            panneau.addEventListener('transitionend', function done(e) {
                if (e.propertyName !== 'max-height') return;
                panneau.style.display    = 'none';
                panneau.style.transition = '';
                panneau.removeEventListener('transitionend', done);
            });

            btn.innerHTML = '<i class="bi bi-eye me-1"></i> Voir comment fonctionne le système actuel';
            isOpen = false;
        }
    };
})();


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