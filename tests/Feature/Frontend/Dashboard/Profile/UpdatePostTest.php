<?php

namespace Tests\Feature\Frontend\Dashboard;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;
    protected Category $category;
    protected $fakeImage;
    protected array $validPostData;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['status' => '1']);
        $this->fakeImage = UploadedFile::fake()->image('test.jpg');
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $this->validPostData = [
            'post_id' => $this->post->id,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'category_id' => $this->category->id,
            'images' => [$this->fakeImage]
        ];
    }

    private function makeUpdateRequest($data = [], $user = null)
    {
        if ($user) {
            $this->actingAs($user);
        }
        return $this->post(route('frontend.dashboard.post.update'), array_merge($this->validPostData, $data));
    }

    public function test_authorized_user_can_view_edit_page()
    {
        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.post.edit', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(200)
            ->assertViewIs('frontend.dashboard.edit_post')
            ->assertViewHas('post')
            ->assertViewHas('categories');
    }

    public function test_unauthorized_user_cannot_edit_others_post()
    {
        $otherUser = User::factory()->create();
        $response = $this->makeUpdateRequest([], $otherUser);

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_nonexistent_post_returns_404()
    {
        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.post.edit', 999));
        /////////////////////////////////////////////////////////////////////////////
        $response->assertStatus(404);
    }

    public function test_edit_page_shows_active_categories()
    {
        $this->actingAs($this->user);

        // Create inactive and active categories
        Category::factory()->create(['status' => '0']);
        $activeCategory = Category::factory()->create(['status' => '1']);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.dashboard.post.edit', $this->post->id));
        /////////////////////////////////////////////////////////////////////////////
        $response->assertViewHas('categories', function($categories) {
            // Debug the categories collection
            dump($categories->pluck('status')->toArray());

            return $categories->where('status', '1')->count() === $categories->count();
        });
    }

    /// Update Post User ///////////////////////////////////

    public function test_authorized_user_can_update_post()
    {
        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.update'), [
            'post_id' => $this->post->id,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'category_id' => $this->category->id,
            'comment_able' => 'on',
            'images' => [$this->fakeImage]
        ]);
        /////////////////////////////////////////////////////////////////////////////
        $response->assertRedirect(route('frontend.dashboard.profile'));
        $this->assertDatabaseHas('posts', [
            'id' => $this->post->id,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'comment_able' => 1
        ]);
    }

    public function test_unauthorized_user_cannot_update_others_post()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.update'), [
            'post_id' => $this->post->id,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'category_id' => $this->category->id,
        ]);
        /////////////////////////////////////////////////////////////////////////////
        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_cache_is_cleared_after_post_update()
    {
        Cache::spy();
        $response = $this->makeUpdateRequest([], $this->user);

        foreach(['read_more_posts', 'latest_posts', 'popular_posts'] as $cacheKey) {
            Cache::shouldHaveReceived('forget')->with($cacheKey);
        }
    }

    public function test_update_post_with_invalid_data_fails()
    {
        $invalidData = [
            'title' => '',
            'desc' => '',
            'category_id' => '',
        ];

        $response = $this->makeUpdateRequest($invalidData, $this->user);

        $response->assertRedirect()
            ->assertSessionHasErrors('errors');

        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id,
            'title' => '',
            'desc' => ''
        ]);
    }

    public function test_nonexistent_post_update_returns_404()
    {
        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.update'), [
            'post_id' => 999,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'category_id' => $this->category->id,
            'images' => [
                UploadedFile::fake()->image('updated.jpg')
            ]
        ]);
        /////////////////////////////////////////////////////////////////////////////
        $response->assertRedirect();

    }
}
