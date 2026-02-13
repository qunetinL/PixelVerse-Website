<div class="container admin-container py-5">
    <div class="admin-header">
        <h1>Gestion des Utilisateurs</h1>
        <p class="subtitle">Modérez les comptes joueurs</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <strong>
                                <?= htmlspecialchars($u['pseudo']) ?>
                            </strong><br>
                            <small class="text-dim">
                                <?= htmlspecialchars($u['email']) ?>
                            </small>
                        </td>
                        <td>
                            <span
                                class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'employe' ? 'warning' : 'info') ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['is_suspended']): ?>
                                <span class="status-badge suspended">Suspendu</span>
                            <?php else: ?>
                                <span class="status-badge active">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="/admin/utilisateurs/toggle-suspension" method="POST" style="display:inline;">
                                <?= \PixelVerseApp\Core\Security::csrfInput() ?>
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit"
                                    class="btn <?= $u['is_suspended'] ? 'btn-success' : 'btn-danger' ?> btn-sm">
                                    <i class="fas <?= $u['is_suspended'] ? 'fa-user-check' : 'fa-user-slash' ?>"></i>
                                    <?= $u['is_suspended'] ? 'Réactiver' : 'Suspendre' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-badge.active {
        background: rgba(46, 204, 113, 0.2);
        color: #2ecc71;
    }

    .status-badge.suspended {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    .badge-danger {
        background: #e74c3c;
        color: white;
    }

    .badge-warning {
        background: #f1c40f;
        color: black;
    }

    .badge-info {
        background: #3498db;
        color: white;
    }

    .badge {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>