<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_vote_up()
    {
        $this->withExceptionHandling();
        $answer = create(Answer::class);

        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]))
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_vote_up()
    {
        $this->signIn();
        $answer = create(Answer::class);

        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]))
            ->assertStatus(201);
        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());

        $this->delete(route('answer-up-votes.destroy', ['answer' => $answer->id]));

        $this->assertCount(0, $answer->refresh()->votes('vote_up')->get());
    }
}
