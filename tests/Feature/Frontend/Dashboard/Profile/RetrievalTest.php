<?php

namespace Tests\Feature\Frontend\Dashboard\Profile;

use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_profile()
    {
        $response = $this->get(route('frontend.dashboard.profile'));
        $response->assertStatus(302)
        ->assertRedirect(route('frontend.login'));
    }

    public function test_authenticated_user_can_see_their_posts()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'status' => '1'
        ]);
        Image::factory()->create(['post_id' => $post->id]);
        ///////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.profile'));
        /////////////////////////////////////////////////////////////
        $response->assertStatus(200)
                ->assertViewIs('frontend.dashboard.profile')
                ->assertViewHas('posts')
                ->assertSee($post->title)
                ->assertSee('Add Post')
                ->assertSee('Edit')
                ->assertSee('Delete')
                ->assertSee('Show Comments')
                ->assertSee('Notifications')
                ->assertSee('Settings');
    }

    public function test_user_cannot_see_other_users_posts()
    {
        $this->actingAs($this->user);
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();

        $otherPost = Post::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'status' => '1'
        ]);
        ////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.profile'));
        //////////////////////////////////////////////////////////
        $response->assertStatus(200)
                ->assertDontSee($otherPost->title);
    }

    public function test_posts_are_ordered_by_latest()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create();

        $oldPost = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'status' => '1',
            'created_at' => now()->subDays(2)
        ]);

        $newPost = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'status' => '1',
            'created_at' => now()
        ]);
        ////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.profile'));
        $posts = $response->viewData('posts');
        ///////////////////////////////////////////////////////////
        $this->assertTrue($posts->first()->id === $newPost->id);
    }
}
