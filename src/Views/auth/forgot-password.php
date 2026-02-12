<div class="container" style="max-width: 500px; margin-top: 50px;">
    <div class="card">
        <h2 style="margin-bottom: 20px;">Mot de passe oublié ?</h2>
        <p style="color: var(--color-text-dim); margin-bottom: 30px;">Saisissez votre email pour recevoir un lien de
            réinitialisation.</p>

        <?php if (isset($error)): ?>
            <div
                style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff4444; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div
                style="background: rgba(0, 255, 0, 0.1); border: 1px solid #00c851; color: #00c851; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: left;">
                <?php echo $success; ?>
            </div>
            <div style="text-align: center;">
                <a href="/connexion" class="btn btn-outline">Retour à la connexion</a>
            </div>
        <?php else: ?>
            <form action="/mot-de-passe-oublie" method="POST">
                <?= \PixelVerseApp\Core\Security::csrfInput() ?>
                <div style="margin-bottom: 20px; text-align: left;">
                    <label for="email" style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Adresse
                        Email</label>
                    <input type="email" name="email" id="email" required
                        style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; justify-content: center; margin-bottom: 20px;">
                    Récupérer mon accès
                </button>

                <div style="text-align: center; color: var(--color-text-dim); font-size: 0.9rem;">
                    <a href="/connexion" style="color: var(--color-secondary);">Retour à la connexion</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>