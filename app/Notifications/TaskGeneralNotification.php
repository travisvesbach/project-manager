<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use Auth;

class TaskGeneralNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $user;
    protected $description;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task, $description)
    {
        $this->task = $task;
        $this->user = Auth::user() ? Auth::user() : $task->owner;
        $this->description = $description;
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
        return [
            'user' => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "profile_photo_url" => $this->user->profile_photo_url,
            ],
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'path' => $this->task->path(),
            'description' => $this->description,
        ];
    }
}
