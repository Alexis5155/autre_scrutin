const COLORS = ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16'];

let chartInstance = null;

const { createApp } = Vue;

createApp({
    data() {
        return {
            modeSaisie: 'voix',
            parametres: { sieges: 39, isPLM: false },
            listes: [
                { id: 'L1', valeurSaisie: 4871 },
                { id: 'L2', valeurSaisie: 4294 },
                { id: 'L3', valeurSaisie: 1732 },
                { id: 'L4', valeurSaisie: 263 }
            ],
            COLORS,
            nextId: 4,
            vainqueur2ndTour: null,
            resultats: null,
            drawerOpen: false
        };
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
            return this.listes.map((l, index) => {
                let pct = 0;
                if (this.modeSaisie === 'pourcentage') {
                    pct = Number(l.valeurSaisie) || 0;
                } else if (this.totalVoix > 0) {
                    pct = ((Number(l.valeurSaisie) || 0) / this.totalVoix) * 100;
                }
                return {
                    id: l.id,
                    nom: 'Liste ' + this.getLettre(index),
                    scoreReel: Math.round(pct * 100) / 100,
                    couleur: COLORS[index % COLORS.length]
                };
            });
        },
        victoirePremierTour() {
            return this.listesAvecPourcentage.some(l => l.scoreReel > 50);
        },
        listesTriees() {
            return [...this.listesAvecPourcentage].sort((a, b) => b.scoreReel - a.scoreReel);
        },
        finaliste1() { return this.listesTriees[0] || {}; },
        finaliste2() { return this.listesTriees[1] || {}; }
    },

    methods: {
        mounted() {
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
        getLettre(index) { return String.fromCharCode(65 + index); },
        getLettreById(id) {
            const index = this.listes.findIndex(l => l.id === id);
            return index !== -1 ? this.getLettre(index) : '?';
        },
        getCouleurById(id) {
            const index = this.listes.findIndex(l => l.id === id);
            return index !== -1 ? COLORS[index % COLORS.length] : '#ccc';
        },
        getLettreByName(name) { return name.replace('Liste ', ''); },
        ajouterListe() {
            if (this.listes.length >= 20) return;
            this.listes.push({ id: 'L' + this.nextId++, valeurSaisie: 0 });
        },
        supprimerListe(index) {
            this.listes.splice(index, 1);
        },
        async lancerSimulation() {
            const listesPourPhp = {};
            this.listesAvecPourcentage.forEach(l => {
                listesPourPhp[l.id] = { id: l.id, nom: l.nom, score_1er_tour: l.scoreReel, couleur: l.couleur };
            });

            const runnerUpId = (!this.victoirePremierTour && this.vainqueur2ndTour)
                ? (this.vainqueur2ndTour === this.finaliste1.id ? this.finaliste2.id : this.finaliste1.id)
                : null;

            const payload = {
                sieges: this.parametres.sieges,
                isPLM:  this.parametres.isPLM,
                listes: listesPourPhp,
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
                if (data.error) {
                    alert('Erreur : ' + data.error);
                    return;
                }
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

            const labels = this.resultats.map(r => r.nom);
            const dataSieges = this.resultats.map(r => r.total_sieges);
            const bgColors = this.resultats.map(r => r.couleur);

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