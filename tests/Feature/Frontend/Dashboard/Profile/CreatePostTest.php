<?php

namespace Tests\Feature\Frontend\Dashboard\Profile;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Category $category;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_authenticated_user_can_create_post()
    {
        Storage::fake('public');
        $this->actingAs($this->user);


        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'comment_able' => 'on',
            'images' => [
                UploadedFile::fake()->image('news1.jpg')
            ]
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $response->assertRedirect()
                ->assertSessionHasNoErrors();
        /////////////////////////////////////////////////////////////////////////////////////
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'comment_able' => 1,
            'user_id' => $this->user->id,

        ]);
    }

    public function test_can_create_post_with_images()
    {

        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'images' => [
                UploadedFile::fake()->image('news1.jpg'),
                UploadedFile::fake()->image('news2.jpg'),
            ]
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $post = Post::first();
        $this->assertCount(2, $post->images);

        // Fix path assertion to match actual storage location
        foreach ($post->images as $image) {
            $this->assertFileExists(public_path('/frontend/img/test/' . basename($image->path)));
        }
    }

    public function test_cache_is_cleared_after_post_creation()
    {
        Storage::fake('public');
        Cache::spy();
        $this->actingAs($this->user);
       /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'images' => [
                UploadedFile::fake()->image('post1.jpg')
            ]
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        Cache::shouldHaveReceived('forget')->with('read_more_posts');
        Cache::shouldHaveReceived('forget')->with('latest_posts');
        Cache::shouldHaveReceived('forget')->with('popular_posts');
    }

    public function test_validation_fails_with_invalid_data()
    {
        $this->actingAs($this->user);
        /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => '',
            'desc' => '',
            'category_id' => '',
        ]);
       /////////////////////////////////////////////////////////////////////////////////////
        $response->assertSessionHasErrors(['title', 'desc', 'category_id']);
    }

    public function test_comment_able_is_toggled_correctly()
    {
        Storage::fake('public');
        $this->actingAs($this->user);
         /////////////////////////////////////////////////////////////////////////////////////
        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'comment_able' => 'off',
            'images' => [
                UploadedFile::fake()->image('post1.jpg')
            ]
        ]);
        /////////////////////////////////////////////////////////////////////////////////////
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'comment_able' => 0
        ]);
    }
}
