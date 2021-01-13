<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;

class AnswerDownVotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Answer $answer): Response|Application|ResponseFactory
    {
        $answer->voteDown(Auth::user());

        return response([], 201);
    }

    public function destroy(Answer $answer): Response|Application|ResponseFactory
    {

        $answer->cancelVoteDown(Auth::user());

        return response([], 201);
    }
}
