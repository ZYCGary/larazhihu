<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot publish a question.
     *
     * @test
     * @covers \App\Http\Controllers\PublishedQuestionsController
     */
    public function guest_cannot_publish_a_question()
    {
        $this->withExceptionHandling();

        $question = create(Question::class);

        $this->post(route('published-questions.store', ['question' => $question->id]))
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can publish a question.
     *
     * @test
     * @covers \App\Http\Controllers\PublishedQuestionsController
     */
    public function member_can_publish_a_question()
    {
        $this->signIn();

        $question = create(Question::class, ['user_id' => auth()->id()]);

        $this->assertCount(0, Question::published()->get());

        $this->postJson(route('published-questions.store', ['question' => $question]));

        $this->assertCount(1, Question::published()->get());
    }

    /**
     * Testing a question can only be published by the creator.
     *
     * @test
     * @covers \App\Http\Controllers\PublishedQuestionsController
     */
    public function only_question_creator_can_publish_it()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = create(Question::class, ['user_id' => auth()->id()]);

        // Change to another user
        $this->signIn(create(User::class));

        $this->postJson(route('published-questions.store', ['question' => $question]))
            ->assertStatus(403);

        $this->assertCount(0, Question::published()->get());
    }

}
