<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Books;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\LoginApi;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, LoginApi;

    public function test_get_buscar_todos_os_books()
    {
        $count = 3;
        $books = Books::factory($count)->create();
        
        $response = $this->login()->getJson('/api/books');

        $response->assertStatus(200);
        $response->assertJsonCount($count);
        
        $response->assertJson(function (AssertableJson $json) use ($books) {
            $json->hasAll(['0.title', '0.isbn']);
            
            $json->whereAlltype([
                '0.title' => 'string',
                '0.isbn' => 'string',
            ]);

            $book = $books->first();

            $json->whereAll([
                '0.title' => $book->title,
                '0.isbn' => $book->isbn,
            ]);
        });
    }

    public function test_post_cadastrar_book()
    {
        $book = Books::factory()->make()->toArray();

        $response = $this->login()->postJson('/api/books', $book);
        
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['title', 'isbn'])->etc();

            $json->where('title', $book['title'])
                ->where('isbn', $book['isbn']);
        });        
    }

    public function test_get_buscar_book_especifico()
    {
        $book = Books::factory()->createOne();
     
        $response = $this->login()->getJson('/api/books');

        $response = $this->getJson('/api/books/'. $book->id);
        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['title', 'isbn'])->etc();

            $json->whereAlltype([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string',
            ]);

            $json->where('title', $book->title)
                ->where('isbn', $book->isbn);
        });
    }

    public function test_put_atualizar_book_especifico()
    {
        $bookFactory = Books::factory()->createOne();

        $book = [
            'title' => 'Titulo atualizado...',
            'isbn' => '13512341232'
        ];
    
        $response = $this->login()->putJson('/api/books/' . $bookFactory->id, $book);
        
        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['title', 'isbn'])->etc();

            $json->whereAlltype([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string',
            ]);

            $json->where('title', $book['title'])
                ->where('isbn', $book['isbn']);
        });
    }

    public function test_patch_atualizar_book_especifico()
    {
        $bookFactory = Books::factory()->createOne();

        $book = [
            'title' => 'Titulo atualizado patch...'
        ];
    
        $response = $this->login()->patchJson('/api/books/' . $bookFactory->id, $book);
        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['title', 'isbn'])->etc();

            $json->whereAlltype([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string',
            ]);

            $json->where('title', $book['title']);
        });
    }

    public function test_destroy_remove_book_especifico()
    {
        $book = Books::factory()->createOne();

        $book = Books::find($book->id);
        $this->assertNotNull($book);

        $response = $this->login()->deleteJson('/api/books/' . $book->id);
        $response->assertStatus(204);

        $book = Books::find($book->id);
        $this->assertNull($book);
    }
}
