<!-- app/views/simulateur.php -->
<div class="container mt-5 pt-4" id="app-simulateur">
    <div class="row fade-in-up">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Simulateur <span style="color:var(--primary)">Manuel</span></h2>
            <p class="text-muted">Testez la réforme sur n'importe quelle configuration</p>
        </div>
    </div>

    <div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
        <!-- Panneau de Configuration (Gauche) -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <h4 class="mb-4 border-bottom pb-2"><i class="bi bi-sliders me-2"></i>Configuration</h4>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Sièges à pourvoir</label>
                        <input type="number" class="form-control" v-model="parametres.sieges" min="9" max="163">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Mode de saisie</label>
                        <select class="form-select" v-model="modeSaisie">
                            <option value="pourcentage">Pourcentage (%)</option>
                            <option value="voix">Nombre de Voix</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 form-check form-switch border rounded p-2 bg-light bg-opacity-50">
                    <input class="form-check-input ms-1" type="checkbox" v-model="parametres.isPLM" id="plmSwitch">
                    <label class="form-check-label ms-2" for="plmSwitch">Mode Métropole (PLM) - Prime 30%</label>
                </div>

                <h5 class="mt-4 mb-3 d-flex justify-content-between align-items-center">
                    Résultats du 1er Tour
                    <span v-if="modeSaisie === 'pourcentage'" class="badge" :class="totalScores === 100 ? 'bg-success' : 'bg-danger'">
                        Total: {{ totalScores }}%
                    </span>
                    <span v-else class="badge bg-secondary">Total: {{ totalVoix }} voix</span>
                </h5>
                
                <!-- Liste des candidats -->
                <div v-for="(liste, index) in listes" :key="liste.id" class="mb-3 p-3 border rounded bg-white bg-opacity-75 shadow-sm d-flex align-items-center gap-3">
                    <!-- Badge Lettre -->
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fw-bold" style="width: 40px; height: 40px; flex-shrink: 0;">
                        {{ getLettre(index) }}
                    </div>
                    
                    <!-- Saisie de la valeur (Curseur caché, remplacé par Input number) -->
                    <div class="flex-grow-1">
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control text-end fw-bold" 
                                   v-model.number="liste.valeurSaisie" 
                                   :step="modeSaisie === 'pourcentage' ? '0.1' : '1'" min="0">
                            <span class="input-group-text">{{ modeSaisie === 'pourcentage' ? '%' : 'voix' }}</span>
                        </div>
                    </div>
                    
                    <button v-if="listes.length > 2" @click="supprimerListe(index)" class="btn btn-outline-danger btn-sm border-0">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <button @click="ajouterListe" class="btn btn-light border btn-sm w-100 mb-4 text-primary fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>Ajouter une liste
                </button>

                <!-- Section 2nd Tour -->
                <div v-if="!victoirePremierTour && isCalculable" class="p-3 bg-primary bg-opacity-10 border border-primary rounded mb-4">
                    <h5 class="text-primary mb-3"><i class="bi bi-people-fill me-2"></i>Duel du 2nd Tour</h5>
                    <p class="small text-muted mb-2">Sélectionnez le vainqueur du duel :</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" :value="finaliste1.id" v-model="vainqueur2ndTour" name="duel">
                        <label class="form-check-label fw-bold">Liste {{ getLettreById(finaliste1.id) }} <span class="badge bg-secondary ms-1">{{ finaliste1.scoreReel }}%</span></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" :value="finaliste2.id" v-model="vainqueur2ndTour" name="duel">
                        <label class="form-check-label fw-bold">Liste {{ getLettreById(finaliste2.id) }} <span class="badge bg-secondary ms-1">{{ finaliste2.scoreReel }}%</span></label>
                    </div>
                </div>

                <button @click="lancerSimulation" class="btn btn-custom w-100 fs-5" :disabled="!isCalculable || (!victoirePremierTour && !vainqueur2ndTour)">
                    <i class="bi bi-calculator me-2"></i>Projeter le Conseil
                </button>
                <div v-if="modeSaisie === 'pourcentage' && totalScores !== 100" class="text-danger small text-center mt-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>Le total doit être exactement de 100%
                </div>
            </div>
        </div>

        <!-- Panneau de Résultats (Droite) -->
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                
                <div v-if="!resultats" class="text-center text-muted m-auto">
                    <i class="bi bi-pie-chart display-1 opacity-25"></i>
                    <p class="mt-3">Configurez l'élection et lancez la simulation pour générer l'hémicycle.</p>
                </div>

                <div v-else class="fade-in-up">
                    <h4 class="mb-4 text-center">Hémicycle simulé ({{ parametres.sieges }} sièges)</h4>
                    
                    <!-- Graphique de l'hémicycle -->
                    <div class="position-relative mx-auto mb-4" style="height: 250px; width: 100%; max-width: 500px;">
                        <canvas id="hemicycleChart"></canvas>
                        <div class="position-absolute bottom-0 start-50 translate-middle-x text-center mb-2">
                            <span class="fs-2 fw-bold text-primary">{{ parametres.sieges }}</span><br>
                            <span class="small text-muted fw-semibold">élus</span>
                        </div>
                    </div>
                    
                    <!-- Tableau de résultats -->
                    <div class="table-responsive mt-2">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Liste</th>
                                    <th>1er Tour</th>
                                    <th class="text-center" title="Proportionnelle figée au 1er tour">Prop.</th>
                                    <th class="text-center" title="Primes du 2nd tour">Primes</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="res in resultats" :key="res.nom">
                                    <td class="fw-semibold">
                                        <span class="badge text-white me-2" :style="{backgroundColor: res.couleur}">{{ getLettreByName(res.nom) }}</span>
                                    </td>
                                    <td>{{ res.score }}%</td>
                                    <td class="text-center text-muted">{{ res.sieges_prop }}</td>
                                    <td class="text-center fw-bold" :class="res.sieges_prime > 0 ? 'text-success' : 'text-muted'">
                                        {{ res.sieges_prime > 0 ? '+' + res.sieges_prime : '-' }}
                                    </td>
                                    <td class="text-center fs-5 fw-bold">{{ res.total_sieges }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3 p-3 bg-light rounded text-center small text-muted border">
                        <i class="bi bi-info-circle me-1"></i> Majorité absolue requise : <strong>{{ Math.floor(parametres.sieges / 2) + 1 }} sièges</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Librairies -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
    let chartInstance = null; // Stocke l'instance du graphique Chart.js
    
    // Palette de couleurs pour l'hémicycle
    const COLORS = ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1', '#ef4444'];

    const { createApp } = Vue

    createApp({
        data() {
            return {
                modeSaisie: 'pourcentage', // 'pourcentage' ou 'voix'
                parametres: {
                    sieges: 43,
                    isPLM: false
                },
                listes: [
                    { id: 'L1', valeurSaisie: 45.0 },
                    { id: 'L2', valeurSaisie: 35.0 },
                    { id: 'L3', valeurSaisie: 20.0 }
                ],
                nextId: 4,
                vainqueur2ndTour: null,
                resultats: null
            }
        },
        computed: {
            totalVoix() {
                if(this.modeSaisie === 'pourcentage') return 0;
                return this.listes.reduce((acc, l) => acc + (Number(l.valeurSaisie) || 0), 0);
            },
            totalScores() {
                if(this.modeSaisie === 'pourcentage') {
                    let total = this.listes.reduce((acc, l) => acc + (Number(l.valeurSaisie) || 0), 0);
                    return Math.round(total * 10) / 10;
                }
                return 100; // Si mode voix, le total % est toujours techniquement 100
            },
            isCalculable() {
                if(this.modeSaisie === 'pourcentage') return this.totalScores === 100;
                return this.totalVoix > 0; // Au moins 1 voix au total
            },
            listesAvecPourcentage() {
                // Calcule le % réel de chaque liste selon le mode de saisie
                return this.listes.map((l, index) => {
                    let pct = 0;
                    if (this.modeSaisie === 'pourcentage') {
                        pct = Number(l.valeurSaisie) || 0;
                    } else if (this.totalVoix > 0) {
                        pct = ((Number(l.valeurSaisie) || 0) / this.totalVoix) * 100;
                    }
                    return {
                        id: l.id,
                        nom: 'Liste ' + this.getLettre(index), // On génère le nom dynamiquement
                        scoreReel: Math.round(pct * 100) / 100, // Arrondi 2 décimales
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
            getLettre(index) {
                // Génère A, B, C, D...
                return String.fromCharCode(65 + index);
            },
            getLettreById(id) {
                const index = this.listes.findIndex(l => l.id === id);
                return index !== -1 ? this.getLettre(index) : '?';
            },
            getLettreByName(name) {
                return name.replace('Liste ', '');
            },
            ajouterListe() {
                this.listes.push({
                    id: 'L' + this.nextId++,
                    valeurSaisie: 0
                });
            },
            supprimerListe(index) {
                this.listes.splice(index, 1);
            },
            async lancerSimulation() {
                // Préparation des données avec le % converti
                let listesPourPhp = {};
                this.listesAvecPourcentage.forEach(l => { 
                    listesPourPhp[l.id] = { id: l.id, nom: l.nom, score_1er_tour: l.scoreReel, couleur: l.couleur }; 
                });

                let runnerUpId = null;
                if (!this.victoirePremierTour && this.vainqueur2ndTour) {
                    runnerUpId = (this.vainqueur2ndTour === this.finaliste1.id) ? this.finaliste2.id : this.finaliste1.id;
                }

                const payload = {
                    sieges: this.parametres.sieges,
                    isPLM: this.parametres.isPLM,
                    listes: listesPourPhp,
                    winner_2nd_tour: this.vainqueur2ndTour,
                    runner_up_2nd_tour: runnerUpId
                };

                try {
                    const response = await fetch(BASE_URL + 'simulateur/calculer', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    
                    const data = await response.json();
                    
                    if(data.error) {
                        alert("Erreur: " + data.error);
                    } else {
                        // On attache la couleur à chaque résultat pour le graphique
                        this.resultats = Object.values(data).map(res => {
                            const listeOrigine = listesPourPhp[Object.keys(listesPourPhp).find(key => listesPourPhp[key].nom === res.nom)];
                            res.couleur = listeOrigine ? listeOrigine.couleur : '#ccc';
                            return res;
                        });
                        
                        // Dessiner l'hémicycle après le rendu du DOM
                        this.$nextTick(() => {
                            this.dessinerHemicycle();
                        });
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                }
            },
            dessinerHemicycle() {
                const ctx = document.getElementById('hemicycleChart');
                if (!ctx) return;

                // Détruire l'ancien graphique s'il existe
                if (chartInstance) {
                    chartInstance.destroy();
                }

                // Extraction des données pour Chart.js
                const labels = this.resultats.map(r => r.nom);
                const dataSieges = this.resultats.map(r => r.total_sieges);
                const bgColors = this.resultats.map(r => r.couleur);

                chartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
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
                        // Paramètres pour faire un demi-cercle (Hémicycle)
                        rotation: -90,
                        circumference: 180,
                        cutout: '60%', // Épaisseur de l'anneau
                        plugins: {
                            legend: {
                                display: false // On la cache car on a déjà les couleurs dans le tableau
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ' : ' + context.raw + ' sièges';
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true
                        }
                    }
                });
            }
        },
        watch: {
            // Remettre à zéro le vainqueur si on bascule entre 1er tour et 2nd tour
            victoirePremierTour(val) {
                if(val) this.vainqueur2ndTour = null;
            },
            // Convertir intelligemment lors du changement de mode de saisie
            modeSaisie(newMode, oldMode) {
                if (newMode === 'pourcentage' && oldMode === 'voix' && this.totalVoix > 0) {
                    let currTotal = this.totalVoix;
                    this.listes.forEach(l => {
                        l.valeurSaisie = Number(((l.valeurSaisie / currTotal) * 100).toFixed(1));
                    });
                }
                // (Note : on ne convertit pas de % vers voix car on ne connait pas le total réel)
            }
        }
    }).mount('#app-simulateur')
</script>