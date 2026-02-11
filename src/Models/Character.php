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

    /**
     * Récupère les accessoires équipés par un personnage
     */
    public function getAccessories(int $characterId): array
    {
        $sql = "SELECT a.* FROM accessories a 
                JOIN character_accessories ca ON a.id = ca.accessory_id 
                WHERE ca.character_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$characterId]);
        return $stmt->fetchAll();
    }

    /**
     * Équipe un personnage avec un accessoire
     */
    public function equipAccessory(int $characterId, int $accessoryId): bool
    {
        $sql = "INSERT IGNORE INTO character_accessories (character_id, accessory_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$characterId, $accessoryId]);
    }

    /**
     * Retire un accessoire d'un personnage
     */
    public function unequipAccessory(int $characterId, int $accessoryId): bool
    {
        $sql = "DELETE FROM character_accessories WHERE character_id = ? AND accessory_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$characterId, $accessoryId]);
    }

    /**
     * Met à jour tout l'équipement d'un personnage
     */
    public function syncAccessories(int $characterId, array $accessoryIds): bool
    {
        $this->db->beginTransaction();
        try {
            // On retire tout
            $stmt = $this->db->prepare("DELETE FROM character_accessories WHERE character_id = ?");
            $stmt->execute([$characterId]);

            // On ajoute les nouveaux
            $stmt = $this->db->prepare("INSERT INTO character_accessories (character_id, accessory_id) VALUES (?, ?)");
            foreach ($accessoryIds as $accId) {
                $stmt->execute([$characterId, (int) $accId]);
            }
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Récupère les personnages en attente de validation
     */
    public function getPending(): array
    {
        $sql = "SELECT c.*, u.pseudo as user_pseudo 
                FROM characters c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.status = 'pending' AND c.deleted_at IS NULL 
                ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Met à jour le statut d'un personnage
     */
    public function updateStatus(int $id, string $status, ?string $reason = null): bool
    {
        $sql = "UPDATE characters SET status = :status, rejection_reason = :reason WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'reason' => $reason
        ]);
    }

    /**
     * Récupère les personnages approuvés avec des filtres dynamiques
     */
    public function getFilteredApproved(array $filters = []): array
    {
        $sql = "SELECT c.*, u.pseudo as user_pseudo 
                FROM characters c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.status = 'approved' AND c.deleted_at IS NULL";

        $params = [];

        // Filtre par genre
        if (!empty($filters['gender'])) {
            $sql .= " AND c.gender = :gender";
            $params['gender'] = $filters['gender'];
        }

        // Filtre par pseudo (recherche partielle)
        if (!empty($filters['pseudo'])) {
            $sql .= " AND u.pseudo LIKE :pseudo";
            $params['pseudo'] = '%' . $filters['pseudo'] . '%';
        }

        // Tri par date
        $sort = $filters['sort'] ?? 'desc';
        $sql .= " ORDER BY c.created_at " . ($sort === 'asc' ? 'ASC' : 'DESC');

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
