<?php

namespace Tests\Feature\Api\General\Posts;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostsRetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected $posts;

    public function setUp(): void
    {
        parent::setUp();

        // Create active user and category
        $this->user = User::factory()->create(['status' => '1']);
        $this->category = Category::factory()->create(['status' => '1']);

        // Create 10 active posts with images
        $this->posts = Post::factory()->count(10)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1'
        ])->each(function ($post) {
            Image::factory()->create(['post_id' => $post->id]);
        });
    }

    /** @test */
    public function it_retrieves_all_posts_with_proper_structure()
    {
        $response = $this->getJson(route('api.posts.all'));

        $response->assertStatus(200)
             ->assertOk()
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    'posts',
                    'latest_posts',
                    'oldest_posts',
                    'popular_posts',
                    'most_viewed_posts',
                    'category_with_posts'
                ]
            ]);
    }

    /** @test */
    public function it_paginates_posts_correctly()
    {
        $response = $this->getJson(route('api.posts.all'));

        $response->assertOk();
        $posts = collect($response->json('data.posts.data.posts'));
        $this->assertCount(4, $posts);
        $this->assertEquals(1, $response->json('data.posts.meta.current_page'));
    }

    /** @test */
    public function it_returns_latest_posts()
    {
        $latestPost = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1',
            'created_at' => now()
        ]);

        $response = $this->getJson(route('api.posts.all'));

        $latestPosts = collect($response->json('data.latest_posts.posts'));
        $this->assertEquals($latestPost->id, $latestPosts->first()['id']);
    }

    /** @test */
    public function it_returns_popular_posts_by_comments()
    {
        $popularPost = $this->posts[0];
        Comment::factory()->count(5)->create(['post_id' => $popularPost->id]);

        $response = $this->getJson(route('api.posts.all'));

        $popularPosts = collect($response->json('data.popular_posts.posts'));
        $this->assertEquals($popularPost->id, $popularPosts->first()['id']);
    }

    /** @test */
    public function it_returns_most_viewed_posts()
    {
        $viewedPost = $this->posts[0];
        $viewedPost->update(['num_of_views' => 100]);

        $response = $this->getJson(route('api.posts.all'));

        $mostViewedPosts = collect($response->json('data.most_viewed_posts.posts'));
        $this->assertEquals($viewedPost->id, $mostViewedPosts->first()['id']);
    }

    /** @test */
    public function it_only_returns_active_posts()
    {
        Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '0'
        ]);

        $response = $this->getJson(route('api.posts.all'));

        $posts = collect($response->json('data.posts.data.posts'));
        foreach ($posts as $post) {
            $this->assertContains($post['status'], ['Active']);
        }
    }

    /** @test */
    public function it_includes_category_with_posts()
    {
        $response = $this->getJson(route('api.posts.all'));

        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'category_with_posts' => [
                    'categories' => [
                        '*' => [
                            'id',
                            'category_name',
                            'slug',
                            'status',
                            'created_at',
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
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_returns_404_when_no_posts_exist()
    {
        Post::query()->delete(); // Reset database state

        $response = $this->getJson(route('api.posts.all'));

        $response->assertStatus(404)
            ->assertExactJson([
                'message' => 'No posts found',
                'status' => 404
            ]);
    }
}


