<?php

namespace Tests\Feature;

use App\Models\User;
use Auth;
use Exception;

trait VoteUpContractTest
{

    abstract protected function getVoteUpRoute($model = null);

    abstract protected function getCancelVoteUpRoute($model = null);

    abstract protected function upVotes($model);

    abstract protected function getModel();

    /** @test */
    public function guest_cannot_vote_up()
    {
        $this->withExceptionHandling();

        $this->post($this->getVoteUpRoute())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_vote_up()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model))
            ->assertStatus(201);

        $this->assertCount(1, $this->upVotes($model));
    }

    /** @test */
    public function authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model));

        $this->assertCount(1, $this->upVotes($model));

        $this->delete($this->getCancelVoteUpRoute($model))
            ->assertStatus(201);

        $this->assertCount(0, $this->upVotes($model));
    }

    /** @test */
    public function authenticated_user_can_vote_up_only_once()
    {
        $this->signIn();

        $model = create($this->getModel());

        try {
            $this->post($this->getVoteUpRoute($model));
            $this->post($this->getVoteUpRoute($model));
        } catch (Exception $exception) {
            $this->fail('Cannot vote up twice.');
        }

        $this->assertCount(1, $this->upVotes($model));
    }

    /** @test */
    public function authenticated_user_can_know_if_an_answer_is_voted_up()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model));

        $this->assertTrue($model->refresh()->isVotedUp(Auth::user()));
    }

    /** @test */
    public function authenticated_user_can_know_up_votes_count()
    {
        $model = create($this->getModel());

        $this->signIn();
        $this->post($this->getVoteUpRoute($model));
        $this->assertEquals(1, $model->refresh()->upVotesCount);

        $this->signIn(create(User::class));
        $this->post($this->getVoteUpRoute($model));
        $this->assertEquals(2, $model->refresh()->upVotesCount);
    }

}
