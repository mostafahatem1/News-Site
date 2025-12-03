<?php

namespace Tests\Feature\Api\General\Posts;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['status' => '1']);
        $this->category = Category::factory()->create(['status' => '1']);
    }

    /** @test */
    public function it_can_search_posts_by_title()
    {
        Post::factory()->create([
            'title' => 'Unique Title Test',
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1'
        ]);

        $response = $this->getJson(route('api.posts.search', ['search' => 'Unique']));

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
                            'user' => [
                                'name',
                                'status',
                                'created_at'
                            ],
                            'media' => [
                                '*' => ['path']
                            ],
                            'category'
                        ]
                    ],
                    'count'
                ]
            ]);
    }

    /** @test */
    public function it_can_search_posts_by_description()
    {
        Post::factory()->create([
            'desc' => 'Unique Description Content',
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1'
        ]);

        $response = $this->getJson(route('api.posts.search', ['search' => 'Unique Description']));

        $response->assertOk()
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
                            'user' => [
                                'name',
                                'status',
                                'created_at'
                            ],
                            'media' => [
                                '*' => ['path']
                            ],
                            'category'
                        ]
                    ],
                    'count'
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_no_posts_found()
    {
        $response = $this->getJson(route('api.posts.search', ['search' => 'NonExistentContent']));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No posts found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_paginates_search_results()
    {
        Post::factory()->count(10)->create([
            'title' => 'Searchable Title',
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1'
        ]);

        $response = $this->getJson(route('api.posts.search', ['search' => 'Searchable']));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'posts',
                    'count'
                ]
            ])
            ->assertJsonPath('data.count', 6);
    }

    /** @test */
    public function it_only_returns_active_posts()
    {
        Post::factory()->create([
            'title' => 'Active Post',
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => 1
        ]);

        Post::factory()->create([
            'title' => 'Inactive Post',
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => 0
        ]);

        $response = $this->getJson(route('api.posts.search', ['search' => 'Active Post']));

        $response->assertOk()
            ->assertJsonCount(1, 'data.posts');
    }
}
