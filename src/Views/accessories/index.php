<div class="container admin-container">
    <div class="admin-header">
        <h1>Gestion du Catalogue d'Accessoires</h1>
        <a href="/admin/accessoires/nouveau" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un article
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            if ($_GET['success'] === 'accessoire_cree')
                echo "L'accessoire a été ajouté au catalogue.";
            if ($_GET['success'] === 'accessoire_supprime')
                echo "L'accessoire a été retiré du catalogue.";
            ?>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Icone</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accessories as $acc): ?>
                    <tr>
                        <td><i class="fas <?= htmlspecialchars($acc['icon']) ?> fa-lg"></i></td>
                        <td><strong>
                                <?= htmlspecialchars($acc['name']) ?>
                            </strong></td>
                        <td><span class="type-badge">
                                <?= ucfirst($acc['type']) ?>
                            </span></td>
                        <td>
                            <span class="status-indicator <?= $acc['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $acc['is_active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="/admin/accessoires/supprimer?id=<?= $acc['id'] ?>" class="btn-delete"
                                onclick="return confirm('Êtes-vous sûr de vouloir retirer cet article ?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($accessories)): ?>
                    <tr>
                        <td colspan="5" class="empty-msg">Aucun accessoire dans le catalogue.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .admin-container {
        padding: 60px 20px;
        max-width: 1000px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .admin-header h1 {
        font-family: 'Cinzel', serif;
        font-size: 2rem;
        color: var(--color-text);
    }

    .admin-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        padding: 20px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .admin-table th {
        background: rgba(0, 0, 0, 0.2);
        color: var(--color-primary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .type-badge {
        background: rgba(255, 255, 255, 0.1);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .status-indicator {
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-indicator::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-indicator.active::before {
        background: #2ecc71;
        box-shadow: 0 0 5px #2ecc71;
    }

    .status-indicator.inactive::before {
        background: #e74c3c;
    }

    .btn-delete {
        color: #e74c3c;
        transition: color 0.3s;
    }

    .btn-delete:hover {
        color: #ff5e5e;
    }

    .empty-msg {
        text-align: center;
        color: var(--color-text-dim);
        font-style: italic;
        padding: 40px !important;
    }
</style>