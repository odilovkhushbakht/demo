<?php

namespace App\Modules\Rotations\Services;

use App\Acme\Services\BaseService;
use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationPeriod;

class RotationPeriodService extends BaseService
{
    public function create(Rotation $rotation): RotationPeriod
    {
        $rotationPeriod = new RotationPeriod();
        $rotationPeriod->rotation_id = $rotation->id;
        $rotationPeriod->start_date = $this->request->start_date;
        $rotationPeriod->end_date = $this->request->end_date;
        $rotationPeriod->save();

        return $rotationPeriod;
    }
}
