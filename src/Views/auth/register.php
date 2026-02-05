<div class="container" style="max-width: 600px; margin-top: 50px;">
    <div class="card">
        <h2 style="margin-bottom: 20px;">Rejoindre l'aventure</h2>
        <p style="color: var(--color-text-dim); margin-bottom: 30px;">Créez votre compte pour forger votre légende.</p>

        <?php if (isset($error)): ?>
            <div
                style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff4444; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/inscription" method="POST">
            <div style="margin-bottom: 20px; text-align: left;">
                <label for="pseudo" style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Pseudo
                    d'aventurier</label>
                <input type="text" name="pseudo" id="pseudo" required value="<?php echo $data['pseudo'] ?? ''; ?>"
                    placeholder="Ex: GorthakTheBrave"
                    style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
            </div>

            <div style="margin-bottom: 20px; text-align: left;">
                <label for="email" style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Adresse
                    Email</label>
                <input type="email" name="email" id="email" required value="<?php echo $data['email'] ?? ''; ?>"
                    style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
            </div>

            <div style="margin-bottom: 20px; text-align: left;">
                <label for="password" style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Mot de
                    passe secret</label>
                <input type="password" name="password" id="password" required
                    style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
                <small style="color: #666; display: block; margin-top: 5px;">Utilisez au moins 12 caractères pour une
                    protection maximale.</small>
            </div>

            <div style="margin-bottom: 30px; text-align: left; display: flex; gap: 10px; align-items: flex-start;">
                <input type="checkbox" id="rgpd" required style="margin-top: 5px;">
                <label for="rgpd" style="font-size: 0.85rem; color: var(--color-text-dim);">
                    J'accepte que mes données soient stockées pour la gestion de mon compte (RGPD).
                </label>
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; justify-content: center; margin-bottom: 20px;">
                Créer mon compte
            </button>

            <div style="text-align: center; color: var(--color-text-dim); font-size: 0.9rem;">
                Déjà membre ? <a href="/connexion" style="color: var(--color-secondary);">Se connecter</a>
            </div>
        </form>
    </div>
</div>