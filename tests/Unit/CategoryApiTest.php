<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories()
    {
        Category::factory()->count(5)->create();

        $request = new Request([
            'limit' => 2,
            'page' => 1,
        ]);

        $response = $this->getJson('/api/category',$request->all());
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'is_publish',
                         ],
                     ],
                 ]);
    }

    public function test_can_search_categories()
    {
        Category::factory()->create([
            'name' => 'Test Category',
        ]);

        $request = new Request([
            'search' => 'Test Category',
        ]);

        $response = $this->getJson('/api/category', $request->all());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'is_publish',
                         ],
                     ],
                 ]);
    }

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/category', [
            'name' => 'Test Category',
            'is_publish' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    }

    public function test_can_read_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/category/' . $category->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $category->id,
                         'name' => $category->name,
                         'is_publish' => $category->is_publish,
                     ],
                 ]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson('/api/category/' . $category->id, [
            'name' => 'Updated Category',
            'is_publish' => 0,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('/api/category/' . $category->id);

        $response->assertStatus(200);
        $this->assertDeleted($category);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/category', []);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message', 'errors']);
    }
}
