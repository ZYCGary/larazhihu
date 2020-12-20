<?php

namespace Tests\Contract;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait VoteDownContractTest
{
    /**
     * Get the route for voting down $model.
     *
     * @param string|null $model
     * @return mixed
     */
    abstract protected function getVoteDownRoute($model = null): mixed;

    /**
     * Get the route for cancelling the down vote to $model.
     *
     * @param string|null $model
     * @return mixed
     */
    abstract protected function getCancelVoteDownRoute($model = null): mixed;

    /**
     * Get all the down votes to $model.
     *
     * @param Model $model
     * @return mixed
     */
    abstract protected function downVotes(Model $model): mixed;

    /**
     * Get the $model.
     *
     * @return string
     */
    abstract protected function getModel(): string;

    /**
     * Testing a guest cannot vote down.
     *
     * @test
     */
    public function guest_cannot_vote_down()
    {
        $this->withExceptionHandling();

        $this->post($this->getVoteDownRoute())
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can vote down.
     *
     * @test
     */
    public function member_can_vote_down()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model))
            ->assertStatus(201);

        $this->assertCount(1, $this->downVotes($model));
    }

    /**
     * Testing a member can cancel a down vote.
     *
     * @test
     */
    public function member_can_cancel_down_vote()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model));

        $this->assertCount(1, $this->downVotes($model));

        $this->delete($this->getCancelVoteDownRoute($model))
            ->assertStatus(201);

        $this->assertCount(0, $this->downVotes($model));
    }

    /**
     * Testing a member user can vote down only once.
     *
     * @test
     */
    public function member_can_vote_down_only_once()
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

    /**
     * Testing a member can know whether he/she has voted down.
     *
     * @test
     */
    public function member_can_know_if_is_voted_down()
    {
        $this->signIn();

        $model = create($this->getModel());

        $this->post($this->getVoteDownRoute($model));

        $this->assertTrue($model->refresh()->isVotedDown(Auth::user()));
    }

    /**
     * Testing a member can know the count of down votes.
     *
     * @test
     */
    public function member_can_know_down_votes_count()
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
