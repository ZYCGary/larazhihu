<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FollowQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question): Response|Application|ResponseFactory
    {
        $question->followedBy(auth()->id());

        return response([], 201);
    }

    public function destroy(Question $question): Response|Application|ResponseFactory
    {
        $question->unfollowedBy(auth()->id());

        return response([], 201);
    }
}
