<div class="container admin-container py-5">
    <div class="admin-header text-center mb-5">
        <h1 class="display-4 standout-text">Modération des Avis</h1>
        <p class="subtitle"><?= count($reviews) ?> avis en attente de validation</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php
            if ($_GET['success'] === 'avis_approuve') echo "L'avis a été publié.";
            if ($_GET['success'] === 'avis_supprime') echo "L'avis a été rejeté.";
            if (isset($_GET['mail_sent'])) echo " <br><strong>[SIMULATION]</strong> Notification envoyée.";
            ?>
        </div>
    <?php endif; ?>

    <div class="admin-card p-0" style="background: transparent; border: none;">
        <div class="reviews-moderation-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="mod-card">
                    <div class="mod-header-nav">
                        <div class="user-info">
                            <span class="font-weight-bold"><?= htmlspecialchars($review['user_pseudo']) ?></span>
                            <span class="text-muted mx-1">sur</span>
                            <span class="text-secondary"><?= htmlspecialchars($review['character_name']) ?></span>
                        </div>
                        <div class="mod-rating">
                            <?php for($i=0; $i<5; $i++): ?>
                                <i class="fas fa-star <?= $i < $review['rating'] ? 'text-warning' : 'opacity-20' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mod-body">
                        <p class="mb-0">"<?= nl2br(htmlspecialchars($review['comment'])) ?>"</p>
                    </div>
                    <div class="mod-footer-nav">
                        <span class="mod-date">
                            <i class="far fa-clock mr-1"></i> <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                        </span>
                        <div class="mod-actions">
                            <a href="/admin/avis/approuver?id=<?= $review['id'] ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-check mr-1"></i> Approuver
                            </a>
                            <a href="/admin/avis/supprimer?id=<?= $review['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Rejeter cet avis ?')">
                                <i class="fas fa-times mr-1"></i> Rejeter
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($reviews)): ?>
                <div class="empty-state text-center py-5">
                    <i class="fas fa-check-circle fa-4x mb-3 text-success opacity-50"></i>
                    <p class="lead">Tous les avis ont été traités !</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .reviews-moderation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
        gap: 25px;
    }

    .mod-card {
        background: var(--color-bg-card);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .mod-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .mod-header-nav {
        padding: 15px 20px;
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .mod-body {
        padding: 25px 20px;
        flex-grow: 1;
        font-style: italic;
        color: var(--color-text-dim);
        line-height: 1.6;
    }

    .mod-footer-nav {
        padding: 15px 20px;
        border-top: 1px solid rgba(255,255,255,0.05);
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
        gap: 10px;
    }

    @media (max-width: 768px) {
        .reviews-moderation-grid {
            grid-template-columns: 1fr;
        }
    }
</style>