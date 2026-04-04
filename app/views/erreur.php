<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="glass-card p-5 shadow-sm border-0 bg-white text-center rounded-4">

                <div class="mb-4">
                    <span class="display-1 text-<?= htmlspecialchars($config['couleur']) ?>">
                        <i class="bi <?= htmlspecialchars($config['icon']) ?>"></i>
                    </span>
                </div>

                <span class="badge bg-<?= htmlspecialchars($config['couleur']) ?> bg-opacity-10
                             text-<?= htmlspecialchars($config['couleur']) ?>
                             rounded-pill px-3 py-2 mb-3 fw-semibold fs-6">
                    Erreur <?= $code ?>
                </span>

                <h1 class="fw-bold fs-3 mt-3 mb-2"><?= htmlspecialchars($config['titre']) ?></h1>

                <p class="text-muted mb-4 lh-base">
                    <?= htmlspecialchars($message) ?>
                </p>

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-house-fill me-2"></i>Retour à l'accueil
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Page précédente
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>