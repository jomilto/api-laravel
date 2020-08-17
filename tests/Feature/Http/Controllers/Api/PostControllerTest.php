<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        // $this->withoutExceptionHandling();
        $response = $this->json('POST','/api/post', [
            'title' => 'Post de Prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
        ->assertJson(['title' => 'Post de Prueba'])
        ->assertStatus(201); //Creando, Recurso

        $this->assertDatabaseHas('posts',['title' => 'Post de Prueba']);
    }

    public function test_validate_title()
    {
        $response = $this->json('POST','/api/post', [
            'title' => ''
        ]);

        // estatus de que no se pudo completar
        $response->assertStatus(422)
                 ->assertJsonValidationErrors('title');
    }
}


// Para correr el test:
// php vendor/phpunit/phpunit/phpunit
// o con:
// php artisan test
// Recuerda descomentar de phpunit xml las dos lineas de base de datos

// Para crear el test:
// php artisan make:test PageTest