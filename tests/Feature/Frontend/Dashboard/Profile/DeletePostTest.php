<?php

namespace Tests\Feature\Frontend\Dashboard\Profile;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;
    protected Admin $admin;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->user = User::factory()->create();
        $this->admin = Admin::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        Image::factory()->create([
            'post_id' => $this->post->id,
            'path' => 'test/test.jpg'  // Changed from file_name to path
        ]);
    }

    public function test_authorized_user_can_delete_own_post()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('frontend.dashboard.post.delete', $this->post->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
        $this->assertDatabaseMissing('images', ['post_id' => $this->post->id]);
    }

    public function test_unauthorized_user_cannot_delete_others_post()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $response = $this->delete(route('frontend.dashboard.post.delete', $this->post->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $this->post->id]);
    }

    // public function test_admin_can_delete_any_post()
    // {
    //     $this->actingAs($this->admin, 'admin');

    //     // Verify admin is authenticated
    //     $this->assertTrue(auth('admin')->check());

    //     $response = $this->delete(route('frontend.dashboard.post.delete', $this->post->id));

    //     $response->assertRedirect();
    //     $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);

    //     // Also verify image is deleted
    //     $this->assertDatabaseMissing('images', ['post_id' => $this->post->id]);
    // }

    public function test_cache_is_cleared_after_post_deletion()
    {
        Cache::spy();
        $this->actingAs($this->user);

        $this->delete(route('frontend.dashboard.post.delete', $this->post->id));

        Cache::shouldHaveReceived('forget')->with('read_more_posts');
        Cache::shouldHaveReceived('forget')->with('latest_posts');
        Cache::shouldHaveReceived('forget')->with('popular_posts');
    }

    public function test_deleting_nonexistent_post_returns_error()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('frontend.dashboard.post.delete', 999));

        $response->assertRedirect();
        $response->assertSessionHasErrors('errors');
    }
}
