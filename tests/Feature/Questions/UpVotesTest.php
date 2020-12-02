<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contract\VoteUpContractTest;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteUpContractTest;

    /**
     * Get the route for voting up a question.
     *
     * @param Question|null $question
     * @return string
     */
    protected function getVoteUpRoute(Question $question = null)
    {
        return $question
            ? route('question-up-votes.store', ['question' => $question->id])
            : route('question-up-votes.store', ['question' => '1']);
    }

    /**
     * Get the route for cancelling the up vote to an question.
     *
     * @param Question|null $question
     * @return string
     */
    protected function getCancelVoteUpRoute(Question $question = null)
    {
        return $question
            ? route('question-up-votes.destroy', ['question' => $question->id])
            : route('question-up-votes.destroy', ['question' => '1']);
    }

    /**
     * Get all the up votes to an question.
     *
     * @param Question $question
     * @return mixed
     */
    protected function upVotes(Question $question)
    {
        return $question->refresh()->votes('vote_up')->get();
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
