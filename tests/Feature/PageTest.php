<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_home()
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
    }
}

// Para correr el test:
// php vendor/phpunit/phpunit/phpunit

// Para crear el test:
// php artisan make:test PageTest