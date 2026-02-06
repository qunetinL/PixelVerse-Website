<?php

namespace PixelVerseTests\Unit;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\Character;

class CharacterTest extends TestCase
{
    private $characterModel;

    protected function setUp(): void
    {
        $this->characterModel = new Character();
    }

    public function testNameExistsLogic()
    {
        $this->assertIsBool($this->characterModel->nameExists("GhostName"));
    }

    public function testRandomTraitsReturnValidValues()
    {
        $traits = $this->characterModel->getRandomTraits();

        $this->assertArrayHasKey('gender', $traits);
        $this->assertArrayHasKey('hair_style', $traits);
        $this->assertArrayHasKey('skin_color', $traits);

        $this->assertContains($traits['gender'], ['homme', 'femme', 'autre']);
        $this->assertContains($traits['hair_style'], ['court', 'long', 'punk', 'chauve']);
        $this->assertStringStartsWith('#', $traits['skin_color']);
    }
}
