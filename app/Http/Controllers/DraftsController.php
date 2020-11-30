<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Auth;
use Illuminate\Http\Request;

class DraftsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $drafts = Question::drafts(Auth::user())->get();

        return view('drafts.index', [
            'drafts' => $drafts
        ]);
    }
}
