<?php

namespace Tests\Feature;

use App\Models\Following;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot follow or unfollow a question.
     *
     * @test
     * @covers \App\Http\Controllers\FollowQuestionsController
     */
    public function guest_cannot_follow_or_unfollow_a_question()
    {
        $this->withExceptionHandling();

        $question = create(Question::class);

        $this->post(route('follow-questions.store', ['question' => $question->id]))
            ->assertRedirect(route('login'));
        $this->post(route('follow-questions.destroy', ['question' => $question->id]))
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can follow a question.
     *
     * @test
     * @covers \App\Http\Controllers\FollowQuestionsController
     */
    public function member_can_follow_a_question()
    {
        $this->signIn();

        $question = Question::factory()->published()->create();

        $this->post(route('follow-questions.store', ['question' => $question->id]));

        $this->assertCount(1, $question->followers);
    }

    /**
     * Testing a follower can unfollow a question.
     *
     * @test
     * @covers \App\Http\Controllers\FollowQuestionsController
     */
    public function follower_can_unfollow_a_question()
    {
        $this->signIn();

        $question = Question::factory()->published()->create();

        create(Following::class, [
            'user_id' => auth()->id(),
            'question_id' => $question->id
        ]);

        $this->delete(route('follow-questions.destroy', ['question' => $question->id]));

        $this->assertCount(0, $question->followers);
    }
}
