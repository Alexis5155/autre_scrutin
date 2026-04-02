<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L'autre scrutin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #ec4899;
            --bg-color: #f8fafc;
            --text-main: #0f172a;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
        }

        .wrapper {
            position: relative;
            /* On retire overflow-x: hidden d'ici car il est géré par body, 
               évitant ainsi le conflit de la double scrollbar */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* On coupe les éléments qui déborderaient (comme le translate de l'animation) 
               en bas de page pour ne pas créer de hauteur fantôme */
            overflow: hidden; 
        }

        main {
            flex-grow: 1; /* Pousse le footer vers le bas si la page est courte */
        }

        /* Design Organique */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(79, 70, 229, 0.15);
        }
        .blob-bg {
            position: absolute;
            top: -10%; right: -5%;
            width: 50vw; height: 50vw;
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            filter: blur(100px);
            border-radius: 50%;
            opacity: 0.15;
            z-index: -1;
            animation: float 10s infinite ease-in-out alternate;
            pointer-events: none; /* Empêche la bulle d'interférer avec les clics */
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-50px, 50px) scale(1.1); }
        }
        
        /* Animations d'apparition */
        .fade-in-up {
            /* On force l'élément à être invisible et décalé vers le bas AVANT l'animation */
            opacity: 0;
            transform: translateY(30px);
            /* L'animation se joue normalement, 'forwards' maintient l'état final (opacité 1) */
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        @keyframes fadeInUp {
            0% { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            100% { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        .btn-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: scale(1.05);
            color: white;
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

                /* Overlay de Recherche */
        .search-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(248, 250, 252, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .search-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }
        .search-container {
            width: 90%;
            max-width: 700px;
            transform: translateY(40px) scale(0.95);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .search-overlay.active .search-container {
            transform: translateY(0) scale(1);
        }
        .search-input-main {
            width: 100%;
            font-size: 2.5rem;
            padding: 15px 0;
            border: none;
            border-bottom: 3px solid var(--primary);
            background: transparent;
            color: var(--text-main);
            outline: none;
            font-weight: 800;
        }
        .search-input-main::placeholder {
            color: rgba(15, 23, 42, 0.3);
        }
        .btn-close-overlay {
            position: absolute;
            top: 40px; right: 50px;
            background: none; border: none;
            font-size: 3rem; color: var(--text-main);
            cursor: pointer; opacity: 0.5; transition: opacity 0.2s;
        }
        .btn-close-overlay:hover { opacity: 1; }
        
        .capsule {
            display: inline-block;
            padding: 8px 20px; margin: 5px;
            border-radius: 50px;
            background: white; border: 1px solid rgba(79,70,229,0.2);
            color: var(--primary); font-weight: 600; font-size: 0.9rem;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .capsule:hover {
            background: var(--primary); color: white;
            transform: translateY(-3px); box-shadow: 0 6px 12px rgba(79,70,229,0.2);
        }
        
        /* Résultats de l'auto-complétion */
        .search-results {
            position: absolute; width: 100%;
            background: white; border-radius: 0 0 16px 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden; z-index: 10;
        }
        .search-result-item {
            padding: 15px 20px; font-size: 1.2rem; cursor: pointer;
            border-bottom: 1px solid #eee; transition: background 0.2s;
        }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: var(--bg-color); color: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Overlay de Recherche -->
    <div id="searchOverlay" class="search-overlay">
        <button class="btn-close-overlay" id="closeSearch"><i class="bi bi-x"></i></button>
        <div class="search-container position-relative">
            <input type="text" id="searchInputMain" class="search-input-main" placeholder="Votre commune..." autocomplete="off">
            
            <!-- Conteneur des résultats de l'API -->
            <div id="searchResults" class="search-results d-none"></div>

            <div class="mt-4">
                <p class="text-muted small text-uppercase fw-bold mb-2">Villes principales</p>
                <div>
                    <!-- Les codes INSEE sont utilisés en data-code -->
                    <span class="capsule" data-code="75056">Paris</span>
                    <span class="capsule" data-code="13055">Marseille</span>
                    <span class="capsule" data-code="69123">Lyon</span>
                    <span class="capsule" data-code="31555">Toulouse</span>
                    <span class="capsule" data-code="06088">Nice</span>
                    <span class="capsule" data-code="44109">Nantes</span>
                    <span class="capsule" data-code="34172">Montpellier</span>
                    <span class="capsule" data-code="67482">Strasbourg</span>
                    <span class="capsule" data-code="33063">Bordeaux</span>
                    <span class="capsule" data-code="59350">Lille</span>
                    <span class="capsule" data-code="35238">Rennes</span>
                    <span class="capsule" data-code="51454">Reims</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Le Wrapper encapsule tout pour gérer l'overflow -->
    <div class="wrapper">
        <div class="blob-bg"></div>

        <nav class="navbar navbar-expand-lg navbar-light pt-4 fade-in-up">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="<?= BASE_URL ?>">L'autre<span style="color:var(--primary)">Scrutin</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav align-items-center gap-3">
                        <li class="nav-item"><a class="nav-link fw-semibold" href="<?= BASE_URL ?>">La Réforme</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="<?= BASE_URL ?>simulateur/manuel">Simulateur Manuel</a></li>
                        <li class="nav-item">
                            <a class="btn btn-custom btn-sm" href="<?= BASE_URL ?>#recherche">Chercher ma ville</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <?= $content ?>
        </main>

        <footer class="container text-center py-5 mt-5 fade-in-up">
            <p class="text-muted">© 2026 - Alexis Leleu</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        // Gestion de l'Overlay de recherche
        const searchOverlay = document.getElementById('searchOverlay');
        const searchInputMain = document.getElementById('searchInputMain');
        const closeSearch = document.getElementById('closeSearch');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout = null;

        // Fonction pour ouvrir l'overlay
        function openSearch() {
            searchOverlay.classList.add('active');
            setTimeout(() => searchInputMain.focus(), 100); // Focus après l'animation
        }

        // Fermer l'overlay
        closeSearch.addEventListener('click', () => {
            searchOverlay.classList.remove('active');
            searchResults.classList.add('d-none');
            searchInputMain.value = '';
        });

        // Fermer avec la touche Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch.click();
            }
        });

        // Intercepter tous les liens menant à #recherche
        document.querySelectorAll('a[href$="#recherche"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                openSearch();
            });
        });

        // Clic sur une capsule
        document.querySelectorAll('.capsule').forEach(capsule => {
            capsule.addEventListener('click', () => {
                const codeInsee = capsule.getAttribute('data-code');
                window.location.href = '<?= BASE_URL ?>simulateur/ville/' + codeInsee;
            });
        });

        // Appel à l'API Geo Gouv lors de la frappe (Autocomplétion)
        searchInputMain.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchResults.classList.add('d-none');
                return;
            }

            // Debounce : on attend 300ms après la dernière frappe avant d'interroger l'API
            searchTimeout = setTimeout(async () => {
                try {
                    // L'API Geo de l'Etat. On demande les villes, boostées par population
                    const response = await fetch(`https://geo.api.gouv.fr/communes?nom=${query}&boost=population&limit=5`);
                    const communes = await response.json();
                    
                    searchResults.innerHTML = '';
                    
                    if (communes.length > 0) {
                        communes.forEach(commune => {
                            const div = document.createElement('div');
                            div.className = 'search-result-item d-flex justify-content-between align-items-center';
                            div.innerHTML = `
                                <span><i class="bi bi-geo-alt me-2 text-primary"></i> ${commune.nom}</span>
                                <span class="badge bg-light text-dark border">${commune.codesPostaux[0]}</span>
                            `;
                            div.addEventListener('click', () => {
                                window.location.href = '<?= BASE_URL ?>simulateur/ville/' + commune.code;
                            });
                            searchResults.appendChild(div);
                        });
                        searchResults.classList.remove('d-none');
                    } else {
                        searchResults.innerHTML = '<div class="search-result-item text-muted">Aucune commune trouvée</div>';
                        searchResults.classList.remove('d-none');
                    }
                } catch (err) {
                    console.error("Erreur API Geo", err);
                }
            }, 300);
        });
    </script>
</body>

</html>