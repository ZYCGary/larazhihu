<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Vote;

trait VoteTrait
{
    public function voteUp($user)
    {
        if (!$this->isVotedUp($user)) {
            $this->votes('vote_up')->create(['user_id' => $user->id, 'type' => 'vote_up']);
        }
    }

    public function cancelVoteUp($user)
    {
        if ($user) {
            $this->votes('vote_up')->where('user_id', $user->id)->delete();
        }
    }

    public function isVotedUp($user)
    {
        if (!$user) {
            return false;
        }

        return (bool)$this->votes('vote_up')->where('user_id', $user->id)->exists();
    }

    public function getUpVotesCountAttribute()
    {
        return $this->votes('vote_up')->count();
    }

    public function voteDown($user)
    {
        if (!$this->isVotedDown($user)) {
            $this->votes('vote_down')->create(['user_id' => $user->id, 'type' => 'vote_down']);
        }
    }

    public function cancelVoteDown($user)
    {
        if ($user) {
            $this->votes('vote_down')->where('user_id', $user->id)->delete();
        }
    }

    public function isVotedDown($user)
    {
        if (!$user) {
            return false;
        }

        return $this->votes('vote_down')->where('user_id', $user->id)->exists();
    }

    public function getDownVotesCountAttribute()
    {
        return $this->votes('vote_down')->count();
    }

    public function votes($type)
    {
        return $this->morphMany(Vote::class, 'votable')->where('type', $type);
    }
}
