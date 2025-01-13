<?php

namespace App\Modules\Rotations\Actions\Rotation;

use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationManager;
use Illuminate\Http\Request;

class RotationUpdateAction
{
    public function execute(Request $request, Rotation $rotation): Rotation
    {
        $rotation->name = $request->input('name');
        $rotation->description = $request->input('description');
        $rotation->requirement = $request->input('requirement');
        $rotation->city_id = $request->input('city_id');
        $rotation->created_by = auth()->id();
        $rotation->save();

        return $rotation;
    }
}