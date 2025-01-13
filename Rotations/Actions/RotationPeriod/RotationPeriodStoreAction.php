<?php

namespace App\Modules\Rotations\Actions\RotationPeriod;

use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationPeriod;
use Illuminate\Http\Request;

class RotationPeriodStoreAction
{
    public function execute(Request $request, Rotation $rotation): RotationPeriod
    {
        $rotationPeriod = new RotationPeriod();
        $rotationPeriod->rotation_id = $rotation->id;
        $rotationPeriod->start_date = $request->input('start_date');
        $rotationPeriod->end_date = $request->input('end_date');
        $rotationPeriod->save();

        return $rotationPeriod;
    }
}