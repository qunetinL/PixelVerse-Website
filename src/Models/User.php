<?php

namespace PixelVerseApp\Models;

use PixelVerseApp\Core\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Trouve un utilisateur par son email
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Trouve un utilisateur par son pseudo
     */
    public function findByPseudo(string $pseudo)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create(array $data)
    {
        // Hachage du mot de passe avec Argon2id (recommandé pour PHP 8.2)
        $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID);

        $sql = "INSERT INTO users (pseudo, email, password, role) VALUES (:pseudo, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'pseudo' => $data['pseudo'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $data['role'] ?? 'joueur'
        ]);
    }

    /**
     * Vérifie les identifiants de connexion
     */
    public function login(string $email, string $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_suspended']) {
                return ['error' => 'Votre compte est suspendu.'];
            }
            return $user;
        }

        return false;
    }

    /**
     * Génère un token de réinitialisation de mot de passe
     */
    public function generateResetToken(string $email): string|bool
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$token, $expires, $email])) {
            return $token;
        }

        return false;
    }

    /**
     * Réinitialise le mot de passe avec un token valide
     */
    public function resetPassword(string $token, string $newPassword)
    {
        $sql = "SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
            $updateSql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?";
            $updateStmt = $this->db->prepare($updateSql);
            return $updateStmt->execute([$hashedPassword, $user['id']]);
        }

        return false;
    }

    /**
     * Récupère tous les utilisateurs d'un rôle spécifique
     */
    public function getAllByRole(string $role)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? ORDER BY pseudo ASC");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les utilisateurs sauf les administrateurs
     */
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role != 'admin' ORDER BY pseudo ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations d'un utilisateur
     */
    public function update(int $id, array $data)
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "password = :password";
                $params['password'] = password_hash($value, PASSWORD_ARGON2ID);
            } else {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Bascule l'état de suspension d'un utilisateur
     */
    public function toggleSuspension(int $id)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_suspended = NOT is_suspended WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
