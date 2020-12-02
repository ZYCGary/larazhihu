<?php

namespace App\Models;

use App\Models\Traits\VoteTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperQuestion
 */
class Question extends Model
{
    use HasFactory;
    use VoteTrait;

    protected $guarded = ['id'];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
    ];

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

    public function markAsBest($answer)
    {
        $this->update([
            'best_answer_id' => $answer->id,
        ]);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function publish()
    {
        $this->update([
            'published_at' => Carbon::now()
        ]);
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([^\s.]+)/', $this->content, $matches);

        return $matches[1];
    }

    public function incrementPopularity()
    {
        $this->increment('popularity');
    }
}
