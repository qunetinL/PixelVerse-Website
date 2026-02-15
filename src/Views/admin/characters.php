<div class="container admin-container py-5">
    <div class="admin-header">
        <h1>Modération des Personnages</h1>
        <p class="subtitle">Validez les nouvelles créations des joueurs</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['success'] === 'personnage_approuve')
                echo "Personnage approuvé avec succès.";
            if ($_GET['success'] === 'personnage_refuse')
                echo "Personnage refusé et l'utilisateur a été notifié.";

            if (isset($_GET['mail_sent'])) {
                echo " <br><strong>[SIMULATION]</strong> Un email de notification a été envoyé au joueur.";
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($characters)): ?>
        <div class="empty-state">
            <i class="fas fa-check-circle fa-3x mb-3"></i>
            <p>Aucun personnage en attente de validation. Beau travail !</p>
        </div>
    <?php else: ?>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Joueur</th>
                        <th>Nom du Personnage</th>
                        <th>Apparence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($characters as $char): ?>
                        <tr>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($char['created_at'])) ?>
                            </td>
                            <td><span class="badge-role">
                                    <?= htmlspecialchars($char['user_pseudo']) ?>
                                </span></td>
                            <td><strong>
                                    <?= htmlspecialchars($char['name']) ?>
                                </strong></td>
                            <td>
                                <span class="char-trait">
                                    <?= ucfirst($char['gender']) ?>
                                </span>
                                <span class="char-trait">
                                    <?= ucfirst($char['hair_style']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="/admin/personnages/approuver?id=<?= $char['id'] ?>" class="btn-admin btn-approve"
                                    onclick="return confirm('Approuver ce personnage ?')" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </a>
                                <button class="btn-admin btn-reject"
                                    onclick="showRejectModal(<?= $char['id'] ?>, '<?= addslashes($char['name']) ?>')"
                                    title="Refuser">
                                    <i class="fas fa-times"></i>
                                </button>
                                <a href="/personnage?id=<?= $char['id'] ?>" class="btn-admin btn-view" target="_blank"
                                    title="Voir la fiche">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de Refus -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <h3>Refuser le personnage : <span id="rejectCharName"></span></h3>
        <form action="/admin/personnages/refuser" method="POST">
            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
            <input type="hidden" name="id" id="rejectCharId">
            <div class="form-group">
                <label for="reason">Raison du refus (sera visible par le joueur) :</label>
                <textarea name="reason" id="reason" required
                    placeholder="Ex: Nom inapproprié, apparence non conforme..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Annuler</button>
                <button type="submit" class="btn btn-danger">Confirmer le Refus</button>
            </div>
        </form>
    </div>
</div>

<style>
    .admin-container {
        padding: 40px 20px;
    }

    .admin-header {
        margin-bottom: 30px;
    }

    .subtitle {
        color: var(--color-text-dim);
    }

    .admin-table-container {
        background: var(--color-bg-card);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #333;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #333;
    }

    .admin-table th {
        background: rgba(255, 255, 255, 0.05);
        color: var(--color-secondary);
        font-family: var(--font-title);
        font-size: 0.8rem;
    }

    .badge-role {
        background: rgba(111, 66, 193, 0.2);
        color: var(--color-primary);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .char-trait {
        display: inline-block;
        background: #2a2a2a;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.75rem;
        margin-right: 5px;
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .btn-admin {
        width: 35px;
        height: 35px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .btn-approve {
        background: rgba(46, 204, 113, 0.2);
        color: #2ecc71;
    }

    .btn-approve:hover {
        background: #2ecc71;
        color: white;
    }

    .btn-reject {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    .btn-reject:hover {
        background: #e74c3c;
        color: white;
    }

    .btn-view {
        background: rgba(13, 202, 240, 0.2);
        color: var(--color-secondary);
    }

    .btn-view:hover {
        background: var(--color-secondary);
        color: white;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: var(--color-bg-card);
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        border: 1px solid #444;
    }

    .form-group {
        margin: 20px 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: var(--color-text-dim);
    }

    .form-group textarea {
        width: 100%;
        height: 120px;
        background: #121212;
        border: 1px solid #444;
        color: white;
        padding: 10px;
        border-radius: 4px;
        resize: vertical;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
</style>

<script>
    function showRejectModal(id, name) {
        document.getElementById('rejectCharId').value = id;
        document.getElementById('rejectCharName').textContent = name;
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    // Fermer si on clique en dehors
    window.onclick = function (event) {
        const modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>