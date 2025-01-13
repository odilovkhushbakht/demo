<?php

namespace App\Modules\Rotations\Models;

use App\Modules\Users\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Modules\Rotations\Models\UserRotation
 *
 * @property int $id
 * @property int $user_id
 * @property int $rotation_id
 * @method static Builder|UserRotation newModelQuery()
 * @method static Builder|UserRotation newQuery()
 * @method static Builder|UserRotation query()
 * @method static Builder|UserRotation whereId($value)
 * @method static Builder|UserRotation whereRotationId($value)
 * @method static Builder|UserRotation whereUserId($value)
 * @property string $status
 * @method static Builder|UserRotation whereStatus($value)
 * @method whereRotationPeriodId(mixed $input)
 * @property-read Collection|RotationPollAnswer[] $answerRotation
 * @property-read int|null $answer_rotation_count
 * @property-read User $user
 * @property-read Rotation $rotation
 * @mixin Eloquent
 */
class UserRotation extends Model
{
    public $timestamps = false;
    protected $table = 'user_rotations';
    protected $fillable = ['user_id', 'status', 'rotation_period_id'];

    public function answers(): HasMany
    {
        return $this->hasMany(RotationPollAnswer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(RotationPeriod::class, 'rotation_period_id');
    }
}
