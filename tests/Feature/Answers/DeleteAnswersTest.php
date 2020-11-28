<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_answers()
    {
        $this->withExceptionHandling();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_user_cannot_delete_answers()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_answers()
    {
        $this->signIn();

        $answer = create(Answer::class, ['user_id' => auth()->id()]);

        $this->delete(route('answers.destroy', ['answer' => $answer]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('answers', ['id' => $answer->id]);
    }
}
