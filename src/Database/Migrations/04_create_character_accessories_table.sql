-- Migration 04: Création de la table de liaison personnages-accessoires
CREATE TABLE IF NOT EXISTS character_accessories (
    character_id INT NOT NULL,
    accessory_id INT NOT NULL,
    PRIMARY KEY (character_id, accessory_id),
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
    FOREIGN KEY (accessory_id) REFERENCES accessories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
