<?php

namespace Tests\Feature\Frontend\Dashboard\Setting;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);
    }

    public function test_user_can_view_settings_page()
    {
        $this->actingAs($this->user)
            ->get(route('frontend.dashboard.setting'))
            ->assertViewIs('frontend.dashboard.setting')
            ->assertStatus(200);
    }

    public function test_user_can_update_settings_without_password()
    {
        $response = $this->actingAs($this->user)
            ->put(route('frontend.dashboard.setting.update'), [
                'name' => 'New Name',
                'username' => 'newusername',
                'email' => 'new@email.com',
                'phone' => '1234567890',
                'country' => 'New Country',
                'city' => 'New City',
                'street' => 'New Street',
                'gender' => '0'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'New Name',
            'email' => 'new@email.com'
        ]);
    }

    public function test_user_can_update_password_with_correct_current_password()
    {
        $response = $this->actingAs($this->user)
            ->put(route('frontend.dashboard.setting.update'), [
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',  // Added confirmation
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'gender' => '0'  // Added required field
            ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    public function test_user_cannot_update_password_with_incorrect_current_password()
    {
        $response = $this->actingAs($this->user)
            ->put(route('frontend.dashboard.setting.update'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'gender' => '0'
            ]);

        $response->assertRedirect()
            ->assertSessionHasErrors('current_password');
    }

    public function test_user_can_update_settings_with_image()
    {
        $image = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->user)
            ->put(route('frontend.dashboard.setting.update'), [
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'gender' => '0',
                'image' => $image
            ]);

        $response->assertRedirect();
        $this->assertNotNull($this->user->fresh()->image);
    }

    public function test_invalid_data_returns_validation_errors()
    {
        $response = $this->actingAs($this->user)
            ->put(route('frontend.dashboard.setting.update'), [
                'name' => '',
                'email' => 'invalid-email',
            ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['name', 'email']);
    }
}
