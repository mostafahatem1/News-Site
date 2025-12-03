<?php

namespace Tests\Feature\Frontend;

use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_show_contact_page()
    {
        $response = $this->get(route('frontend.contact.show'));
        $response->assertStatus(200);
          $response->assertSeeText('Send Message');
        $response->assertViewIs('frontend.contact-us');
    }



        public function test_store_contact_form_sends_notification_and_redirects()
    {
        // Fake notifications
        \Illuminate\Support\Facades\Notification::fake();

        // Create multiple admins for notification
        \App\Models\Admin::factory()->count(3)->create();

        // Prepare valid data
        $contactUser = Contact::factory()->make();

        $response = $this->post(route('frontend.contact.store'), $contactUser->toArray())
            ->assertStatus(302)
            ->assertRedirect();

        // Assert notification sent to all admins
        $admins = \App\Models\Admin::all();
        \Illuminate\Support\Facades\Notification::assertSentTo($admins, NewContactNotification::class);

        // Assert contact stored in DB
        $this->assertDatabaseHas('contacts', $contactUser->toArray());
    }

     public function test_store_contact_form_validation()
    {
        // Missing required fields
        $response = $this->post(route('frontend.contact.store'), []);
        $response->assertSessionHasErrors(['name', 'email', 'title', 'body']);

        // Invalid email
        $response = $this->post(route('frontend.contact.store'), [
            'name' => 'Test',
            'email' => 'not-an-email',
            'title' => 'Test Title',
            'body' => 'Test body',
        ]);
        $response->assertSessionHasErrors(['email']);

        // Exceed max length
        $response = $this->post(route('frontend.contact.store'), [
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'title' => str_repeat('b', 256),
            'body' => str_repeat('c', 1001),
        ]);
        $response->assertSessionHasErrors(['name', 'title', 'body']);

        // Valid data should not have errors
        $response = $this->post(route('frontend.contact.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'title' => 'Test Title',
            'body' => 'Test body',
            'phone' => '1234567890',
        ]);
        $response->assertSessionDoesntHaveErrors();
    }
}
