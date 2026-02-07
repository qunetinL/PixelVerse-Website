<?php

namespace PixelVerseTests\Unit;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Models\Accessory;

class AccessoryTest extends TestCase
{
    private $accessoryModel;

    protected function setUp(): void
    {
        $this->accessoryModel = new Accessory();
    }

    public function testModelInstantiation()
    {
        $this->assertInstanceOf(Accessory::class, $this->accessoryModel);
    }
}
