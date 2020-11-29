<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contract\VoteDownContractTest;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;

    /**
     * Get the route for voting down an answer.
     *
     * @param Answer|null $answer
     * @return string
     */
    protected function getVoteDownRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-down-votes.store', ['answer' => $answer->id])
            : route('answer-down-votes.store', ['answer' => '1']);
    }

    /**
     * Get the route for cancelling the down vote to an answer.
     *
     * @param Answer|null $answer
     * @return string
     */
    protected function getCancelVoteDownRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-down-votes.destroy', ['answer' => $answer->id])
            : route('answer-down-votes.destroy', ['answer' => '1']);
    }

    /**
     * Get all the down votes to an answer.
     *
     * @param Answer $answer
     * @return mixed
     */
    protected function downVotes(Answer $answer)
    {
        return $answer->refresh()->votes('vote_down')->get();
    }

    /**
     * Get the Answer class.
     *
     * @return string
     */
    protected function getModel()
    {
        return Answer::class;
    }
}
