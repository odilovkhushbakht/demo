<?php

namespace App\Modules\Rotations\Actions\RotationManager;

use App\Modules\Rotations\Models\Rotation;

class RotationManagersSyncAction
{
    public function execute(Rotation $rotation, array $managerIds): void
    {
        $rotation->managers()->sync($managerIds);
    }
}