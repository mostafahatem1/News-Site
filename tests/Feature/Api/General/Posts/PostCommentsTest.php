<?php

namespace Tests\Feature\Api\General\Posts;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCommentsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['status' => '1']);
        $this->category = Category::factory()->create(['status' => '1']);

        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => 1
        ]);
    }

    /** @test */
    public function it_can_get_post_comments()
    {
        Comment::factory()->count(2)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson(route('api.posts.comments', $this->post->slug));

        $response->assertOk()
            ->assertJson([
                'message' => 'Post comments retrieved successfully',
                'status' => 200,
                'data' => [
                    'comments_count' => 2
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    'comments' => [
                        '*' => [
                            'comments',
                            'user_name',
                            'user_image',
                            'created_at'
                        ]
                    ],
                    'comments_count'
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_post_has_no_comments()
    {
        $response = $this->getJson(route('api.posts.comments', $this->post->slug));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No comments found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_returns_404_for_inactive_post()
    {
        $inactivePost = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => 0
        ]);

        $response = $this->getJson(route('api.posts.comments', $inactivePost->slug));

        $response->assertStatus(404);
    }

    /** @test */
    public function comments_include_user_information()
    {
        Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson(route('api.posts.comments', $this->post->slug));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'comments' => [
                        '*' => [
                            'comments',
                            'user_name',
                            'user_image',
                            'created_at'
                        ]
                    ]
                ]
            ]);
    }
}
