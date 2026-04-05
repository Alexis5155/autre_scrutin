const COLORS = ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16'];

let chartInstance = null;

const { createApp } = Vue;

createApp({
    data() {
        return {
            modeSaisie: 'voix',
            villeImportee: null,
            parametres: { sieges: 39, isPLM: false },
            listes: [
                { id: 'L1', valeurSaisie: 4871, nom: null, vainqueur2nd: false },
                { id: 'L2', valeurSaisie: 4294, nom: null, vainqueur2nd: true },
                { id: 'L3', valeurSaisie: 1732, nom: null, vainqueur2nd: false },
                { id: 'L4', valeurSaisie: 263,  nom: null, vainqueur2nd: false },
            ],
            COLORS,
            nextId: 5,
            vainqueur2ndTour: null,
            resultats: null,
            drawerOpen: false
        };
    },

    // ══ ICI au bon niveau, PAS dans methods ══
    mounted() {
        const raw = sessionStorage.getItem('simulateur_import');
        if (raw) {
            try {
                const imp = JSON.parse(raw);
                sessionStorage.removeItem('simulateur_import');

                this.parametres.sieges = imp.sieges ?? this.parametres.sieges;
                this.parametres.isPLM  = imp.isPLM  ?? false;
                this.modeSaisie        = 'voix';
                this.villeImportee     = imp.commune ?? null;

                let nextId = 1;
                this.listes = imp.listes.map(l => ({
                    id:           l.id ?? ('L' + nextId++),
                    nom:          l.nom ?? null,
                    valeurSaisie: l.voix ?? 0,
                    vainqueur2nd: l.id === imp.winner_id
                }));
                const maxId = imp.listes.reduce((max, l) => {
                    const num = parseInt(String(l.id).replace('L', '')) || 0;
                    return Math.max(max, num);
                }, 0);
                this.nextId = maxId + 1;

                if (!imp.elu1erTour && imp.winner_id) {
                    this.vainqueur2ndTour = imp.winner_id;
                }

                this.$nextTick(() => this.lancerSimulation());

            } catch (e) {
                console.error('Import simulateur : JSON invalide', e);
            }
        }

        // Popover PLM
        this.$nextTick(() => {
            const btn = document.getElementById('plmInfoBtn');
            if (btn) {
                new bootstrap.Popover(btn, {
                    container: 'body',
                    html: true,
                    sanitize: false,
                    trigger: 'hover focus',
                    placement: 'top',
                    title: '<i class="bi bi-building me-1"></i> Mode Métropole (PLM)',
                    content: `<div style="font-size:0.82rem;">
                        Dans les communes soumises au scrutin <strong>Paris–Lyon–Marseille</strong>,
                        la prime majoritaire est abaissée de <strong>40%</strong> à <strong>30%</strong>
                        des sièges, laissant davantage de place à la représentation proportionnelle.
                    </div>`
                });
            }
        });
    },

    computed: {
        totalVoix() {
            return this.listes.reduce((acc, l) => acc + (Number(l.valeurSaisie) || 0), 0);
        },
        totalScores() {
            if (this.modeSaisie !== 'pourcentage') return 100;
            return Math.round(this.listes.reduce((acc, l) => acc + (Number(l.valeurSaisie) || 0), 0) * 10) / 10;
        },
        isCalculable() {
            if (this.modeSaisie === 'pourcentage') return this.totalScores === 100;
            return this.totalVoix > 0;
        },
        listesAvecPourcentage() {
            // Total uniquement sur les listes avec des voix
            const totalReel = this.listes.reduce((acc, l) => acc + (Number(l.valeurSaisie) || 0), 0);
            return this.listes.map((l, index) => {
                let pct = 0;
                if (this.modeSaisie === 'pourcentage') {
                    pct = Number(l.valeurSaisie) || 0;
                } else if (totalReel > 0) {
                    pct = ((Number(l.valeurSaisie) || 0) / totalReel) * 100;
                }
                return {
                    id:        l.id,
                    nom:       this.getNom(index),
                    scoreReel: Math.round(pct * 100) / 100,
                    couleur:   COLORS[index % COLORS.length]
                };
            });
        },
        victoirePremierTour() {
            return this.listesAvecPourcentage.some(l => l.scoreReel > 50);
        },
        listesTriees() {
            return [...this.listesAvecPourcentage]
                .filter(l => l.scoreReel > 0)
                .sort((a, b) => b.scoreReel - a.scoreReel);
        },
        finaliste1() { return this.listesTriees[0] || {}; },
        finaliste2() { return this.listesTriees[1] || {}; }
    },

    methods: {
        getLettre(index) {
            return String.fromCharCode(65 + index);
        },
        getNom(index) {
            const liste = this.listes[index];
            if (liste?.nom) return liste.nom;
            return 'Liste ' + this.getLettre(index);
        },
        getNomById(id) {
            const index = this.listes.findIndex(l => l.id === id);
            return index !== -1 ? this.getNom(index) : '?';
        },
        getLettreById(id) {
            const index = this.listes.findIndex(l => l.id === id);
            return index !== -1 ? this.getLettre(index) : '?';
        },
        getCouleurById(id) {
            const index = this.listes.findIndex(l => l.id === id);
            return index !== -1 ? COLORS[index % COLORS.length] : '#ccc';
        },
        getIndexByNom(nom) {
            return this.listes.findIndex((l, i) => this.getNom(i) === nom);
        },
        ajouterListe() {
            if (this.listes.length >= 20) return;
            this.listes.push({ id: 'L' + this.nextId++, valeurSaisie: 0, nom: null, vainqueur2nd: false });
        },
        supprimerListe(index) {
            this.listes.splice(index, 1);
        },
        reinitialiser() {
            this.parametres       = { sieges: 39, isPLM: false };
            this.modeSaisie       = 'voix';
            this.listes           = [
                { id: 'L1', valeurSaisie: 0, nom: null, vainqueur2nd: false },
                { id: 'L2', valeurSaisie: 0, nom: null, vainqueur2nd: false },
            ];
            this.nextId           = 3;   // ← correct ici car seulement L1 et L2
            this.vainqueur2ndTour = null;
            this.resultats        = null;
            this.villeImportee    = null;
        },
        async lancerSimulation() {
            const listesPourPhp = {};

            // N'envoyer QUE les listes avec des voix > 0
            this.listesAvecPourcentage
                .filter(l => l.scoreReel > 0)
                .forEach(l => {
                    listesPourPhp[l.id] = {
                        id:             l.id,
                        nom:            l.nom,
                        score_1er_tour: l.scoreReel,
                        couleur:        l.couleur
                    };
                });

            // runnerUp = le finaliste qui n'est PAS le vainqueur, parmi les 2 premiers
            const runnerUpId = (!this.victoirePremierTour && this.vainqueur2ndTour)
                ? (this.vainqueur2ndTour === this.finaliste1.id
                    ? this.finaliste2.id
                    : this.finaliste1.id)
                : null;

            const payload = {
                sieges:             this.parametres.sieges,
                isPLM:              this.parametres.isPLM,
                listes:             listesPourPhp,
                winner_2nd_tour:    this.vainqueur2ndTour,
                runner_up_2nd_tour: runnerUpId
            };

            try {
                const response = await fetch(BASE_URL + 'simulateur/calculer', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (data.error) { alert('Erreur : ' + data.error); return; }

                this.resultats = Object.values(data.resultats ?? data).map(res => {
                    const origine = Object.values(listesPourPhp).find(l => l.nom === res.nom);
                    return { ...res, couleur: origine?.couleur ?? '#ccc' };
                });
                this.$nextTick(() => this.dessinerHemicycle());
            } catch (e) {
                console.error('Erreur:', e);
            }
            this.drawerOpen = false;
        },
        dessinerHemicycle() {
            const ctx = document.getElementById('hemicycleChart');
            if (!ctx) return;
            if (chartInstance) chartInstance.destroy();

            const labels     = this.resultats.map(r => r.nom);
            const dataSieges = this.resultats.map(r => r.total_sieges);
            const bgColors   = this.resultats.map(r => r.couleur);

            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: dataSieges,
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    rotation: -90,
                    circumference: 180,
                    cutout: '62%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.label} : ${ctx.raw} sièges`
                            }
                        }
                    },
                    animation: { animateRotate: true, animateScale: true }
                }
            });
        }
    },

    watch: {
    victoirePremierTour(val) {
        if (val) this.vainqueur2ndTour = null;
    },
    // Réinitialise le vainqueur si la liste choisie n'est plus dans le top 2
    listesTriees(nouvelles) {
        if (!this.vainqueur2ndTour) return;
        const ids = nouvelles.slice(0, 2).map(l => l.id);
        if (!ids.includes(this.vainqueur2ndTour)) {
            this.vainqueur2ndTour = null;
        }
    },
    modeSaisie(newMode, oldMode) {
        if (newMode === 'pourcentage' && oldMode === 'voix' && this.totalVoix > 0) {
            const total = this.totalVoix;
            this.listes.forEach(l => {
                l.valeurSaisie = Number(((l.valeurSaisie / total) * 100).toFixed(1));
            });
        }
    }
}
}).mount('#app-simulateur');