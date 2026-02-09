<section class="hero">
    <div class="container">
        <h1>FantasyRealm Online</h1>
        <p>Forge your destiny. Create your legend.</p>
        <div class="hero-btns">
            <a href="/creer-personnage" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Créer mon Personnage
            </a>
            <a href="/en-savoir-plus" class="btn btn-outline">
                En savoir plus
            </a>
        </div>
    </div>
</section>

<section class="container features">
    <?php foreach ($features as $feature): ?>
        <?php $this->renderPartial('_feature_card', $feature); ?>
    <?php endforeach; ?>
</section>