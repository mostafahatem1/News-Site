<?php

namespace Tests\Feature\Frontend\Dashboard\Profile;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AuthorizationPostTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected Category $category;
    protected $fakeImage;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->fakeImage = UploadedFile::fake()->image('test.jpg');
        $this->post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $this->category->id,
        ]);
    }



    public function test_unauthorized_cannot_access_profile()
    {
        $response = $this->get(route('frontend.dashboard.profile'));
        $response->assertRedirect(route('frontend.login'));
    }

    public function test_unauthorized_cannot_create_post()
    {
        $response = $this->post(route('frontend.dashboard.post.store'), [
            'title' => 'Test Post',
            'desc' => 'Test Description',
            'category_id' => $this->category->id,
            'comment_able' => 'on',
            'images' => [$this->fakeImage]
        ]);

        $response->assertRedirect(route('frontend.login'));
    }

    public function test_unauthorized_cannot_edit_post()
    {
        $response = $this->get(route('frontend.dashboard.post.edit', $this->post->id));
        $response->assertRedirect(route('frontend.login'));
    }

    public function test_unauthorized_cannot_update_post()
    {
        $response = $this->post(route('frontend.dashboard.post.update'), [
            'post_id' => $this->post->id,
            'title' => 'Updated Title',
            'desc' => 'Updated Description',
            'category_id' => $this->category->id,
            'comment_able' => 'on',
            'images' => [$this->fakeImage]
        ]);

        $response->assertRedirect(route('frontend.login'));
    }

    public function test_unauthorized_cannot_delete_post()
    {
        $response = $this->delete(route('frontend.dashboard.post.delete', $this->post->id));
        $response->assertRedirect(route('frontend.login'));
    }

    public function test_unauthorized_cannot_access_post_comments()
    {
        $response = $this->getJson(route('frontend.dashboard.post.comments', $this->post->id));
        $response->assertStatus(401);
    }
}
