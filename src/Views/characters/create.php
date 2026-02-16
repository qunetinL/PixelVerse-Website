<div class="character-creation-container">
    <div class="creation-header">
        <h1>CRÉATION DE PERSONNAGE</h1>
        <div class="header-actions">
            <a href="/mes-personnages" class="btn btn-outline">Annuler</a>
            <button type="submit" form="character-form" class="btn btn-success">
                <i class="fas fa-save"></i> Sauvegarder
            </button>
        </div>
    </div>

    <form id="character-form" action="/creer-personnage" method="POST" class="creation-content">
        <?= \PixelVerseApp\Core\Security::csrfInput() ?>
        <!-- Panneau de gauche : Aperçu -->
        <div class="preview-panel">
            <div class="preview-box">
                <div class="preview-placeholder">
                    <i class="fas fa-user-ninja fa-5x"></i>
                    <p>Aperçu en temps réel</p>
                </div>
                <div class="preview-stats">
                    <span>Stats: FOR 10 | INT 5</span>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-full btn-random">
                <i class="fas fa-dice"></i> Aléatoire
            </button>
        </div>

        <!-- Panneau de droite : Personnalisation -->
        <div class="customization-panel">
            <div class="identity-section">
                <label for="char-name">Identité</label>
                <div class="identity-row">
                    <input type="text" id="char-name" name="name" placeholder="Nom du personnage" required>
                    <select name="gender" id="char-gender">
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button type="button" class="tab-btn active" data-tab="physique">Physique</button>
                    <button type="button" class="tab-btn" data-tab="equipement">Équipement</button>
                    <button type="button" class="tab-btn" data-tab="stats">Stats</button>
                </div>

                <div class="tab-content" id="physique">
                    <div class="option-group">
                        <label>Couleur de peau</label>
                        <div class="color-picker-container">
                            <input type="color" name="skin_color" value="#fbc3bc" class="skin-color-input">
                        </div>
                    </div>

                    <div class="option-group">
                        <label>Coiffure</label>
                        <div class="choice-grid">
                            <label class="choice-item">
                                <input type="radio" name="hair_style" value="court" checked>
                                <span>Court</span>
                            </label>
                            <label class="choice-item">
                                <input type="radio" name="hair_style" value="long">
                                <span>Long</span>
                            </label>
                            <label class="choice-item">
                                <input type="radio" name="hair_style" value="punk">
                                <span>Punk</span>
                            </label>
                            <label class="choice-item">
                                <input type="radio" name="hair_style" value="chauve">
                                <span>Chauve</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="tab-content hidden" id="equipement">
                    <div class="accessory-selection">
                        <?php foreach (['armes' => 'Armes', 'vetements' => 'Vêtements', 'accessoires' => 'Accessoires'] as $key => $label): ?>
                            <div class="accessory-category">
                                <h3><?= $label ?></h3>
                                <div class="accessory-grid">
                                    <?php foreach ($accessories[$key] as $acc): ?>
                                        <label class="accessory-item">
                                            <input type="checkbox" name="accessories[]" value="<?= $acc['id'] ?>">
                                            <div class="accessory-card">
                                                <i class="fas <?= htmlspecialchars($acc['icon']) ?>"></i>
                                                <span><?= htmlspecialchars($acc['name']) ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                    <?php if (empty($accessories[$key])): ?>
                                        <p class="empty-cat">Pas d'article disponible.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="tab-content hidden" id="stats">
                    <p class="empty-msg">Statistiques de base générées selon le genre.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .character-creation-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    .creation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .creation-header h1 {
        font-family: 'Cinzel', serif;
        font-size: 2.5rem;
        color: var(--color-text);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .creation-content {
        display: grid;
        grid-template-columns: 450px 1fr;
        gap: 40px;
    }

    /* Aperçu */
    .preview-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .preview-box {
        background: rgba(0, 0, 0, 0.4);
        border: 2px solid var(--color-primary);
        border-radius: 12px;
        height: 400px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-placeholder {
        text-align: center;
        color: var(--color-text-dim);
    }

    .preview-placeholder p {
        margin-top: 15px;
    }

    .preview-stats {
        position: absolute;
        bottom: 20px;
        left: 20px;
        font-size: 0.8rem;
        color: var(--color-text-dim);
    }

    .btn-random {
        border: 1px solid var(--color-primary);
        background: transparent;
        color: var(--color-primary);
    }

    /* Customization */
    .customization-panel {
        background: rgba(255, 255, 255, 0.05);
        padding: 30px;
        border-radius: 12px;
    }

    .identity-section {
        margin-bottom: 40px;
    }

    .identity-section label {
        display: block;
        color: var(--color-primary);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .identity-row {
        display: flex;
        gap: 15px;
    }

    .identity-row input {
        flex: 1;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid #444;
        padding: 12px;
        border-radius: 6px;
        color: white;
    }

    .identity-row select {
        width: 150px;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid #444;
        color: white;
        padding: 0 10px;
        border-radius: 6px;
    }

    /* Tabs */
    .tabs-header {
        display: flex;
        gap: 10px;
        border-bottom: 1px solid #444;
        margin-bottom: 25px;
    }

    .tab-btn {
        background: transparent;
        border: none;
        color: var(--color-text-dim);
        padding: 10px 20px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .tab-btn.active {
        color: var(--color-text);
        border-bottom: 2px solid var(--color-primary);
    }

    .option-group {
        margin-bottom: 25px;
    }

    .option-group label {
        display: block;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .skin-color-input {
        width: 100%;
        height: 30px;
        border: none;
        cursor: pointer;
        background: none;
    }

    .choice-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .choice-item {
        cursor: pointer;
    }

    .choice-item input {
        display: none;
    }

    .choice-item span {
        display: inline-block;
        padding: 8px 15px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #444;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .choice-item input:checked+span {
        border-color: var(--color-primary);
        background: rgba(0, 212, 255, 0.1);
    }

    .hidden {
        display: none;
    }

    .empty-msg {
        color: var(--color-text-dim);
        font-style: italic;
        font-size: 0.9rem;
    }

    /* Accessory Selection Styles */
    .accessory-category h3 {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: var(--color-primary);
        margin: 20px 0 10px 0;
        letter-spacing: 1px;
    }

    .accessory-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }

    .accessory-item input {
        display: none;
    }

    .accessory-card {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .accessory-card i {
        font-size: 1.5rem;
        color: var(--color-text-dim);
    }

    .accessory-card span {
        font-size: 0.75rem;
        color: var(--color-text-dim);
    }

    .accessory-item input:checked+.accessory-card {
        border-color: var(--color-primary);
        background: rgba(0, 212, 255, 0.1);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
    }

    .accessory-item input:checked+.accessory-card i,
    .accessory-item input:checked+.accessory-card span {
        color: var(--color-primary);
    }

    .empty-cat {
        font-size: 0.8rem;
        color: #666;
        font-style: italic;
    }

    @media (max-width: 992px) {
        .creation-content {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion des onglets
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.add('hidden'));

                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.remove('hidden');
            });
        });

        // Simuler le changement de couleur de peau
        const skinInput = document.querySelector('.skin-color-input');
        const previewBox = document.querySelector('.preview-box');

        skinInput.addEventListener('input', (e) => {
            // En prod, ceci mettrait à jour le shader ou l'image SVG
            previewBox.style.boxShadow = `inset 0 0 100px ${e.target.value}44`;
        });

        // RNG Logic
        const btnRandom = document.querySelector('.btn-random');
        const genders = ['homme', 'femme', 'autre'];
        const hairStyles = ['court', 'long', 'punk', 'chauve'];
        const skinColors = ['#fbc3bc', '#e0ac69', '#8d5524', '#c68642', '#ffdbac'];

        if (btnRandom) {
            btnRandom.addEventListener('click', () => {
                // Random Gender
                const randomGender = genders[Math.floor(Math.random() * genders.length)];
                const genderSelect = document.getElementById('char-gender');
                if (genderSelect) genderSelect.value = randomGender;

                // Random Hair
                const randomHair = hairStyles[Math.floor(Math.random() * hairStyles.length)];
                const hairRadio = document.querySelector(`input[name="hair_style"][value="${randomHair}"]`);
                if (hairRadio) hairRadio.checked = true;

                // Random Skin
                const randomSkin = skinColors[Math.floor(Math.random() * skinColors.length)];
                const skinInput = document.querySelector('input[name="skin_color"]');
                if (skinInput) {
                    skinInput.value = randomSkin;
                    // Update preview manually since changing value in JS doesn't trigger 'input' event
                    previewBox.style.boxShadow = `inset 0 0 100px ${randomSkin}44`;
                }
            });
        }
    });
</script>