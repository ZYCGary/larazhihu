<?php

namespace Tests\Contract;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait VoteUpContractTest
{

    /**
     * Get the route for voting up $model.
     *
     * @param string|null $model
     * @return mixed
     */
    abstract protected function getVoteUpRoute($model = null): mixed;

    /**
     * Get the route for cancelling the up vote to $model.
     *
     * @param string|null $model
     * @return mixed
     */
    abstract protected function getCancelVoteUpRoute($model = null): mixed;

    /**
     * Get all the up votes to $model.
     *
     * @param Model $model
     * @return mixed
     */
    abstract protected function upVotes(Model $model): mixed;

    /**
     * Get the $model.
     *
     * @return string mixed
     */
    abstract protected function getModel(): string;

    /**
     * Testing a guest cannot vote up.
     *
     * @test
     */
    public function guest_cannot_vote_up()
    {
        $this->withExceptionHandling();

        $this->post($this->getVoteUpRoute())
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can vote up.
     *
     * @test
     */
    public function member_can_vote_up()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model))
            ->assertStatus(201);

        $this->assertCount(1, $this->upVotes($model));
    }

    /**
     * Testing a member can cancel an up vote.
     *
     * @test
     */
    public function member_can_cancel_up_vote()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model));

        $this->assertCount(1, $this->upVotes($model));

        $this->delete($this->getCancelVoteUpRoute($model))
            ->assertStatus(201);

        $this->assertCount(0, $this->upVotes($model));
    }

    /**
     * Testing a member can vote up only once.
     *
     * @test
     */
    public function member_can_vote_up_only_once()
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

    /**
     * Testing a member can know whether he/she has voted up.
     *
     * @test
     */
    public function member_can_know_if_is_voted_up()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteUpRoute($model));

        $this->assertTrue($model->refresh()->isVotedUp(Auth::user()));
    }

    /**
     * Testing a member can know the count of up votes.
     *
     * @test
     */
    public function member_can_know_up_votes_count()
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
