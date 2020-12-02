<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class FollowQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        $question->followedBy(auth()->id());

        return response([], 201);
    }

    public function destroy(Question $question)
    {
        $question->unfollowedBy(auth()->id());

        return response([], 201);
    }
}
