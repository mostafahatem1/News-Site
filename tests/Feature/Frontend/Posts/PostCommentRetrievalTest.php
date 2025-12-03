<?php

namespace Tests\Feature\Frontend\Posts;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCommentRetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $category = Category::factory()->create();
        $this->post = Post::factory()->create([
            'category_id' => $category->id,
            'status' => '1',
        ]);
        $this->user = User::factory()->create();
    }

    /**
     * A basic feature test example.
     */


    public function test_inactive_post_returns_404()
    {
        $this->actingAs($this->user);

        $inactivePost = Post::factory()->create(['status' => '0']);
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.all.comment', ['slug' => $inactivePost->slug]));
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(404);
    }

    /////////////////   show all Comments
    
    public function test_can_get_all_active_comments_for_post()
    {
        $this->actingAs($this->user);

        Comment::factory()->count(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.all.comment', ['slug' => $this->post->slug]));
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonStructure(['comments'])
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
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.all.comment', ['slug' => $this->post->slug]));
        //////////////////////////////////////////////////////////////////////////////////////////////
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
        /////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.all.comment', ['slug' => $this->post->slug]));
        ////////////////////////////////////////////////////////////////////////////////////////////

        $response->assertStatus(200)
            ->assertJsonCount(0, 'comments');
    }

    ////////////// show less Comments

    public function test_less_comments_are_limited_to_three()
    {
        $this->actingAs($this->user);

        Comment::factory()->count(5)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        /////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.less.comment', ['slug' => $this->post->slug]));
        ////////////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonStructure(['comments'])
            ->assertJsonCount(3, 'comments');
    }

    public function test_less_comments_are_ordered_by_latest()
    {
        $this->actingAs($this->user);

        $oldest = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
            'created_at' => now()->subDays(3),
        ]);

        $middle = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
            'created_at' => now()->subDays(2),
        ]);

        $newest = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
            'created_at' => now(),
        ]);
        /////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.less.comment', ['slug' => $this->post->slug]));
        ////////////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonPath('comments.0.id', $newest->id)
            ->assertJsonPath('comments.1.id', $middle->id)
            ->assertJsonPath('comments.2.id', $oldest->id);
    }

    public function test_less_comments_excludes_inactive_ones()
    {
        $this->actingAs($this->user);

        Comment::factory()->count(2)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 0,
        ]);

        $active = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response = $this->getJson(route('frontend.post.less.comment', ['slug' => $this->post->slug]));
        //////////////////////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertJsonCount(1, 'comments')
            ->assertJsonPath('comments.0.id', $active->id);
    }

    public function test_unauthenticated_ajax_request_returns_401_with_redirect_url()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest'
        ])->getJson(route('frontend.post.all.comment', ['slug' => $this->post->slug]));
        ///////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['redirect_url'])
            ->assertJsonPath('redirect_url', route('frontend.login'));
    }
}
