<div class="container admin-container py-5">
    <div class="admin-header text-center mb-5">
        <h1 class="display-4 standout-text">Gestion des Employés</h1>
        <p class="subtitle">Créez et gérez les comptes de modération de la plateforme</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-actions mb-4">
        <button class="btn btn-primary" onclick="document.getElementById('addEmployeeModal').style.display='flex'">
            <i class="fas fa-plus"></i> Ajouter un Employé
        </button>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Inscrit le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">Aucun employé trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td class="font-weight-bold">
                                    <i class="fas fa-user-tie mr-2 opacity-50"></i>
                                    <?= htmlspecialchars($emp['pseudo']) ?>
                                </td>
                                <td><?= htmlspecialchars($emp['email']) ?></td>
                                <td>
                                    <span
                                        class="text-muted"><?= $emp['date_creation'] ? date('d/m/Y', strtotime($emp['date_creation'])) : 'N/A' ?></span>
                                </td>
                                <td class="text-center">
                                    <form action="/admin/employes/supprimer" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer cet employé ?');">
                                        <?= \PixelVerseApp\Core\Security::csrfInput() ?>
                                        <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                        <button type="submit" class="btn-icon delete" title="Supprimer l'employé">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout Employé -->
<div id="addEmployeeModal" class="modal">
    <div class="modal-content animate-slide-down">
        <div class="modal-header-nav">
            <h3 class="mb-0">Nouvel Employé</h3>
            <button class="close-btn"
                onclick="document.getElementById('addEmployeeModal').style.display='none'">&times;</button>
        </div>
        <form action="/admin/employes/nouveau" method="POST" class="mt-4">
            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
            <div class="form-group mb-3">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" class="form-control" required
                    placeholder="nom d'utilisateur">
            </div>
            <div class="form-group mb-3">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" required
                    placeholder="email@pixelverse.com">
            </div>
            <div class="form-group mb-4">
                <label for="password">Mot de passe provisoire</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <p class="text-small text-muted mt-1">L'employé devra le changer à sa première connexion.</p>
            </div>
            <div class="modal-footer px-0 pb-0">
                <button type="button" class="btn btn-outline"
                    onclick="document.getElementById('addEmployeeModal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer le compte</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .modal-content {
        background: var(--color-bg-card);
        padding: 40px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        border: 1px solid var(--color-primary);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }

    .modal-header-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 15px;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        line-height: 1;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .close-btn:hover {
        opacity: 1;
    }

    .animate-slide-down {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .form-control {
        background: rgba(0, 0, 0, 0.2) !important;
        border: 1px solid #444 !important;
        color: white !important;
        padding: 12px !important;
    }

    .form-control:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 2px rgba(108, 92, 231, 0.2) !important;
    }
</style>