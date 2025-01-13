<?php

namespace App\Modules\Rotations\Models;

use App\Modules\Contacts\Models\City;
use App\Modules\OrganizationalUnits\Models\Position;
use App\Modules\Users\Models\User;
use Eloquent;
use Filter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Rotations\Models\Rotation
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $requirement
 * @property int $city_id
 * @property int $created_by
 * @property string $start_date
 * @property string $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Rotation newModelQuery()
 * @method static Builder|Rotation newQuery()
 * @method static Builder|Rotation query()
 * @method static Builder|Rotation whereCreatedAt($value)
 * @method static Builder|Rotation whereCreatedBy($value)
 * @method static Builder|Rotation whereDescription($value)
 * @method static Builder|Rotation whereEndDate($value)
 * @method static Builder|Rotation whereId($value)
 * @method static Builder|Rotation whereName($value)
 * @method static Builder|Rotation whereManagerId($value)
 * @method static Builder|Rotation whereCityId($value)
 * @method static Builder|Rotation whereRequirement($value)
 * @method static Builder|Rotation whereStartDate($value)
 * @method static Builder|Rotation whereUpdatedAt($value)
 * @property-read int|null $polls_count
 * @property-read Position $position
 * @property-read User $user
 * @property-read Collection|RotationPoll[] $polls
 * @property-read UserRotation|null $userRotation
 * @property-read Collection|RotationPollAnswer[] $answerRotation
 * @property-read int|null $answer_rotation_count
 * @property int $manager_id
 * @property-read City $city
 * @property-read User $manager
 * @property-read int|null $user_rotation_count
 * @method static Builder|Rotation filter($filter)
 * @mixin Eloquent
 */
class Rotation extends Model
{
    use Filterable;

    public $timestamps = true;
    protected $table = 'rotations';
    protected $fillable = ['name', 'description', 'requirement', 'manager_id', 'city_id'];

    public function polls(): HasMany
    {
        return $this->hasMany(RotationPoll::class, 'rotation_id', 'id')->orderBy('id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'manager_rotation', 'rotation_id', 'manager_id')
            ->select(['id', 'name', 'surname', 'patronymic']);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function userRotation(): HasManyThrough
    {
        return $this->hasManyThrough(UserRotation::class, RotationPeriod::class, 'rotation_id', 'rotation_period_id');
    }

    public function answerRotation(): HasManyThrough
    {
        return $this->hasManyThrough(RotationPollAnswer::class, RotationPoll::class, 'rotation_id', 'poll_id')->orderBy('poll_id');
    }

    public function userNotInterestedRotation(): HasMany
    {
        return $this->hasMany(UserNotInterestedRotation::class, 'rotation_id', 'id');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(RotationPeriod::class, 'rotation_id', 'id');
    }

    public function period(): HasOne
    {
        return $this->hasOne(RotationPeriod::class, 'rotation_id', 'id')
            ->orderByDesc('end_date');
    }

    public function scopeOfManagerById($query, $managerId)
    {
        return $query->whereHas('managers', function ($query) use ($managerId) {
            $query->where('id', $managerId);
        });
    }
}
