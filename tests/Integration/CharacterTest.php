<?php

namespace PixelVerseTests\Integration;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\Character;
use PixelVerseApp\Core\Database;

class CharacterTest extends TestCase
{
    private $characterModel;
    private $db;
    private $testUserId;
    private $testCharName = "TestNinja_99";

    protected function setUp(): void
    {
        $this->characterModel = new Character();
        $this->db = Database::getInstance()->getConnection();

        // On récupère un utilisateur existant pour la clé étrangère
        $stmt = $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $stmt->fetch();
        $this->testUserId = $user['id'] ?? null;

        $this->cleanup();
    }

    protected function tearDown(): void
    {
        $this->cleanup();
    }

    private function cleanup()
    {
        // Hard delete pour le nettoyage des tests
        $stmt = $this->db->prepare("DELETE FROM characters WHERE name LIKE 'TestNinja_%'");
        $stmt->execute();
    }

    public function testCharacterLifecycle()
    {
        $data = [
            'user_id' => $this->testUserId,
            'name' => $this->testCharName,
            'gender' => 'homme',
            'skin_color' => '#ffffff',
            'hair_style' => 'punk',
            'status' => 'pending'
        ];

        // 1. Création
        $result = $this->characterModel->create($data);
        $this->assertTrue($result, "La création du personnage a échoué.");

        // 2. Vérification unicité nom
        $this->assertTrue($this->characterModel->nameExists($this->testCharName));

        // 3. Récupération par UserID
        $chars = $this->characterModel->getByUserId($this->testUserId);
        $this->assertNotEmpty($chars);
        $this->assertEquals($this->testCharName, $chars[0]['name']);

        // 4. Duplication
        $dupName = "TestNinja_DUP";
        $dupResult = $this->characterModel->duplicate($chars[0]['id'], $this->testUserId, $dupName);
        $this->assertTrue($dupResult, "La duplication a échoué.");
        $this->assertTrue($this->characterModel->nameExists($dupName));

        // 5. Soft Delete
        $deleteResult = $this->characterModel->softDelete($chars[0]['id'], $this->testUserId);
        $this->assertTrue($deleteResult, "La suppression logique a échoué.");

        $charAfterDelete = $this->characterModel->getById($chars[0]['id']);
        $this->assertFalse($charAfterDelete, "Le personnage ne devrait plus être récupérable par getById après soft delete.");
    }
}
