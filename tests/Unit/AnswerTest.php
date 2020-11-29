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

    /**
     * Testing an answer can indicate whether it is the best answer or not.
     *
     * @test
     * @covers \App\Models\Answer
     */
    public function knows_it_is_the_best()
    {
        $answer = create(Answer::class);

        $this->assertFalse($answer->isBest());

        $answer->question->update(['best_answer_id' => $answer->id]);

        $this->assertTrue($answer->isBest());
    }

    /**
     * Testing an answer belongs to a question.
     *
     * Testing many-to-one relationship to Question.
     *
     * @test
     * @covers \App\Models\Answer
     */
    public function an_answer_belongs_to_a_question()
    {
        $answer = create(Answer::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $answer->question());
        $this->assertInstanceOf('App\Models\Question', $answer->question);
    }

    /**
     * Testing an answer has an owner.
     *
     * Testing many-to-one relationship with User.
     *
     * @test
     * @covers \App\Models\Answer
     */
    public function an_answer_belongs_to_an_owner()
    {
        $answer = create(Answer::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $answer->owner());
        $this->assertInstanceOf('App\Models\User', $answer->owner);
    }

    /**
     * Testing an answer can be voted up by a member.
     *
     * @test
     * @covers \App\Models\Answer
     */
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

    /**
     * The up vote to an answer can be cancelled by a member.
     *
     * @test
     * @covers \App\Models\Answer
     */
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

    /**
     * Testing an answer can indicate whether it is voted up by a given user.
     *
     * @test
     * @covers \App\Models\Answer
     */
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

    /**
     * Testing an answer can get the count of its up votes.
     *
     * @test
     * @covers \App\Models\Answer
     */
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

    /**
     * Testing an answer can be voted down by a member.
     *
     * @test
     * @covers \App\Models\Answer
     */
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

    /**
     * Testing an answer can indicate whether it is voted down by a given user.
     *
     * @test
     * @covers \App\Models\Answer
     */
    public function knows_it_is_voted_down()
    {
        $user = create(User::class);
        $answer = create(Answer::class);
        create(Vote::class, [
            'user_id' => $user->id,
            'votable_id' => $answer->id,
            'votable_type' => get_class($answer),
            'type' => 'vote_down'
        ]);

        $this->assertTrue($answer->refresh()->isVotedDown($user));
    }
}
