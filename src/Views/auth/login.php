<div class="container" style="max-width: 500px; margin-top: 50px;">
    <div class="card">
        <h2 style="margin-bottom: 20px;">Connexion</h2>

        <?php if (isset($error)): ?>
            <div
                style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff4444; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div
                style="background: rgba(0, 255, 0, 0.1); border: 1px solid #00c851; color: #00c851; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                Votre compte a été créé avec succès. Connectez-vous maintenant !
            </div>
        <?php endif; ?>

        <form action="/connexion" method="POST">
            <?= \PixelVerseApp\Core\Security::csrfInput() ?>
            <div style="margin-bottom: 20px; text-align: left;">
                <label for="email"
                    style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Email</label>
                <input type="email" name="email" id="email" required value="<?php echo $email ?? ''; ?>"
                    style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
            </div>

            <div style="margin-bottom: 20px; text-align: left;">
                <label for="password" style="display: block; margin-bottom: 5px; color: var(--color-text-dim);">Mot de
                    passe</label>
                <input type="password" name="password" id="password" required
                    style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #444; background: #2a2a2a; color: white;">
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; justify-content: center; margin-bottom: 15px;">
                Se Connecter
            </button>

            <div style="text-align: center; font-size: 0.9rem;">
                <a href="/mot-de-passe-oublie" style="color: var(--color-secondary);">Mot de passe oublié ?</a>
                <span style="color: var(--color-text-dim); margin: 0 10px;">|</span>
                <a href="/inscription" style="color: var(--color-secondary);">Créer un compte</a>
            </div>
        </form>
    </div>
</div>