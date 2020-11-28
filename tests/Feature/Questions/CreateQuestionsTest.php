<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_question()
    {
        $this->withExceptionHandling();

        $this->post(route('questions.store'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_new_questions()
    {
        $this->signIn();

        $question = make(Question::class);

        $this->assertCount(0, Question::all());

        $this->post(route('questions.store', $question->toArray()))
            ->assertRedirect(route('questions.show', ['question' => Question::first()->id]));

        $this->assertCount(1, Question::all());
    }

}
