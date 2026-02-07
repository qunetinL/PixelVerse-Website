<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?? 'PixelVerse Studios'; ?>
    </title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="container nav-container">
            <a href="/" class="logo">
                <i class="fas fa-gamepad"></i> PIXELVERSE
            </a>
            <button class="menu-toggle" aria-label="Ouvrir le menu">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="main-nav">
                <ul>
                    <li><a href="/" class="active">Accueil</a></li>
                    <li><a href="/galerie">Galerie</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if (in_array($_SESSION['user']['role'], ['admin', 'employe'])): ?>
                            <li><a href="/admin/accessoires" class="nav-link admin-highlight">Admin</a></li>
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
                        <li><a href="/contact">Contact</a></li>
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
            <?php if (isset($dbStatus)): ?>
                <p style="margin-top:10px; font-size: 0.7rem;">DB Status:
                    <?php echo $dbStatus; ?>
                </p>
            <?php endif; ?>
        </div>
    </footer>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function () {
            document.getElementById('main-nav').classList.toggle('active');
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.replace('fa-bars', 'fa-xmark');
            } else {
                icon.classList.replace('fa-xmark', 'fa-bars');
            }
        });
    </script>
</body>

</html>