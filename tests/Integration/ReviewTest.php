<?php

namespace PixelVerseTests\Integration;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\Review;
use PixelVerseApp\Models\Character;
use PixelVerseApp\Core\Database;

class ReviewTest extends TestCase
{
    private $reviewModel;
    private $characterModel;
    private $db;
    private $testUserId;
    private $testCharId;

    protected function setUp(): void
    {
        $this->reviewModel = new Review();
        $this->characterModel = new Character();
        $this->db = Database::getInstance()->getConnection();

        // On récupère ou crée un utilisateur de test
        $stmt = $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $stmt->fetch();
        $this->testUserId = $user['id'] ?? null;

        if ($this->testUserId) {
            // On récupère un personnage de test
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
        $this->db->exec("DELETE FROM reviews WHERE comment LIKE 'TestReview_%'");
    }

    public function testReviewLifecycle()
    {
        if (!$this->testCharId || !$this->testUserId) {
            $this->markTestSkipped("Données de test manquantes.");
        }

        $data = [
            'character_id' => $this->testCharId,
            'user_id' => $this->testUserId,
            'rating' => 4,
            'comment' => 'TestReview_Content'
        ];

        // 1. Soumission
        $result = $this->reviewModel->create($data);
        $this->assertTrue($result);

        // 2. Vérification invisible
        $visible = $this->reviewModel->getVisibleByCharacter($this->testCharId);
        $visibleComments = array_column($visible, 'comment');
        $this->assertNotContains($data['comment'], $visibleComments);

        // 3. Présence dans le dashboard modération
        $pending = $this->reviewModel->getPending();
        $pendingComments = array_column($pending, 'comment');
        $this->assertContains($data['comment'], $pendingComments);

        // 4. Approbation
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE comment = ?");
        $stmt->execute([$data['comment']]);
        $reviewId = $stmt->fetchColumn();

        $this->reviewModel->approve($reviewId);

        // 5. Vérification visible
        $visible = $this->reviewModel->getVisibleByCharacter($this->testCharId);
        $visibleComments = array_column($visible, 'comment');
        $this->assertContains($data['comment'], $visibleComments);

        // 6. Suppression
        $this->reviewModel->delete($reviewId);
        $visibleAfter = $this->reviewModel->getVisibleByCharacter($this->testCharId);
        $this->assertEmpty($visibleAfter);
    }

    public function testDuplicateVotePrevention()
    {
        if (!$this->testCharId || !$this->testUserId) {
            $this->markTestSkipped("Données de test manquantes.");
        }

        $data = [
            'character_id' => $this->testCharId,
            'user_id' => $this->testUserId,
            'rating' => 5,
            'comment' => 'TestReview_Duplicate'
        ];

        $this->reviewModel->create($data);

        $this->assertTrue($this->reviewModel->hasUserVoted($this->testUserId, $this->testCharId));
    }
}
