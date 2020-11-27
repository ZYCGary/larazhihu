<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\User;
use App\Models\Vote;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Auth;
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

        $this->delete(route('answer-up-votes.destroy', ['answer' => $answer->id]))
            ->assertStatus(201);

        $this->assertCount(0, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_vote_up_only_once()
    {
        $this->signIn();

        $answer = create(Answer::class);

        try {
            $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));
            $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));
        } catch (Exception $exception) {
            $this->fail('Cannot vote up twice.');
        }

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_know_it_is_voted_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));

        $this->assertTrue($answer->refresh()->isVotedUp(Auth::user()));
    }

    /** @test */
    public function can_know_up_votes_count()
    {
        $answer = create(Answer::class);

        $this->signIn();
        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));
        $this->assertEquals(1, $answer->refresh()->upVotesCount);

        $this->signIn(create(User::class));
        $this->post(route('answer-up-votes.store', ['answer' => $answer->id]));
        $this->assertEquals(2, $answer->refresh()->upVotesCount);
    }
}
