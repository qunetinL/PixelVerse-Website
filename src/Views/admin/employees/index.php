<div class="container admin-container py-5">
    <div class="admin-header">
        <h1>Gestion des Employés</h1>
        <p class="subtitle">Créez et gérez les comptes de modération</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-actions mb-4">
        <button class="btn btn-primary" onclick="document.getElementById('addEmployeeModal').style.display='flex'">
            <i class="fas fa-plus"></i> Ajouter un Employé
        </button>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($emp['pseudo']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($emp['email']) ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($emp['created_at'])) ?>
                        </td>
                        <td>
                            <form action="/admin/employes/supprimer" method="POST" style="display:inline;"
                                onsubmit="return confirm('Supprimer cet employé ?');">
                                <?= \PixelVerseApp\Core\Security::csrfInput() ?>
                                <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                <button type="submit" class="btn-icon delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout Employé -->
<div id="addEmployeeModal" class="modal">
    <div class="modal-content">
        <h3>Ajouter un nouvel employé</h3>
        <form action="/admin/employes/nouveau" method="POST">
            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe provisoire</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline"
                    onclick="this.closest('.modal').style.display='none'">Annuler</button>
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
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .modal-content {
        background: var(--color-bg-card);
        padding: 30px;
        border-radius: 8px;
        width: 100%;
        max-width: 450px;
        border: 1px solid var(--color-primary);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        background: #2a2a2a;
        border: 1px solid #444;
        color: white;
        border-radius: 4px;
    }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
</style>