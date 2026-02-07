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
        $sql = "INSERT INTO accessories (name, type, icon, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['type'],
            $data['icon'] ?? 'fa-item',
            $data['is_active'] ?? 1
        ]);
    }

    /**
     * Récupère tous les accessoires
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM accessories ORDER BY type, name";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Récupère les accessoires actifs par type
     */
    public function getActiveByType(string $type): array
    {
        $sql = "SELECT * FROM accessories WHERE type = ? AND is_active = 1 ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un accessoire par son ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT * FROM accessories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Met à jour un accessoire
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE accessories SET name = ?, type = ?, icon = ?, is_active = ? WHERE id = ?";
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
        $sql = "DELETE FROM accessories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
