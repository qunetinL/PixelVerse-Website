-- Fixtures for PixelVerse
USE pixelverse;

-- Insert Users (Password: 'password123' hashed would be better but using plain for mock)
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES
('AdminPixel', 'admin@pixelverse.com', 'GVIjB0J5lXZQmeSRfV4jtDSKkLWPHVsnyafPQXO', 'admin'),
('EmployeJean', 'jean@pixelverse.com', 'GVIjB0J5lXZQmeSRfV4jtDSKkLWPHVsnyafPQXO', 'employe'),
('PlayerOne', 'player1@gmail.com', 'GVIjB0J5lXZQmeSRfV4jtDSKkLWPHVsnyafPQXO', 'joueur'),
('PlayerTwo', 'player2@gmail.com', 'GVIjB0J5lXZQmeSRfV4jtDSKkLWPHVsnyafPQXO', 'joueur');

-- Insert Accessories
INSERT INTO accessoires (nom, type, image_url) VALUES
('Épée de Pixel', 'arme', '/assets/items/sword_pixel.png'),
('Bouclier de Fer', 'armure', '/assets/items/shield_iron.png'),
('Casque Solaire', 'armure', '/assets/items/helmet_sun.png'),
('Barbe Sage', 'visage', '/assets/items/beard_wise.png');

-- Insert Characters
INSERT INTO personnages (utilisateur_id, nom, genre, visage_yeux, visage_nez, visage_bouche, couleur_peau, couleur_cheveux, est_partage, statut_validation) VALUES
(3, 'PixelHero', 'homme', 'yeux_bleus', 'nez_fin', 'sourire', '#FFCC99', 'brun', TRUE, 'valide'),
(3, 'WarriorGhost', 'homme', 'yeux_rouges', 'nez_normal', 'colere', '#CCCCCC', 'gris', FALSE, 'en_attente'),
(4, 'ShadowElf', 'femme', 'yeux_verts', 'nez_long', 'neutre', '#E0E0E0', 'blanc', TRUE, 'valide');

-- Equip characters
INSERT INTO personnage_accessoire (personnage_id, accessoire_id) VALUES
(1, 1), -- PixelHero has Sword
(1, 2), -- PixelHero has Shield
(3, 3); -- ShadowElf has Sun Helmet

-- Insert Reviews
INSERT INTO avis (utilisateur_id, personnage_id, note, commentaire, statut_validation) VALUES
(4, 1, 5, 'Super design ! J\'adore les yeux bleus.', 'valide'),
(3, 3, 4, 'Très mystérieux comme personnage.', 'en_attente');
