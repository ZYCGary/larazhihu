<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contract\VoteDownContractTest;
use Tests\TestCase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;

    /**
     * Get the route for voting down an question.
     *
     * @param Question|null $question
     * @return string
     */
    protected function getVoteDownRoute(Question $question = null)
    {
        return $question
            ? route('question-down-votes.store', ['question' => $question->id])
            : route('question-down-votes.store', ['question' => '1']);
    }

    /**
     * Get the route for cancelling the down vote to an question.
     *
     * @param Question|null $question
     * @return string
     */
    protected function getCancelVoteDownRoute(Question $question = null)
    {
        return $question
            ? route('question-down-votes.destroy', ['question' => $question->id])
            : route('question-down-votes.destroy', ['question' => '1']);
    }

    /**
     * Get all the down votes to an question.
     *
     * @param Question $question
     * @return mixed
     */
    protected function downVotes(Question $question)
    {
        return $question->refresh()->votes('vote_down')->get();
    }

    /**
     * Get the Question class.
     *
     * @return string
     */
    protected function getModel()
    {
        return Question::class;
    }
}
