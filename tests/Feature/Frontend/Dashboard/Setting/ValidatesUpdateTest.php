<?php

namespace Tests\Feature\Frontend\Dashboard\Setting;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidatesUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private const VALID_PASSWORD = 'password123';
    private const UPDATED_PASSWORD = 'newpassword123';
    private const INVALID_PASSWORD = 'wrong';
    private const MAX_STRING_LENGTH = 255;
    private const GENDER_MALE = '0';
    private const GENDER_FEMALE = '1';

    protected User $user;
    protected array $validData;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->user = User::factory()->create([
            'password' => Hash::make(self::VALID_PASSWORD)
        ]);

        $this->validData = [
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'gender' => self::GENDER_MALE,
        ];
    }

    /**
     * @test
     * @dataProvider validUpdateDataProvider
     */
    public function it_updates_user_profile_with_valid_data(array $updateData)
    {
        $this->actingAs($this->user);

        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, $updateData)
        );

        $response->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->user->refresh();

        // Handle password update separately
        if (isset($updateData['password'])) {
            $this->assertTrue(
                Hash::check($updateData['password'], $this->user->password),
                'Password was not updated correctly'
            );
            // Remove password-related fields before checking other updates
            unset($updateData['password'], $updateData['password_confirmation'], $updateData['current_password']);
        }

        // Check other field updates
        foreach ($updateData as $key => $value) {
            $this->assertEquals(
                $value,
                $this->user->$key,
                "Failed asserting that field '{$key}' was updated correctly"
            );
        }
    }

    /**
     * @test
     * @dataProvider invalidUpdateDataProvider
     */
    public function it_validates_update_data(array $invalidData, array $expectedErrors)
    {
        $this->actingAs($this->user);

        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, $invalidData)
        );

        $response->assertRedirect()
            ->assertSessionHasErrors($expectedErrors);
    }

    public static function validUpdateDataProvider(): array
    {
        return [
            'basic profile update' => [[
                'name' => 'New Name',
                'phone' => '1234567890',
            ]],
            'complete profile update' => [[
                'name' => 'Complete Name',
                'phone' => '1234567890',
                'country' => 'Test Country',
                'city' => 'Test City',
                'street' => 'Test Street',
                'gender' => self::GENDER_FEMALE,
            ]],
            'password update' => [[
                'current_password' => self::VALID_PASSWORD,
                'password' => self::UPDATED_PASSWORD,
                'password_confirmation' => self::UPDATED_PASSWORD,
            ]],
        ];
    }

    public static function invalidUpdateDataProvider(): array
    {
        return [
            'empty required fields' => [
                ['name' => '', 'email' => ''],
                ['name', 'email']
            ],
            'invalid email format' => [
                ['email' => 'invalid-email'],
                ['email']
            ],
            'password mismatch' => [
                [
                    'current_password' => self::VALID_PASSWORD,
                    'password' => self::UPDATED_PASSWORD,
                    'password_confirmation' => 'different'
                ],
                ['password']
            ],
            'invalid gender' => [
                ['gender' => '2'],
                ['gender']
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validImageTypesProvider
     */
    public function it_handles_image_uploads(string $extension, bool $shouldBeValid)
    {
        $this->actingAs($this->user);

        $image = $shouldBeValid
            ? UploadedFile::fake()->image("avatar.$extension")
            : UploadedFile::fake()->create("document.$extension");

        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, ['image' => $image])
        );

        if ($shouldBeValid) {
            $response->assertSessionHasNoErrors('image');
            $this->assertNotNull($this->user->fresh()->image);
        } else {
            $response->assertSessionHasErrors('image');
        }
    }

    public static function validImageTypesProvider(): array
    {
        return [
            'jpg is valid' => ['jpg', true],
            'jpeg is valid' => ['jpeg', true],
            'png is valid' => ['png', true],
            'webp is valid' => ['webp', true],
            'pdf is invalid' => ['pdf', false],
            'txt is invalid' => ['txt', false],
        ];
    }

    /** @test */
    public function it_prevents_duplicate_username_and_email()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($this->user);

        $response = $this->put(route('frontend.dashboard.setting.update'), [
            'name' => 'Valid Name',
            'username' => $otherUser->username,
            'email' => $otherUser->email,
            'gender' => self::GENDER_MALE,
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }

    /** @test */
    public function it_allows_password_to_be_optional()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('frontend.dashboard.setting.update'), [
            'name' => 'Valid Name',
            'username' => 'validusername',
            'email' => 'valid@email.com',
            'gender' => self::GENDER_MALE
        ]);

        $response->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check(self::VALID_PASSWORD, $this->user->fresh()->password));
    }

    /** @test */
    public function it_validates_image_size_and_type()
    {
        $this->actingAs($this->user);

        // Test oversized image
        $oversizedImage = UploadedFile::fake()->image('large.jpg')->size(3000);
        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, ['image' => $oversizedImage])
        );
        $response->assertSessionHasErrors('image');

        // Test invalid mime type
        $invalidImage = UploadedFile::fake()->create('fake.jpg', 500, 'text/plain');
        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, ['image' => $invalidImage])
        );
        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function it_shows_settings_page()
    {
        $this->actingAs($this->user)
            ->get(route('frontend.dashboard.setting'))
            ->assertViewIs('frontend.dashboard.setting')
            ->assertStatus(200);
    }

    /** @test */
    public function it_handles_database_transaction_failure()
    {
        $this->actingAs($this->user);

        // Force a database exception by using a too long string
        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, [
                'name' => str_repeat('a', 1000) // Exceeds database column length
            ])
        );

        $response->assertRedirect();  // Check for error flash message

        // Verify no changes were saved
        $this->user->refresh();
        $this->assertNotEquals(str_repeat('a', 1000), $this->user->name);
    }

    /** @test */
    public function it_keeps_existing_image_when_no_new_image_is_provided()
    {
        $this->actingAs($this->user);

        // First update with an image
        $initialImage = UploadedFile::fake()->image('initial.jpg');
        $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, ['image' => $initialImage])
        );

        $originalImage = $this->user->fresh()->image;

        // Update without providing new image
        $response = $this->put(route('frontend.dashboard.setting.update'),
            array_merge($this->validData, ['name' => 'New Name'])
        );

        $this->user->refresh();
        $this->assertEquals($originalImage, $this->user->image);
    }


    /** @test */
    public function it_keeps_old_input_when_password_validation_fails()
    {
        $this->actingAs($this->user);

        $data = array_merge($this->validData, [
            'current_password' => 'wrong_password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response = $this->put(route('frontend.dashboard.setting.update'), $data);

        $response->assertRedirect()
            ->assertSessionHasErrors('current_password')
            ->assertSessionHasInput('email', $data['email'])
            ->assertSessionHasInput('name', $data['name']);
    }


}




