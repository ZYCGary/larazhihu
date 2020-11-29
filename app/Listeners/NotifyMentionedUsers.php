<?php

namespace App\Listeners;

use App\Events\QuestionPublished;
use App\Models\User;
use App\Notifications\YouWereMentioned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  QuestionPublished  $event
     * @return void
     */
    public function handle(QuestionPublished $event)
    {
        User::whereIn('name', $event->question->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->question));
            });
    }
}
