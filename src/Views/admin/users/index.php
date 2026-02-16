<div class="container admin-container py-5">
    <div class="admin-header  text-center mb-5">
        <h1 class="display-4 standout-text">Gestion des Utilisateurs</h1>
        <p class="subtitle">Modérez les comptes joueurs et gérez les accès</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm mr-3">
                                        <i class="fas fa-user-circle fa-2x opacity-20"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold"><?= htmlspecialchars($u['pseudo']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role role-<?= $u['role'] ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['est_suspendu']): ?>
                                    <span class="status-badge suspended">
                                        <i class="fas fa-ban mr-1"></i> Suspendu
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge active">
                                        <i class="fas fa-check mr-1"></i> Actif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="dropdown action-dropdown">
                                    <button class="btn-icon dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <form action="/admin/utilisateurs/toggle-suspension" method="POST">
                                            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <button type="submit"
                                                class="dropdown-item <?= $u['est_suspendu'] ? 'text-success' : 'text-danger' ?>">
                                                <i
                                                    class="fas <?= $u['est_suspendu'] ? 'fa-user-check' : 'fa-user-slash' ?> mr-2"></i>
                                                <?= $u['est_suspendu'] ? 'Réactiver' : 'Suspendre' ?>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .badge-role {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .role-employe {
        background: rgba(241, 196, 15, 0.2);
        color: #f1c40f;
        border: 1px solid rgba(241, 196, 15, 0.3);
    }

    .role-joueur {
        background: rgba(52, 152, 219, 0.2);
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
    }

    .status-badge.suspended {
        background: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }

    .action-dropdown .dropdown-toggle::after {
        display: none;
    }

    .action-dropdown .btn-icon {
        background: none;
        border: none;
        color: var(--color-text-dim);
        padding: 8px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .action-dropdown .btn-icon:hover {
        color: white;
    }

    .dropdown-menu {
        background: var(--color-bg-card);
        border: 1px solid #444;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    .dropdown-item {
        color: white;
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }
</style>