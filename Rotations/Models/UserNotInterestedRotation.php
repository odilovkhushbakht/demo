<?php

namespace App\Modules\Rotations\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotInterestedRotation extends Model
{
    public $timestamps = false;
    protected $table = 'user_not_interested_rotations';
    protected $fillable = ['user_id', 'rotation_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rotation(): BelongsTo
    {
        return $this->belongsTo(Rotation::class);
    }
}
