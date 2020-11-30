<?php

namespace App\Notifications;

use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class YouWereMentioned extends Notification
{
    use Queueable;

    /**
     * @var Question
     */
    private $question;

    /**
     * Create a new notification instance.
     *
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
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
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $questionCreator = $this->question->creator;

        return [
            'user_id' => $questionCreator->id,
            'user_name' => $questionCreator->name,
            'user_avatar' => $questionCreator->userAvatar,
            'question_link' => route('questions.show', $this->question->id),
            'question_title' => $this->question->title,
        ];
    }
}