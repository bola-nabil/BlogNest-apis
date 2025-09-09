<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Blog;

class BlogLikedNotification extends Notification
{
    use Queueable;

    protected $blog;
    protected $liker;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Blog $blog, $liker)
    {
        $this->blog = $blog;
        $this->liker = $liker;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'message'    => "{$this->liker->name} liked your blog: {$this->blog->title}"
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
