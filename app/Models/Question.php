<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Question
 *
 * @mixin IdeHelperQuestion
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $title
 * @property string $content
 * @property string|null $published_at
 * @property int|null $best_answer_id
 * @property int $popularity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Answer[] $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Following[] $followers
 * @property-read int|null $followers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Question drafts(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Question filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question published()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereBestAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question wherePopularity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUserId($value)
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
