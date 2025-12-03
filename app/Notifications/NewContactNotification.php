<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

      public function toDatabase(object $notifiable): array
    {
        return [
            'contact_id' => $this->data->id, // Assuming you have an ID to link to the contact
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'title' => $this->data['title'],
            'body' => $this->data['body'],
            'phone' => $this->data['phone'] ?? null,
            'ip_address' => $this->data['ip_address'] ?? null,
            'created_at' => now(),
            'link' => route('admin.contact.show', $this->data->id), // Assuming you have an ID to link to the contact
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
         return [

            'name' => $this->data['name'],
            'title' => $this->data['title'],
            'created_at' => now(),
            'link' => route('admin.contact.show', $this->data->id), // Assuming you have an ID to link to the contact
        ];
    }
   

}
