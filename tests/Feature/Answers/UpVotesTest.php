<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contract\VoteUpContractTest;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    protected function getVoteUpRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-up-votes.store', ['answer' => $answer->id])
            : route('answer-up-votes.store', ['answer' => '1']);
    }

    protected function getCancelVoteUpRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-up-votes.destroy', ['answer' => $answer->id])
            : route('answer-up-votes.destroy', ['answer' => '1']);
    }

    protected function upVotes(Answer $answer)
    {
        return $answer->refresh()->votes('vote_up')->get();
    }

    protected function getModel()
    {
        return Answer::class;
    }
}
