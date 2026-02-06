<?php

namespace PixelVerseApp\Models;

use PixelVerseApp\Core\Database;
use PDO;

class Character
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouveau personnage
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO characters (user_id, name, gender, skin_color, hair_style, status) 
                VALUES (:user_id, :name, :gender, :skin_color, :hair_style, :status)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'gender' => $data['gender'],
            'skin_color' => $data['skin_color'],
            'hair_style' => $data['hair_style'],
            'status' => $data['status'] ?? 'pending'
        ]);
    }

    /**
     * Récupère tous les personnages d'un utilisateur (non supprimés)
     */
    public function getByUserId(int $userId): array
    {
        $sql = "SELECT * FROM characters WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un personnage par son ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT * FROM characters WHERE id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Vérifie si un nom de personnage est déjà pris
     */
    public function nameExists(string $name): bool
    {
        $sql = "SELECT COUNT(*) FROM characters WHERE name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Suppression logique (soft delete) d'un personnage
     */
    public function softDelete(int $id, int $userId): bool
    {
        $sql = "UPDATE characters SET deleted_at = NOW() WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    /**
     * Dupliquer un personnage
     */
    public function duplicate(int $id, int $userId, string $newName): bool
    {
        $char = $this->getById($id);
        if (!$char || $char['user_id'] !== $userId) {
            return false;
        }

        $newData = $char;
        $newData['name'] = $newName;
        $newData['status'] = 'pending'; // Le duplicata doit repasser par la validation

        return $this->create($newData);
    }

    /**
     * Génère des traits aléatoires pour un personnage
     */
    public function getRandomTraits(): array
    {
        $genders = ['homme', 'femme', 'autre'];
        $hairStyles = ['court', 'long', 'punk', 'chauve'];
        $skinColors = ['#fbc3bc', '#e0ac69', '#8d5524', '#c68642', '#ffdbac'];

        return [
            'gender' => $genders[array_rand($genders)],
            'hair_style' => $hairStyles[array_rand($hairStyles)],
            'skin_color' => $skinColors[array_rand($skinColors)]
        ];
    }
}
