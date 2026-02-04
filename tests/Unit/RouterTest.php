<?php

namespace PixelVerseTests\Unit;

use PHPUnit\Framework\TestCase;
use PixelVerseApp\Router\Router;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouteMatching()
    {
        $called = false;
        $this->router->add('GET', '/test', function () use (&$called) {
            $called = true;
        });

        // Mocking behavior or using output buffering to test dispatch
        ob_start();
        $this->router->dispatch('GET', '/test');
        ob_end_clean();

        $this->assertTrue($called, "Le handler de la route n'a pas été appelé.");
    }

    public function testNotFound()
    {
        ob_start();
        $this->router->dispatch('GET', '/non-existent');
        $output = ob_get_clean();

        $this->assertEquals(404, http_response_code(), "Le code HTTP devrait être 404.");
        $this->assertStringContainsString("404 Not Found", $output);
    }

    public function testMethodMatching()
    {
        $called = false;
        $this->router->add('POST', '/submit', function () use (&$called) {
            $called = true;
        });

        ob_start();
        $this->router->dispatch('GET', '/submit'); // Wrong method
        ob_end_clean();

        $this->assertFalse($called, "Le handler ne devrait pas être appelé avec la mauvaise méthode HTTP.");
    }
}
