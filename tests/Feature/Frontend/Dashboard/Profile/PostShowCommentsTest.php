<?php

namespace Tests\Feature\Frontend\Dashboard\Profile;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostShowCommentsTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $this->post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'status' => '1',
        ]);
    }

    public function test_can_get_post_comments()
    {
        $this->actingAs($this->user);
        Comment::factory()->count(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.dashboard.post.comments', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'comments',
                'status'
            ])
            ->assertJsonCount(3, 'comments');
    }

    public function test_comments_are_ordered_by_latest()
    {
        $this->actingAs($this->user);
        $older = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
            'created_at' => now()->subDays(2),
        ]);

        $newer = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
            'created_at' => now(),
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.dashboard.post.comments', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonPath('comments.0.id', $newer->id)
            ->assertJsonPath('comments.1.id', $older->id);
    }

    public function test_inactive_comments_are_excluded()
    {
        $this->actingAs($this->user);
        Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 0,
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.dashboard.post.comments', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonCount(0, 'comments');
    }

    public function test_comments_include_user_data()
    {
        $this->actingAs($this->user);
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.dashboard.post.comments', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonPath('comments.0.user.id', $this->user->id)
            ->assertJsonPath('comments.0.user.name', $this->user->name);
    }
}
