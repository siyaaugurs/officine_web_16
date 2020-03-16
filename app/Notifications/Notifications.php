<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use HtmlString;

class Notifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $notification_detail;
    public $image_url;
    public function __construct($notification_detail)
    {
        $this->notification_detail = $notification_detail;
        $this->image_url = url("storage/Notification/".$notification_detail->file);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->line($this->notification_detail->title)
                    ->subject($this->notification_detail->subject)
                    ->action('Notification Action', $this->notification_detail->url)
                    // ->line($this->notification_detail->content)
                    ->line(strip_tags($this->notification_detail->content))
                    ->attach($this->image_url)
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
        return [
            //
        ];
    }
}
