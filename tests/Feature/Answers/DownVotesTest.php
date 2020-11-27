<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\VoteDownContractTest;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;

    protected function getVoteDownRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-down-votes.store', ['answer' => $answer->id])
            : route('answer-down-votes.store', ['answer' => '1']);
    }

    protected function getCancelVoteDownRoute(Answer $answer = null)
    {
        return $answer
            ? route('answer-down-votes.destroy', ['answer' => $answer->id])
            : route('answer-down-votes.destroy', ['answer' => '1']);
    }

    protected function downVotes(Answer $answer)
    {
        return $answer->refresh()->votes('vote_down')->get();
    }

    protected function getModel()
    {
        return Answer::class;
    }
}
