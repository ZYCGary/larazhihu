<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_it_is_the_best()
    {
        $answer = create(Answer::class);

        $this->assertFalse($answer->isBest());

        $answer->question->update(['best_answer_id' => $answer->id]);

        $this->assertTrue($answer->isBest());
    }

    /** @test */
    public function an_answer_belongs_to_a_question()
    {
        $answer = create(Answer::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $answer->question());
        $this->assertInstanceOf('App\Models\Question', $answer->question);
    }

    /** @test */
    public function an_answer_belongs_to_an_owner()
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
}
