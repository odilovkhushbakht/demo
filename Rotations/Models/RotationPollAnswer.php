<?php

namespace App\Modules\Rotations\Models;

use App\Modules\Users\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Modules\Rotations\Models\RotationPollAnswer
 *
 * @property int $id
 * @property int $poll_id
 * @property int $user_id
 * @property array $answer
 * @property string $created_at
 * @property-read string $answer_string
 * @method static Builder|RotationPollAnswer newModelQuery()
 * @method static Builder|RotationPollAnswer newQuery()
 * @method static Builder|RotationPollAnswer query()
 * @method static Builder|RotationPollAnswer whereAnswer($value)
 * @method static Builder|RotationPollAnswer whereCreatedAt($value)
 * @method static Builder|RotationPollAnswer whereId($value)
 * @method static Builder|RotationPollAnswer wherePollId($value)
 * @method static Builder|RotationPollAnswer whereUserId($value)
 * @property-read RotationPoll $poll
 * @mixin Eloquent
 */
class RotationPollAnswer extends Model
{
    public $timestamps = false;
    protected $table = 'rotation_poll_answers';
    protected $fillable = ['id', 'poll_id', 'answer', 'created_at', 'user_rotation_id'];
    protected $attributes = ['answer_string'];
    protected $casts = ['answer' => 'array'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(RotationPoll::class)->orderBy('id');
    }

    public function getAnswerStringAttribute(): string
    {
        return implode(";", $this->answer);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function userRotation(): HasMany
    {
        return $this->hasMany(UserRotation::class,'user_id','user_id');
    }
}
