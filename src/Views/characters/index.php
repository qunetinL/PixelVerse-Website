<div class="container character-list-container">
    <div class="list-header">
        <h1>Mes Personnages</h1>
        <a href="/creer-personnage" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Personnage
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['success'] === 'personnage_cree')
                echo "Votre personnage a été créé et est en attente de validation.";
            if ($_GET['success'] === 'personnage_supprime')
                echo "Personnage supprimé avec succès.";
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($characters)): ?>
        <div class="empty-state">
            <i class="fas fa-ghost fa-3x"></i>
            <p>Vous n'avez pas encore de personnage. <a href="/creer-personnage">Créez-en un dès maintenant !</a></p>
        </div>
    <?php else: ?>
        <div class="character-grid">
            <?php foreach ($characters as $char): ?>
                <div class="character-card <?= $char['status'] ?>">
                    <div class="card-image">
                        <i class="fas fa-user-ninja fa-3x"></i>
                        <span class="badge badge-<?= $char['status'] ?>">
                            <?= ucfirst($char['status']) ?>
                        </span>
                    </div>
                    <div class="card-info">
                        <h3>
                            <?= htmlspecialchars($char['name']) ?>
                        </h3>
                        <p>
                            <?= ucfirst($char['gender']) ?> |
                            <?= ucfirst($char['hair_style']) ?>
                        </p>
                        <div class="card-actions">
                            <a href="/creer-personnage?id=<?= $char['id'] ?>" class="btn-icon" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/supprimer-personnage?id=<?= $char['id'] ?>" class="btn-icon text-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce personnage ?')"
                                title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .character-list-container {
        padding: 40px 20px;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .character-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .character-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #333;
        transition: transform 0.3s ease;
    }

    .character-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
    }

    .card-image {
        background: rgba(0, 0, 0, 0.3);
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: var(--color-text-dim);
    }

    .badge {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-pending {
        background: #f39c12;
        color: white;
    }

    .badge-approved {
        background: #2ecc71;
        color: white;
    }

    .badge-rejected {
        background: #e74c3c;
        color: white;
    }

    .card-info {
        padding: 20px;
    }

    .card-info h3 {
        margin: 0 0 10px 0;
        color: var(--color-text);
    }

    .card-info p {
        color: var(--color-text-dim);
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .card-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn-icon {
        color: var(--color-text-dim);
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .btn-icon:hover {
        color: var(--color-primary);
    }

    .btn-icon.text-danger:hover {
        color: #e74c3c;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: var(--color-text-dim);
    }

    .empty-state i {
        margin-bottom: 20px;
    }
</style>