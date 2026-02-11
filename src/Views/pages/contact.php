<div class="container py-5">
    <div class="contact-header text-center mb-5">
        <h1 class="display-4">Contactez PixelVerse</h1>
        <p class="lead text-dim">Une question ? Un bug ? Notre équipe est à votre écoute.</p>
    </div>

    <div class="contact-wrapper">
        <div class="contact-info">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>Localisation</h3>
                    <p>Pixel Street, 75000 Paris, France</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>contact@pixelverse.com</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>Téléphone</h3>
                    <p>+33 1 23 45 67 89</p>
                </div>
            </div>

            <div class="social-links mt-4">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-discord"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
        </div>

        <div class="contact-form-container">
            <?php if (isset($_GET['success']) && $_GET['success'] === 'message_envoye'): ?>
                <div class="alert alert-success scroll-mt" id="success-alert">
                    <i class="fas fa-paper-plane mr-2"></i> Votre message a été envoyé avec succès ! Nous vous répondrons
                    sous 24h.
                </div>
            <?php endif; ?>

            <form action="/contact" method="POST" class="contact-form">
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required placeholder="Votre nom complet"
                            value="<?= htmlspecialchars($_SESSION['user']['pseudo'] ?? '') ?>">
                    </div>
                    <div class="form-group flex-1">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com"
                            value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <select id="sujet" name="sujet">
                        <option value="general">Question générale</option>
                        <option value="bug">Signaler un bug</option>
                        <option value="partenariat">Partenariat</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required
                        placeholder="Comment pouvons-nous vous aider ?"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-paper-plane"></i> Envoyer le message
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .contact-wrapper {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 50px;
        background: var(--color-bg-card);
        padding: 50px;
        border-radius: 20px;
        border: 1px solid #333;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .info-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .info-item i {
        font-size: 1.5rem;
        color: var(--color-secondary);
        background: rgba(13, 202, 240, 0.1);
        padding: 15px;
        border-radius: 10px;
    }

    .info-item h3 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: var(--color-text);
    }

    .info-item p {
        color: var(--color-text-dim);
    }

    .social-links {
        display: flex;
        gap: 15px;
    }

    .social-links a {
        font-size: 1.5rem;
        color: var(--color-text-dim);
        transition: var(--transition);
    }

    .social-links a:hover {
        color: var(--color-primary);
        transform: translateY(-3px);
    }

    /* Form Styles */
    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .flex-1 {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        background: #121212;
        border: 1px solid #444;
        border-radius: 8px;
        color: white;
        transition: var(--transition);
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--color-primary);
        outline: none;
        box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.2);
    }

    .text-dim {
        color: var(--color-text-dim);
    }

    @media (max-width: 992px) {
        .contact-wrapper {
            grid-template-columns: 1fr;
            padding: 30px;
        }

        .form-row {
            flex-direction: column;
            gap: 20px;
        }
    }
</style>