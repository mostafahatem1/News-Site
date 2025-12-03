<?php

namespace Tests\Feature\Frontend;

use App\Mail\Frontend\NewSubscriberMail;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewSebscribeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_home_page(): void
    {
        $response = $this->get('/')
            ->assertStatus(200);
    }

    public function test_subscribe_with_valid_email()
    {
        Mail::fake();

        $response = $this->post('/subscribe', [
            'email' => 'test@example.com',
        ])
            ->assertRedirect();

        // Verify email was stored
        $this->assertDatabaseHas('new_sebscribers', [
            'email' => 'test@example.com'
        ]);

        Mail::assertSent(NewSubscriberMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }
    
    public function test_subscribe_with_invalid_email_fails_validation()
    {
        Mail::fake();

        $response = $this->post('/subscribe', [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_subscribe_with_duplicate_email_fails_validation()
    {
        Mail::fake();

        // Create a subscriber first
        \App\Models\NewSebscriber::create(['email' => 'test@example.com']);

        $response = $this->post('/subscribe', [
            'email' => 'test@example.com',
        ])
            ->assertStatus(302)
            ->assertRedirect();

        $response->assertSessionHasErrors('email');
    }
}
