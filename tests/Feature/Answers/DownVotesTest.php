<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_vote_down()
    {
        $this->withExceptionHandling();

        $answer = create(Answer::class);

        $this->post(route('answer-down-votes.store', ['answer' => $answer->id]))
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_vote_down()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post(route('answer-down-votes.store', ['answer' => $answer->id]))
            ->assertStatus(201);

        $this->assertCount(1, $answer->refresh()->votes('vote_down')->get());
    }

    /** @test */
    public function authenticated_user_can_cancel_vote_down()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post(route('answer-down-votes.store', ['answer' => $answer->id]));

        $this->assertCount(1, $answer->refresh()->votes('vote_down')->get());

        $this->delete(route('answer-down-votes.destroy', ['answer' => $answer->id]))
            ->assertStatus(201);

        $this->assertCount(0, $answer->refresh()->votes('vote_down')->get());
    }

    /** @test */
    public function can_vote_down_only_once()
    {
        $this->signIn();

        $answer = create(Answer::class);

        try {
            $this->post(route('answer-down-votes.store', ['answer' => $answer->id]));
            $this->post(route('answer-down-votes.store', ['answer' => $answer->id]));
        } catch (Exception $exception) {
            $this->fail('Cannot vote down twice.');
        }

        $this->assertCount(1, $answer->refresh()->votes('vote_down')->get());
    }
}
