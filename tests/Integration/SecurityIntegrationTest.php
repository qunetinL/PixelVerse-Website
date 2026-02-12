<?php

namespace PixelVerseTests\Integration;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\User;
use PixelVerseApp\Core\Database;
use PixelVerseApp\Core\Security;

class SecurityIntegrationTest extends TestCase
{
    private $userModel;
    private $db;
    private $testEmail = "security_test@pixelverse.com";

    protected function setUp(): void
    {
        $this->userModel = new User();
        $this->db = Database::getInstance()->getConnection();
        $this->cleanup();
    }

    protected function tearDown(): void
    {
        $this->cleanup();
    }

    private function cleanup()
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
    }

    /**
     * Test de la complexité du mot de passe (Regex CNIL)
     */
    public function testPasswordComplexityRegex()
    {
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';

        $this->assertEquals(1, preg_match($passwordRegex, 'SecureP@ssw0rd123'), "Devrait accepter un mot de passe robuste.");
        $this->assertEquals(0, preg_match($passwordRegex, 'simple'), "Devrait refuser un mot de passe trop court.");
        $this->assertEquals(0, preg_match($passwordRegex, 'SecurePassword'), "Devrait refuser sans chiffre ni spécial.");
        $this->assertEquals(0, preg_match($passwordRegex, 'Secure123!'), "Devrait refuser moins de 12 caractères.");
    }

    /**
     * Test de la génération et vérification de jeton CSRF
     */
    public function testCsrfTokenMechanism()
    {
        $token = Security::generateCsrfToken();
        $this->assertNotEmpty($token);

        $this->assertTrue(Security::verifyCsrfToken($token), "Le jeton valide devrait être accepté.");
        $this->assertFalse(Security::verifyCsrfToken("invalid-token"), "Un jeton invalide devrait être refusé.");
        $this->assertFalse(Security::verifyCsrfToken(null), "Un jeton null devrait être refusé.");
    }

    /**
     * Test de la protection contre les injections (via PDO)
     */
    public function testSqlInjectionProtection()
    {
        // On tente une injection dans le pseudo
        $maliciousPseudo = "admin' OR '1'='1";
        $data = [
            'pseudo' => $maliciousPseudo,
            'email' => $this->testEmail,
            'password' => 'Complex123!@#',
            'role' => 'joueur'
        ];

        $this->userModel->create($data);

        // On vérifie que le pseudo a été inséré littéralement et non interprété
        $user = $this->userModel->findByEmail($this->testEmail);
        $this->assertEquals($maliciousPseudo, $user['pseudo'], "L'injection SQL devrait être traitée comme du texte brut.");
    }
}
