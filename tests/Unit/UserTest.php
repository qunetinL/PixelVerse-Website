<?php

namespace PixelVerseTests\Unit;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\User;
use PixelVerseApp\Core\Database;

class UserTest extends TestCase
{
    private $userModel;

    protected function setUp(): void
    {
        $this->userModel = new User();
    }

    public function testPasswordHashing()
    {
        $password = "Secret123!";
        $hashed = password_hash($password, PASSWORD_ARGON2ID);

        $this->assertTrue(password_verify($password, $hashed));
    }

    public function testResetTokenGeneration()
    {
        $token = bin2hex(random_bytes(32));
        $this->assertEquals(64, strlen($token));
    }
}
