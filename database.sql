-- Database creation script for PixelVerse
-- Tables will be created in the database defined by MYSQL_DATABASE env var

-- Drop tables if they exist to force schema update
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS personnage_accessoire;
DROP TABLE IF EXISTS personnages;
DROP TABLE IF EXISTS accessoires;
DROP TABLE IF EXISTS utilisateurs;

-- Table Utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('joueur', 'employe', 'admin') NOT NULL DEFAULT 'joueur',
    est_suspendu BOOLEAN NOT NULL DEFAULT FALSE,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires_at DATETIME DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table Accessoires
CREATE TABLE IF NOT EXISTS accessoires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('arme', 'armure', 'visage', 'vetement', 'accessoire', 'autre') NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    est_actif BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- Table Personnages
CREATE TABLE IF NOT EXISTS personnages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    nom VARCHAR(50) NOT NULL,
    genre ENUM('homme', 'femme', 'autre') NOT NULL,
    visage_yeux VARCHAR(100),
    visage_nez VARCHAR(100),
    visage_bouche VARCHAR(100),
    couleur_peau VARCHAR(20),
    couleur_cheveux VARCHAR(20),
    style_cheveux VARCHAR(50),
    est_partage BOOLEAN NOT NULL DEFAULT FALSE,
    statut_validation ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_suppression DATETIME DEFAULT NULL,
    rejection_reason TEXT,
    CONSTRAINT fk_personnage_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Junction table Personnage_Accessoire
CREATE TABLE IF NOT EXISTS personnage_accessoire (
    personnage_id INT NOT NULL,
    accessoire_id INT NOT NULL,
    PRIMARY KEY (personnage_id, accessoire_id),
    CONSTRAINT fk_pa_personnage FOREIGN KEY (personnage_id) REFERENCES personnages(id) ON DELETE CASCADE,
    CONSTRAINT fk_pa_accessoire FOREIGN KEY (accessoire_id) REFERENCES accessoires(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table Avis
CREATE TABLE IF NOT EXISTS avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    personnage_id INT NOT NULL,
    note TINYINT UNSIGNED NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    statut_validation ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    date_depot TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_avis_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    CONSTRAINT fk_avis_personnage FOREIGN KEY (personnage_id) REFERENCES personnages(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Indexes
CREATE INDEX idx_utilisateurs_email ON utilisateurs(email);
CREATE INDEX idx_personnages_statut ON personnages(statut_validation);
CREATE INDEX idx_avis_statut ON avis(statut_validation);

-- Default Accessories
INSERT INTO accessoires (nom, type, image_url) VALUES 
('Épée de Chevalier', 'arme', 'fa-khanda'),
('Bouclier en Fer', 'armure', 'fa-shield-halved'),
('Chapeau de Magicien', 'autre', 'fa-hat-wizard'),
('Dague de Voleur', 'arme', 'fa-pen-nib'),
('Cotte de Mailles', 'armure', 'fa-shirt'),
('Yeux de Braise', 'visage', 'fa-eye');

-- Default Users
-- Admin123!
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('AdminPixel', 'admin@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$R0d1SzVkWGYvZEFWV2MyMg$tZKdx5wydHJV5bueJ8T5Birl+i2/XSmbPY9otqf6dnA', 'admin')
ON DUPLICATE KEY UPDATE mot_de_passe = VALUES(mot_de_passe);

-- Password123!
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('EmployeJean', 'employe@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$RVA4cXQ4N3V6bDNMWWxuQw$/DqDVV5mlj5J0aMBaa41dx6Z9CZcsCSIAPzhYl+2rS4', 'employe')
ON DUPLICATE KEY UPDATE mot_de_passe = VALUES(mot_de_passe);

-- Password123!
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('JoueurTest', 'joueur@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$RVA4cXQ4N3V6bDNMWWxuQw$/DqDVV5mlj5J0aMBaa41dx6Z9CZcsCSIAPzhYl+2rS4', 'joueur')
ON DUPLICATE KEY UPDATE mot_de_passe = VALUES(mot_de_passe);
