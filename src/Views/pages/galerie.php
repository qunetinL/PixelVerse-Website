<div class="container py-5">
    <div class="gallery-header text-center mb-5">
        <h1 class="display-4">Galerie des Héros</h1>
        <p class="lead text-dim">Découvrez les légendes forgées par notre communauté.</p>
    </div>

    <?php if (empty($characters)): ?>
        <div class="text-center py-5">
            <i class="fas fa-users-slash fa-4x mb-3 text-muted"></i>
            <p>Aucun héros n'a encore été approuvé. Soyez le premier !</p>
            <a href="/creer-personnage" class="btn btn-primary mt-3">Créer mon personnage</a>
        </div>
    <?php else: ?>
        <div class="character-grid">
            <?php foreach ($characters as $char): ?>
                <div class="character-card">
                    <div class="card-preview">
                        <!-- Simulation de l'apparence -->
                        <div class="mini-avatar" style="background: <?= $char['skin_color'] ?>">
                            <div class="mini-hair <?= $char['hair_style'] ?>"></div>
                        </div>
                        <a href="/personnage?id=<?= $char['id'] ?>" class="card-overlay">
                            <i class="fas fa-eye"></i> Voir le profil
                        </a>
                    </div>
                    <div class="card-body">
                        <h3>
                            <?= htmlspecialchars($char['name']) ?>
                        </h3>
                        <p class="creator">Par <span>
                                <?= htmlspecialchars($char['user_pseudo']) ?>
                            </span></p>
                        <div class="traits">
                            <span class="trait-tag">
                                <?= ucfirst($char['gender']) ?>
                            </span>
                            <span class="trait-tag">
                                <?= ucfirst($char['hair_style']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .character-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .character-card {
        background: var(--color-bg-card);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #333;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .character-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-secondary);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .card-preview {
        height: 200px;
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(111, 66, 193, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: white;
        opacity: 0;
        transition: var(--transition);
        text-decoration: none;
        font-weight: bold;
    }

    .character-card:hover .card-overlay {
        opacity: 1;
    }

    .card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-body h3 {
        margin-bottom: 5px;
        color: var(--color-secondary);
    }

    .creator {
        font-size: 0.9rem;
        color: var(--color-text-dim);
        margin-bottom: 15px;
    }

    .creator span {
        color: var(--color-primary);
        font-weight: bold;
    }

    .trait-tag {
        background: #2a2a2a;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-right: 5px;
    }

    /* Simulation Avatar */
    .mini-avatar {
        width: 80px;
        height: 120px;
        border-radius: 10px;
        position: relative;
    }

    .mini-hair {
        position: absolute;
        top: -10px;
        left: 0;
        width: 100%;
        height: 40px;
        background: #4a3728;
        border-radius: 10px 10px 0 0;
    }

    .mini-hair.punk {
        border-radius: 50% 50% 0 0;
        background: #e74c3c;
        height: 50px;
        top: -20px;
    }

    .mini-hair.chauve {
        opacity: 0;
    }

    .text-dim {
        color: var(--color-text-dim);
    }
</style>