<div class="character-detail-container">
    <div class="detail-header">
        <a href="/mes-personnages" class="back-link"><i class="fas fa-arrow-left"></i> Retour à mes personnages</a>
        <h1>
            <?= htmlspecialchars($character['name']) ?>
        </h1>
        <span class="status-badge status-<?= $character['status'] ?>">
            <?= ucfirst($character['status']) ?>
        </span>
    </div>

    <div class="detail-grid">
        <!-- Panel de visualisation -->
        <div class="visual-panel">
            <div class="character-preview-large" style="box-shadow: inset 0 0 100px <?= $character['skin_color'] ?>44;">
                <i class="fas fa-user-ninja fa-8x"></i>
                <div class="char-info">
                    <span class="tag">
                        <?= ucfirst($character['gender']) ?>
                    </span>
                    <span class="tag">
                        <?= ucfirst($character['hair_style']) ?>
                    </span>
                </div>
            </div>

            <div class="equipment-section">
                <h3>Équipements Équipés</h3>
                <div class="equipped-grid">
                    <?php foreach ($equipped as $item): ?>
                        <div class="item-icon" title="<?= htmlspecialchars($item['name']) ?>">
                            <i class="fas <?= htmlspecialchars($item['icon']) ?>"></i>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($equipped)): ?>
                        <p class="empty-msg">Aucun équipement spécial.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel de commentaires -->
        <div class="social-panel">
            <div class="reviews-section">
                <h2>Avis de la Communauté</h2>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'avis_en_attente'): ?>
                    <div class="alert alert-success">Merci ! Votre avis est en attente de modération.</div>
                <?php endif; ?>

                <?php if (!$hasVoted && isset($_SESSION['user'])): ?>
                    <div class="review-form-card">
                        <h3>Laissez un avis</h3>
                        <form action="/avis/nouveau" method="POST">
                            <input type="hidden" name="character_id" value="<?= $character['id'] ?>">
                            <div class="form-group">
                                <label>Note</label>
                                <select name="rating" class="rating-select">
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Très bon)</option>
                                    <option value="3">⭐⭐⭐ (Moyen)</option>
                                    <option value="2">⭐⭐ (Bof)</option>
                                    <option value="1">⭐ (Mauvais)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea name="comment" placeholder="Que pensez-vous de ce personnage ?"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full">Publier l'avis</button>
                        </form>
                    </div>
                <?php elseif (!isset($_SESSION['user'])): ?>
                    <p class="login-msg">Veuillez vous <a href="/connexion">connecter</a> pour laisser un avis.</p>
                <?php endif; ?>

                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-top">
                                <strong>
                                    <?= htmlspecialchars($review['pseudo']) ?>
                                </strong>
                                <span class="stars">
                                    <?= str_repeat('⭐', $review['rating']) ?>
                                </span>
                            </div>
                            <p class="review-comment">
                                <?= nl2br(htmlspecialchars($review['comment'])) ?>
                            </p>
                            <span class="review-date">
                                <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($reviews)): ?>
                        <div class="empty-state">
                            <i class="fas fa-comment-slash fa-3x"></i>
                            <p>Aucun avis pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .character-detail-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .detail-header {
        margin-bottom: 30px;
    }

    .back-link {
        color: var(--color-text-dim);
        font-size: 0.9rem;
        display: block;
        margin-bottom: 15px;
    }

    .detail-header h1 {
        display: inline-block;
        margin-right: 20px;
        font-size: 2.5rem;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-pending {
        background: #f39c12;
        color: white;
    }

    .status-approved {
        background: #27ae60;
        color: white;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 450px 1fr;
        gap: 40px;
    }

    .character-preview-large {
        background: rgba(0, 0, 0, 0.4);
        height: 500px;
        border-radius: 12px;
        border: 2px solid var(--color-primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 30px;
    }

    .char-info {
        position: absolute;
        bottom: 30px;
        display: flex;
        gap: 10px;
    }

    .tag {
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .equipped-grid {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .item-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--color-secondary);
    }

    .social-panel {
        background: rgba(255, 255, 255, 0.03);
        padding: 30px;
        border-radius: 12px;
    }

    .review-form-card {
        background: rgba(255, 255, 255, 0.05);
        padding: 25px;
        border-radius: 8px;
        margin: 25px 0 40px;
        border-left: 4px solid var(--color-primary);
    }

    .rating-select {
        width: 100%;
        padding: 10px;
        background: #222;
        border: 1px solid #444;
        color: white;
        margin-bottom: 15px;
    }

    textarea {
        width: 100%;
        height: 100px;
        background: #222;
        border: 1px solid #444;
        color: white;
        padding: 15px;
        border-radius: 6px;
        resize: none;
        margin-bottom: 15px;
    }

    .review-card {
        background: rgba(255, 255, 255, 0.02);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .review-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .review-comment {
        font-size: 0.95rem;
        color: var(--color-text);
        margin-bottom: 8px;
    }

    .review-date {
        font-size: 0.75rem;
        color: var(--color-text-dim);
    }

    .empty-state {
        text-align: center;
        padding: 60px 0;
        color: var(--color-text-dim);
    }

    .empty-state i {
        margin-bottom: 20px;
        opacity: 0.3;
    }

    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>