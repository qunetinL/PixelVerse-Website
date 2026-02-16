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
        $sql = "INSERT INTO personnages (utilisateur_id, nom, genre, couleur_peau, style_cheveux, statut_validation) 
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
        $sql = "SELECT id, utilisateur_id as user_id, nom as name, genre as gender, couleur_peau as skin_color, style_cheveux as hair_style, statut_validation as status, date_creation as created_at 
                FROM personnages WHERE utilisateur_id = ? AND date_suppression IS NULL ORDER BY date_creation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un personnage par son ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT id, utilisateur_id as user_id, nom as name, genre as gender, couleur_peau as skin_color, style_cheveux as hair_style, statut_validation as status, date_creation as created_at, rejection_reason 
                FROM personnages WHERE id = ? AND date_suppression IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Vérifie si un nom de personnage est déjà pris
     */
    public function nameExists(string $name): bool
    {
        $sql = "SELECT COUNT(*) FROM personnages WHERE nom = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Suppression logique (soft delete) d'un personnage
     */
    public function softDelete(int $id, int $userId): bool
    {
        $sql = "UPDATE personnages SET date_suppression = NOW() WHERE id = ? AND utilisateur_id = ?";
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
        $sql = "SELECT a.id, a.nom as name, a.type, a.image_url as icon, a.est_actif as is_active 
                FROM accessoires a 
                JOIN personnage_accessoire pa ON a.id = pa.accessoire_id 
                WHERE pa.personnage_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$characterId]);
        return $stmt->fetchAll();
    }

    /**
     * Équipe un personnage avec un accessoire
     */
    public function equipAccessory(int $characterId, int $accessoryId): bool
    {
        $sql = "INSERT IGNORE INTO personnage_accessoire (personnage_id, accessoire_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$characterId, $accessoryId]);
    }

    /**
     * Retire un accessoire d'un personnage
     */
    public function unequipAccessory(int $characterId, int $accessoryId): bool
    {
        $sql = "DELETE FROM personnage_accessoire WHERE personnage_id = ? AND accessoire_id = ?";
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
            $stmt = $this->db->prepare("DELETE FROM personnage_accessoire WHERE personnage_id = ?");
            $stmt->execute([$characterId]);

            // On ajoute les nouveaux
            $stmt = $this->db->prepare("INSERT INTO personnage_accessoire (personnage_id, accessoire_id) VALUES (?, ?)");
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
        $sql = "SELECT c.id, c.utilisateur_id as user_id, c.nom as name, c.genre as gender, c.couleur_peau as skin_color, c.style_cheveux as hair_style, c.statut_validation as status, c.date_creation as created_at, u.pseudo as user_pseudo 
                FROM personnages c 
                JOIN utilisateurs u ON c.utilisateur_id = u.id 
                WHERE c.statut_validation = 'pending' AND c.date_suppression IS NULL 
                ORDER BY c.date_creation ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Met à jour le statut d'un personnage
     */
    public function updateStatus(int $id, string $status, ?string $reason = null): bool
    {
        $sql = "UPDATE personnages SET statut_validation = :status, rejection_reason = :reason WHERE id = :id";
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
        $sql = "SELECT c.id, c.utilisateur_id as user_id, c.nom as name, c.genre as gender, c.couleur_peau as skin_color, c.style_cheveux as hair_style, c.statut_validation as status, c.date_creation as created_at, u.pseudo as user_pseudo 
                FROM personnages c 
                JOIN utilisateurs u ON c.utilisateur_id = u.id 
                WHERE c.statut_validation = 'approved' AND c.date_suppression IS NULL";

        $params = [];

        // Filtre par genre
        if (!empty($filters['gender'])) {
            $sql .= " AND c.genre = :gender";
            $params['gender'] = $filters['gender'];
        }

        // Filtre par pseudo (recherche partielle)
        if (!empty($filters['pseudo'])) {
            $sql .= " AND u.pseudo LIKE :pseudo";
            $params['pseudo'] = '%' . $filters['pseudo'] . '%';
        }

        // Tri par date
        $sort = $filters['sort'] ?? 'desc';
        $sql .= " ORDER BY c.date_creation " . ($sort === 'asc' ? 'ASC' : 'DESC');

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
