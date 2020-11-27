<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(AnswerRequest $request, $questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        $question->answers()->create([
            'user_id' => auth()->id(),
            'content' => request('content')
        ]);

        return back()->with('flash', 'Post your answer successfully!');
    }

    public function destroy(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        return back()->with('flash', 'Delete your answer successfully');
    }
}
