<?php

namespace Tests\Contract;

use App\Models\User;
use Auth;
use Exception;

trait VoteDownContractTest
{

    abstract protected function getVoteDownRoute($model = null);

    abstract protected function getCancelVoteDownRoute($model = null);

    abstract protected function downVotes($model);

    abstract protected function getModel();

    /** @test */
    public function guest_cannot_vote_down()
    {
        $this->withExceptionHandling();

        $this->post($this->getVoteDownRoute())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_vote_down()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model))
            ->assertStatus(201);

        $this->assertCount(1, $this->downVotes($model));
    }

    /** @test */
    public function authenticated_user_can_cancel_vote_down()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model));

        $this->assertCount(1, $this->downVotes($model));

        $this->delete($this->getCancelVoteDownRoute($model))
            ->assertStatus(201);

        $this->assertCount(0, $this->downVotes($model));
    }

    /** @test */
    public function authenticated_user_can_vote_down_only_once()
    {
        $this->signIn();

        $model = create($this->getModel());

        try {
            $this->post($this->getVoteDownRoute($model));
            $this->post($this->getVoteDownRoute($model));
        } catch (Exception $exception) {
            $this->fail('Cannot vote down twice.');
        }

        $this->assertCount(1, $this->downVotes($model));
    }

    /** @test */
    public function authenticated_user_can_know_if_an_answer_is_voted_down()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model));

        $this->assertTrue($model->refresh()->isVotedDown(Auth::user()));
    }

    /** @test */
    public function authenticated_user_can_know_down_votes_count()
    {
        $model = create($this->getModel());

        $this->signIn();
        $this->post($this->getVoteDownRoute($model));
        $this->assertEquals(1, $model->refresh()->downVotesCount);

        $this->signIn(create(User::class));
        $this->post($this->getVoteDownRoute($model));
        $this->assertEquals(2, $model->refresh()->downVotesCount);
    }

}
