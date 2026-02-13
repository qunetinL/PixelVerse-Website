<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?? 'PixelVerse Studios'; ?>
    </title>
    <link rel="stylesheet" href="/css/style.css?v=1.2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="container nav-container">
            <a href="/" class="logo">
                <i class="fas fa-gamepad"></i> PIXELVERSE
            </a>
            <button class="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="main-nav">
                <ul>
                    <li><a href="/" class="nav-link">Accueil</a></li>
                    <li><a href="/galerie" class="nav-link">Galerie</a></li>
                    <li><a href="/contact" class="nav-link">Contact</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if (in_array($_SESSION['user']['role'], ['admin', 'employe'])): ?>
                            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                <li><a href="/admin/employes" class="nav-link admin-highlight">Employés</a></li>
                            <?php endif; ?>
                            <li><a href="/admin/utilisateurs" class="nav-link admin-highlight">Utilisateurs</a></li>
                            <li><a href="/admin/accessoires" class="nav-link admin-highlight">Admin</a></li>
                            <li><a href="/admin/personnages" class="nav-link admin-highlight">Modération Persos</a></li>
                            <li><a href="/admin/avis" class="nav-link admin-highlight">Modération Avis</a></li>
                            <li><a href="/admin/logs" class="nav-link admin-highlight">Logs NoSQL</a></li>
                        <?php endif; ?>
                        <li><a href="/creer-personnage" class="nav-link standout">Créer Perso</a></li>
                        <li><a href="/mes-personnages" class="nav-link">Mes Persos</a></li>
                        <li class="user-profile-item">
                            <div class="user-profile">
                                <span class="pseudo"><?= htmlspecialchars($_SESSION['user']['pseudo']) ?></span>
                                <a href="/deconnexion" class="logout-icon" title="Déconnexion">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="/connexion" class="btn-connexion">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <?php echo $content; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 PixelVerse Studios. Tous droits réservés.</p>
            <div class="footer-links">
                <a href="/mentions-legales">Mentions Légales</a>
                <a href="/cgv">CGV</a>
            </div>
            <div class="social-links-footer mt-3">
                <a href="#" aria-label="Suivez-nous sur Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Rejoignez notre Discord"><i class="fab fa-discord"></i></a>
                <a href="#" aria-label="Voir notre code sur GitHub"><i class="fab fa-github"></i></a>
            </div>
            <?php if (isset($dbStatus)): ?>
                <p style="margin-top:10px; font-size: 0.7rem;">DB Status:
                    <?php echo $dbStatus; ?>
                </p>
            <?php endif; ?>
        </div>
    </footer>

    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.getElementById('main-nav');

        menuToggle.addEventListener('click', function () {
            const isActive = mainNav.classList.toggle('active');
            this.setAttribute('aria-expanded', isActive);
            this.setAttribute('aria-label', isActive ? 'Fermer le menu' : 'Ouvrir le menu');

            const icon = this.querySelector('i');
            if (isActive) {
                icon.classList.replace('fa-bars', 'fa-xmark');
            } else {
                icon.classList.replace('fa-xmark', 'fa-bars');
            }
        });
    </script>
</body>

</html>