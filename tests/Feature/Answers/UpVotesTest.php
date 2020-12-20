<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contract\VoteUpContractTest;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    /**
     * Get the route for voting up an answer.
     *
     * @param Answer|null $answer
     * @return string
     */
    protected function getVoteUpRoute($answer = null): string
    {
        return $answer
            ? route('answer-up-votes.store', ['answer' => $answer->id])
            : route('answer-up-votes.store', ['answer' => '1']);
    }

    /**
     * Get the route for cancelling the up vote to an answer.
     *
     * @param Answer|null $answer
     * @return string
     */
    protected function getCancelVoteUpRoute($answer = null): string
    {
        return $answer
            ? route('answer-up-votes.destroy', ['answer' => $answer->id])
            : route('answer-up-votes.destroy', ['answer' => '1']);
    }

    /**
     * Get all the up votes to an answer.
     *
     * @param Answer $answer
     * @return mixed
     */
    protected function upVotes($answer): Collection
    {
        return $answer->refresh()->votes('vote_up')->get();
    }

    /**
     * Get the Answer class.
     *
     * @return string
     */
    protected function getModel(): string
    {
        return Answer::class;
    }
}
