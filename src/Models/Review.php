<?php

namespace PixelVerseApp\Models;

use PixelVerseApp\Core\Database;
use PDO;

class Review
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouvel avis (invisible par défaut)
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO reviews (character_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['character_id'],
            $data['user_id'],
            $data['rating'],
            $data['comment']
        ]);
    }

    /**
     * Récupère les avis visibles pour un personnage
     */
    public function getVisibleByCharacter(int $characterId): array
    {
        $sql = "SELECT r.*, u.pseudo FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.character_id = ? AND r.is_visible = 1 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$characterId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les avis en attente de validation
     */
    public function getPending(): array
    {
        $sql = "SELECT r.*, u.pseudo as user_pseudo, c.name as character_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN characters c ON r.character_id = c.id 
                WHERE r.is_visible = 0 
                ORDER BY r.created_at ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Valide un avis (le rend visible)
     */
    public function approve(int $id): bool
    {
        $sql = "UPDATE reviews SET is_visible = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Supprime un avis (rejet ou suppression admin)
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Vérifie si un utilisateur a déjà voté pour ce personnage
     */
    public function hasUserVoted(int $userId, int $characterId): bool
    {
        $sql = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND character_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $characterId]);
        return $stmt->fetchColumn() > 0;
    }
}
