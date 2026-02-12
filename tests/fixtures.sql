-- Fixtures for PixelVerse (English Schema)
USE pixelverse;

-- Insert Users
-- Passwords are hashed with Argon2id: 'password123'
INSERT INTO users (pseudo, email, password, role) VALUES
('AdminPixel', 'admin@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$ek1OUEpYU0J6RkJ3dlV4T283Y3Zzdw$YVlSQUZSMVJWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1J', 'admin'),
('EmployeJean', 'jean@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$ek1OUEpYU0J6RkJ3dlV4T283Y3Zzdw$YVlSQUZSMVJWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1J', 'employe'),
('PlayerOne', 'player1@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ek1OUEpYU0J6RkJ3dlV4T283Y3Zzdw$YVlSQUZSMVJWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1J', 'joueur'),
('PlayerTwo', 'player2@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ek1OUEpYU0J6RkJ3dlV4T283Y3Zzdw$YVlSQUZSMVJWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1JWU1J', 'joueur');

-- Insert Accessories
INSERT INTO accessories (name, type, icon, is_active) VALUES
('Épée de Pixel', 'arme', 'fa-sword', 1),
('Bouclier de Fer', 'accessoire', 'fa-shield', 1),
('Casque Solaire', 'vetement', 'fa-helmet-safety', 1),
('Barbe Sage', 'accessoire', 'fa-user', 1);

-- Insert Characters
INSERT INTO characters (user_id, name, gender, skin_color, hair_style, status) VALUES
(3, 'PixelHero', 'homme', '#FFCC99', 'brun', 'approved'),
(3, 'WarriorGhost', 'homme', '#CCCCCC', 'gris', 'pending'),
(4, 'ShadowElf', 'femme', '#E0E0E0', 'blanc', 'approved');

-- Equip characters
INSERT INTO character_accessories (character_id, accessory_id) VALUES
(1, 1), -- PixelHero has Sword
(1, 2), -- PixelHero has Shield
(3, 3); -- ShadowElf has Sun Helmet

-- Insert Reviews
INSERT INTO reviews (character_id, user_id, rating, comment, is_visible) VALUES
(1, 4, 5, 'Super design ! J\'adore les yeux bleus.', 1),
(3, 3, 4, 'Très mystérieux comme personnage.', 0);
