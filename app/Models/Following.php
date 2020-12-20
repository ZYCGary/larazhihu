<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Following
 *
 * @property int $id
 * @property int $user_id
 * @property int $question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Following newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Following newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Following query()
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereUserId($value)
 * @mixin \Eloquent
 */
class Following extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
