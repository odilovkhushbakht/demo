<?php

namespace App\Modules\Rotations\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Modules\Rotations\Models\RotationPeriod
 *
 * @property int $id
 * @property int $rotation_id
 * @property string|null $date
 * @mixin Eloquent
 */
class RotationPeriod extends Model
{
    public $timestamps = false;
    protected $table = 'rotation_periods';
    protected $fillable = ['rotation_id', 'start_date', 'end_date'];

    public function rotation(): BelongsTo
    {
        return $this->belongsTo(Rotation::class, 'rotation_id');
    }

    public function userRotations(): HasMany
    {
        return $this->hasMany(UserRotation::class, 'rotation_period_id', 'id');
    }
}
