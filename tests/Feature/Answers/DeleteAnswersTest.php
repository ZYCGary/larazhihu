<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAnswersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot delete an answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function guest_cannot_delete_an_answer()
    {
        $this->withExceptionHandling();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertRedirect(route('login'));
    }

    /**
     * Testing only the answer creator can delete the answer.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function unauthorized_user_cannot_delete_an_answer()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertStatus(403);
    }

    /**
     * Testing a member can delete an answer created by him/her.
     *
     * @test
     * @covers \App\Http\Controllers\AnswersController
     */
    public function member_can_delete_an_answer()
    {
        $this->signIn();

        $answer = create(Answer::class, ['user_id' => auth()->id()]);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('answers', ['id' => $answer->id]);
    }
}
