<?php

namespace App\Modules\Rotations\Actions\RotationPeriod;

use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationPeriod;
use Illuminate\Http\Request;

class RotationPeriodUpdateAction
{
    public function execute(Request $request, Rotation $rotation): RotationPeriod
    {
        $rotationPeriod = RotationPeriod::where('rotation_id', $rotation->id)
            ->orderByDesc('id')
            ->first();
        $rotationPeriod->start_date = $request->input('start_date');
        $rotationPeriod->end_date = $request->input('end_date');
        $rotationPeriod->save();

        return $rotationPeriod;
    }
}