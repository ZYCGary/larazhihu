<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class AnswerUpVotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Answer $answer): Response|Application|ResponseFactory
    {
        $answer->voteUp(Auth::user());

        return response([], 201);
    }

    public function destroy(Answer $answer): Response|Application|ResponseFactory
    {
        $answer->cancelVoteUp(Auth::user());

        return response([], 201);
    }
}
