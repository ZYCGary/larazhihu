<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestAnswerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot mark an answer as the best answer.
     *
     * @test
     * @covers \App\Http\Controllers\BestAnswersController
     */
    public function guests_cannot_mark_best_answer()
    {
        $question = create(Question::class);

        $answers = create(Answer::class, ['question_id' => $question->id], 2);

        $this->withExceptionHandling()
            ->post(route('best-answers.store', ['answer' => $answers[1]]), [$answers[1]])
            ->assertRedirect(route('login'));
    }

    /**
     * Testing the question creator can mark an answer as the best answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function question_creator_can_mark_an_answer_as_the_best()
    {
        $this->signIn();

        $question = create(Question::class, ['user_id' => auth()->id(), 'best_answer_id' => null]);

        $answers = create(Answer::class, ['question_id' => $question->id], 2);

        $this->assertFalse($answers[0]->isBest());
        $this->assertFalse($answers[1]->isBest());

        $this->postJson(route('best-answers.store', ['answer' => $answers[1]]), [$answers[1]]);

        $this->assertFalse($answers[0]->fresh()->isBest());
        $this->assertTrue($answers[1]->fresh()->isBest());
    }

    /**
     * Testing only the question creator can mark an answer as the best answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function only_the_question_creator_can_mark_best_answer()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = create(Question::class, ['user_id' => auth()->id()]);
        $answer = create(Answer::class, ['question_id' => $question->id]);

        $this->signIn();
        $this->postJson(route('best-answers.store', ['answer' => $answer]), [$answer])
            ->assertStatus(403);

        $this->assertFalse($answer->fresh()->isBest());
    }
}
