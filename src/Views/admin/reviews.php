<div class="container admin-container">
    <div class="admin-header">
        <h1>Modération des Avis</h1>
        <p>
            <?= count($reviews) ?> avis en attente de validation
        </p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['success'] === 'avis_approuve')
                echo "L'avis a été publié.";
            if ($_GET['success'] === 'avis_supprime')
                echo "L'avis a été rejeté.";
            ?>
        </div>
    <?php endif; ?>

    <div class="reviews-moderation-grid">
        <?php foreach ($reviews as $review): ?>
            <div class="mod-card">
                <div class="mod-header">
                    <div class="user-info">
                        <strong>
                            <?= htmlspecialchars($review['user_pseudo']) ?>
                        </strong>
                        sur <span>
                            <?= htmlspecialchars($review['character_name']) ?>
                        </span>
                    </div>
                    <div class="mod-rating">
                        <?= str_repeat('⭐', $review['rating']) ?>
                    </div>
                </div>
                <div class="mod-body">
                    <p>"
                        <?= nl2br(htmlspecialchars($review['comment'])) ?>"
                    </p>
                </div>
                <div class="mod-footer">
                    <span class="mod-date">
                        <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                    </span>
                    <div class="mod-actions">
                        <a href="/admin/avis/approuver?id=<?= $review['id'] ?>" class="btn-mod approve">
                            <i class="fas fa-check"></i> Approuver
                        </a>
                        <a href="/admin/avis/supprimer?id=<?= $review['id'] ?>" class="btn-mod reject"
                            onclick="return confirm('Rejeter cet avis ?')">
                            <i class="fas fa-times"></i> Rejeter
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($reviews)): ?>
            <div class="empty-mod">
                <i class="fas fa-check-circle fa-4x"></i>
                <p>Tous les avis ont été traités !</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .admin-container {
        padding: 60px 20px;
        max-width: 1000px;
    }

    .admin-header {
        margin-bottom: 40px;
    }

    .admin-header h1 {
        font-size: 2rem;
        color: var(--color-text);
    }

    .admin-header p {
        color: var(--color-primary);
        font-weight: bold;
    }

    .reviews-moderation-grid {
        display: grid;
        gap: 20px;
    }

    .mod-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .mod-header {
        padding: 15px 20px;
        background: rgba(0, 0, 0, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-info span {
        color: var(--color-secondary);
        font-size: 0.85rem;
    }

    .mod-body {
        padding: 20px;
        font-style: italic;
        color: var(--color-text-dim);
    }

    .mod-footer {
        padding: 15px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mod-date {
        font-size: 0.8rem;
        color: #666;
    }

    .mod-actions {
        display: flex;
        gap: 15px;
    }

    .btn-mod {
        padding: 6px 15px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .approve {
        background: #27ae60;
        color: white;
    }

    .approve:hover {
        background: #2ecc71;
    }

    .reject {
        background: #c0392b;
        color: white;
    }

    .reject:hover {
        background: #e74c3c;
    }

    .empty-mod {
        text-align: center;
        padding: 100px 0;
        color: #27ae60;
    }

    .empty-mod i {
        margin-bottom: 20px;
    }
</style>