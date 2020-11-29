<?php

namespace Tests\Feature\Answers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot post an answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function guest_cannot_post_an_answer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $question = Question::factory()->published()->create();

        $response = $this->post(route('answers.store', [
            'question' => $question->id,
            'content' => "this is an answer."
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can post an answer to a published question.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function member_can_answer_a_published_question()
    {
        $question = Question::factory()->published()->create();
        $user = create(User::class);

        $response = $this->signIn($user)
            ->post(route('answers.store', [
                'question' => $question->id,
                'content' => "this is an answer."
            ]));
        $response->assertStatus(302);

        $answer = $question->answers()->where('user_id', $user->id)->first();
        $this->assertNotNull($answer);

        $this->assertEquals(1, $question->answers->count());
    }

    /**
     * Testing a user cannot post an answer to an unpublished question.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function user_cannot_answer_an_unpublished_question()
    {
        $question = Question::factory()->unpublished()->create();
        $user = create(User::class);

        $response = $this->withExceptionHandling()
            ->signIn($user)
            ->post(route('answers.store', [
                'question' => $question->id,
                'content' => "this is an answer."
            ]));

        $response->assertStatus(404);

        $this->assertDatabaseMissing('answers', ['question_id' => $question->id]);
        $this->assertEquals(0, $question->answers()->count());
    }

    /**
     * Testing a user must include 'content' in his/her answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function content_is_required()
    {
        $this->withExceptionHandling();

        $question = Question::factory()->published()->create();
        $user = create(User::class);

        $response = $this->signIn($user)
            ->post(route('answers.store', [
                'question' => $question->id,
                'content' => null
            ]));

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }
}
