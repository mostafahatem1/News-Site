<?php

namespace Tests\Feature\Api\General\Categories;

use App\Models\Admin;
use App\Notifications\NewContactNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function it_can_create_a_contact_successfully()
    {
        Admin::factory()->create();

        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'title' => 'Test Contact',
            'body' => 'This is a test message',
            'phone' => '1234567890'
        ];

        $response = $this->postJson('/api/contact/store', $contactData);

        $response->assertOk()
            ->assertJson([
                'message' => 'Your message has been sent successfully! We will get back to you soon.',
                'status' => 200
            ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        Notification::assertSentTo(
            [Admin::first()],
            NewContactNotification::class
        );
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/contact/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'title', 'body']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'title' => 'Test Contact',
            'body' => 'This is a test message'
        ];

        $response = $this->postJson('/api/contact/store', $contactData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'title' => 'Test Contact',
            'body' => 'This is a test message'
        ];

        // Make multiple requests to trigger rate limiting
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/contact/store', $contactData);
        }

        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function it_stores_ip_address()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'title' => 'Test Contact',
            'body' => 'This is a test message',
            'phone' => '01012345678',
        ];

        $response = $this->postJson('/api/contact/store', $contactData);

        $response->assertOk();

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'ip_address' => request()->ip()
        ]);
    }
}
