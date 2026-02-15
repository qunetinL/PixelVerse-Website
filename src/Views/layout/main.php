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
                            <li class="dropdown">
                                <a href="#" class="nav-link admin-highlight dropdown-toggle">
                                    Administration <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                        <li><a href="/admin/employes">Gestion Employés</a></li>
                                    <?php endif; ?>
                                    <li><a href="/admin/utilisateurs">Gestion Utilisateurs</a></li>
                                    <li><a href="/admin/accessoires">Catalogue Accessoires</a></li>
                                    <li><a href="/admin/personnages">Modération Persos</a></li>
                                    <li><a href="/admin/avis">Modération Avis</a></li>
                                    <li><a href="/admin/logs">Logs NoSQL</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <li class="dropdown">
                            <a href="#" class="nav-link standout dropdown-toggle">
                                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user']['pseudo']) ?> <i
                                    class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/creer-personnage">Créer un Personnage</a></li>
                                <li><a href="/mes-personnages">Mes Personnages</a></li>
                                <li><a href="/deconnexion" style="color: #e74c3c;">Déconnexion</a></li>
                            </ul>
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