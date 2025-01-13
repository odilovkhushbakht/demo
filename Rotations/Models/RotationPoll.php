<?php

namespace App\Modules\Rotations\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Modules\Rotations\Models\RotationPoll
 *
 * @property int $id
 * @property int $rotation_id
 * @property string $label
 * @property int $required
 * @property string $inputs
 * @method static Builder|RotationPoll newModelQuery()
 * @method static Builder|RotationPoll newQuery()
 * @method static Builder|RotationPoll query()
 * @method static Builder|RotationPoll whereId($value)
 * @method static Builder|RotationPoll whereInputs($value)
 * @method static Builder|RotationPoll whereLabel($value)
 * @method static Builder|RotationPoll whereRequired($value)
 * @method static Builder|RotationPoll whereRotationId($value)
 * @property-read Collection|RotationPollAnswer[] $answerRotation
 * @property-read int|null $answer_rotation_count
 * @mixin Eloquent
 */
class RotationPoll extends Model
{
    public $timestamps = false;
    protected $table = 'rotation_polls';
    protected $fillable = ['label', 'rotation_id', 'required', 'inputs'];
    protected $casts = ['inputs' => 'json'];

    public function answerRotation(): HasManyThrough
    {
        return $this->hasManyThrough(RotationPollAnswer::class, RotationPoll::class, 'rotation_id', 'poll_id')->orderBy('poll_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(RotationPollAnswer::class, 'poll_id');
    }
}
