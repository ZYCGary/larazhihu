<?php

use App\Http\Controllers\AnswerDownVotesController;
use App\Http\Controllers\AnswersController;
use App\Http\Controllers\AnswerUpVotesController;
use App\Http\Controllers\BestAnswersController;
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

Auth::routes();

Route::get('/questions', [QuestionsController::class, 'index']);
Route::get('/questions/{question}', [QuestionsController::class, 'show']);

Route::post('/questions/{question}/answers', [AnswersController::class, 'store']);

Route::post('/answers/{answer}/best', [BestAnswersController::class, 'store'])->name('best-answers.store');
Route::delete('/answers/{answer}', [AnswersController::class, 'destroy'])->name('answers.destroy');

Route::post('/answers/{answer}/up-votes', [AnswerUpVotesController::class, 'store'])->name('answer-up-votes.store');
Route::delete('/answers/{answer}/up-votes', [AnswerUpVotesController::class, 'destroy'])->name('answer-up-votes.destroy');
Route::post('/answers/{answer}/down-votes', [AnswerDownVotesController::class, 'store'])->name('answer-down-votes.store');

