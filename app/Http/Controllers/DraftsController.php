<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DraftsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Factory|View|Application
    {
        $drafts = Question::drafts(Auth::user())->get();

        return view('drafts.index', [
            'drafts' => $drafts
        ]);
    }
}
