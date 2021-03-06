<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BestAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Answer $answer): RedirectResponse
    {
        $this->authorize('update', $answer->question);

        $answer->question->markAsBest($answer);

        return back()->with('flash', 'Set as the best answer successfully!');;
    }
}
