<?php

namespace PixelVerseApp\Models;

use PixelVerseApp\Core\Database;
use PDO;

class Accessory
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouvel accessoire
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO accessoires (nom, type, image_url, est_actif) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['type'],
            $data['icon'] ?? 'fa-item', // Note: DB expects image_url, check if icon is correct mapping or if we need path
            $data['is_active'] ?? 1
        ]);
    }

    /**
     * Récupère tous les accessoires
     */
    public function getAll(): array
    {
        $sql = "SELECT id, nom as name, type, image_url as icon, est_actif as is_active FROM accessoires ORDER BY type, nom";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Récupère les accessoires actifs par type
     */
    public function getActiveByType(string $type): array
    {
        // Aliasing columns to match what Controller expects
        $sql = "SELECT id, nom as name, type, image_url as icon, est_actif as is_active FROM accessoires WHERE type = ? AND est_actif = 1 ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un accessoire par son ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT id, nom as name, type, image_url as icon, est_actif as is_active FROM accessoires WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Met à jour un accessoire
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE accessoires SET nom = ?, type = ?, image_url = ?, est_actif = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['type'],
            $data['icon'],
            $data['is_active'],
            $id
        ]);
    }

    /**
     * Supprime un accessoire
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM accessoires WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
