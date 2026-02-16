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
        $sql = "INSERT INTO avis (personnage_id, utilisateur_id, note, commentaire, statut_validation) VALUES (?, ?, ?, ?, 'pending')";
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
        $sql = "SELECT r.id, r.utilisateur_id as user_id, r.personnage_id as character_id, r.note as rating, r.commentaire as comment, r.statut_validation as status, r.date_depot as created_at, u.pseudo 
                FROM avis r 
                JOIN utilisateurs u ON r.utilisateur_id = u.id 
                WHERE r.personnage_id = ? AND r.statut_validation = 'approved' 
                ORDER BY r.date_depot DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$characterId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les avis en attente de validation
     */
    public function getPending(): array
    {
        $sql = "SELECT r.id, r.utilisateur_id as user_id, r.personnage_id as character_id, r.note as rating, r.commentaire as comment, r.statut_validation as status, r.date_depot as created_at, u.pseudo as user_pseudo, c.nom as character_name 
                FROM avis r 
                JOIN utilisateurs u ON r.utilisateur_id = u.id 
                JOIN personnages c ON r.personnage_id = c.id 
                WHERE r.statut_validation = 'pending' 
                ORDER BY r.date_depot ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Valide un avis (le rend visible)
     */
    public function approve(int $id): bool
    {
        $sql = "UPDATE avis SET statut_validation = 'approved' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Supprime un avis (rejet ou suppression admin)
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM avis WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Vérifie si un utilisateur a déjà voté pour ce personnage
     */
    public function hasUserVoted(int $userId, int $characterId): bool
    {
        $sql = "SELECT COUNT(*) FROM avis WHERE utilisateur_id = ? AND personnage_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $characterId]);
        return $stmt->fetchColumn() > 0;
    }
}
