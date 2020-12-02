<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Auth;
use Illuminate\Http\Request;

class QuestionDownVotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function store(Question $question)
    {
        $question->voteDown(Auth::user());

        return response([], 201);
    }

    public function destroy(Question $question)
    {
        $question->cancelVoteDown(Auth::user());

        return response([], 201);
    }
}
