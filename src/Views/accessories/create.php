<div class="container admin-container">
    <div class="admin-header">
        <h1>Nouveau Personnage / Article</h1>
        <a href="/admin/accessoires" class="btn btn-outline">Retour</a>
    </div>

    <div class="admin-form-card">
        <form action="/admin/accessoires/nouveau" method="POST">
            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
            <div class="form-group">
                <label for="name">Nom de l'article</label>
                <input type="text" name="name" id="name" required placeholder="Ex: Épée de Cristal">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" required>
                        <option value="arme">Arme</option>
                        <option value="vêtement">Vêtement</option>
                        <option value="accessoire">Accessoire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="icon">Icone (FontAwesome class)</label>
                    <input type="text" name="icon" id="icon" placeholder="fa-sword">
                </div>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" checked>
                <label for="is_active">Rendre cet article disponible immédiatement</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Créer l'article</button>
            </div>
        </form>
    </div>
</div>

<style>
    .admin-container {
        padding: 60px 20px;
        max-width: 800px;
    }

    .admin-form-card {
        background: rgba(255, 255, 255, 0.05);
        padding: 40px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: var(--color-primary);
        font-size: 0.9rem;
    }

    .form-group input[type="text"],
    .form-group select {
        width: 100%;
        padding: 12px;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        color: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .checkbox-group input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin-bottom: 0;
        color: var(--color-text-dim);
        cursor: pointer;
    }

    .form-actions {
        margin-top: 40px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 30px;
    }

    .btn-large {
        width: 100%;
        padding: 15px;
    }
</style>