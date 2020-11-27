<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Auth;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function knows_it_is_the_best()
    {
        $answer = create(Answer::class);

        $this->assertFalse($answer->isBest());

        $answer->question->update(['best_answer_id' => $answer->id]);

        $this->assertTrue($answer->isBest());
    }

    /** @test */
    public function belongs_to_a_question()
    {
        $answer = create(Answer::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $answer->question());
        $this->assertInstanceOf('App\Models\Question', $answer->question);
    }

    /** @test */
    public function belongs_to_an_owner()
    {
        $answer = create(Answer::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $answer->owner());
        $this->assertInstanceOf('App\Models\User', $answer->owner);
    }

    /** @test */
    public function can_vote_up_an_answer()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->assertDatabaseMissing('votes', [
            'user_id' => auth()->id(),
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer),
            'type' => 'vote_up',
        ]);

        $answer->voteUp(Auth::user());

        $this->assertDatabaseHas('votes', [
            'user_id' => auth()->id(),
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer),
            'type' => 'vote_up',
        ]);
    }

    /** @test */
    public function can_cancel_a_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $answer->voteUp(Auth::user());

        $answer->cancelVoteUp(Auth::user());

        $this->assertDatabaseMissing('votes', [
            'user_id' => auth()->id(),
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer)
        ]);
    }

    /** @test */
    public function knows_it_is_voted_up()
    {
        $user = create(User::class);
        $answer = create(Answer::class);

        create(Vote::class, [
            'user_id' => $user->id,
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer)
        ]);

        $this->assertTrue($answer->refresh()->isVotedUp($user));
    }

    /** @test */
    public function can_count_up_votes()
    {
        $answer = create(Answer::class);

        $user1 = create(User::class);
        create(Vote::class, [
            'user_id' => $user1->id,
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer)
        ]);

        $user2 = create(User::class);
        create(Vote::class, [
            'user_id' => $user2->id,
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer)
        ]);
        $this->assertEquals(2, $answer->refresh()->upVotesCount);
    }

    /** @test */
    public function can_vote_down_an_answer()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->assertDatabaseMissing('votes', [
            'user_id' => auth()->id(),
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer),
            'type' => 'vote_down'
        ]);

        $answer->voteDown(Auth::user());

        $this->assertDatabaseHas('votes', [
            'user_id' => auth()->id(),
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer),
            'type' => 'vote_down'
        ]);
    }
}
