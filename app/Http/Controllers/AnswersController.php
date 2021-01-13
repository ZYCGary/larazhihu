<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class AnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(AnswerRequest $request, $questionId): RedirectResponse
    {
        $question = Question::published()->findOrFail($questionId);

        $question->addAnAnswer([
            'user_id' => auth()->id(),
            'content' => request('content')
        ]);

        return back()->with('flash', 'Post your answer successfully!');
    }

    public function destroy(Answer $answer): RedirectResponse
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        return back()->with('flash', 'Delete your answer successfully');
    }
}
