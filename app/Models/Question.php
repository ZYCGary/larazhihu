<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperQuestion
 */
class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeDrafts($query, User $user)
    {
        return $query->where(['user_id' => $user->id])
            ->whereNull('published_at');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function followers()
    {
        return $this->hasMany(Following::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function publish()
    {
        return $this->update([
            'published_at' => Carbon::now()
        ]);
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([^\s.]+)/', $this->content, $matches);

        return $matches[1];
    }

    public function markAsBest(Answer $answer)
    {
        return $this->update([
            'best_answer_id' => $answer->id,
        ]);
    }

    public function incrementPopularity()
    {
        return $this->increment('popularity');
    }

    public function followedBy(Int $userId)
    {
        return $this->followers()
            ->create(['user_id' => $userId]);
    }

    public function unfollowedBy(Int $userId)
    {
        return $this->followers()
            ->where(['user_id' => $userId])
            ->delete();
    }

    public function addAnAnswer(Array $answerAttributes)
    {
        return $this->answers()->create($answerAttributes);
    }
}
