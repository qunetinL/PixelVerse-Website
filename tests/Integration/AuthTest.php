<?php

namespace PixelVerseTests\Integration;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\User;
use PixelVerseApp\Core\Database;

class AuthTest extends TestCase
{
    private $userModel;
    private $db;
    private $testEmail = "test_integration@pixelverse.com";

    protected function setUp(): void
    {
        $this->userModel = new User();
        $this->db = Database::getInstance()->getConnection();

        // Nettoyage avant chaque test
        $this->cleanup();
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        $this->cleanup();
    }

    private function cleanup()
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
    }

    public function testRegistrationAndLogin()
    {
        $data = [
            'pseudo' => 'TestRunner',
            'email' => $this->testEmail,
            'password' => 'Password123!',
            'role' => 'joueur'
        ];

        // 1. Test Inscription
        $result = $this->userModel->create($data);
        $this->assertTrue($result, "La création de l'utilisateur a échoué.");

        // 2. Test Login Réussi
        $user = $this->userModel->login($this->testEmail, 'Password123!');
        $this->assertIsArray($user, "Le login devrait retourner un tableau utilisateur.");
        $this->assertEquals('TestRunner', $user['pseudo']);

        // 3. Test Login Échoué (Mauvais mot de passe)
        $failedLogin = $this->userModel->login($this->testEmail, 'WrongPassword');
        $this->assertFalse($failedLogin, "Le login devrait échouer avec un mauvais mot de passe.");
    }

    public function testPasswordResetFlow()
    {
        // Création d'un utilisateur pour le test
        $this->userModel->create([
            'pseudo' => 'ResetUser',
            'email' => $this->testEmail,
            'password' => 'OldPassword123!',
            'role' => 'joueur'
        ]);

        // 1. Génération du token
        $token = $this->userModel->generateResetToken($this->testEmail);
        $this->assertIsString($token, "La génération du token a échoué.");

        // 2. Réinitialisation
        $resetResult = $this->userModel->resetPassword($token, 'NewPassword456!');
        $this->assertTrue($resetResult, "La réinitialisation du mot de passe a échoué.");

        // 3. Vérification du nouveau mot de passe
        $user = $this->userModel->login($this->testEmail, 'NewPassword456!');
        $this->assertIsArray($user, "Le login avec le nouveau mot de passe a échoué.");
    }
}
