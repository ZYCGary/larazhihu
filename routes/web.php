<?php

use App\Http\Controllers\AnswerDownVotesController;
use App\Http\Controllers\AnswersController;
use App\Http\Controllers\AnswerUpVotesController;
use App\Http\Controllers\BestAnswersController;
use App\Http\Controllers\DraftsController;
use App\Http\Controllers\PublishedQuestionsController;
use App\Http\Controllers\QuestionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/questions', [QuestionsController::class, 'index'])->name('questions.index');
Route::get('/questions/create', [QuestionsController::class, 'create'])->name('questions.create');
Route::post('/questions', [QuestionsController::class, 'store'])->name('questions.store');
Route::get('/questions/{question}', [QuestionsController::class, 'show'])->name('questions.show');

Route::post('/questions/{question}/published-questions', [PublishedQuestionsController::class, 'store'])->name('published-questions.store');

Route::post('/questions/{question}/answers', [AnswersController::class, 'store'])->name('answers.store');

Route::post('/answers/{answer}/best', [BestAnswersController::class, 'store'])->name('best-answers.store');
Route::delete('/answers/{answer}', [AnswersController::class, 'destroy'])->name('answers.destroy');

Route::post('/answers/{answer}/up-votes', [AnswerUpVotesController::class, 'store'])->name('answer-up-votes.store');
Route::delete('/answers/{answer}/up-votes', [AnswerUpVotesController::class, 'destroy'])->name('answer-up-votes.destroy');
Route::post('/answers/{answer}/down-votes', [AnswerDownVotesController::class, 'store'])->name('answer-down-votes.store');
Route::delete('/answers/{answer}/down-votes', [AnswerDownVotesController::class, 'destroy'])->name('answer-down-votes.destroy');

Route::get('/drafts', [DraftsController::class, 'index'])->name('drafts.index');
