<?php

namespace App\Models;

use App\Models\Traits\VoteTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAnswer
 */
class Answer extends Model
{
    use HasFactory, VoteTrait;

    protected $guarded = ['id'];

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
/*
    public function voteUp(User $user)
    {
        if (!$this->isVotedUp($user)) {
            $this->votes('vote_up')->create(['user_id' => $user->id, 'type' => 'vote_up']);
        }
    }

    public function cancelVoteUp(User $user)
    {
        $this->votes('vote_up')->where('user_id', $user->id)->delete();
    }

    public function isVotedUp(User $user)
    {
        if (!$user)
            return false;

        return $this->votes('vote_up')->where('user_id', $user->id)->exists();
    }

    public function votes($type)
    {
        return $this->morphMany(Vote::class, 'votable')->where('type', $type);
    }

    public function getUpVotesCountAttribute()
    {
        return $this->votes('vote_up')->count();
    }

    public function voteDown(User $user)
    {
        $attributes = ['user_id' => $user->id];

        if (!$this->votes('vote_down')->where($attributes)->exists()) {
            $this->votes('vote_down')->create(['user_id' => $user->id, 'type' => 'vote_down']);
        }
    }

    public function cancelVoteDown(User $user)
    {
        $this->votes('vote_down')->where('user_id', $user->id)->delete();
    }

    public function isVotedDown(User $user)
    {
        if (!$user) {
            return false;
        }

        return $this->votes('vote_down')->where('user_id', $user->id)->exists();
    }

    public function getDownVotesCountAttribute()
    {
        return $this->votes('vote_down')->count();
    }*/
}
