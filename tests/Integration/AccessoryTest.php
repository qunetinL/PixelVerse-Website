<?php

namespace PixelVerseTests\Integration;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\Accessory;
use PixelVerseApp\Models\Character;
use PixelVerseApp\Core\Database;

class AccessoryTest extends TestCase
{
    private $accessoryModel;
    private $characterModel;
    private $db;
    private $testUserId;
    private $testCharId;

    protected function setUp(): void
    {
        $this->accessoryModel = new Accessory();
        $this->characterModel = new Character();
        $this->db = Database::getInstance()->getConnection();

        // On récupère un utilisateur et un personnage existants pour les tests
        $stmt = $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $stmt->fetch();
        $this->testUserId = $user['id'] ?? null;

        if ($this->testUserId) {
            $stmt = $this->db->prepare("SELECT id FROM characters WHERE user_id = ? LIMIT 1");
            $stmt->execute([$this->testUserId]);
            $char = $stmt->fetch();
            $this->testCharId = $char['id'] ?? null;
        }

        $this->cleanup();
    }

    protected function tearDown(): void
    {
        $this->cleanup();
    }

    private function cleanup()
    {
        // Nettoyage des accessoires de test
        $this->db->exec("DELETE FROM accessories WHERE name LIKE 'TestItem_%'");
    }

    public function testAccessoryCRUD()
    {
        $data = [
            'name' => 'TestItem_Sword',
            'type' => 'arme',
            'icon' => 'fa-sword',
            'is_active' => 1
        ];

        // 1. Création
        $result = $this->accessoryModel->create($data);
        $this->assertTrue($result);

        // 2. Lecture / GetById
        $stmt = $this->db->prepare("SELECT id FROM accessories WHERE name = ?");
        $stmt->execute([$data['name']]);
        $accId = $stmt->fetchColumn();

        $acc = $this->accessoryModel->getById($accId);
        $this->assertEquals($data['name'], $acc['name']);

        // 3. Update
        $updateData = [
            'name' => 'TestItem_Sword_Upgraded',
            'type' => 'arme',
            'icon' => 'fa-sword',
            'is_active' => 0
        ];
        $this->accessoryModel->update($accId, $updateData);
        $updatedAcc = $this->accessoryModel->getById($accId);
        $this->assertEquals('TestItem_Sword_Upgraded', $updatedAcc['name']);
        $this->assertEquals(0, $updatedAcc['is_active']);

        // 4. Delete
        $this->accessoryModel->delete($accId);
        $this->assertFalse($this->accessoryModel->getById($accId));
    }

    public function testActiveByTypeFilter()
    {
        // Création de plusieurs items
        $this->accessoryModel->create(['name' => 'TestItem_Active_Arme', 'type' => 'arme', 'is_active' => 1]);
        $this->accessoryModel->create(['name' => 'TestItem_Inactive_Arme', 'type' => 'arme', 'is_active' => 0]);
        $this->accessoryModel->create(['name' => 'TestItem_Active_Vet', 'type' => 'vetement', 'is_active' => 1]);

        $activeArmes = $this->accessoryModel->getActiveByType('arme');

        $names = array_column($activeArmes, 'name');
        $this->assertContains('TestItem_Active_Arme', $names);
        $this->assertNotContains('TestItem_Inactive_Arme', $names);
        $this->assertNotContains('TestItem_Active_Vet', $names);
    }

    public function testCharacterEquipmentSync()
    {
        if (!$this->testCharId) {
            $this->markTestSkipped("Aucun personnage disponible pour tester l'équipement.");
        }

        // 1. Création de deux accessoires
        $this->accessoryModel->create(['name' => 'TestItem_Epee', 'type' => 'arme']);
        $this->accessoryModel->create(['name' => 'TestItem_Bouclier', 'type' => 'arme']);

        $accs = $this->db->query("SELECT id FROM accessories WHERE name LIKE 'TestItem_%'")->fetchAll(\PDO::FETCH_COLUMN);

        // 2. Synchronisation (Équipement)
        $result = $this->characterModel->syncAccessories($this->testCharId, $accs);
        $this->assertTrue($result);

        // 3. Vérification
        $equipped = $this->characterModel->getAccessories($this->testCharId);
        $this->assertCount(2, $equipped);

        $equippedNames = array_column($equipped, 'name');
        $this->assertContains('TestItem_Epee', $equippedNames);
        $this->assertContains('TestItem_Bouclier', $equippedNames);

        // 4. Retrait (Sync vide)
        $this->characterModel->syncAccessories($this->testCharId, []);
        $equippedAfter = $this->characterModel->getAccessories($this->testCharId);
        $this->assertEmpty($equippedAfter);
    }
}
