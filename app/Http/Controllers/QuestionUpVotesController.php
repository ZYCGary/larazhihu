<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Auth;
use Illuminate\Http\Request;

class QuestionUpVotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function store(Question $question)
    {
        $question->voteUp(Auth::user());

        return response([], 201);
    }

    public function destroy(Question $question)
    {
        $question->cancelVoteUp(Auth::user());

        return response([], 201);
    }
}
