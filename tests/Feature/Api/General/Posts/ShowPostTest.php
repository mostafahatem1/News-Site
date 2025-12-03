<?php

namespace Tests\Feature\Api\General\Posts;

use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();

        // Create active user and category
        $this->user = User::factory()->create(['status' => '1']);
        $this->category = Category::factory()->create(['status' => '1']);

        // Create active post with image
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '1'
        ]);

        Image::factory()->create(['post_id' => $this->post->id]);
    }

    /** @test */
    public function it_can_show_post_details()
    {
        $response = $this->getJson(route('api.posts.show', $this->post->slug));

        $response->assertOk()
            ->assertJson([
                'message' => 'Post retrieved successfully',
                'status' => 200,
                'data' => [
                    'id' => $this->post->id,
                    'title' => $this->post->title,
                    'slug' => $this->post->slug
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_post()
    {
        $response = $this->getJson(route('api.posts.show', 'non-existent-slug'));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Post not found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_returns_404_for_inactive_post()
    {
        $inactivePost = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => '0'
        ]);

        $response = $this->getJson(route('api.posts.show', $inactivePost->slug));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Post not found',
                'status' => 404
            ]);
    }

    /** @test */
    public function it_returns_404_for_post_with_inactive_user()
    {
        $this->user->update(['status' => '0']);

        $response = $this->getJson(route('api.posts.show', $this->post->slug));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Post not found',
                'status' => 404
            ]);
    }
}
