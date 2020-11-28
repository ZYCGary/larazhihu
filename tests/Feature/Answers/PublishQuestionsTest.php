<?php

namespace Tests\Feature\Answers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublishQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_publish_questions()
    {
        $this->withExceptionHandling();

        $question = create(Question::class);

        $this->post(route('published-questions.store', ['question' => $question->id]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_publish_questions()
    {
        $this->signIn();

        $question = create(Question::class, ['user_id' => auth()->id()]);

        $this->assertCount(0, Question::published()->get());

        $this->postJson(route('published-questions.store', ['question' => $question]));

        $this->assertCount(1, Question::published()->get());
    }

    /** @test */
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
