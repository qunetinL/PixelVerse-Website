-- Database creation script for PixelVerse
CREATE DATABASE IF NOT EXISTS pixelverse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pixelverse;

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
    type ENUM('arme', 'armure', 'visage', 'autre') NOT NULL,
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
    est_partage BOOLEAN NOT NULL DEFAULT FALSE,
    statut_validation ENUM('en_attente', 'valide', 'rejete') NOT NULL DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    statut_validation ENUM('en_attente', 'valide', 'rejete') NOT NULL DEFAULT 'en_attente',
    date_depot TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_avis_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    CONSTRAINT fk_avis_personnage FOREIGN KEY (personnage_id) REFERENCES personnages(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Indexes
CREATE INDEX idx_utilisateurs_email ON utilisateurs(email);
CREATE INDEX idx_personnages_statut ON personnages(statut_validation);
CREATE INDEX idx_avis_statut ON avis(statut_validation);

-- Default Users
-- Admin123!
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('AdminPixel', 'admin@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$Vno4ZmpDdzdLbFk4OHdtdg$6FH7BtWx3uS6fq9shxFr7k3XEGihp01gX6SwQg1/X5k', 'admin');

-- Password123!
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('EmployeJean', 'jean@pixelverse.com', '$argon2id$v=19$m=65536,t=4,p=1$aXZPd2pqeEJucjFPb0VVcQ$maJj4Wzq7/p08nGc13uxdX0CvPvw6t1nQJeuszPdzBo', 'employe');

INSERT INTO utilisateurs (pseudo, email, mot_de_passe, role) VALUES 
('PlayerOne', 'player1@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aXZPd2pqeEJucjFPb0VVcQ$maJj4Wzq7/p08nGc13uxdX0CvPvw6t1nQJeuszPdzBo', 'joueur'),
('PlayerTwo', 'player2@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aXZPd2pqeEJucjFPb0VVcQ$maJj4Wzq7/p08nGc13uxdX0CvPvw6t1nQJeuszPdzBo', 'joueur');
