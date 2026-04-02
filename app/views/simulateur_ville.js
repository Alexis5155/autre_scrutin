const COLORS = ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16'];

let chartActuel = null;
let chartReforme = null;

function extractVainqueurId(donnees) {
    if (!donnees.explications?.distribution_primes?.vainqueur) return null;
    const nomVainqueur = donnees.explications.distribution_primes.vainqueur.nom;
    const liste = donnees.listesInitiales.find(l => l.nom === nomVainqueur);
    return liste ? liste.id : null;
}

const { createApp } = Vue;

createApp({
    data() {
        return {
            chargement: true,
            erreur: null,
            donnees: { listesInitiales: [], sieges: 0, isPLM: false, elu1erTour: false },
            COLORS: COLORS,
            vainqueurSimuleId: null,
            resultatsReformeSimules: [],
            explicationsSimules: {
                primes: {},
                quotient: { attributions: {} },
                restes: { attributions: {}, sieges_restants: 0 },
                seuil: { eliminees: [] },
                distribution_primes: { vainqueur: {}, perdant: null }
            }
        };
    },

    // ─── UN SEUL mounted() ────────────────────────────────────────────────────
    mounted() {
        fetch(BASE_URL + 'simulateur/data?insee=' + CODE_INSEE)
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    this.erreur    = data.error;
                    this.chargement = false;
                    return;
                }

                this.donnees                 = data;
                this.resultatsReformeSimules = data.resultatsReforme;
                this.explicationsSimules     = data.explications;
                this.vainqueurSimuleId       = extractVainqueurId(data);
                this.chargement              = false;

                this.$nextTick(() => {
                    const la = this.listesInitialesTrieesActuel.map(l => l.nom);
                    const da = this.listesInitialesTrieesActuel.map(l => l.sieges_reel);
                    const ca = this.listesInitialesTrieesActuel.map(l => this.getCouleurByNom(l.nom));
                    this.dessinerHemicycleActuel(la, da, ca);

                    const lr = this.listesCompleteReforme.map(r => r.nom);
                    const dr = this.listesCompleteReforme.map(r => r.totalsieges);  // ← était r.total_sieges (bug)
                    const cr = this.listesCompleteReforme.map(r => this.getCouleurByNom(r.nom));
                    this.dessinerHemicycleReforme(lr, dr, cr);
                });
            })
            .catch(() => {
                this.erreur     = 'Erreur réseau lors du chargement des données.';
                this.chargement = false;
            });
    },

    computed: {
        listesInitialesFiltrees() {
            if (!this.donnees.listesInitiales) return [];
            return this.donnees.listesInitiales
                .filter(l => l.nom && l.nom.trim() !== '')
                .sort((a, b) => b.score_1er_tour - a.score_1er_tour);
        },
        listesInitialesTrieesActuel() {
            return [...this.listesInitialesFiltrees].sort((a, b) => {
                if (b.sieges_reel !== a.sieges_reel) return b.sieges_reel - a.sieges_reel;
                return b.score_1er_tour - a.score_1er_tour;
            });
        },
        finalistes() {
            if (this.donnees.elu1erTour) return [];
            if (this.explicationsSimules?.distribution_primes?.perdant) {
                const idVainqueur = this.trouverIdParNom(this.donnees.listesInitiales, this.explicationsSimules.distribution_primes.vainqueur.nom);
                const idPerdant   = this.trouverIdParNom(this.donnees.listesInitiales, this.explicationsSimules.distribution_primes.perdant.nom);
                return this.listesInitialesFiltrees.filter(l => l.id === idVainqueur || l.id === idPerdant);
            }
            return this.listesInitialesFiltrees.slice(0, 2);
        },
        listesCompleteReforme() {
            if (!this.listesInitialesFiltrees.length) return [];
            const resultatsReforme = Array.isArray(this.resultatsReformeSimules)
                ? this.resultatsReformeSimules
                : Object.values(this.resultatsReformeSimules || {});

            return this.listesInitialesFiltrees.map(liste => {
                const r = resultatsReforme.find(r => r.nom === liste.nom);
                if (r) {
                    return {
                        ...r,
                        nom:         liste.nom,
                        candidat:    liste.candidat,
                        nuance:      liste.nuance,
                        totalsieges: r.totalsieges  || r.sieges         || r.total_sieges || 0,
                        siegesprop:  r.siegesprop   || r.sieges_prop    || 0,
                        siegesprime: r.siegesprime  || r.sieges_prime   || r.sieges_majo  || 0,
                        siegesmin:   r.siegesmin    || r.sieges_min     || 0,
                    };
                }
                return { nom: liste.nom, candidat: liste.candidat, nuance: liste.nuance, totalsieges: 0, siegesprop: 0, siegesprime: 0, siegesmin: 0 };
            }).sort((a, b) => b.totalsieges - a.totalsieges);
        },
        estEluDesLe1erTour() {
            if (this.donnees.elu1erTour === true || this.donnees.elu1erTour === 1 || this.donnees.elu1erTour === "1") return true;
            if (this.explicationsSimules?.distribution_primes && !this.explicationsSimules.distribution_primes.perdant) return true;
            return false;
        },
        listesActuellesLegende() {
            let listesAffichees = [];
            let listesSansElu   = 0;
            this.listesInitialesTrieesActuel.forEach(liste => {
                if (liste.sieges_reel > 0) {
                    listesAffichees.push({ ...liste, couleur: this.getCouleurByNom(liste.nom) });
                } else {
                    listesSansElu++;
                }
            });
            listesAffichees.sort((a, b) => b.sieges_reel - a.sieges_reel);
            if (listesSansElu > 0) {
                listesAffichees.push({
                    id: 'autres',
                    nom: listesSansElu > 1
                        ? `${listesSansElu} listes n'ayant obtenu aucun siège`
                        : `1 liste n'ayant obtenu aucun siège`,
                    candidat: '', sieges_reel: 0, couleur: '#e9ecef'
                });
            }
            return listesAffichees;
        },
        listesReformeLegende() {
            return this.listesCompleteReforme
                .filter(l => l.totalsieges > 0)
                .sort((a, b) => b.totalsieges - a.totalsieges);
        },
        analyseTexte() {
            if (!this.listesCompleteReforme.length) return '';
            let html = '';
            const aEuPrimeMinoritaire = this.explicationsSimules?.distribution_primes?.perdant;
            const elu1erTourActif     = !aEuPrimeMinoritaire;
            const maire               = this.listesCompleteReforme[0];
            const majoriteAbsolue     = Math.floor(this.donnees.sieges / 2) + 1;
            const aMajoriteAbsolue    = maire.totalsieges >= majoriteAbsolue;
            const premierDu1erTour    = this.listesInitialesFiltrees[0];

            html += `<div class="mb-3">`;
            if (this.listesInitialesFiltrees.length === 1) {
                html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> bénéficie de l'intégralité des sièges, puisqu'elle était la seule liste candidate lors de ces élections. Cette réforme n'aurait ici <strong>aucun impact</strong>.`;
            } else if (!aMajoriteAbsolue) {
                if (this.donnees.isPLM) {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, la prime n'étant que de 30% dans la commune, son faible score au premier tour ne lui permet pas de disposer de la <strong>majorité absolue</strong> des sièges à la mairie centrale, l'obligeant à former une coalition.`;
                } else {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, compte tenu de son faible score au 1er tour, elle ne disposera pas de la <strong>majorité absolue</strong> des sièges au conseil municipal, l'obligeant à former une coalition.`;
                }
            } else {
                const diffMaire = maire.totalsieges - this.getSiegesReelsByNom(maire.nom);
                if (!elu1erTourActif && premierDu1erTour && premierDu1erTour.nom !== maire.nom) {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Si elle n'était pas la liste en tête au 1er tour, il semble que les électeurs des listes disqualifiées ont préféré lui confier la gestion de la collectivité, lui permettant de disposer d'une <strong>majorité absolue</strong> de sièges.`;
                } else if (diffMaire < 0) {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Toutefois, la majorité devient plus juste et moins hégémonique (<strong>perte de ${Math.abs(diffMaire)} sièges</strong> par rapport au système actuel), forçant le débat démocratique.`;
                } else {
                    html += `La <strong>liste ${this.getLettreByNom(maire.nom)} (${maire.nom})</strong> remporte les élections et sécurise la prime majoritaire. Elle dispose d'une <strong>majorité absolue</strong> de sièges.`;
                }
            }
            html += `</div>`;

            if (this.listesInitialesFiltrees.length > 1) {
                const perdant = this.listesCompleteReforme[1];
                if (perdant) {
                    html += `<div class="mb-3">`;
                    if (elu1erTourActif) {
                        html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong>, bien qu'arrivée en 2ème position, ne bénéficie pas de la prime minoritaire de 10% compte tenu de l'élection dès le 1er tour de la liste gagnante.`;
                    } else {
                        const etaitPremier = premierDu1erTour?.nom === perdant.nom;
                        if (etaitPremier) {
                            html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong>, qui était en tête au 1er tour, n'est pas parvenue à fédérer une majorité d'électeurs. Son score du 1er tour et la <strong>prime minoritaire de 10%</strong> lui permettent de disposer d'un important groupe au conseil municipal.`;
                        } else {
                            html += `La <strong>liste ${this.getLettreByNom(perdant.nom)} (${perdant.nom})</strong> obtient le groupe d'opposition le plus important au conseil municipal grâce à la <strong>prime minoritaire de 10%</strong>.`;
                        }
                    }
                    html += `</div>`;
                }
            }

            let blocAutreHTML = '';
            if (elu1erTourActif) {
                if (this.listesInitialesFiltrees.length > 1) {
                    const pourcentagePrime = this.donnees.isPLM ? "30%" : "40%";
                    blocAutreHTML += `<div class="mb-2"><i class="bi bi-people-fill text-info me-2"></i><strong>Meilleure représentativité :</strong> Grâce à l'abaissement de la prime majoritaire à ${pourcentagePrime}, les listes minoritaires sont mieux représentées au conseil municipal.</div>`;
                }
            } else {
                const perdant    = this.listesCompleteReforme[1];
                const nomPerdant = perdant ? perdant.nom : "";
                const listesGagnantes = this.listesCompleteReforme.filter(l =>
                    l.nom !== maire.nom &&
                    l.nom !== nomPerdant &&
                    this.getSiegesReelsByNom(l.nom) > 0 &&
                    l.totalsieges > this.getSiegesReelsByNom(l.nom)
                );
                if (listesGagnantes.length > 0) {
                    const nomsGagnants  = listesGagnantes.map(s => `<strong>${this.getLettreByNom(s.nom)} (${s.nom})</strong>`).join(" et ");
                    const textePluriel  = listesGagnantes.length > 1 ? "Les listes" : "La liste";
                    const verbePluriel  = listesGagnantes.length > 1 ? "bénéficient" : "bénéficie";
                    blocAutreHTML += `<div class="mb-2"><i class="bi bi-arrow-up-circle-fill text-success me-2"></i><strong>Fin du vote utile :</strong> ${textePluriel} ${nomsGagnants} ${verbePluriel} de sièges supplémentaires grâce à la prise en compte du score réalisé au 1er tour pour l'attribution de la part proportionnelle.</div>`;
                }
                const sauvees = this.listesCompleteReforme.filter(l =>
                    l.nom !== maire.nom &&
                    l.totalsieges > 0 &&
                    this.getSiegesReelsByNom(l.nom) === 0
                );
                if (sauvees.length > 0) {
                    const nomsSauves   = sauvees.map(s => `<strong>${this.getLettreByNom(s.nom)} (${s.nom})</strong>`).join(" et ");
                    const textePluriel = sauvees.length > 1 ? "Les listes" : "La liste";
                    const verbeEtre    = sauvees.length > 1 ? "étaient totalement effacées" : "était totalement effacée";
                    const verbeEntrer  = sauvees.length > 1 ? "entrent" : "entre";
                    blocAutreHTML += `<div><i class="bi bi-door-open-fill text-primary me-2"></i>${textePluriel} ${nomsSauves} qui ${verbeEtre} dans le système actuel, soit par retrait, fusion, ou non qualification au 2nd tour, ${verbeEntrer} au conseil municipal grâce au figeage de la proportionnelle au 1er tour.</div>`;
                }
            }
            if (blocAutreHTML) {
                html += `<div class="mt-3 p-3 bg-light border rounded shadow-sm" style="font-size: 0.9rem;">${blocAutreHTML}</div>`;
            }
            return html;
        },
    },

    methods: {
        trouverIdParNom(listes, nom) {
            return listes.find(l => l.nom === nom)?.id ?? null;
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
            return this.donnees.listesInitiales.find(l => l.nom === nom)?.sieges_reel ?? 0;
        },
        getCandidatByNom(nom) {
            return this.donnees.listesInitiales.find(l => l.nom === nom)?.candidat ?? '-';
        },
        getNuanceByNom(nom) {
            return this.donnees.listesInitiales.find(l => l.nom === nom)?.nuance ?? 'N/C';
        },
        formatDiff(diff) {
            if (diff > 0) return '+' + diff;
            if (diff < 0) return String(diff);
            return '=';
        },
        getDiffColorClass(diff) {
            if (diff > 0) return 'text-success bg-success bg-opacity-10';
            if (diff < 0) return 'text-danger bg-danger bg-opacity-10';
            return 'text-muted';
        },
        getNuanceStyle(nuance) {
            const n = nuance ? nuance.trim().toUpperCase() : '';
            let bgColor = '#f8f9fa', textColor = '#212529', borderColor = '#dee2e6';
            if (n.includes('EXG') || n === 'LCOM' || n === 'LFI')                          { bgColor = '#ffe4e6'; textColor = '#be123c'; borderColor = '#fda4af'; }
            else if (n === 'LSOC' || n === 'LDVG' || n === 'LUG')                          { bgColor = '#fce7f3'; textColor = '#be185d'; borderColor = '#f9a8d4'; }
            else if (n.includes('ECO') || n === 'LVEC')                                     { bgColor = '#dcfce7'; textColor = '#15803d'; borderColor = '#86efac'; }
            else if (n === 'LREM' || n === 'LMDM' || n === 'LDVC' || n === 'LUC' || n === 'LHOR') { bgColor = '#fef08a'; textColor = '#a16207'; borderColor = '#fde047'; }
            else if (n === 'LLR'  || n === 'LDVD' || n === 'LUD'  || n === 'LUDI')         { bgColor = '#e0f2fe'; textColor = '#0369a1'; borderColor = '#7dd3fc'; }
            else if (n === 'LRN'  || n === 'LEXD' || n === 'LREC' || n === 'LUXD')         { bgColor = '#eed3c8'; textColor = '#7c2d12'; borderColor = '#a48460'; }
            else if (n.includes('DIV') || n.includes('REG'))                                { bgColor = '#f3f4f6'; textColor = '#4b5563'; borderColor = '#d1d5db'; }
            return { backgroundColor: bgColor, color: textColor, borderColor: borderColor + ' !important' };
        },
        async recalculerReforme() {
            const finalisteMedaileArgent = this.finalistes.find(f => f.id !== this.vainqueurSimuleId);
            const runnerUpId = finalisteMedaileArgent?.id ?? null;
            const listesPourPhp = {};
            this.donnees.listesInitiales.forEach(l => {
                listesPourPhp[l.id] = { id: l.id, nom: l.nom, score_1er_tour: l.score_1er_tour, voix: l.voix };
            });
            const payload = {
                sieges: this.donnees.sieges,
                isPLM:  this.donnees.isPLM,
                listes: listesPourPhp,
                winner_2nd_tour:    this.vainqueurSimuleId,
                runner_up_2nd_tour: runnerUpId
            };
            try {
                const response = await fetch(BASE_URL + 'simulateur/calculer', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (data.error) { console.error("Erreur PHP :", data.error); return; }

                this.resultatsReformeSimules = [...Object.values(data.resultats)];
                this.explicationsSimules     = JSON.parse(JSON.stringify(data.explications));

                this.$nextTick(() => {
                    const lr = this.listesCompleteReforme.map(r => r.nom);
                    const dr = this.listesCompleteReforme.map(r => r.totalsieges); // ← corrigé
                    const cr = this.listesCompleteReforme.map(r => this.getCouleurByNom(r.nom));
                    this.dessinerHemicycleReforme(lr, dr, cr);
                });
            } catch (e) {
                console.error('Erreur Javascript de recalcul:', e);
            }
        },
        dessinerHemicycleActuel(labels, dataSieges, bgColors) {
            const ctx = document.getElementById('chartActuel');
            if (!ctx) return;
            const f = this.filtrerZero(labels, dataSieges, bgColors);
            if (chartActuel) chartActuel.destroy();
            chartActuel = new Chart(ctx, {
                type: 'doughnut',
                data: { labels: f.labels, datasets: [{ data: f.data, backgroundColor: f.colors, borderWidth: 2 }] },
                options: { responsive: true, maintainAspectRatio: false, rotation: -90, circumference: 180, cutout: '60%', plugins: { legend: { display: false } } }
            });
        },
        dessinerHemicycleReforme(labels, dataSieges, bgColors) {
            const ctx = document.getElementById('chartReforme');
            if (!ctx) return;
            const f = this.filtrerZero(labels, dataSieges, bgColors);
            if (chartReforme) chartReforme.destroy();
            chartReforme = new Chart(ctx, {
                type: 'doughnut',
                data: { labels: f.labels, datasets: [{ data: f.data, backgroundColor: f.colors, borderWidth: 2 }] },
                options: { responsive: true, maintainAspectRatio: false, rotation: -90, circumference: 180, cutout: '55%', plugins: { legend: { display: false } } }
            });
        },
        filtrerZero(labels, data, colors) {
            const fData = [], fLabels = [], fColors = [];
            for (let i = 0; i < data.length; i++) {
                if (data[i] > 0) { fData.push(data[i]); fLabels.push(labels[i]); fColors.push(colors[i]); }
            }
            return { data: fData, labels: fLabels, colors: fColors };
        },
        estVraiVainqueur2026(nomListe) {
            if (!this.listesActuellesLegende.length) return false;
            const vraiGagnant = this.listesActuellesLegende[0];
            if (vraiGagnant.id === 'autres' || !vraiGagnant.sieges_reel || vraiGagnant.sieges_reel <= 0) return false;
            return String(nomListe).trim().toLowerCase() === String(vraiGagnant.nom).trim().toLowerCase();
        },
        changementVainqueurEtScroll() {
            this.recalculerReforme();
            setTimeout(() => {
                const el = document.getElementById('chartReforme');
                if (el) {
                    const conteneur = el.closest('.row');
                    if (conteneur) {
                        window.scrollTo({ top: conteneur.getBoundingClientRect().top + window.pageYOffset - 120, behavior: 'smooth' });
                    }
                }
            }, 150);
        },
    }
}).mount('#app-ville');