<?php

namespace App\Modules\Rotations\Actions\Rotation;

use App\Modules\Rotations\Models\Rotation;
use Illuminate\Support\Facades\DB;

class RotationStoreAction
{
    public function execute($request): Rotation
    {
        return DB::transaction(function () use ($request) {
            $rotation = new Rotation();
            $rotation->name = $request->input('name');
            $rotation->description = $request->input('description');
            $rotation->requirement = $request->input('requirement');
            $rotation->city_id = $request->input('city_id');
            $rotation->created_by = auth()->id();
            $rotation->save();

            return $rotation;
        });
    }
}
