<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\{Post,User};

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST','/api/post', [
            'title' => 'Post de Prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
        ->assertJson(['title' => 'Post de Prueba'])
        ->assertStatus(201); //Creando, Recurso

        $this->assertDatabaseHas('posts',['title' => 'Post de Prueba']);
    }

    public function test_validate_title()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('POST','/api/post', [
            'title' => ''
        ]);

        // estatus de que no se pudo completar
        $response->assertStatus(422)
                 ->assertJsonValidationErrors('title');
    }
    public function test_show()
    {
        $post = factory(Post::class)->create();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('GET',"/api/post/$post->id");

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
        ->assertJson(['title' => $post->title])
        ->assertStatus(200); //ok  
    }

    public function test_404_show()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('GET','/api/post/1000');

        $response->assertStatus(404); //not found  
    }

    public function test_update()
    {
        $update = factory(Post::class)->create();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('PUT',"/api/post/$update->id", [
            'title' => 'Post de Prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
        ->assertJson(['title' => 'Post de Prueba'])
        ->assertStatus(200); //OK

        $this->assertDatabaseHas('posts',['title' => 'Post de Prueba']);
    }

    public function test_delete()
    {
        $delete = factory(Post::class)->create();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('DELETE',"/api/post/$delete->id");

        $response->assertSee(null)
        ->assertStatus(204); //Sin contenido

        $this->assertDatabaseMissing('posts',['id' => $delete->id]);
    }

    public function test_index()
    {
        factory(Post::class,5)->create();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,'api')->json('GET',"/api/post");

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id','title','created_at','updated_at']
            ]
        ])->assertStatus(200); //OK
    }

    public function test_guest()
    {
        $this->json('GET','/api/post')->assertStatus(401);
        $this->json('POST','/api/post')->assertStatus(401);
        $this->json('GET','/api/post/100')->assertStatus(401);
        $this->json('PUT','/api/post/100')->assertStatus(401);
        $this->json('DELETE','/api/post/100')->assertStatus(401);
    }
}


// Para correr el test:
// php vendor/phpunit/phpunit/phpunit
// o con:
// php artisan test
// Recuerda descomentar de phpunit xml las dos lineas de base de datos

// Para crear el test:
// php artisan make:test PageTest