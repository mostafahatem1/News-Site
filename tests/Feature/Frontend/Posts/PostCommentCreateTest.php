<?php

namespace Tests\Feature\Frontend\Posts;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostCommentCreateTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;
    protected User $postOwner;

    public function setUp(): void
    {
        parent::setUp();
        $this->postOwner = User::factory()->create();
        $category = Category::factory()->create();
        $this->post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $this->postOwner->id,
            'status' => '1',
        ]);
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_create_comment()
    {
        $this->actingAs($this->user);
        ///////////////////////////////////////////////////////////////////
        $response = $this->postJson(route('frontend.post.comment.store'), [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
        ////////////////////////////////////////////////////////////////////
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'comment' => ['id', 'comment', 'user_id', 'post_id', 'ip_address', 'user'],
                'status'
            ]);

        $this->assertDatabaseHas('comments', [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
    }


    public function test_comment_creation_sends_notification_to_post_owner()
    {
        Notification::fake();
        $this->actingAs($this->user);
        ///////////////////////////////////////////////////////////////////////
        $response = $this->postJson(route('frontend.post.comment.store'), [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
        ///////////////////////////////////////////////////////////////////////////////
        Notification::assertSentTo(
            $this->postOwner,
            NewCommentNotification::class
        );
    }

    public function test_comment_stores_ip_address()
    {
        $this->actingAs($this->user);
        ///////////////////////////////////////////////////////////////////
        $response = $this->postJson(route('frontend.post.comment.store'), [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
        ////////////////////////////////////////////////////////////////////
        $this->assertDatabaseHas('comments', [
            'ip_address' => request()->ip(),
        ]);
    }

    public function test_validation_fails_with_empty_comment()
    {
        $this->actingAs($this->user);
         ////////////////////////////////////////////////////////////////////////////
        $response = $this->postJson(route('frontend.post.comment.store'), [
            'comment' => '',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
         ////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['comment']);
    }

    public function test_unauthenticated_user_cannot_create_comment()
    {
        $response = $this->postJson(route('frontend.post.comment.store'), [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response->assertStatus(401);
     ////////////////////////////////////////////////////////////////////////////
        $this->assertDatabaseMissing('comments', [
            'comment' => 'Test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
    }
}
