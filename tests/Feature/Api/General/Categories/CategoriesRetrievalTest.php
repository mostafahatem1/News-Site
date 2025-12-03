<?php

namespace Tests\Feature\Api\General\Categories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesRetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['status' => '1']);
    }

    /** @test */
    public function it_can_retrieve_all_active_categories()
    {
        Category::factory()->count(3)->create(['status' => '1']);
        Category::factory()->create(['status' => '0']);

        $response = $this->getJson('/api/all/categories');

        $response->assertOk()
            ->assertJson([
                'message' => 'Categories retrieved successfully',
                'status' => 200
            ])
            ->assertJsonCount(3, 'data.categories')
            ->assertJsonStructure([
                'data' => [
                    'categories' => [
                        '*' => [
                            'id',
                            'category_name',
                            'slug',
                            'status',
                            'created_at'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_no_categories_exist()
    {
        $response = $this->getJson('/api/all/categories');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'No categories found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_can_retrieve_posts_for_a_category()
    {
        $category = Category::factory()->create(['status' => '1']);
        Post::factory()->count(4)->create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'status' => '1'
        ]);

        $response = $this->getJson("/api/category/{$category->slug}/posts");

        $response->assertOk()
            ->assertJson([
                'message' => 'Posts retrieved successfully',
                'status' => 200
            ])
            ->assertJsonStructure([
                'data' => [
                    'posts' => [
                        '*' => [
                            'id',
                            'title',
                            'desc',
                            'num_of_views',
                            'slug',
                            'status',
                            'created_at',
                            'user',
                            'media',
                            'category'
                        ]
                    ],
                    'count'
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_category()
    {
        $response = $this->getJson('/api/category/non-existent-category/posts');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Category not found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_returns_404_when_category_has_no_posts()
    {
        $category = Category::factory()->create(['status' => '1']);

        $response = $this->getJson("/api/category/{$category->slug}/posts");

        $response->assertNotFound()
            ->assertJson([
                'message' => 'No posts found for this category',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_only_returns_active_posts_from_active_users()
    {
        $category = Category::factory()->create(['status' => '1']);
        $inactiveUser = User::factory()->create(['status' => '0']);

        // Active post from active user
        Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'status' => '1'
        ]);

        // Inactive post from active user
        Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'status' => '0'
        ]);

        // Active post from inactive user
        Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $inactiveUser->id,
            'status' => '1'
        ]);

        $response = $this->getJson("/api/category/{$category->slug}/posts");

        $response->assertOk()
            ->assertJsonCount(1, 'data.posts');
    }
}
