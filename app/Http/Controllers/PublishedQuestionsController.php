<?php

namespace App\Http\Controllers;

use App\Events\QuestionPublished;
use App\Models\Question;
use App\Models\User;
use App\Notifications\YouWereMentioned;

class PublishedQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        $this->authorize('update', $question);

        $question->publish();

        event(new QuestionPublished($question));

        return redirect("/questions/{$question->id}")->with('flash', "Your question is published successfully！");
    }
}
