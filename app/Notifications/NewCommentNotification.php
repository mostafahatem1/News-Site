<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $comment;
    public $post;

    /**
     * Create a new notification instance.
     */
    public function __construct($comment, $post)
    {
        $this->comment = $comment;
        $this->post = $post;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */


    public function toDatabase(object $notifiable): array
    {
        return [
            'comment' => $this->comment->comment,
            'user_id' => $this->comment->user_id,
            'post_title' => $this->post->title,
            'commented_by' => $this->comment->user->name,
            'commented_at' => $this->comment->created_at,
            'link' => route('frontend.post.show', $this->post->slug),
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
         return [
            'comment' => $this->comment->comment,
            'user_id' => $this->comment->user_id,
            'post_title' => $this->post->title,
            'commented_by' => $this->comment->user->name,
            'commented_at' => $this->comment->created_at,
            'link' => route('frontend.post.show', $this->post->slug),
        ];
    }
}
