<?php

namespace App\Notifications;

use App\Events\QuestionPublished;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

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
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    #[ArrayShape(['user_id' => "int", 'user_name' => "string", 'user_avatar' => "mixed", 'question_link' => "string", 'question_title' => "string"])]
    public function toArray($notifiable): array
    {
        $questionCreator = $this->question->creator;

        return [
            'user_id' => $questionCreator->id,
            'user_name' => $questionCreator->name,
            'user_avatar' => $questionCreator->userAvatar,
            'question_link' => route('questions.show', [
                'category' => Category::find($this->question->category_id)->slug,
                'question' => $this->question->id
            ]),
            'question_title' => $this->question->title,
        ];
    }
}
