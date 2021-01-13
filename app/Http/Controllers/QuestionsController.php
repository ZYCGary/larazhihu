<?php

namespace App\Http\Controllers;

use App\Filters\QuestionFilter;
use App\Http\Requests\QuestionRequest;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('verified')->except(['index', 'show']);
    }

    public function index(Category $category, QuestionFilter $filters): Factory|View|Application
    {
        // Filter question by category
        if ($category->exists) {
            $questions = Question::published()->where('category_id', $category->id);
        } else {
            $questions = Question::published();
        }

        // Filter question in terms of filter conditions
        $questions = $questions->filter($filters);

        $questions = $questions->paginate(20);

        return view('questions.index', [
            'questions' => $questions
        ]);
    }

    public function create(Question $question): Factory|View|Application
    {
        $categories = Category::all();

        return view('questions.create', [
            'question' => $question,
            'categories' => $categories
        ]);
    }

    public function show($category, $questionId): Factory|View|Application
    {
        $question = Question::published()->findOrFail($questionId);

        $answers = $question->answers()->paginate(20);

        array_map(function ($item) {
            return $this->appendVotedAttribute($item);
        }, $answers->items());

        return view('questions.show', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    public function store(QuestionRequest $request): Redirector|Application|RedirectResponse
    {
        $question = Question::create([
            'user_id' => auth()->id(),
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return redirect(route('drafts.index'))->with('flash', 'Create the question successfully！');
    }
}
