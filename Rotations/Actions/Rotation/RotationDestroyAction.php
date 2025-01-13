<?php

namespace App\Modules\Rotations\Actions\Rotation;

use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationManager;
use App\Modules\Rotations\Models\RotationPeriod;
use App\Modules\Rotations\Models\UserRotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RotationDestroyAction
{
    /**
     * @throws \Throwable
     */
    public function execute(Rotation $rotation): JsonResponse
    {
        $periodIds = RotationPeriod::where('rotation_id', $rotation->id)->pluck('id');

        DB::transaction(function () use ($rotation, $periodIds) {
            UserRotation::whereIn('rotation_period_id', $periodIds)->delete();
            RotationPeriod::where('rotation_id', $rotation->id)->delete();
            $rotation->delete();
        });

        return response()->json(['message' => 'Rotation deleted!']);
    }
}
